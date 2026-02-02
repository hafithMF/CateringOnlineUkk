@extends('layouts.app')

@section('title', 'Detail Pengiriman #' . $pengiriman->pemesanan->no_resi)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Pengiriman</h1>
            <p class="text-gray-600">No. Resi: {{ $pengiriman->pemesanan->no_resi }}</p>
        </div>
        
        @php
            $statusColors = [
                'Belum Dikirim' => 'bg-yellow-100 text-yellow-800',
                'Sedang Dikirim' => 'bg-blue-100 text-blue-800',
                'Tiba di Tujuan' => 'bg-emerald-100 text-emerald-800',
                'Dibatalkan' => 'bg-rose-100 text-rose-800',
            ];
        @endphp
        <span class="px-4 py-2 text-lg font-semibold rounded-full {{ $statusColors[$pengiriman->status_kirim] ?? 'bg-gray-100 text-gray-800' }}">
            {{ $pengiriman->status_kirim }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Pengiriman -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Detail Pesanan -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Pesanan</h2>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">No. Resi</label>
                            <p class="text-gray-800 font-medium">{{ $pengiriman->pemesanan->no_resi }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status Pesanan</label>
                            @php
                                $pesananColors = [
                                    'Menunggu Konfirmasi' => 'bg-yellow-100 text-yellow-800',
                                    'Menunggu Kurir' => 'bg-purple-100 text-purple-800',
                                    'Selesai' => 'bg-emerald-100 text-emerald-800',
                                    'Dibatalkan' => 'bg-rose-100 text-rose-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $pesananColors[$pengiriman->pemesanan->status_pesan] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $pengiriman->pemesanan->status_pesan }}
                            </span>
                        </div>
                    </div>
                    
                    @foreach($pengiriman->pemesanan->detailPemesanans as $detail)
                    <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                        @if($detail->paket->foto1_url)
                            <img src="{{ $detail->paket->foto1_url }}" 
                                 alt="{{ $detail->paket->name_paket }}"
                                 class="w-20 h-20 object-cover rounded-lg">
                        @else
                            <div class="w-20 h-20 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-utensils text-emerald-400 text-xl"></i>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800">{{ $detail->paket->name_paket }}</h3>
                            <div class="flex space-x-2 mt-1">
                                <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ $detail->paket->jenis }}
                                </span>
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ $detail->paket->kategori }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between mt-3">
                                <div>
                                    <p class="text-gray-600">
                                        {{ $detail->jumlah_pax }} pax × 
                                        Rp {{ number_format($detail->paket->harga_paket, 0, ',', '.') }}
                                    </p>
                                </div>
                                <p class="font-bold text-emerald-600">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Lokasi dan Informasi Pelanggan -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Lokasi Pengiriman</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nama Pelanggan</label>
                        <p class="text-gray-800 font-medium">{{ $pengiriman->pemesanan->pelanggan->name_pelanggan }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Telepon</label>
                        <p class="text-gray-800">{{ $pengiriman->pemesanan->pelanggan->telepon }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Lengkap</label>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-800 font-medium">{{ $pengiriman->pemesanan->pelanggan->alamat1 }}</p>
                            @if($pengiriman->pemesanan->pelanggan->alamat2)
                                <p class="text-gray-600 mt-1">{{ $pengiriman->pemesanan->pelanggan->alamat2 }}</p>
                            @endif
                            @if($pengiriman->pemesanan->pelanggan->alamat3)
                                <p class="text-gray-600 text-sm mt-1">{{ $pengiriman->pemesanan->pelanggan->alamat3 }}</p>
                            @endif
                        </div>
                    </div>
                    
                    @if($pengiriman->pemesanan->catatan)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-yellow-600 mt-1 mr-2"></i>
                            <div>
                                <p class="text-sm text-yellow-800">
                                    <strong>Catatan Pelanggan:</strong>
                                    "{{ $pengiriman->pemesanan->catatan }}"
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Status Pengiriman -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Status Pengiriman</h2>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status Saat Ini</label>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$pengiriman->status_kirim] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $pengiriman->status_kirim }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Pengiriman</label>
                        <p class="text-gray-800">
                            @if($pengiriman->tgl_kirim)
                                {{ date('d M Y H:i', strtotime($pengiriman->tgl_kirim)) }}
                            @else
                                <span class="text-gray-400">Belum ditentukan</span>
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Tiba</label>
                        <p class="text-gray-800">
                            @if($pengiriman->tgl_tiba)
                                {{ date('d M Y H:i', strtotime($pengiriman->tgl_tiba)) }}
                            @else
                                <span class="text-gray-400">Belum tiba</span>
                            @endif
                        </p>
                    </div>
                    
                    @if($pengiriman->catatan)
                    <div class="mt-3 pt-3 border-t">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Catatan</label>
                        <p class="text-gray-700 text-sm italic">"{{ $pengiriman->catatan }}"</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Aksi Kurir -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Aksi Pengiriman</h2>
                
                <div class="space-y-3">
                    @if($pengiriman->status_kirim == 'Belum Dikirim')
                    <form action="{{ route('kurir.pengiriman.status', $pengiriman->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status_kirim" value="Sedang Dikirim">
                        <button type="submit" 
                                onclick="return confirm('Mulai proses pengiriman?')"
                                class="w-full text-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium py-3 rounded-lg hover:from-blue-600 hover:to-indigo-700">
                            <i class="fas fa-play mr-2"></i> Mulai Pengiriman
                        </button>
                    </form>
                    @endif
                    
                    @if($pengiriman->status_kirim == 'Sedang Dikirim')
                    <button onclick="openCompleteModal()" 
                            class="w-full text-center bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-medium py-3 rounded-lg hover:from-emerald-600 hover:to-teal-700">
                        <i class="fas fa-check-circle mr-2"></i> Konfirmasi Tiba
                    </button>
                    
                    <form action="{{ route('kurir.pengiriman.status', $pengiriman->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status_kirim" value="Belum Dikirim">
                        <button type="submit" 
                                onclick="return confirm('Batalkan pengiriman?')"
                                class="w-full text-center border border-rose-600 text-rose-600 font-medium py-3 rounded-lg hover:bg-rose-50">
                            <i class="fas fa-times mr-2"></i> Batalkan
                        </button>
                    </form>
                    @endif
                    
                    @if($pengiriman->status_kirim == 'Tiba di Tujuan')
                    <div class="text-center p-4 bg-emerald-50 rounded-lg border border-emerald-200">
                        <i class="fas fa-check-circle text-emerald-500 text-3xl mb-3"></i>
                        <p class="text-emerald-800 font-bold text-lg">Pengiriman Selesai</p>
                        <p class="text-emerald-600 text-sm mt-1">
                            {{ date('d M Y H:i', strtotime($pengiriman->tgl_tiba)) }}
                        </p>
                        @if($pengiriman->bukti_foto_url)
                        <div class="mt-3">
                            <a href="{{ $pengiriman->bukti_foto_url }}" 
                               target="_blank"
                               class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-image mr-1"></i> Lihat Bukti
                            </a>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    <a href="{{ route('kurir.pengiriman') }}" 
                       class="w-full text-center border border-gray-300 text-gray-700 font-medium py-3 rounded-lg hover:bg-gray-50 block">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Bukti Pengiriman Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Bukti Pengiriman</h2>
                
                @if($pengiriman->bukti_foto_url && $pengiriman->bukti_foto_exists)
                <div class="mb-4">
                    <div class="relative group">
                        <img src="{{ $pengiriman->bukti_foto_url }}" 
                             alt="Bukti Pengiriman"
                             class="w-full h-64 object-contain rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition duration-300"
                             onclick="previewImage('{{ $pengiriman->bukti_foto_url }}')">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition duration-300 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <span class="text-white bg-black bg-opacity-50 px-3 py-1 rounded-lg text-sm">
                                Klik untuk memperbesar
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-3 flex flex-wrap gap-2">
                        <a href="{{ $pengiriman->bukti_foto_url }}" 
                           target="_blank"
                           class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Buka di Tab Baru
                        </a>
                        <button onclick="previewImage('{{ $pengiriman->bukti_foto_url }}')" 
                                class="inline-flex items-center px-3 py-2 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                            <i class="fas fa-expand-alt mr-2"></i>
                            Perbesar Gambar
                        </button>
                        <button onclick="downloadImage('{{ $pengiriman->bukti_foto_url }}', 'bukti-pengiriman-{{ $pengiriman->pemesanan->no_resi }}')" 
                                class="inline-flex items-center px-3 py-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition">
                            <i class="fas fa-download mr-2"></i>
                            Download
                        </button>
                    </div>
                    
                    <div class="mt-2 text-xs text-gray-500">
                        <p>Upload: {{ $pengiriman->updated_at->format('d M Y H:i') }}</p>
                        <p>File: {{ $pengiriman->bukti_foto }}</p>
                    </div>
                </div>
                @elseif($pengiriman->bukti_foto_url && !$pengiriman->bukti_foto_exists)
                <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                        <div>
                            <p class="text-yellow-800 font-medium">File Tidak Ditemukan</p>
                            <p class="text-yellow-600 text-sm mt-1">
                                File bukti pengiriman (<strong>{{ $pengiriman->bukti_foto }}</strong>) tidak ditemukan di server.
                            </p>
                        </div>
                    </div>
                </div>
                @else
                <div class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
                    <i class="fas fa-camera text-gray-400 text-3xl mb-3"></i>
                    <p class="text-gray-600">Belum ada bukti pengiriman</p>
                </div>
                @endif
                
                <!-- Form Upload Bukti -->
                @if($pengiriman->status_kirim == 'Sedang Dikirim' || $pengiriman->status_kirim == 'Tiba di Tujuan')
                <form action="{{ route('kurir.pengiriman.upload', $pengiriman->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Foto Bukti Baru
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition duration-300 cursor-pointer" onclick="document.getElementById('bukti_foto').click()">
                                <input type="file" name="bukti_foto" 
                                       id="bukti_foto"
                                       accept="image/*"
                                       class="hidden"
                                       onchange="previewUploadImage(this)">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                <p class="text-gray-600">Klik atau drag & drop file</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Format: JPEG, PNG, JPG, GIF, WEBP (maks. 5MB)
                                </p>
                            </div>
                            <div id="uploadPreview" class="mt-2 hidden">
                                <img id="previewUploadImage" class="w-full h-48 object-contain rounded-lg border">
                                <div class="mt-2 flex justify-between">
                                    <span class="text-sm text-gray-600" id="fileName"></span>
                                    <button type="button" onclick="clearUpload()" class="text-red-500 hover:text-red-700 text-sm">
                                        <i class="fas fa-times mr-1"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" 
                                class="w-full text-center bg-gradient-to-r from-purple-500 to-pink-600 text-white font-medium py-3 rounded-lg hover:from-purple-600 hover:to-pink-700 transition duration-300">
                            <i class="fas fa-upload mr-2"></i> Upload Bukti
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Complete Modal -->
<div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Konfirmasi Pengiriman Selesai</h3>
                
                <form action="{{ route('kurir.pengiriman.status', $pengiriman->id) }}" method="POST" enctype="multipart/form-data">
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
                                   id="buktiFoto"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                   accept="image/*" required
                                   onchange="previewCompleteImage(this)">
                            <div class="mt-1 text-xs text-gray-500">
                                Foto bukti paket sudah diterima pelanggan
                            </div>
                            <div id="filePreview" class="mt-2 hidden">
                                <img id="previewCompleteImage" class="w-full h-48 object-contain rounded-lg border">
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
                <button onclick="downloadModalImage()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 mr-2">
                    <i class="fas fa-download mr-2"></i> Download
                </button>
                <a id="openNewTab" href="#" target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    <i class="fas fa-external-link-alt mr-2"></i> Buka di Tab Baru
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function openCompleteModal() {
    document.getElementById('completeModal').classList.remove('hidden');
}

function closeCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
}

let currentImageUrl = '';

function previewImage(imageUrl) {
    if (!imageUrl) {
        alert('Gambar tidak tersedia');
        return;
    }
    
    currentImageUrl = imageUrl;
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('openNewTab').href = imageUrl;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    currentImageUrl = '';
}

function downloadImage(url, filename) {
    const link = document.createElement('a');
    link.href = url;
    link.download = filename || 'bukti-pengiriman.jpg';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function downloadModalImage() {
    if (currentImageUrl) {
        const filename = 'bukti-pengiriman-' + Date.now() + '.jpg';
        downloadImage(currentImageUrl, filename);
    }
}

function previewUploadImage(input) {
    const file = input.files[0];
    const preview = document.getElementById('uploadPreview');
    const previewImage = document.getElementById('previewUploadImage');
    const fileName = document.getElementById('fileName');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            fileName.textContent = file.name;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}

function clearUpload() {
    document.getElementById('bukti_foto').value = '';
    document.getElementById('uploadPreview').classList.add('hidden');
}

function previewCompleteImage(input) {
    const file = input.files[0];
    const preview = document.getElementById('filePreview');
    const previewImage = document.getElementById('previewCompleteImage');
    
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
    
    if ((e.ctrlKey || e.metaKey) && e.key === 's' && currentImageUrl) {
        e.preventDefault();
        downloadModalImage();
    }
});

const dropArea = document.querySelector('form .border-dashed');
if (dropArea) {
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dropArea.classList.add('border-blue-400', 'bg-blue-50');
    }

    function unhighlight() {
        dropArea.classList.remove('border-blue-400', 'bg-blue-50');
    }

    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        const input = document.getElementById('bukti_foto');
        
        if (files.length > 0) {
            input.files = files;
            previewUploadImage(input);
        }
    }
}

@if(session('success'))
setTimeout(() => {
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        successAlert.classList.remove('hidden');
        setTimeout(() => {
            successAlert.classList.add('hidden');
        }, 5000);
    }
}, 500);
@endif
</script>

<!-- Success Alert -->
@if(session('success'))
<div class="alert-success fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <span>{{ session('success') }}</span>
    </div>
</div>
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