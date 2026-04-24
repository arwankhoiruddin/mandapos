const express = require('express');
const session = require('express-session');
const bcrypt = require('bcryptjs');
const path = require('path');
const crypto = require('crypto');
const { Pool } = require('pg');

const app = express();
const port = process.env.PORT || 3000;
const publicDir = path.join(__dirname, 'public');

const databaseUrl = process.env.DATABASE_URL;
if (!databaseUrl) {
  throw new Error('DATABASE_URL wajib diisi untuk menjalankan auth berbasis PostgreSQL.');
}

const pool = new Pool({
  connectionString: databaseUrl,
  ssl: process.env.NODE_ENV === 'production' ? { rejectUnauthorized: false } : false,
});

const sessionSecret = process.env.SESSION_SECRET || 'mandapos-dev-session-secret';

app.set('trust proxy', 1);
app.use(express.urlencoded({ extended: false }));
app.use(
  session({
    secret: sessionSecret,
    resave: false,
    saveUninitialized: false,
    cookie: {
      httpOnly: true,
      sameSite: 'lax',
      secure: process.env.NODE_ENV === 'production',
      maxAge: 1000 * 60 * 60 * 24 * 7,
    },
  }),
);

async function initDb() {
  await pool.query(`
    CREATE TABLE IF NOT EXISTS users (
      id UUID PRIMARY KEY,
      nama VARCHAR(120) NOT NULL,
      email VARCHAR(190) UNIQUE NOT NULL,
      password_hash TEXT NOT NULL,
      created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
    );
  `);

  await pool.query(`
    CREATE TABLE IF NOT EXISTS restaurants (
      id UUID PRIMARY KEY,
      nama VARCHAR(160) NOT NULL,
      created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
    );
  `);

  await pool.query(`
    CREATE TABLE IF NOT EXISTS restaurant_users (
      restaurant_id UUID NOT NULL REFERENCES restaurants(id) ON DELETE CASCADE,
      user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
      role VARCHAR(40) NOT NULL DEFAULT 'owner',
      created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
      PRIMARY KEY (restaurant_id, user_id)
    );
  `);
}

function authRequired(req, res, next) {
  if (!req.session.userId) {
    return res.redirect('/login?error=Sesi%20Anda%20berakhir.%20Silakan%20login%20lagi.');
  }

  next();
}

