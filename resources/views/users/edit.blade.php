<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ubah Informasi Anggota: {{ $user->name }}
            </h2>
            <a href="{{ route('users.index') }}" class="text-sm bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg shadow transition">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                
                <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Input Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Input Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror" required>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Pilihan Role Akses -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hak Akses (Role)</label>
                        <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="member" {{ old('role', $user->role) === 'member' ? 'selected' : '' }}>Member / Anggota</option>
                            <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff Perpustakaan</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end space-x-2">
                        <a href="{{ route('users.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold shadow transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>