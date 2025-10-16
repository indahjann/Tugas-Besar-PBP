<nav class="admin-navbar">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Side - Logo & Navigation -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                        <span class="text-white text-2xl font-bold">BUKUKU</span>
                        <span class="ml-2 px-2 py-1 bg-blue-600 text-white text-xs rounded-full">Admin</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:ml-10 md:flex md:space-x-4">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        Dashboard
                    </a>

                    <!-- Kelola Produk/Buku -->
                    <div class="relative admin-dropdown" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="admin-nav-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                            <i class="fas fa-book mr-2"></i>
                            Kelola Produk
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        
                        <div x-show="open" 
                             x-cloak
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="admin-dropdown-menu">
                            <a href="{{ route('admin.books.index') }}" class="admin-dropdown-item">
                                <i class="fas fa-list mr-2"></i>
                                Daftar Produk
                            </a>
                            <a href="{{ route('admin.books.create') }}" class="admin-dropdown-item">
                                <i class="fas fa-plus-circle mr-2"></i>
                                Tambah Produk
                            </a>
                        </div>
                    </div>

                    <!-- Kelola Pesanan -->
                    <a href="{{ route('admin.orders.index') }}" 
                       class="admin-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Kelola Pesanan
                        @php
                            $pendingOrdersCount = \App\Models\Order::where('status', 'pending')->count();
                        @endphp
                        @if($pendingOrdersCount > 0)
                            <span class="ml-2 px-2 py-1 bg-red-500 text-white text-xs rounded-full">
                                {{ $pendingOrdersCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Kelola Kategori -->
                    <a href="{{ route('admin.categories.index') }}" 
                       class="admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags mr-2"></i>
                        Kategori
                    </a>
                </div>
            </div>

            <!-- Right Side - User Menu -->
            <div class="flex items-center">
                <!-- View Site -->
                <a href="{{ route('books.index') }}" 
                   class="hidden md:block mr-4 px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md transition">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Lihat Situs
                </a>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center text-gray-300 hover:text-white focus:outline-none focus:text-white">
                        <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold mr-2">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <span class="hidden md:block mr-1">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <div x-show="open"
                         x-cloak
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        
                        <div class="px-4 py-2 text-xs text-gray-500 border-b">
                            Administrator
                        </div>
                        
                        <a href="{{ route('profile.edit') }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i>
                            Profile
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button @click="open = !open" 
                        type="button" 
                        class="text-gray-300 hover:text-white focus:outline-none focus:text-white"
                        x-data="{ open: false }">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="open"
         x-cloak
         x-data="{ open: false }"
         class="md:hidden bg-gray-700">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}" 
               class="block px-3 py-2 text-white hover:bg-gray-600 rounded-md">
                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
            </a>
            <a href="{{ route('admin.books.index') }}" 
               class="block px-3 py-2 text-white hover:bg-gray-600 rounded-md">
                <i class="fas fa-book mr-2"></i>Kelola Produk
            </a>
            <a href="{{ route('admin.books.create') }}" 
               class="block px-3 py-2 text-white hover:bg-gray-600 rounded-md">
                <i class="fas fa-plus-circle mr-2"></i>Tambah Produk
            </a>
            <a href="{{ route('admin.orders.index') }}" 
               class="block px-3 py-2 text-white hover:bg-gray-600 rounded-md">
                <i class="fas fa-shopping-bag mr-2"></i>Kelola Pesanan
            </a>
            <a href="{{ route('admin.categories.index') }}" 
               class="block px-3 py-2 text-white hover:bg-gray-600 rounded-md">
                <i class="fas fa-tags mr-2"></i>Kategori
            </a>
            <a href="{{ route('books.index') }}" 
               class="block px-3 py-2 text-white hover:bg-gray-600 rounded-md">
                <i class="fas fa-external-link-alt mr-2"></i>Lihat Situs
            </a>
        </div>
    </div>
</nav>
