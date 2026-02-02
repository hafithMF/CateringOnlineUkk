@extends('layouts.app')

@section('title', 'Catering Online - Beranda')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-emerald-50 to-teal-50 py-16">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="md:w-1/2 mb-8 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Catering Terbaik untuk Acara Spesial Anda
                </h1>
                <p class="text-lg text-gray-600 mb-8">
                    Nikmati berbagai pilihan paket catering dengan kualitas premium dan harga terjangkau. Dari pernikahan hingga rapat kantor, kami siap melayani.
                </p>
                <a href="{{ route('paket') }}" class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-8 py-3 rounded-lg text-lg inline-flex items-center hover:from-emerald-600 hover:to-teal-700">
                    <i class="fas fa-shopping-cart mr-2"></i> Lihat Paket
                </a>
            </div>
            <div class="md:w-1/2">
                <div class="w-full h-64 md:h-96 bg-gradient-to-br from-emerald-200 to-teal-200 rounded-2xl shadow-2xl flex items-center justify-center">
                    <i class="fas fa-utensils text-emerald-400 text-8xl"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kategori Section -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Kategori Acara</h2>
        <div class="grid grid-cols-2 md:grid-cols-5  gap-6">
            @php
                $kategoriList = ['Pernikahan', 'Selamatan', 'Ulang Tahun', 'Studi Tour', 'Rapat'];
                $iconMap = [
                    'Pernikahan' => 'heart',
                    'Selamatan' => 'hands-praying',
                    'Ulang Tahun' => 'birthday-cake',
                    'Studi Tour' => 'bus',
                    'Rapat' => 'briefcase',
                    'default' => 'calendar-alt'
                ];
            @endphp
            
            @foreach($kategoriList as $kategori)
                @php
                    $icon = $iconMap[$kategori] ?? $iconMap['default'];
                    $kategoriUrl = strtolower(str_replace(' ', '-', $kategori));
                @endphp
                
                <a href="{{ route('paket') }}?kategori={{ $kategoriUrl }}" 
                   class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition border border-gray-100">
                    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-{{ $icon }} text-emerald-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">{{ $kategori }}</h3>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Paket Populer -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Paket Populer</h2>
            <a href="{{ route('paket') }}" class="text-emerald-600 hover:text-emerald-800 font-medium">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        @if($paketPopuler && $paketPopuler->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($paketPopuler as $paket)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        @if($paket->foto1_url && $paket->foto1_url != asset('images/default-paket.jpg'))
                            <img src="{{ $paket->foto1_url }}" 
                                 alt="{{ $paket->name_paket }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center">
                                <i class="fas fa-utensils text-emerald-400 text-6xl"></i>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-xl font-bold text-gray-800">{{ $paket->name_paket }}</h3>
                                <span class="bg-emerald-100 text-emerald-800 text-sm font-semibold px-3 py-1 rounded-full">
                                    {{ $paket->jenis }}
                                </span>
                            </div>
                            
                            <p class="text-gray-600 mb-4 line-clamp-2">
                                {{ Str::limit($paket->deskripsi, 100) }}
                            </p>
                            
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <span class="text-2xl font-bold text-emerald-600">
                                        Rp {{ number_format($paket->harga_paket, 0, ',', '.') }}
                                    </span>
                                    <span class="text-gray-500 text-sm">/pax</span>
                                </div>
                                <span class="text-gray-600">
                                    <i class="fas fa-users mr-1"></i> {{ $paket->jumlah_pax }} pax
                                </span>
                            </div>
                            
                            <div class="flex space-x-3">
                                <a href="{{ route('detail-paket', $paket->id) }}" 
                                   class="flex-1 text-center border border-emerald-600 text-emerald-600 hover:bg-emerald-50 font-medium py-2 rounded-lg">
                                    Detail
                                </a>
                                @auth('pelanggan')
                                    <a href="{{ route('pesan', $paket->id) }}" 
                                       class="flex-1 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-center py-2 rounded-lg hover:from-emerald-600 hover:to-teal-700">
                                        Pesan
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="flex-1 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-center py-2 rounded-lg hover:from-emerald-600 hover:to-teal-700">
                                        Login untuk Pesan
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box-open text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-600">Belum ada paket catering tersedia</p>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-16">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Siap Memesan Catering?</h2>
            <p class="text-gray-600 mb-8">
                Daftar sekarang dan dapatkan kemudahan memesan catering untuk berbagai acara Anda.
            </p>
            
            @auth('pelanggan')
                <a href="{{ route('paket') }}" class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-8 py-3 rounded-lg text-lg inline-flex items-center hover:from-emerald-600 hover:to-teal-700">
                    <i class="fas fa-shopping-cart mr-2"></i> Lihat Paket Lainnya
                </a>
            @else
                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-8 py-3 rounded-lg text-lg hover:from-emerald-600 hover:to-teal-700">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('paket') }}" class="border border-emerald-600 text-emerald-600 hover:bg-emerald-50 px-8 py-3 rounded-lg text-lg">
                        Lihat Paket
                    </a>
                </div>
            @endauth
        </div>
    </div>
</section>
@endsection