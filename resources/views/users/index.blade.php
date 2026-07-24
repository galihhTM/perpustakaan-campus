<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Anggota & Hak Akses') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg shadow transition">
                ← Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded shadow-sm text-sm font-medium">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50 text-gray-700 text-sm font-semibold uppercase">
                                <th class="p-4">Nama</th>
                                <th class="p-4">Email</th>
                                <th class="p-4">Role / Hak Akses</th>
                                <th class="p-4">Terdaftar Pada</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm divide-y divide-gray-100">
                            @forelse($users as $user)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="p-4 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="p-4 font-mono text-xs">{{ $user->email }}</td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full 
                                        {{ $user->role === 'admin' ? 'bg-red-50 text-red-700 border border-red-100' : '' }}
                                        {{ $user->role === 'staff' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : '' }}
                                        {{ $user->role === 'member' ? 'bg-blue-50 text-blue-700 border border-blue-100' : '' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="p-4 text-gray-500">{{ $user->created_at->format('d M Y H:i') }}</td>
                                <td class="p-4 flex justify-center space-x-2">
                                    <a href="{{ route('users.edit', $user->id) }}" class="px-3 py-1 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded text-xs font-semibold border border-amber-200 transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded text-xs font-semibold border border-rose-200 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400 italic">
                                    Belum ada data anggota lain di sistem.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>