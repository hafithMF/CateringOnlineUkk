@extends('layouts.app')

@section('title', 'Profil ' . ucfirst(Auth::user()->level))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-10 md:px-8 md:py-12">
            <div class="flex flex-col md:flex-row items-center space-y-6 md:space-y-0 md:space-x-6">
                <div class="w-20 h-20 md:w-24 md:h-24 bg-white rounded-full flex items-center justify-center">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-emerald-100 rounded-full flex items-center justify-center">
                        @if(Auth::user()->isOwner())
                            <i class="fas fa-crown text-emerald-600 text-2xl md:text-3xl"></i>
                        @elseif(Auth::user()->isAdmin())
                            <i class="fas fa-user-shield text-emerald-600 text-2xl md:text-3xl"></i>
                        @elseif(Auth::user()->isKurir())
                            <i class="fas fa-truck text-emerald-600 text-2xl md:text-3xl"></i>
                        @else
                            <i class="fas fa-user-circle text-emerald-600 text-2xl md:text-3xl"></i>
                        @endif
                    </div>
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-2xl md:text-3xl font-bold text-white">{{ $user->name }}</h1>
                    <div class="flex items-center justify-center md:justify-start mt-2">
                        <span class="px-3 py-1 bg-white/20 text-white rounded-full text-sm font-medium">
                            {{ ucfirst($user->level) }}
                        </span>
                        <span class="mx-3 text-white/60">•</span>
                        <span class="text-emerald-100">{{ $user->email }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Profile Content -->
        <div class="p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-id-card mr-2 text-emerald-500"></i>
                        Informasi Akun
                    </h2>
                    <div class="space-y-4 bg-gray-50 rounded-lg p-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                            <p class="text-gray-800 font-medium">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-gray-800 font-medium">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Jabatan</label>
                            <p class="text-gray-800 font-medium">{{ ucfirst($user->level) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Bergabung Sejak</label>
                            <p class="text-gray-800 font-medium">
                                {{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') }}
                                <span class="text-gray-500 text-sm">
                                    ({{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }})
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-bolt mr-2 text-emerald-500"></i>
                        Aksi Cepat
                    </h2>
                    <div class="space-y-3">
                        <a href="{{ route('staff.profile.edit') }}" 
                           class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-edit text-emerald-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">Edit Profil</p>
                                <p class="text-sm text-gray-600">Ubah data profil Anda</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        
                        @if(Auth::user()->isOwner())
                            <a href="{{ route('owner.reports') }}" 
                               class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-chart-line text-emerald-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">Laporan Owner</p>
                                    <p class="text-sm text-gray-600">Lihat laporan sistem</p>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </a>
                        @endif
                        
                        @if(Auth::user()->isAdmin() || Auth::user()->isOwner())
                            <a href="{{ route('admin.pemesanan') }}" 
                               class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-shopping-bag text-emerald-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">Kelola Pesanan</p>
                                    <p class="text-sm text-gray-600">Lihat semua pesanan</p>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </a>
                        @endif
                        
                        @if(Auth::user()->isKurir())
                            <a href="{{ route('kurir.pengiriman') }}" 
                               class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-truck-fast text-emerald-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">Pengiriman Saya</p>
                                    <p class="text-sm text-gray-600">Lihat tugas pengiriman</p>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-history mr-2 text-emerald-500"></i>
                    Aktivitas Terbaru
                </h2>
                <div class="space-y-3">
                    @if(Auth::user()->isOwner() || Auth::user()->isAdmin())
                        @php
                            $recentOrders = \App\Models\Pemesanan::with('pelanggan')
                                ->orderBy('created_at', 'desc')
                                ->limit(3)
                                ->get();
                        @endphp
                        
                        @if($recentOrders->count() > 0)
                            @foreach($recentOrders as $order)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-receipt text-emerald-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">Pesanan #{{ $order->no_resi }}</p>
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-user mr-1"></i>
                                                {{ $order->pelanggan->name_pelanggan ?? 'N/A' }} • 
                                                <i class="fas fa-clock ml-2 mr-1"></i>
                                                {{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    @if($order->status_pesan == 'Selesai') bg-emerald-100 text-emerald-800
                                    @elseif($order->status_pesan == 'Menunggu Konfirmasi') bg-yellow-100 text-yellow-800
                                    @elseif($order->status_pesan == 'Dibatalkan') bg-rose-100 text-rose-800
                                    @else bg-emerald-100 text-emerald-800 @endif">
                                    {{ $order->status_pesan }}
                                </span>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-2xl mb-2"></i>
                                <p>Belum ada pesanan</p>
                            </div>
                        @endif
                        
                    @elseif(Auth::user()->isKurir())
                        @php
                            $recentDeliveries = \App\Models\Pengiriman::with('pemesanan.pelanggan')
                                ->where('id_user', Auth::id())
                                ->orderBy('created_at', 'desc')
                                ->limit(3)
                                ->get();
                        @endphp
                        
                        @if($recentDeliveries->count() > 0)
                            @foreach($recentDeliveries as $delivery)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-box text-emerald-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">Pengiriman #{{ $delivery->pemesanan->no_resi ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-user mr-1"></i>
                                                {{ $delivery->pemesanan->pelanggan->name_pelanggan ?? 'N/A' }} • 
                                                <i class="fas fa-clock ml-2 mr-1"></i>
                                                {{ \Carbon\Carbon::parse($delivery->created_at)->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    @if($delivery->status_kirim == 'Tiba di Tujuan') bg-emerald-100 text-emerald-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $delivery->status_kirim }}
                                </span>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-truck text-2xl mb-2"></i>
                                <p>Belum ada pengiriman</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection