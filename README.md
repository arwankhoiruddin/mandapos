# mandapos

**Free and Open-Source POS System by Mandatech**

mandapos adalah sistem Point-of-Sale (POS) gratis dan open-source yang dirancang khusus untuk UMKM di Indonesia. Dibangun dengan Laravel, mandapos menyediakan antarmuka yang sederhana dan mudah digunakan agar kasir maupun pemilik usaha dapat langsung produktif tanpa pelatihan panjang.

---

## 🎯 Target

Restoran UMKM di Indonesia. UI/UX harus sangat mudah dan mempunyai _learning curve_ yang sangat pendek sehingga siapa pun dapat langsung menggunakannya.

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|---|---|
| Backend Framework | [Laravel](https://laravel.com) |
| Database (Offline) | SQLite |
| Database (Online/Cloud) | PostgreSQL |
| Deployment | [Railway](https://railway.app) |

Aplikasi mendukung **mode offline** menggunakan SQLite pada perangkat lokal. Saat perangkat terhubung ke internet, data secara otomatis diunggah ke server PostgreSQL.

---

## ✨ Fitur

### Fitur Kunci Wajib POS Restoran

#### 1. Table & Floor Management
- Denah meja interaktif per lantai/area (floor) dengan status real-time:
  - **Kosong**
  - **Terisi/Pesan**
  - **Dibersihkan**
- Pindah pesanan antar meja.
- Gabung pesanan dari beberapa meja.
- Split bill per meja (berdasarkan item atau per orang).

#### 2. Order Flow ke Dapur (KDS-lite)
- Pesanan dari kasir/waiter langsung masuk ke layar dapur.
- Status pesanan per item/per tiket:
  - **Pending**
  - **In Progress**
  - **Ready**
- Notifikasi status untuk waiter dan kasir agar alur saji lebih cepat.
- Pelanggan dapat melihat status antrian order mereka berdasarkan nomor order (mis. menunggu diproses, sedang dimasak, siap diambil).
- User dapur dapat melihat rincian item dan urutan antrean pesanan untuk diproses sesuai prioritas.

#### 3. Manajemen Menu, Varian, dan Modifier
- Master menu utama dan kategori menu (makanan, minuman, dessert).
- Dukungan varian menu:
  - Ukuran (small/medium/large)
  - Topping/add-on
  - Level pedas
- Modifier tambahan (mis. extra es, no gula, no bawang).
- Catatan khusus ke dapur per item/per order.

#### 4. Payment & Tips Handling
- Metode pembayaran lengkap:
  - Tunai
  - Kartu
  - QRIS
  - E-wallet
- Split payment dalam satu transaksi (contoh: cash + QRIS, cash + kartu).
- Manajemen tip:
  - Input tip manual
  - Tip per transaksi
  - Rekap tip untuk laporan staf.

#### 5. Inventory Bahan Baku & Recipe
- Manajemen bahan baku terpisah dari menu jual.
- Resep/BOM per menu untuk konsumsi stok otomatis saat order dibayar.
- Notifikasi stok hampir habis (low stock alert).
- Alert stok negatif jika ada anomali.
- Draft purchase order untuk restock bahan.

#### 6. Reporting Khusus Restoran
- Laporan menu terlaris.
- Laporan jam ramai (peak hour) berdasarkan jumlah transaksi dan omzet.
- Rata-rata bill per meja.
- Laporan penjualan per kategori menu (makanan, minuman, dessert).
- Laporan kinerja staf (kasir/waiter) berdasarkan transaksi yang ditangani.

### Modul POS Pendukung

#### Operasional Kasir
- Track **cash in** dan **cash out** dari drawer.
- Simpan dan edit order tanpa pembayaran.
- Kategorikan order sebagai **dine in**, **takeaway**, dan **delivery**.
- Tampilkan informasi order ke layar customer, termasuk status antrian per nomor pesanan.

#### Manajemen Pegawai & Akses
- Employee list, access rights, timecards, total hours.
- Track clock in/out pegawai.
- Level akses:
  - Backoffice & POS: Owner, Administrator, Manager.
  - POS only: Kasir.
  - KDS only: Dapur (melihat rincian pesanan, urutan antrean, dan update status masak).

#### Pelanggan & Nota
- Manajemen customer.
- Setting layout dan informasi nota pembelian.

#### Multi-Store
- Multi-store support.
- Isolasi data per store untuk transaksi, stok, dan laporan.

### Ringkasan Dampak ke Arsitektur
- Transaksi restoran berpusat pada entitas: floor, meja, order, order_item, modifier, payment_split, tip, recipe, dan stok bahan.
- Alur order harus event-driven agar status KDS, waiter, dan kasir sinkron real-time.
- Semua query operasional wajib tetap cepat di mode offline (SQLite) dan konsisten saat sinkron ke PostgreSQL.

---

## 🚀 Instalasi

### Prasyarat

- PHP >= 8.2
- Composer
- Node.js & npm
- SQLite (untuk development lokal)

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/arwankhoiruddin/mandapos.git
cd mandapos

# 2. Install dependensi PHP
composer install

# 3. Install dependensi JavaScript
npm install

# 4. Salin file konfigurasi
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Jalankan migrasi database
php artisan migrate --seed

# 7. Build aset frontend
npm run build

# 8. Jalankan server development
php artisan serve
```

Buka browser dan akses `http://localhost:8000`.

---

## ☁️ Deployment ke Railway

1. Buat akun di [Railway](https://railway.app) dan buat proyek baru.
2. Hubungkan repository GitHub ini ke proyek Railway.
3. Tambahkan layanan **PostgreSQL** dari Railway marketplace.
4. Set environment variable berikut di Railway:
   - `APP_KEY` — hasil `php artisan key:generate --show`
   - `DB_CONNECTION=pgsql`
   - `DATABASE_URL` — otomatis diisi Railway dari layanan PostgreSQL
   - `APP_ENV=production`
   - `APP_URL` — URL domain yang diberikan Railway
5. Railway akan otomatis menjalankan build dan deploy.

---

## 🤝 Kontribusi

Kontribusi sangat disambut! Silakan ikuti langkah berikut:

1. **Fork** repository ini.
2. Buat branch baru: `git checkout -b fitur/nama-fitur`
3. Lakukan perubahan dan **commit**: `git commit -m "feat: tambah fitur X"`
4. **Push** ke branch Anda: `git push origin fitur/nama-fitur`
5. Buka **Pull Request** ke branch `main`.

### Panduan Commit Message

Gunakan format [Conventional Commits](https://www.conventionalcommits.org/):

```
feat: tambah fitur baru
fix: perbaiki bug pada modul X
docs: update dokumentasi
refactor: refaktor kode tanpa mengubah fungsionalitas
test: tambah atau perbaiki unit test
```

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah **MIT License**. Lihat file [LICENSE](LICENSE) untuk detail lebih lanjut.

---

## 📬 Kontak

Dibuat dan dikelola oleh [Mandatech](https://github.com/arwankhoiruddin).
Jika ada pertanyaan atau saran, silakan buka [issue](https://github.com/arwankhoiruddin/mandapos/issues) baru.