function escapeHtml(value) {
  return String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

app.use(express.static(publicDir));

app.get('/login', (req, res) => {
  if (req.session.userId) {
    return res.redirect('/dashboard');
  }

  return res.sendFile(path.join(publicDir, 'login.html'));
});

app.get('/register', (req, res) => {
  if (req.session.userId) {
    return res.redirect('/dashboard');
  }

  return res.sendFile(path.join(publicDir, 'register.html'));
});

app.post('/auth/register', async (req, res) => {
  const nama = (req.body.nama || '').trim();
  const email = (req.body.email || '').trim().toLowerCase();
  const restoran = (req.body.restoran || '').trim();
  const password = req.body.password || '';

  if (!nama || !email || !restoran || !password) {
    return res.redirect('/register?error=Semua%20field%20wajib%20diisi.');
  }

  if (password.length < 8) {
    return res.redirect('/register?error=Password%20minimal%208%20karakter.');
  }

  try {
    const existed = await pool.query('SELECT id FROM users WHERE email = $1 LIMIT 1', [email]);
    if (existed.rowCount > 0) {
      return res.redirect('/register?error=Email%20sudah%20terdaftar.%20Silakan%20login.');
    }

    const passwordHash = await bcrypt.hash(password, 10);
    const userId = crypto.randomUUID();
    const restaurantId = crypto.randomUUID();

    const client = await pool.connect();
    try {
      await client.query('BEGIN');
      await client.query('INSERT INTO users (id, nama, email, password_hash) VALUES ($1, $2, $3, $4)', [
        userId,
        nama,
        email,
        passwordHash,
      ]);
      await client.query('INSERT INTO restaurants (id, nama) VALUES ($1, $2)', [restaurantId, restoran]);
      await client.query('INSERT INTO restaurant_users (restaurant_id, user_id, role) VALUES ($1, $2, $3)', [
        restaurantId,
        userId,
        'owner',
      ]);
      await client.query('COMMIT');
    } catch (error) {
      await client.query('ROLLBACK');
      throw error;
    } finally {
      client.release();
    }

    req.session.userId = userId;
    req.session.userName = nama;
    req.session.userEmail = email;

    return res.redirect('/dashboard');
  } catch (_error) {
    return res.redirect('/register?error=Terjadi%20kendala.%20Silakan%20coba%20lagi.');
  }
});

app.post('/auth/login', async (req, res) => {
  const email = (req.body.email || '').trim().toLowerCase();
  const password = req.body.password || '';

  if (!email || !password) {
    return res.redirect('/login?error=Email%20dan%20password%20wajib%20diisi.');
  }

  try {
    const userResult = await pool.query('SELECT id, nama, email, password_hash FROM users WHERE email = $1 LIMIT 1', [
      email,
    ]);

    if (userResult.rowCount === 0) {
      return res.redirect('/login?error=Email%20atau%20password%20tidak%20cocok.');
    }

    const user = userResult.rows[0];
    const validPassword = await bcrypt.compare(password, user.password_hash);
    if (!validPassword) {
      return res.redirect('/login?error=Email%20atau%20password%20tidak%20cocok.');
    }

    req.session.userId = user.id;
    req.session.userName = user.nama;
    req.session.userEmail = user.email;

    return res.redirect('/dashboard');
  } catch (_error) {
    return res.redirect('/login?error=Terjadi%20kendala.%20Silakan%20coba%20lagi.');
  }
});

app.get('/dashboard', authRequired, async (req, res) => {
  try {
    const userResult = await pool.query('SELECT id, nama, email FROM users WHERE id = $1 LIMIT 1', [req.session.userId]);

    if (userResult.rowCount === 0) {
      req.session.destroy(() => {
        res.redirect('/login?error=Akun%20tidak%20ditemukan.%20Silakan%20login%20kembali.');
      });
      return;
    }

    const user = userResult.rows[0];
    const restaurantResult = await pool.query(
      `
      SELECT r.nama
      FROM restaurants r
      INNER JOIN restaurant_users ru ON ru.restaurant_id = r.id
      WHERE ru.user_id = $1
      ORDER BY r.created_at ASC
      `,
      [user.id],
    );

    const restoranList = restaurantResult.rows
      .map((restoranItem) => `<li>${escapeHtml(restoranItem.nama)}</li>`)
      .join('');

    return res.send(`<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - MandaPOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Literata:opsz,wght@7..72,500;7..72,700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="/styles.css" />
  </head>
  <body class="auth-page">
    <main class="auth-shell">
      <a href="/" class="auth-brand" aria-label="Kembali ke landing page">
        <img src="/assets/logo-mandapos.svg" alt="Logo MandaPOS" class="auth-logo" />
      </a>
      <section class="auth-card">
        <p class="eyebrow">Dashboard Demo</p>
        <h1>Halo, ${escapeHtml(user.nama)}</h1>
        <p class="auth-lead">Akun Anda aktif dengan email ${escapeHtml(user.email)}.</p>
        <div class="dashboard-block">
          <h3>Restoran yang terhubung</h3>
          <ul>${restoranList || '<li>Belum ada restoran</li>'}</ul>
        </div>
        <div class="hero-actions">
          <a class="btn btn-ghost" href="/">Kembali ke Landing Page</a>
          <form action="/logout" method="post">
            <button class="btn btn-primary" type="submit">Logout</button>
          </form>
        </div>
      </section>
    </main>
  </body>
</html>`);
  } catch (_error) {
    return res.redirect('/login?error=Terjadi%20kendala%20sistem.%20Silakan%20coba%20lagi.');
  }
});

app.post('/logout', (req, res) => {
  req.session.destroy(() => {
    res.redirect('/login?success=Anda%20berhasil%20logout.');
  });
});

app.get('*', (_req, res) => {
  res.sendFile(path.join(publicDir, 'index.html'));
});

initDb()
  .then(() => {
    app.listen(port, () => {
      console.log(`MandaPOS landing page berjalan di port ${port}`);
    });
  })
  .catch((error) => {
    console.error('Gagal inisialisasi database PostgreSQL:', error.message);
    process.exit(1);
  });
