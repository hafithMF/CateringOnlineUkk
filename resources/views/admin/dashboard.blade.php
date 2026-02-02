@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-md p-6 stat-card">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm">Total Pelanggan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPelanggan }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Pesanan -->
        <div class="bg-white rounded-xl shadow-md p-6 stat-card">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm">Total Pesanan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPemesanan }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Paket -->
        <div class="bg-white rounded-xl shadow-md p-6 stat-card">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm">Total Paket</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPaket }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Pendapatan -->
        <div class="bg-white rounded-xl shadow-md p-6 stat-card">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm">Total Pendapatan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Pesanan Terbaru</h2>
            <a href="{{ route('admin.pemesanan') }}" 
               class="text-blue-600 hover:text-blue-800 font-medium">
                Lihat Semua
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 table-hover">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No. Resi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pelanggan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Pesan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->no_resi }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->pelanggan->name_pelanggan }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($order->tgl_pesan)->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-emerald-600">
                                Rp {{ number_format($order->total_bayar, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'Menunggu Konfirmasi' => 'bg-yellow-100 text-yellow-800',
                                    'Sedang Diproses' => 'bg-blue-100 text-blue-800',
                                    'Menunggu Kurir' => 'bg-purple-100 text-purple-800',
                                    'Selesai' => 'bg-emerald-100 text-emerald-800',
                                    'Dibatalkan' => 'bg-rose-100 text-rose-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status_pesan] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $order->status_pesan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.pemesanan.show', $order->id) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data pesanan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('admin.paket.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg text-center transition">
                <i class="fas fa-plus-circle text-2xl mb-2"></i>
                <p class="font-medium">Tambah Paket</p>
            </a>
            
            <a href="{{ route('admin.pengiriman.create') }}" 
               class="bg-emerald-600 hover:bg-emerald-700 text-white p-4 rounded-lg text-center transition">
                <i class="fas fa-truck text-2xl mb-2"></i>
                <p class="font-medium">Kelola Pengiriman</p>
            </a>
            
            <a href="{{ route('admin.pelanggan') }}" 
               class="bg-yellow-600 hover:bg-yellow-700 text-white p-4 rounded-lg text-center transition">
                <i class="fas fa-users text-2xl mb-2"></i>
                <p class="font-medium">Lihat Pelanggan</p>
            </a>
        </div>
    </div>
</div>
@endsection