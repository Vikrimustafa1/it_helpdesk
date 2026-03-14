## Hospital IT Helpdesk Portal

Aplikasi web Helpdesk IT untuk lingkungan rumah sakit, dibangun dengan **Laravel 12**, **MySQL 8+**, dan **Bootstrap 5.3**. Aplikasi ini memfasilitasi pelaporan gangguan IT oleh user (unit/ruangan) dan penanganan tiket oleh tim IT Support, lengkap dengan SLA, dashboard, dan export laporan PDF.

---

## 1. Requirement

- PHP 8.2 atau lebih baru
- Composer
- MySQL 8 atau kompatibel
- Ekstensi PHP:
  - `pdo_mysql`
  - `mbstring`
  - `openssl`
  - `json`
  - `xml`
  - `fileinfo`
- Node.js & npm (opsional, jika ingin build asset tambahan)
- Web server (Apache/Nginx) atau `php artisan serve`

---

## 2. Instalasi

1. **Clone / salin source code**

   ```bash
   git clone <repository-anda> it_helpdesk
   cd it_helpdesk
   ```

2. **Install dependency PHP**

   ```bash
   composer install
   ```

3. **Copy dan konfigurasi `.env`**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Sesuaikan konfigurasi database di `.env`:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=it_helpdesk
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Tambahkan package yang dibutuhkan**

   ```bash
   composer require laravel/breeze --dev
   composer require barryvdh/laravel-dompdf
   ```

   Jalankan publish konfigurasi DomPDF bila perlu:

   ```bash
   php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
   ```

5. **Migrasi database dan seeder**

   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Buat symlink storage untuk upload foto**

   ```bash
   php artisan storage:link
   ```

7. **Jalankan server pengembangan**

   ```bash
   php artisan serve
   ```

   Akses aplikasi melalui `http://localhost:8000`.

---

## 3. Demo Credentials

Setelah menjalankan `php artisan db:seed`, akun demo berikut akan tersedia:

- **IT Support**
  - Email: `it@rs.com`
  - Password: `password`

- **User 1**
  - Email: `user1@rs.com`
  - Password: `password`

- **User 2**
  - Email: `user2@rs.com`
  - Password: `password`

---

## 4. Struktur Folder Utama

- `app/Models`
  - `User.php` — model user dengan role (`user`, `it_support`) dan relasi tiket.
  - `Ticket.php` — model tiket dengan logika kode tiket, SLA, durasi, badge helper, dan scope filter.
  - `TicketProgress.php` — model catatan progress tiket.

- `app/Http/Middleware`
  - `RoleMiddleware.php` — middleware pengecekan role untuk route tertentu.

- `app/Http/Controllers`
  - `Auth/LoginController.php` — autentikasi login & logout berbasis session.
  - `Auth/RegisterController.php` — registrasi user baru (user & IT support).
  - `DashboardController.php` — dashboard user & dashboard IT (statistik + chart).
  - `TicketController.php` — CRUD tiket, klasifikasi, update status, progress, riwayat user.
  - `ReportController.php` — filter dan export laporan PDF.

- `database/migrations`
  - `*_create_users_table.php` — skema tabel users.
  - `*_create_tickets_table.php` — skema tabel tickets.
  - `*_create_ticket_progress_table.php` — skema tabel ticket_progress.

- `database/seeders`
  - `DatabaseSeeder.php` — membuat akun demo dan 30 tiket contoh dengan variasi status, kategori, severity, prioritas, dan progress.

- `resources/views`
  - `layouts/app.blade.php` — layout utama dengan sidebar, navbar, flash message, dan Chart.js.
  - `auth/login.blade.php` — halaman login.
  - `auth/register.blade.php` — halaman registrasi.
  - `dashboard/user.blade.php` — dashboard user dengan statistik pribadi & tiket terbaru.
  - `dashboard/it.blade.php` — dashboard IT dengan kartu statistik & chart.
  - `tickets/create.blade.php` — form pembuatan tiket baru oleh user.
  - `tickets/my-tickets.blade.php` — riwayat tiket user dengan filter.
  - `tickets/index.blade.php` — daftar tiket untuk IT Support dengan filter lanjutan.
  - `tickets/show.blade.php` — detail tiket + timeline progress + form tambah progress.
  - `tickets/edit.blade.php` — form klasifikasi & update status untuk IT.
  - `reports/index.blade.php` — filter & preview laporan.
  - `reports/pdf.blade.php` — template PDF DomPDF.

- `routes/web.php`
  - Definisi route autentikasi, dashboard user & IT, tiket, dan laporan dengan middleware role.

---

## 5. Fitur Utama

- **Autentikasi Session (Laravel Breeze-style)**
  - Login, register, dan logout menggunakan session Laravel.

- **Manajemen Role**
  - Role `user` (pelapor) dan `it_support` (teknisi).
  - Middleware `role` untuk membatasi akses route.

- **Manajemen Tiket**
  - User dapat membuat tiket dengan:
    - `unit`, `kategori`, `deskripsi`, dan lampiran foto (opsional).
  - Kode tiket otomatis: `IT-YYYYMMDD-XXX` dengan urutan harian dan transaksi database.
  - IT Support dapat:
    - Mengklasifikasi tingkat keparahan (SLA), prioritas, dan metode penanganan.
    - Mengubah status: `Open → Diproses → Selesai → Closed`.
    - Menambahkan catatan progress dengan foto opsional.

- **SLA & Durasi**
  - SLA otomatis berdasarkan tingkat keparahan:
    - `Critical`: +1 jam
    - `High`: +4 jam
    - `Medium`: +8 jam
    - `Low`: +24 jam
  - Indikator **OVERDUE** bila melewati SLA dan status bukan `Selesai`/`Closed`.
  - Durasi penanganan otomatis saat status menjadi `Selesai`.

- **Dashboard & Statistik**
  - Dashboard user:
    - Kartu statistik: Total, Open, Diproses, Selesai.
    - Tabel 5 tiket terbaru.
  - Dashboard IT:
    - 6 kartu: Hari ini, Open, Diproses, Selesai, Closed, Critical aktif.
    - Chart bar tiket per bulan (12 bulan terakhir).
    - Donut chart distribusi tiket per kategori.
    - Tabel 8 tiket aktif paling mendesak (berdasarkan prioritas & tanggal).

- **Laporan & Export PDF**
  - Filter laporan berdasarkan:
    - Rentang tanggal, status, dan kategori.
  - Preview tabel hasil filter.
  - Export ke PDF menggunakan `barryvdh/laravel-dompdf` dengan template khusus.

- **UI/UX**
  - Sidebar gelap (`#1e2a3a`), aksen biru (`#0d6efd`).
  - Menggunakan Bootstrap 5.3 & Bootstrap Icons via CDN.
  - Tabel responsif, breadcrumb di setiap halaman, dan empty state yang jelas.
  - Validasi form dengan tampilan error inline.
  - Upload foto menggunakan `Storage::disk('public')` dengan preview client-side dan batas 5MB.

---

## 6. Catatan Tambahan

- Pastikan direktori `storage` dan `bootstrap/cache` memiliki permission tulis yang benar pada server produksi.
- Untuk penyesuaian lebih lanjut (misalnya integrasi ke SSO, SIMRS, atau modul lain), Anda dapat menambahkan controller/model baru mengikuti pola yang sudah ada.
