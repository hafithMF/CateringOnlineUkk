@extends('layouts.app')

@section('title', 'Register - Catering Online')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full mb-4">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">Daftar Akun Pelanggan</h1>
                <p class="text-gray-600 mt-2">Bergabung dengan Catering Online</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2"></i>Nama Lengkap
                    </label>
                    <input 
                        type="text" 
                        name="name_pelanggan" 
                        value="{{ old('name_pelanggan') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                        placeholder="Nama lengkap"
                        required>
                    @error('name_pelanggan')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2"></i>Email
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                            placeholder="email@contoh.com"
                            required>
                        @error('email')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-2"></i>Telepon
                        </label>
                        <input 
                            type="text" 
                            name="telepon" 
                            value="{{ old('telepon') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                            placeholder="0812-3456-7890"
                            required>
                        @error('telepon')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2"></i>Alamat
                    </label>
                    <textarea 
                        name="alamat1" 
                        rows="2"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                        placeholder="Alamat lengkap"
                        required>{{ old('alamat1') }}</textarea>
                    @error('alamat1')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-2"></i>Tanggal Lahir
                    </label>
                    <input 
                        type="date" 
                        name="tgl_lahir" 
                        value="{{ old('tgl_lahir') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                    @error('tgl_lahir')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2"></i>Password
                        </label>
                        <input 
                            type="password" 
                            name="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                            placeholder="Min. 6 karakter"
                            required>
                        @error('password')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2"></i>Konfirmasi
                        </label>
                        <input 
                            type="password" 
                            name="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                            placeholder="Ulangi password"
                            required>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold py-3 rounded-lg hover:from-emerald-600 hover:to-teal-700 transition">
                    <i class="fas fa-user-plus mr-2"></i>Daftar sebagai Pelanggan
                </button>
            </form>

            <div class="my-6 flex items-center">
                <div class="flex-1 border-t border-gray-300"></div>
                <span class="px-4 text-gray-500 text-sm">atau</span>
                <div class="flex-1 border-t border-gray-300"></div>
            </div>

            <div class="text-center">
                <p class="text-gray-600">Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-emerald-600 font-medium hover:text-emerald-800">
                        Masuk disini
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection