@extends('layouts.app')

@section('title', 'Paket Catering')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Paket Catering</h1>
        <p class="text-gray-600">Pilih paket catering sesuai kebutuhan acara Anda</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form method="GET" action="{{ route('paket') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="semua">Semua Kategori</option>
                    @if(isset($kategori) && is_array($kategori))
                        @foreach($kategori as $item)
                            @if(!empty($item))
                                <option value="{{ $item }}" {{ request('kategori') == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                <select name="jenis" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="semua">Semua Jenis</option>
                    @if(isset($jenis) && is_array($jenis))
                        @foreach($jenis as $item)
                            @if(!empty($item))
                                <option value="{{ $item }}" {{ request('jenis') == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-6 py-2 rounded-lg w-full hover:from-emerald-600 hover:to-teal-700">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('paket') }}" class="ml-2 border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Paket Grid -->
    @if($pakets->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($pakets as $paket)
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
                            <div class="flex flex-col items-end">
                                <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2 py-1 rounded-full mb-1">
                                    {{ $paket->jenis }}
                                </span>
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ $paket->kategori }}
                                </span>
                            </div>
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
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $pakets->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-box-open text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Paket tidak ditemukan</h3>
            <p class="text-gray-600 mb-6">Coba gunakan filter yang berbeda</p>
            <a href="{{ route('paket') }}" class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-6 py-2 rounded-lg hover:from-emerald-600 hover:to-teal-700">
                <i class="fas fa-redo mr-2"></i> Reset Filter
            </a>
        </div>
    @endif
</div>
@endsection