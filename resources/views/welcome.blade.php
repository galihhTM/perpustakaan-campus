<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Perpustakaan Kampus</title>

    <!-- Integrasi Tailwind CSS bawaan Breeze via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">
    
    <div class="min-h-screen flex flex-col justify-center items-center px-4 bg-gradient-to-br from-blue-50 to-indigo-100">
        
        <!-- Main Card Landing -->
        <div class="max-w-xl text-center space-y-6 bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-gray-100">
            <!-- Icon/Logo Simpel -->
            <div class="text-6xl inline-block drop-shadow-md">
                📚
            </div>
            
            <h1 class="text-3xl font-extrabold text-gray-950 tracking-tight sm:text-4xl">
                Sistem Perpustakaan Universitas 
                <span class="text-blue-600 block mt-1 text-2xl sm:text-3xl">Bina Sarana Informatika</span>
            </h1>
            
            <p class="text-gray-600 text-sm sm:text-base max-w-md mx-auto leading-relaxed">
                Selamat datang di layanan peminjaman buku dan akses dokumen digital di Universitas Bina Sarana Informatika.
            </p>

            <div class="pt-4 flex flex-col sm:flex-row justify-center gap-4">
                <!-- Logika Tombol Dinamis Bawaan Laravel -->
                @if (Route::has('login'))
                    @auth
                        <!-- Jika user sudah dalam posisi login -->
                        <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl shadow transition duration-200">
                            Masuk ke Dashboard →
                        </a>
                    @else
                        <!-- Jika belum login -->
                        <a href="{{ route('login') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-xl shadow-md hover:shadow-lg transition duration-200">
                            Login 
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="w-full sm:w-auto bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 px-6 rounded-xl border border-gray-300 shadow-sm transition duration-200">
                                Daftar Anggota Baru
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>

        <!-- Footer Hak Cipta -->
        <footer class="mt-8 text-xs text-gray-400 font-medium">
            &copy; {{ date('Y') }} Aplikasi Perpustakaan Utama BSI &bull; Laravel v{{ App::version() }}
        </footer>
        
    </div>

</body>
</html>