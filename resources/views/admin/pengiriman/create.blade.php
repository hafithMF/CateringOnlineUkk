@extends('layouts.app')

@section('title', 'Buat Pengiriman Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Assign kurir untuk pengiriman pesanan</h1>
        </div>
        
        <!-- Form -->
        <form action="{{ route('admin.pengiriman.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Pilih Pesanan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Pesanan
                    </label>
                    <select name="id_pesan" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required onchange="loadOrderDetails(this.value)">
                        <option value="">Pilih Pesanan</option>
                        @foreach($pemesanans as $pemesanan)
                            @if($pemesanan->status_pesan == 'Menunggu Kurir')
                                <option value="{{ $pemesanan->id }}">
                                    {{ $pemesanan->no_resi }} - {{ $pemesanan->pelanggan->name_pelanggan }} - 
                                    Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hanya pesanan dengan status "Menunggu Kurir" yang ditampilkan</p>
                </div>
                
                <div id="orderDetails" class="hidden bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Detail Pesanan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">No. Resi</label>
                            <p id="detailNoResi" class="text-gray-800"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Pelanggan</label>
                            <p id="detailPelanggan" class="text-gray-800"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Total Bayar</label>
                            <p id="detailTotal" class="text-gray-800 font-bold text-emerald-600"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Acara</label>
                            <p id="detailTanggal" class="text-gray-800"></p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Pengiriman</label>
                            <p id="detailAlamat" class="text-gray-800"></p>
                        </div>
                    </div>
                </div>
                
                <!-- Pilih Kurir -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Kurir
                    </label>
                    <select name="id_user" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Pilih Kurir</option>
                        @foreach($kurirs as $kurir)
                            <option value="{{ $kurir->id }}">
                                {{ $kurir->name }} - {{ $kurir->email }}
                                @if($kurir->telepon)
                                    ({{ $kurir->telepon }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Status Pengiriman -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status Pengiriman
                    </label>
                    <select name="status_kirim" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="Sedang Dikirim">Sedang Dikirim</option>
                        <option value="Tiba di Tujuan">Tiba di Tujuan</option>
                    </select>
                </div>
                
                <!-- Instruksi -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-2"></i>
                        <div>
                            <p class="text-sm text-blue-800">
                                <strong>Informasi:</strong> Setelah pengiriman dibuat, kurir akan mendapatkan notifikasi 
                                dan dapat mengupdate status pengiriman serta upload bukti foto.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Buttons -->
                <div class="flex justify-between pt-6">
                    <a href="{{ route('admin.pengiriman') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Kembali
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Buat Pengiriman
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
async function loadOrderDetails(orderId) {
    if (!orderId) {
        document.getElementById('orderDetails').classList.add('hidden');
        return;
    }
    
    try {
        const response = await fetch(`/api/pemesanan/${orderId}`);
        const data = await response.json();
        
        document.getElementById('detailNoResi').textContent = data.no_resi;
        document.getElementById('detailPelanggan').textContent = data.pelanggan.name_pelanggan;
        document.getElementById('detailTotal').textContent = 'Rp ' + parseInt(data.total_bayar).toLocaleString('id-ID');
        document.getElementById('detailTanggal').textContent = new Date(data.tgl_pesan).toLocaleDateString('id-ID');
        document.getElementById('detailAlamat').textContent = data.pelanggan.alamat1;
        
        document.getElementById('orderDetails').classList.remove('hidden');
    } catch (error) {
        console.error('Error loading order details:', error);
    }
}
</script>
@endsection