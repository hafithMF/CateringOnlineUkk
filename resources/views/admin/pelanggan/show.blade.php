@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-600">Informasi lengkap pelanggan: {{ $pelanggan->name_pelanggan }}</p>
            </div>
            <a href="{{ route('admin.pelanggan') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Pelanggan Details -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pelanggan</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500">Nama Lengkap</label>
                <p class="mt-1 text-lg font-medium text-gray-900">{{ $pelanggan->name_pelanggan }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500">Email</label>
                <p class="mt-1 text-lg font-medium text-gray-900">{{ $pelanggan->email }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500">Nomor Telepon</label>
                <p class="mt-1 text-lg font-medium text-gray-900">{{ $pelanggan->telepon }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500">Tanggal Lahir</label>
                <p class="mt-1 text-lg font-medium text-gray-900">
                    {{ $pelanggan->tgl_lahir ? \Carbon\Carbon::parse($pelanggan->tgl_lahir)->format('d F Y') : '-' }}
                </p>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500">Alamat Lengkap</label>
                <p class="mt-1 text-gray-900">
                    {{ $pelanggan->alamat1 }}
                    @if($pelanggan->alamat2)
                        <br>{{ $pelanggan->alamat2 }}
                    @endif
                    @if($pelanggan->alamat3)
                        <br>{{ $pelanggan->alamat3 }}
                    @endif
                </p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500">Tanggal Bergabung</label>
                <p class="mt-1 text-lg font-medium text-gray-900">
                    {{ \Carbon\Carbon::parse($pelanggan->created_at)->format('d F Y') }}
                </p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500">Total Pemesanan</label>
                <p class="mt-1 text-lg font-medium text-gray-900">
                    {{ $pelanggan->pemesanans->count() }} kali
                </p>
            </div>
        </div>
    </div>

    <!-- Order History -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Pemesanan</h2>
        
        @if($pelanggan->pemesanans->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Resi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paket</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pesan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Bayar</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($pelanggan->pemesanans as $pemesanan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium">{{ $pemesanan->no_resi }}</td>
                            <td class="px-4 py-3 text-sm">
                                @php
                                    $paketNames = [];
                                    foreach($pemesanan->detailPemesanans as $detail) {
                                        if($detail->paket) {
                                            $paketNames[] = $detail->paket->name_paket;
                                        }
                                    }
                                @endphp
                                {{ !empty($paketNames) ? implode(', ', $paketNames) : '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($pemesanan->created_at)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm">
                                @php
                                    $statusColors = [
                                        'Menunggu Konfirmasi' => 'bg-yellow-100 text-yellow-800',
                                        'Sedang Diproses' => 'bg-blue-100 text-blue-800',
                                        'Menunggu Kurir' => 'bg-purple-100 text-purple-800',
                                        'Selesai' => 'bg-green-100 text-green-800',
                                        'Dibatalkan' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$pemesanan->status_pesan] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $pemesanan->status_pesan }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium">
                                Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('admin.pemesanan.show', $pemesanan->id) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="mt-2">Belum ada riwayat pemesanan</p>
            </div>
        @endif
    </div>
</div>
@endsection