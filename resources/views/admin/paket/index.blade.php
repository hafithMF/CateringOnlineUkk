@extends('layouts.app')

@section('title', 'Kelola Paket')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-gray-600">Kelola semua paket catering</p>
        </div>
        
        <a href="{{ route('admin.paket.create') }}" 
        class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-4 py-2 rounded-lg hover:from-emerald-600 hover:to-teal-700">
            <i class="fas fa-plus mr-2"></i> Tambah Paket
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Paket</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $pakets->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Prasmanan</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ \App\Models\Paket::where('jenis', 'Prasmanan')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-utensils text-emerald-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Box</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ \App\Models\Paket::where('jenis', 'Box')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box-open text-purple-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Rata-rata Harga</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        Rp {{ number_format(\App\Models\Paket::avg('harga_paket') ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Paket Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            #
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Paket
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis & Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Harga
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pax
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dibuat
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pakets as $paket)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $loop->iteration + ($pakets->currentPage() - 1) * $pakets->perPage() }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($paket->foto1)
                                    <img src="{{ asset('storage/' . $paket->foto1) }}" 
                                         alt="{{ $paket->name_paket }}"
                                         class="w-12 h-12 object-cover rounded-lg mr-3"
                                         onerror="this.onerror=null; this.src='{{ asset('images/default-paket.jpg') }}'">
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-utensils text-emerald-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $paket->name_paket }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-xs">
                                        {{ Str::limit($paket->deskripsi, 50) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2 py-1 rounded-full w-fit">
                                    {{ $paket->jenis }}
                                </span>
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full w-fit">
                                    {{ $paket->kategori }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-emerald-600">
                                Rp {{ number_format($paket->harga_paket, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-gray-500">/pax</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $paket->jumlah_pax }}</div>
                            <div class="text-xs text-gray-500">minimum</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $paket->created_at->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="{{ route('admin.paket.edit', $paket->id) }}" 
                            class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button onclick="confirmDelete('{{ $paket->id }}', '{{ $paket->name_paket }}')" 
                                    class="text-rose-600 hover:text-rose-900">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Belum ada paket catering
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($pakets->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pakets->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Konfirmasi Hapus</h3>
                <p class="text-gray-600 mb-6" id="deleteMessage"></p>
                
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeDeleteModal()" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700">
                            Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(paketId, paketName) {
    const form = document.getElementById('deleteForm');
    const message = document.getElementById('deleteMessage');
    
    form.action = `/admin/paket/${paketId}`;
    message.textContent = `Apakah Anda yakin ingin menghapus paket "${paketName}"?`;
    
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target.id === 'deleteModal') {
        closeDeleteModal();
    }
});
</script>
@endsection