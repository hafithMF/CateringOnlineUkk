@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $pemesanan->no_resi)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Detail Pesanan</h1>
                <p class="text-gray-600">No. Resi: {{ $pemesanan->no_resi }}</p>
            </div>
            
            @php
                $statusColors = [
                    'Menunggu Konfirmasi' => 'bg-yellow-100 text-yellow-800',
                    'Sedang Diproses' => 'bg-blue-100 text-blue-800',
                    'Menunggu Kurir' => 'bg-purple-100 text-purple-800',
                    'Selesai' => 'bg-emerald-100 text-emerald-800',
                    'Dibatalkan' => 'bg-red-100 text-red-800',
                ];
            @endphp
            <span class="px-4 py-2 text-lg font-semibold rounded-full {{ $statusColors[$pemesanan->status_pesan] }}">
                {{ $pemesanan->status_pesan }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <!-- Detail Paket -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Paket</h2>
                
                @if($pemesanan->detailPemesanans && $pemesanan->detailPemesanans->count() > 0)
                    @foreach($pemesanan->detailPemesanans as $detail)
                    <div class="flex items-start space-x-4 mb-4 pb-4 border-b last:border-0">
                        @if($detail->paket && $detail->paket->foto1)
                            <img src="{{ Storage::url('public/pakets/' . $detail->paket->foto1) }}" 
                                 alt="{{ $detail->paket->name_paket }}"
                                 class="w-20 h-20 object-cover rounded-lg">
                        @else
                            <div class="w-20 h-20 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-utensils text-emerald-400 text-xl"></i>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            @if($detail->paket)
                                <h3 class="font-bold text-gray-800">{{ $detail->paket->name_paket }}</h3>
                                <div class="flex space-x-2 mt-1">
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2 py-1 rounded-full">
                                        {{ $detail->paket->jenis }}
                                    </span>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">
                                        {{ $detail->paket->kategori }}
                                    </span>
                                </div>
                            @endif
                            
                            <div class="flex justify-between mt-3">
                                <div>
                                    <p class="text-gray-600">
                                        {{ $detail->jumlah_pax }} pax × 
                                        Rp {{ number_format($detail->paket->harga_paket ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                                <p class="font-bold text-emerald-600">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-box-open text-2xl mb-2"></i>
                        <p>Detail paket tidak tersedia</p>
                    </div>
                @endif
                
                <div class="flex justify-between items-center pt-4 border-t">
                    <span class="text-lg font-bold text-gray-800">Total Bayar</span>
                    <span class="text-2xl font-bold text-emerald-600">
                        Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Informasi Pengiriman -->
            @if($pemesanan->pengiriman)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pengiriman</h2>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            @php
                                $kirimColors = [
                                    'Sedang Dikirim' => 'bg-yellow-100 text-yellow-800',
                                    'Tiba di Tujuan' => 'bg-emerald-100 text-emerald-800',
                                    'Belum Dikirim' => 'bg-gray-100 text-gray-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $kirimColors[$pemesanan->pengiriman->status_kirim] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $pemesanan->pengiriman->status_kirim }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Kurir</label>
                            <p class="text-gray-800">
                                {{ $pemesanan->pengiriman->kurir->name ?? 'Belum ditugaskan' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Kirim</label>
                            <p class="text-gray-800">
                                @if($pemesanan->pengiriman->tgl_kirim)
                                    {{ date('d M Y H:i', strtotime($pemesanan->pengiriman->tgl_kirim)) }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Tiba</label>
                            <p class="text-gray-800">
                                @if($pemesanan->pengiriman->tgl_tiba)
                                    {{ date('d M Y H:i', strtotime($pemesanan->pengiriman->tgl_tiba)) }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    @if($pemesanan->pengiriman->bukti_foto)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Bukti Pengiriman</label>
                        <img src="{{ Storage::url('public/pengiriman/' . $pemesanan->pengiriman->bukti_foto) }}" 
                             alt="Bukti Pengiriman"
                             class="w-64 h-48 object-cover rounded-lg border">
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Informasi -->
        <div class="space-y-6">
            <!-- Informasi Pemesanan -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pemesanan</h2>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Pesan</label>
                        <p class="text-gray-800">{{ date('d M Y H:i', strtotime($pemesanan->created_at)) }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Acara</label>
                        <p class="text-gray-800">{{ date('d M Y', strtotime($pemesanan->tgl_pesan)) }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Metode Pembayaran</label>
                        @if($pemesanan->jenisPembayaran)
                            <p class="text-gray-800">{{ $pemesanan->jenisPembayaran->metode_pembayaran }}</p>
                            
                            @if($pemesanan->jenisPembayaran->detailJenisPembayarans && $pemesanan->jenisPembayaran->detailJenisPembayarans->count() > 0)
                                @foreach($pemesanan->jenisPembayaran->detailJenisPembayarans as $detail)
                                <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm text-gray-600">{{ $detail->tempal_bayar }}</p>
                                    <p class="font-mono text-gray-800">{{ $detail->no_rek }}</p>
                                </div>
                                @endforeach
                            @endif
                        @else
                            <p class="text-gray-800">-</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informasi Pelanggan -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pelanggan</h2>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nama</label>
                        <p class="text-gray-800">{{ $pemesanan->pelanggan->name_pelanggan }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Telepon</label>
                        <p class="text-gray-800">{{ $pemesanan->pelanggan->telepon ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Alamat</label>
                        <p class="text-gray-800 text-sm">{{ $pemesanan->pelanggan->alamat1 ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Aksi -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Aksi</h2>
                
                <div class="space-y-3">
                    <a href="{{ route('pesanan-saya') }}" 
                       class="w-full text-center border border-emerald-600 text-emerald-600 hover:bg-emerald-50 font-medium py-2 rounded-lg block">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                    </a>
                    
                    @if($pemesanan->status_pesan == 'Menunggu Konfirmasi')
                    <form action="{{ route('pesanan.cancel', $pemesanan->id) }}" method="POST" class="inline w-full">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Yakin ingin membatalkan pesanan ini?')"
                                class="w-full text-center border border-red-600 text-red-600 hover:bg-red-50 font-medium py-2 rounded-lg">
                            <i class="fas fa-times mr-2"></i> Batalkan Pesanan
                        </button>
                    </form>
                    @endif
                    
                    @if($pemesanan->status_pesan == 'Selesai')
                    <button class="w-full text-center bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-medium py-2 rounded-lg hover:from-emerald-600 hover:to-teal-700">
                        <i class="fas fa-star mr-2"></i> Beri Ulasan
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection