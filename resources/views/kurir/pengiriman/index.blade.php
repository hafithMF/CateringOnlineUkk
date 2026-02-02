@extends('layouts.app')

@section('title', 'Pengiriman Saya')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pengiriman Saya</h1>
            <p class="text-gray-600">Daftar pengiriman yang ditugaskan kepada Anda</p>
        </div>
        
        <!-- Stats -->
        <div class="flex space-x-4">
            <div class="text-center bg-white rounded-lg shadow p-4 min-w-24">
                <p class="text-sm text-gray-600">Total</p>
                <p class="text-2xl font-bold text-gray-800">{{ $pengirimans->total() }}</p>
            </div>
            <div class="text-center bg-white rounded-lg shadow p-4 min-w-24">
                <p class="text-sm text-gray-600">Aktif</p>
                <p class="text-2xl font-bold text-yellow-600">
                    {{ \App\Models\Pengiriman::where('id_user', Auth::id())->where('status_kirim', 'Sedang Dikirim')->count() }}
                </p>
            </div>
            <div class="text-center bg-white rounded-lg shadow p-4 min-w-24">
                <p class="text-sm text-gray-600">Selesai</p>
                <p class="text-2xl font-bold text-emerald-600">
                    {{ \App\Models\Pengiriman::where('id_user', Auth::id())->where('status_kirim', 'Tiba di Tujuan')->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Delivery Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No. Resi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pelanggan & Alamat
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Kirim
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Bukti
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pengirimans as $pengiriman)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $pengiriman->pemesanan->no_resi }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ $pengiriman->pemesanan->pelanggan->name_pelanggan }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $pengiriman->pemesanan->pelanggan->telepon }}
                            </div>
                            <div class="text-xs text-gray-600 mt-1 truncate max-w-xs">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $pengiriman->pemesanan->pelanggan->alamat1 }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($pengiriman->tgl_kirim)
                                    {{ \Carbon\Carbon::parse($pengiriman->tgl_kirim)->format('d/m/Y') }}
                                @else
                                    <span class="text-yellow-600">Belum dikirim</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">
                                Acara: {{ \Carbon\Carbon::parse($pengiriman->pemesanan->tgl_pesan)->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'Sedang Dikirim' => 'bg-yellow-100 text-yellow-800',
                                    'Tiba di Tujuan' => 'bg-emerald-100 text-emerald-800',
                                    'Belum Dikirim' => 'bg-gray-100 text-gray-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$pengiriman->status_kirim] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $pengiriman->status_kirim }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pengiriman->bukti_foto_url && $pengiriman->bukti_foto_exists)
                                <div class="flex items-center space-x-2">
                                    <a href="{{ $pengiriman->bukti_foto_url }}" 
                                       target="_blank"
                                       class="inline-flex items-center text-blue-600 hover:text-blue-900 transition">
                                        <i class="fas fa-image mr-1"></i> Lihat
                                    </a>
                                    <button onclick="previewImageFromIndex('{{ $pengiriman->bukti_foto_url }}')" 
                                            class="text-gray-600 hover:text-gray-900 transition">
                                        <i class="fas fa-expand-alt"></i>
                                    </button>
                                </div>
                            @elseif($pengiriman->bukti_foto_url && !$pengiriman->bukti_foto_exists)
                                <span class="text-red-500 text-xs">File hilang</span>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex space-x-2">
                                <a href="{{ route('kurir.pengiriman.show', $pengiriman->id) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                
                                @if($pengiriman->status_kirim == 'Sedang Dikirim')
                                <button onclick="openDeliveryCompleteModal('{{ $pengiriman->id }}')" 
                                        class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition">
                                    <i class="fas fa-check-circle mr-1"></i> Selesai
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3"></i>
                                <p class="text-lg">Belum ada pengiriman yang ditugaskan</p>
                                <p class="text-sm mt-1">Tungggu pemberitahuan dari admin untuk pengiriman baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($pengirimans->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pengirimans->links() }}
        </div>
        @endif
    </div>
</div>

<div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Konfirmasi Pengiriman Selesai</h3>
                
                <form id="completeForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status_kirim" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" required>
                                <option value="Tiba di Tujuan">Tiba di Tujuan</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Bukti Foto Pengiriman <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="bukti_foto" 
                                   id="buktiFotoInput"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                   accept="image/*" required
                                   onchange="previewUploadImage(this)">
                            <div class="mt-1 text-xs text-gray-500">
                                Format: JPEG, PNG, JPG, GIF, WEBP (maks. 5MB)
                            </div>
                            <div id="filePreview" class="mt-2 hidden">
                                <img id="previewUploadImage" class="w-full h-48 object-contain rounded-lg border">
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeCompleteModal()" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                            Konfirmasi Selesai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative max-w-4xl w-full">
            <button onclick="closeImageModal()" 
                    class="absolute -top-12 right-0 text-white text-3xl bg-black/50 rounded-full w-10 h-10 flex items-center justify-center hover:bg-black/70 z-50">
                <i class="fas fa-times"></i>
            </button>
            <div class="bg-white rounded-lg overflow-hidden">
                <img id="modalImage" class="w-full max-h-[80vh] object-contain">
            </div>
            <div class="mt-2 text-center">
                <a id="openNewTab" href="#" target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-external-link-alt mr-2"></i> Buka di Tab Baru
                </a>
            </div>
        </div>
    </div>
</div>

<script>
let currentPengirimanId = null;

function openDeliveryCompleteModal(pengirimanId) {
    currentPengirimanId = pengirimanId;
    const form = document.getElementById('completeForm');
    form.action = `/kurir/pengiriman/${pengirimanId}/status`;
    document.getElementById('completeModal').classList.remove('hidden');    
    document.getElementById('filePreview').classList.add('hidden');
    document.getElementById('buktiFotoInput').value = '';
}

function closeCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
    currentPengirimanId = null;
}

let currentImageUrl = '';

function previewImageFromIndex(imageUrl) {
    if (!imageUrl) {
        alert('Gambar tidak ditemukan');
        return;
    }
    
    currentImageUrl = imageUrl;
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('openNewTab').href = imageUrl;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function previewImage(imageUrl) {
    previewImageFromIndex(imageUrl);
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    currentImageUrl = '';
}

function previewUploadImage(input) {
    const file = input.files[0];
    const preview = document.getElementById('filePreview');
    const previewImage = document.getElementById('previewUploadImage');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}

document.getElementById('completeModal').addEventListener('click', function(e) {
    if (e.target.id === 'completeModal') {
        closeCompleteModal();
    }
});

document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target.id === 'imageModal') {
        closeImageModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCompleteModal();
        closeImageModal();
    }
});

@if(session('success'))
setTimeout(() => {
    showSuccessMessage("{{ session('success') }}");
}, 500);
@endif

function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full';
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.classList.remove('translate-x-full');
        alertDiv.classList.add('translate-x-0');
    }, 10);
    
    setTimeout(() => {
        alertDiv.classList.remove('translate-x-0');
        alertDiv.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(alertDiv);
        }, 300);
    }, 5000);
}
</script>

@if(session('success'))
<div class="alert-success"></div>
@endif

<style>
.alert-success {
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>
@endsection