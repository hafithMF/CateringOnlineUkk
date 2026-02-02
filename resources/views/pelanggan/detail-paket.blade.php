@extends('layouts.app')

@section('title', $paket->name_paket)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('home') }}" class="hover:text-emerald-600">Beranda</a></li>
            <li><i class="fas fa-chevron-right"></i></li>
            <li><a href="{{ route('paket') }}" class="hover:text-emerald-600">Paket</a></li>
            <li><i class="fas fa-chevron-right"></i></li>
            <li class="text-gray-800 font-medium">{{ $paket->name_paket }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Images -->
        <div>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-4">
                @if($paket->foto1_url && $paket->foto1_url != asset('images/default-paket.jpg'))
                    <img src="{{ $paket->foto1_url }}" 
                         alt="{{ $paket->name_paket }}"
                         class="w-full h-96 object-cover">
                @else
                    <div class="w-full h-96 bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center">
                        <i class="fas fa-utensils text-emerald-400 text-8xl"></i>
                    </div>
                @endif
            </div>
            
            @if($paket->foto2_url || $paket->foto3_url)
                <div class="grid grid-cols-2 gap-4">
                    @if($paket->foto2_url)
                        <img src="{{ $paket->foto2_url }}" 
                             alt="{{ $paket->name_paket }}"
                             class="w-full h-48 object-cover rounded-lg">
                    @endif
                    @if($paket->foto3_url)
                        <img src="{{ $paket->foto3_url }}" 
                             alt="{{ $paket->name_paket }}"
                             class="w-full h-48 object-cover rounded-lg">
                    @endif
                </div>
            @endif
        </div>

        <!-- Details -->
        <div>
            <div class="bg-white rounded-xl shadow-lg p-8">
                <!-- Badges -->
                <div class="flex space-x-2 mb-4">
                    <span class="bg-emerald-100 text-emerald-800 text-sm font-semibold px-3 py-1 rounded-full">
                        {{ $paket->jenis }}
                    </span>
                    <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
                        {{ $paket->kategori }}
                    </span>
                </div>

                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $paket->name_paket }}</h1>
                
                <!-- Price -->
                <div class="mb-6">
                    <div class="text-4xl font-bold text-emerald-600">
                        Rp {{ number_format($paket->harga_paket, 0, ',', '.') }}
                        <span class="text-lg text-gray-500">/pax</span>
                    </div>
                    <div class="text-gray-600">
                        <i class="fas fa-users mr-1"></i> Minimum {{ $paket->jumlah_pax }} pax
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Deskripsi Paket</h3>
                    <p class="text-gray-600 whitespace-pre-line">{{ $paket->deskripsi }}</p>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    @auth('pelanggan')
                        <a href="{{ route('pesan', $paket->id) }}" 
                           class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white w-full text-center py-3 rounded-lg text-lg block hover:from-emerald-600 hover:to-teal-700">
                            <i class="fas fa-shopping-cart mr-2"></i> Pesan Sekarang
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white w-full text-center py-3 rounded-lg text-lg block hover:from-emerald-600 hover:to-teal-700">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login untuk Memesan
                        </a>
                    @endauth
                    
                    <a href="{{ route('paket') }}" 
                       class="border border-emerald-600 text-emerald-600 hover:bg-emerald-50 w-full text-center py-3 rounded-lg text-lg block">
                        <i class="fas fa-box mr-2"></i> Lihat Paket Lainnya
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Paket Lainnya -->
    @if($paketLainnya->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Paket Lainnya</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($paketLainnya as $paketLain)
                    <a href="{{ route('detail-paket', $paketLain->id) }}" 
                       class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        @if($paketLain->foto1_url && $paketLain->foto1_url != asset('images/default-paket.jpg'))
                            <img src="{{ $paketLain->foto1_url }}" 
                                 alt="{{ $paketLain->name_paket }}"
                                 class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center">
                                <i class="fas fa-utensils text-emerald-400 text-4xl"></i>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <h3 class="font-bold text-gray-800 mb-1">{{ $paketLain->name_paket }}</h3>
                            <div class="flex justify-between items-center">
                                <span class="text-emerald-600 font-bold">
                                    Rp {{ number_format($paketLain->harga_paket, 0, ',', '.') }}
                                </span>
                                <span class="text-gray-600 text-sm">
                                    <i class="fas fa-users mr-1"></i> {{ $paketLain->jumlah_pax }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection