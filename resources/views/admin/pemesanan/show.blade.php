@extends('layouts.app')

@section('title', 'Detail Pemesanan #' . $pemesanan->no_resi)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-gray-600">No. Resi: {{ $pemesanan->no_resi }}</p>
        </div>
        
        @php
            $statusColors = [
                'Menunggu Konfirmasi' => 'bg-yellow-100 text-yellow-800',
                'Menunggu Kurir' => 'bg-purple-100 text-purple-800',
                'Selesai' => 'bg-emerald-100 text-emerald-800',
                'Dibatalkan' => 'bg-rose-100 text-rose-800',
            ];
        @endphp
        <div class="flex items-center space-x-4">
            <span class="px-4 py-2 text-lg font-semibold rounded-full {{ $statusColors[$pemesanan->status_pesan] }}">
                {{ $pemesanan->status_pesan }}
            </span>
            
            <!-- Status Actions -->
            <div class="relative group">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-cog mr-2"></i> Update Status
                </button>
                
                <!-- Status Dropdown -->
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                    <form action="{{ route('admin.pemesanan.status', $pemesanan->id) }}" method="POST">
                        @csrf
                        <div class="p-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ubah Status</label>
                            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option value="Menunggu Konfirmasi" {{ $pemesanan->status_pesan == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="Menunggu Kurir" {{ $pemesanan->status_pesan == 'Menunggu Kurir' ? 'selected' : '' }}>Menunggu Kurir</option>
                                <option value="Selesai" {{ $pemesanan->status_pesan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Dibatalkan" {{ $pemesanan->status_pesan == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            <button type="submit" class="w-full mt-2 bg-emerald-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-emerald-700">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Pesanan</h2>
                
                <div class="space-y-4">
                    @foreach($pemesanan->detailPemesanans as $detail)
                    <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                        @if($detail->paket->foto1)
                            <img src="{{ Storage::url('public/pakets/' . $detail->paket->foto1) }}" 
                                 alt="{{ $detail->paket->name_paket }}"
                                 class="w-20 h-20 object-cover rounded-lg">
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
                    
                    <!-- Total -->
                    <div class="flex justify-between items-center pt-4 border-t">
                        <span class="text-lg font-bold text-gray-800">Total Bayar</span>
                        <span class="text-2xl font-bold text-emerald-600">
                            Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pelanggan</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nama</label>
                        <p class="text-gray-800 font-medium">{{ $pemesanan->pelanggan->name_pelanggan }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                        <p class="text-gray-800">{{ $pemesanan->pelanggan->email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Telepon</label>
                        <p class="text-gray-800">{{ $pemesanan->pelanggan->telepon }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Lahir</label>
                        <p class="text-gray-800">
                            {{ $pemesanan->pelanggan->tgl_lahir ? date('d M Y', strtotime($pemesanan->pelanggan->tgl_lahir)) : '-' }}
                        </p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Pengiriman</label>
                        <p class="text-gray-800">{{ $pemesanan->pelanggan->alamat1 }}</p>
                        @if($pemesanan->pelanggan->alamat2)
                            <p class="text-gray-800 text-sm mt-1">{{ $pemesanan->pelanggan->alamat2 }}</p>
                        @endif
                    </div>
                </div>
                
                <!-- Customer Stats -->
                <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">Total Pesanan</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $pemesanan->pelanggan->pemesanans->count() }}
                        </p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">Pesanan Aktif</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $pemesanan->pelanggan->pemesanans->whereIn('status_pesan', ['Menunggu Konfirmasi', 'Menunggu Kurir'])->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Order Timeline -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Timeline Pesanan</h2>
                
                <div class="space-y-4">
                    @php
                        $timeline = [
                            'Pesanan Dibuat' => $pemesanan->created_at,
                            'Pembayaran Dikonfirmasi' => $pemesanan->status_pesan != 'Menunggu Konfirmasi' ? $pemesanan->updated_at : null,
                            'Siap Dikirim' => $pemesanan->status_pesan == 'Menunggu Kurir' || $pemesanan->status_pesan == 'Selesai' ? $pemesanan->updated_at : null,
                            'Ditugaskan ke Kurir' => $pemesanan->pengiriman && $pemesanan->pengiriman->id_user ? $pemesanan->pengiriman->updated_at : null,
                            'Pesanan Selesai' => $pemesanan->status_pesan == 'Selesai' ? $pemesanan->updated_at : null,
                        ];
                    @endphp
                    
                    @foreach($timeline as $step => $date)
                    <div class="flex items-start">
                        <div class="mr-4">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center 
                                {{ $date ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-100 text-gray-400' }}">
                                <i class="fas {{ $date ? 'fa-check' : 'fa-clock' }} text-sm"></i>
                            </div>
                            @if(!$loop->last)
                            <div class="h-8 w-0.5 bg-gray-200 ml-4"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-4">
                            <p class="font-medium text-gray-800">{{ $step }}</p>
                            <p class="text-sm text-gray-500">
                                @if($date)
                                    {{ date('d M Y H:i', strtotime($date)) }}
                                @else
                                    Menunggu...
                                @endif
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pembayaran</h2>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Metode Pembayaran</label>
                        <p class="text-gray-800 font-medium">{{ $pemesanan->jenisPembayaran->metode_pembayaran }}</p>
                        
                        @if($pemesanan->jenisPembayaran->detailJenisPembayarans && $pemesanan->jenisPembayaran->detailJenisPembayarans->count() > 0)
                            @foreach($pemesanan->jenisPembayaran->detailJenisPembayarans as $detail)
                            <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">{{ $detail->tempat_bayar }}</p>
                                <p class="font-mono text-gray-800">{{ $detail->no_rek }}</p>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Pesan</label>
                        <p class="text-gray-800">{{ date('d M Y H:i', strtotime($pemesanan->created_at)) }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Acara</label>
                        <p class="text-gray-800">{{ date('d M Y', strtotime($pemesanan->tgl_pesan)) }}</p>
                    </div>
                    
                    @if($pemesanan->catatan)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Catatan Pelanggan</label>
                        <p class="text-gray-800 italic">"{{ $pemesanan->catatan }}"</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Delivery Info -->
            @if($pemesanan->pengiriman)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pengiriman</h2>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status Pengiriman</label>
                        @php
                            $kirimColors = [
                                'Sedang Dikirim' => 'bg-yellow-100 text-yellow-800',
                                'Tiba di Tujuan' => 'bg-emerald-100 text-emerald-800',
                                'Belum Dikirim' => 'bg-gray-100 text-gray-800',
                            ];
                        @endphp
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $kirimColors[$pemesanan->pengiriman->status_kirim] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $pemesanan->pengiriman->status_kirim }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Kurir</label>
                        <p class="text-gray-800">
                            {{ $pemesanan->pengiriman->kurir->name ?? 'Belum ditugaskan' }}
                        </p>
                    </div>
                    
                    @if($pemesanan->pengiriman->tgl_kirim)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Kirim</label>
                        <p class="text-gray-800">{{ date('d M Y H:i', strtotime($pemesanan->pengiriman->tgl_kirim)) }}</p>
                    </div>
                    @endif
                    
                    @if($pemesanan->pengiriman->tgl_tiba)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Tiba</label>
                        <p class="text-gray-800">{{ date('d M Y H:i', strtotime($pemesanan->pengiriman->tgl_tiba)) }}</p>
                    </div>
                    @endif
                    
                    @if($pemesanan->pengiriman->bukti_foto)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Bukti Pengiriman</label>
                        <img src="{{ Storage::url('public/pengiriman/' . $pemesanan->pengiriman->bukti_foto) }}" 
                             alt="Bukti Pengiriman"
                             class="w-full h-48 object-cover rounded-lg border cursor-pointer"
                             onclick="openImageModal(this.src)">
                    </div>
                    @endif
                </div>
                
                <!-- Assign Kurir Button -->
                @if(!$pemesanan->pengiriman->id_user && $pemesanan->status_pesan == 'Menunggu Kurir')
                <button onclick="openAssignModal()" 
                        class="w-full mt-4 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-user-plus mr-2"></i> Assign Kurir
                </button>
                @endif
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h2>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.pemesanan') }}" 
                       class="w-full text-center border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-2 rounded-lg block">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                    </a>
                    
                    @if($pemesanan->status_pesan == 'Menunggu Konfirmasi')
                    <form action="{{ route('admin.pemesanan.status', $pemesanan->id) }}" method="POST" class="inline w-full">
                        @csrf
                        <input type="hidden" name="status" value="Menunggu Kurir">
                        <button type="submit" 
                                class="w-full text-center bg-emerald-600 text-white hover:bg-emerald-700 font-medium py-2 rounded-lg">
                            <i class="fas fa-check mr-2"></i> Konfirmasi & Siap Kirim
                        </button>
                    </form>
                    @endif
                    
                    @if($pemesanan->status_pesan == 'Menunggu Kurir')
                    <form action="{{ route('admin.pemesanan.status', $pemesanan->id) }}" method="POST" class="inline w-full">
                        @csrf
                        <input type="hidden" name="status" value="Selesai">
                        <button type="submit" 
                                class="w-full text-center bg-emerald-600 text-white hover:bg-emerald-700 font-medium py-2 rounded-lg">
                            <i class="fas fa-check-circle mr-2"></i> Tandai Selesai
                        </button>
                    </form>
                    @endif
                    
                    @if($pemesanan->status_pesan != 'Dibatalkan' && $pemesanan->status_pesan != 'Selesai')
                    <form action="{{ route('admin.pemesanan.cancel', $pemesanan->id) }}" method="POST" class="inline w-full">
                        @csrf
                        @method('POST')
                        <button type="submit" 
                                onclick="return confirm('Yakin ingin membatalkan pesanan ini?')"
                                class="w-full text-center border border-rose-600 text-rose-600 hover:bg-rose-50 font-medium py-2 rounded-lg">
                            <i class="fas fa-times mr-2"></i> Batalkan Pesanan
                        </button>
                    </form>
                    @endif
                    
                    <button onclick="window.print()" 
                            class="w-full text-center border border-blue-600 text-blue-600 hover:bg-blue-50 font-medium py-2 rounded-lg">
                        <i class="fas fa-print mr-2"></i> Cetak Invoice
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Kurir Modal -->
<div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Assign Kurir</h3>
                
                <form action="{{ route('admin.pengiriman.assign', $pemesanan->pengiriman->id ?? 0) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kurir</label>
                        <select name="id_user" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">Pilih Kurir</option>
                            @php
                                $kurirs = \App\Models\User::where('level', 'kurir')->get();
                            @endphp
                            @foreach($kurirs as $kurir)
                                <option value="{{ $kurir->id }}">{{ $kurir->name }} ({{ $kurir->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeAssignModal()" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Assign
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="relative">
            <button onclick="closeImageModal()" 
                    class="absolute top-4 right-4 text-white text-2xl">
                <i class="fas fa-times"></i>
            </button>
            <img id="modalImage" class="max-w-full max-h-screen">
        </div>
    </div>
</div>

<script>
function openAssignModal() {
    document.getElementById('assignModal').classList.remove('hidden');
}

function closeAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
}

function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Close modals when clicking outside
document.getElementById('assignModal').addEventListener('click', function(e) {
    if (e.target.id === 'assignModal') {
        closeAssignModal();
    }
});

document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target.id === 'imageModal') {
        closeImageModal();
    }
});
</script>
@endsection