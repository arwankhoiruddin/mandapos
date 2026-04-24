# mandapos

**Free and Open-Source POS System by Mandatech**

mandapos adalah sistem Point-of-Sale (POS) gratis dan open-source yang dirancang khusus untuk UMKM di Indonesia. Dibangun dengan Laravel, mandapos menyediakan antarmuka yang sederhana dan mudah digunakan agar kasir maupun pemilik usaha dapat langsung produktif tanpa pelatihan panjang.

---

## 🎯 Target

UMKM di Indonesia. UI/UX harus sangat mudah dan mempunyai _learning curve_ yang sangat pendek sehingga siapa pun dapat langsung menggunakannya.

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

### Operasional Kasir
- Track **cash in** dan **cash out** dari drawer
- Simpan dan edit order tanpa pembayaran
- Kategorikan order sebagai **dine in**, **takeaway**, dan **delivery**
- Kirim order ke **layar dapur** sesuai urutan pesanan
- Tampilkan informasi order ke **layar customer**

### Manajemen Stok & Produk
- **Items** dan **Categories**
- **Inventory Management**
- Notifikasi email untuk **low stock**
- **Negative stock alert**
- Generate dan scan **barcode** dengan _embedded weight_
- **Discounts**

### Manajemen Pegawai & Akses
- **Employee list**, access rights, timecards, total hours
- Track **clock in/out** pegawai
- Level akses:
  - **Backoffice & POS**: Owner, Administrator, Manager
  - **POS only**: Kasir

### Pelanggan & Nota
- Manajemen **Customers**
- Setting layout dan informasi **nota pembelian**

### Multi-Store & Laporan
- **Multi-store** support
- Laporan lengkap:
  - Sales Summary
  - Sales by Item, Category, Employee, Payment Type
  - Receipts, Modifiers, Discounts, Taxes, Shifts

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
