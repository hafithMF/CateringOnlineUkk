<nav class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-utensils text-white text-xl"></i>
                </div>
                <span class="text-2xl font-bold text-gray-800">CateringOnline</span>
            </a>

            <!-- Menu -->
            <div class="hidden md:flex items-center space-x-8">
                @auth('pelanggan')
                    <!-- Menu untuk pelanggan -->
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-emerald-600 font-medium">
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                    <a href="{{ route('paket') }}" class="text-gray-700 hover:text-emerald-600 font-medium">
                        <i class="fas fa-box mr-2"></i>Paket
                    </a>
                    <a href="{{ route('pesanan-saya') }}" class="text-gray-700 hover:text-emerald-600 font-medium">
                        <i class="fas fa-shopping-bag mr-2"></i>Pesanan Saya
                    </a>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-emerald-600"></i>
                        </div>
                        <a href="{{ route('profile') }}" class="text-gray-700 hover:text-emerald-600 font-medium">
                            {{ Auth::guard('pelanggan')->user()->name_pelanggan }}
                        </a>
                    </div>
                @else
                    <!-- Menu untuk pengunjung -->
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-emerald-600 font-medium">
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                    <a href="{{ route('paket') }}" class="text-gray-700 hover:text-emerald-600 font-medium">
                        <i class="fas fa-box mr-2"></i>Paket
                    </a>
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-emerald-600 font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-6 py-2 rounded-lg hover:from-emerald-600 hover:to-teal-700">
                        <i class="fas fa-user-plus mr-2"></i>Daftar
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>