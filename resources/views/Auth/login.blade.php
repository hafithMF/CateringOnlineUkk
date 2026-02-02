@extends('layouts.app')

@section('title', 'Login - Catering Online')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full mb-4">
                    <i class="fas fa-utensils text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">Catering Online</h1>
                <p class="text-gray-600 mt-2">Masuk untuk melanjutkan</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                           placeholder="Masukkan email"
                           required>
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input type="password" name="password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                           placeholder="••••••••"
                           required>
                    @error('password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold py-3 rounded-lg hover:from-emerald-600 hover:to-teal-700 transition">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                </button>
            </form>

            <!-- Divider -->
            <div class="my-6 flex items-center">
                <div class="flex-1 border-t border-gray-300"></div>
                <span class="px-4 text-gray-500 text-sm">atau</span>
                <div class="flex-1 border-t border-gray-300"></div>
            </div>

            <!-- Register Link -->
            <div class="text-center">
                <p class="text-gray-600">Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-emerald-600 font-medium hover:text-emerald-800">
                        Daftar sebagai Pelanggan
                    </a>
                </p>
            </div>

        </div>
    </div>
</div>
@endsection