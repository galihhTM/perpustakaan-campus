## Aplikasi Manajemen Perpustakaan Kampus (Laravel)

Aplikasi manajemen perpustakaan berbasil Laravel untuk mengelola katalog buku, menambahkan repositori dokumen kampus, mengelola stok otomatis, transaksi peminjaman dan pengembalian, dan backup database.

### Cara Install Secara Lokal

1. Clone repository ini:
    git clone [https://github.com/galihhTM/perpustakaan-campus.git](https://github.com/galihhTM/perpustakaan-campus.git)
        
    cd perpustakaan-campus

2. Install Dependensi PHP & JavaScript
    composer install
    npm install

3. Salin file .env.example menjadi .env
    cp .env.example .env

    buka file .env sesuaikan dengan database kamu
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama database misalnya: library_campus
    DB_USERNAME=root
    DB_PASSWORD=

4. Generate Application Key
    php artisan key:generate

5. Buat Symlink Storage (Wajib untuk Gambar Cover Buku)
    php artisan storage:link

6. Migrasi Database & Seeder
    php artisan migrate --seed

7. Jalankan Aplikasi
    Buka dua terminal di vscode
    
    Terminal pertama untuk asset compiler
    npm run dev

    Terminal kedua untuk server laravel
    php artisan serve

Buka webnya melalui local: http://127.0.0.1:8000 atau localhost:8000