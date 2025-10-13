<nav x-data="{ open: false }" class="bukuku-navbar">
    <div class="navbar-container">
        <div class="navbar-content">
            <!-- Logo Section -->
            <div class="logo-section">
                
                <a href="/" class="logo-text" data-ajax-link>BUKUKU</a>
            </div>

            <!-- Navigation Links -->
            <div class="nav-links">
                <a href="/contact" class="nav-link" data-ajax-link>Contact</a>
                <div class="categories-dropdown" id="categoriesDropdown">
                    <button type="button" class="nav-link categories-trigger" id="categoriesBtn">
                        Categories
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="categories-dropdown-menu" id="categoriesMenu">
                        <a href="/categories" class="dropdown-item-cat">
                            <i class="fas fa-th-large"></i> All Categories
                        </a>
                        <div class="dropdown-divider"></div>
                        @php
                            $categories = App\Models\Category::orderBy('name')->get();
                        @endphp
                        @foreach($categories as $category)
                            <a href="/categories?category={{ $category->id }}" class="dropdown-item-cat">
                                <i class="fas fa-bookmark"></i> {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Search Bar -->
            <div class="search-bar">
                <i class="fas fa-search search-icon-input"></i>
                <input type="text" class="search-input" placeholder="Search books, authors..." value="{{ request('q', '') }}" aria-label="Search books">
            </div>

            <!-- User Actions -->
            <div class="user-actions">
                <a href="{{ route('cart.index') }}" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-badge">0</span>
                </a>

                @auth
                    <div class="user-dropdown" data-user-authenticated="true" data-user-name="{{ Auth::user()->name ?? Auth::user()->username }}">
                        <button class="user-trigger">
                            <div class="user-avatar">{{ substr(Auth::user()->name ?? Auth::user()->username ?? 'U', 0, 1) }}</div>
                            <span>{{ Auth::user()->name ?? Auth::user()->username ?? 'User' }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fas fa-user"></i> Profile
                            </a>
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('dashboard') }}" class="dropdown-item">
                                    <i class="fas fa-dashboard"></i> Dashboard
                                </a>
                            @endif
                            <a href="{{ route('wishlist.index') }}" class="dropdown-item">
                                <i class="fas fa-heart"></i> Wishlist
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-shopping-bag"></i> Orders
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" class="dropdown-item"
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="auth-links">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="auth-link">Login</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="auth-link primary">Register</a>
                        @endif
                    </div>
                @endauth

                <!-- Mobile Menu Toggle -->
                <button class="mobile-toggle" @click="open = !open">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'show': open}" class="mobile-menu">
        <div class="mobile-nav-links">
            <a href="/" class="mobile-nav-link" data-ajax-link>Home</a>
            <a href="/books" class="mobile-nav-link" data-ajax-link>Books</a>
            <a href="/categories" class="mobile-nav-link" data-ajax-link>Categories</a>
            <a href="/about" class="mobile-nav-link" data-ajax-link>About</a>
            <a href="/contact" class="mobile-nav-link" data-ajax-link>Contact</a>
        </div>

        @auth
            <div class="mobile-auth-section">
                <div class="px-4 mb-3">
                    <div class="text-white font-medium">{{ Auth::user()->name ?? Auth::user()->username ?? 'User' }}</div>
                    <div class="text-gray-300 text-sm">{{ Auth::user()->email }}</div>
                </div>
                
                <a href="{{ route('profile.edit') }}" class="mobile-nav-link">
                    <i class="fas fa-user"></i> Profile
                </a>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('dashboard') }}" class="mobile-nav-link">
                        <i class="fas fa-dashboard"></i> Dashboard
                    </a>
                @endif
                <a href="{{ route('wishlist.index') }}" class="mobile-nav-link">
                    <i class="fas fa-heart"></i> Wishlist
                </a>
                <a href="#" class="mobile-nav-link">
                    <i class="fas fa-shopping-bag"></i> Orders
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="mobile-nav-link"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </form>
            </div>
        @else
            <div class="mobile-auth-section">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="mobile-auth-link">Login</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="mobile-auth-link">Register</a>
                @endif
            </div>
        @endauth
    </div>
</nav>
