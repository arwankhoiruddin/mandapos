# Copilot Instructions — mandapos

## Gambaran Umum Proyek

**mandapos** adalah sistem Point-of-Sale (POS) gratis dan open-source yang dibangun di atas **Laravel** untuk UMKM di Indonesia. Prioritas utama adalah kemudahan penggunaan (short learning curve) sehingga kasir maupun pemilik toko dapat langsung produktif tanpa pelatihan panjang.

---

## Tech Stack

- **Backend**: Laravel (PHP >= 8.2)
- **Database lokal/offline**: SQLite
- **Database cloud/online**: PostgreSQL
- **Deployment**: Railway
- **Frontend**: Blade templates + Tailwind CSS (atau Livewire jika digunakan)
- **Build tool**: Vite (via `npm run build`)

---

## Konvensi Kode

### Umum
- Gunakan **bahasa Indonesia** untuk nama variabel domain bisnis (misalnya `$kasir`, `$produk`, `$diskon`) tetapi tetap gunakan **bahasa Inggris** untuk istilah teknis Laravel baku (controller, model, migration, middleware, dsb.).
- Ikuti standar [PSR-12](https://www.php-fig.org/psr/psr-12/) untuk kode PHP.
- Gunakan **type hints** dan **return types** di semua method PHP.

### Laravel Conventions
- Nama **Model**: PascalCase singular (`Produk`, `Kategori`, `Pegawai`, `Order`, `Pelanggan`).
- Nama **Controller**: PascalCase + suffix `Controller` (`ProdukController`, `OrderController`).
- Nama **tabel database**: snake_case plural (`produks`, `kategoris`, `pegawais`, `orders`).
- Nama **migration**: deskriptif, misalnya `create_orders_table`, `add_discount_to_orders_table`.
- Gunakan **Form Request** untuk validasi input di controller.
- Gunakan **Resource Controller** (`php artisan make:controller --resource`) bila sesuai.
- Definisikan **relasi Eloquent** di model, bukan di controller.

### Commit Message
Gunakan format [Conventional Commits](https://www.conventionalcommits.org/):
```
feat: tambah fitur X
fix: perbaiki bug pada modul Y
docs: update dokumentasi
refactor: refaktor tanpa mengubah fungsionalitas
test: tambah unit test untuk Z
chore: update dependensi
```

---

## Arsitektur Aplikasi

### Modul Utama

| Modul | Deskripsi |
|---|---|
| `Kasir / POS` | Antarmuka utama transaksi, input order, pembayaran |
| `Produk` | Manajemen item, kategori, harga, barcode |
| `Stok / Inventori` | Manajemen stok, low-stock alert, negative-stock alert |
| `Diskon` | Manajemen diskon dan promo |
| `Pegawai` | Daftar pegawai, hak akses, timecard, clock in/out |
| `Pelanggan` | Daftar pelanggan, riwayat transaksi |
| `Laporan` | Sales summary, laporan per item/kategori/pegawai/payment |
| `Pengaturan` | Nota pembelian, multi-store, konfigurasi umum |

### Level Akses

- **Owner / Administrator / Manager**: Akses penuh ke backoffice dan POS.
- **Kasir**: Akses terbatas ke antarmuka POS saja.

### Alur Data Offline ↔ Online

- Data transaksi disimpan secara lokal di **SQLite** agar tetap berjalan tanpa internet.
- Saat perangkat online, data disinkronisasi ke **PostgreSQL** di Railway.
- Pastikan logika sinkronisasi menangani **konflik data** dan **duplikasi**.

---

## Panduan Pengembangan Fitur Baru

1. **Buat migration** untuk perubahan schema: `php artisan make:migration`.
2. **Buat atau update Model** dengan relasi dan casting yang tepat.
3. **Buat Controller** dengan Form Request untuk validasi.
4. **Buat Blade view** yang sederhana dan responsif — ingat target pengguna adalah kasir UMKM.
5. **Tambahkan route** di `routes/web.php` atau `routes/api.php`.
6. **Tulis unit/feature test** di `tests/Feature` menggunakan PHPUnit.
7. Jalankan `php artisan test` sebelum membuat PR.

---

## Hal yang Perlu Diperhatikan

- **UX harus semudah mungkin**: hindari modal berlapis, wizard yang panjang, atau form yang rumit.
- **Performa offline**: jangan buat query yang bergantung pada koneksi internet di alur transaksi utama.
- **Multi-store**: semua data bertenant berdasarkan `store_id`; pastikan query selalu difilter per store.
- **Lokalisasi**: Teks UI dalam **Bahasa Indonesia**. Gunakan Laravel lang files (`lang/id/`).
- **Email notifikasi**: gunakan Laravel Queue untuk notifikasi low-stock agar tidak memblokir request.
- **Barcode**: dukung format standar (EAN-13, Code128) dengan _embedded weight_ untuk produk timbangan.

---

## Perintah yang Sering Digunakan

```bash
# Jalankan server development
php artisan serve

# Jalankan semua test
php artisan test

# Buat komponen baru
php artisan make:model NamaModel -mrc   # model + migration + resource controller
php artisan make:request NamaRequest

# Migrasi database
php artisan migrate
php artisan migrate:fresh --seed        # reset dan isi data dummy

# Build aset frontend
npm run dev     # development (watch mode)
npm run build   # production build
```
