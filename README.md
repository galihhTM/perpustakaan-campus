# Aplikasi Manajemen Perpustakaan Kampus (Laravel)

Aplikasi manajemen perpustakaan berbasis Laravel untuk mengelola proses administrasi perpustakaan kampus.

## Fitur

- Manajemen katalog buku
- Repositori dokumen kampus
- Manajemen stok buku otomatis
- Transaksi peminjaman dan pengembalian
- Backup database

---

## Persyaratan

- PHP 8.2+
- Composer
- Node.js & npm

---

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/galihhTM/perpustakaan-campus.git
cd perpustakaan-campus
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`.

```bash
cp .env.example .env
```

Kemudian sesuaikan konfigurasi database pada file `.env`.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_campus
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Buat Storage Link

```bash
php artisan storage:link
```

### 6. Migrasi Database dan Seeder

```bash
php artisan migrate --seed
```

### 7. Jalankan Aplikasi

Buka dua terminal.

Terminal pertama:
(untuk  asset compiler)
```bash
npm run dev
```

Terminal kedua:
(server laravel)
```bash
php artisan serve
```

Aplikasi dapat diakses melalui:

```
http://127.0.0.1:8000
```

atau

```
http://localhost:8000
```