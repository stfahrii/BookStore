<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Katalog') — Bookora</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
    @php
        $cart = session()->get('cart', []);
        $total_cart_items = is_array($cart) ? array_sum($cart) : 0;
    @endphp

    <header class="topbar">
        <div class="topbar-left">
            <button id="sidebar-toggle" class="sidebar-toggle-btn" title="Buka/Tutup Sidebar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <a href="{{ route('user.dashboard') }}" class="topbar-brand">
                <div class="topbar-logo-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <span class="topbar-title">Bookora</span>
            </a>
        </div>
        <div class="topbar-meta">
            <a href="{{ route('user.cart') }}" class="btn btn-secondary btn-sm" style="display: flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                Keranjang
                @if($total_cart_items > 0)
                    <span style="background: #E11D48; color: #fff; font-size: 11px; font-weight: 700; padding: 1px 6px; border-radius: 99px;">
                        {{ $total_cart_items }}
                    </span>
                @endif
            </a>

            <div class="topbar-user">
                <a href="{{ route('logout') }}" class="topbar-logout-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Keluar
                </a>
            </div>
        </div>
    </header>

    <div class="app-layout" id="app-layout">
        <aside class="sidebar" id="app-sidebar">
            <nav class="sidebar-nav">
                <a href="{{ route('user.dashboard') }}" class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" title="Katalog Buku">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span>Katalog Buku</span>
                </a>
                <a href="{{ route('user.cart') }}" class="nav-item {{ request()->routeIs('user.cart') ? 'active' : '' }}" title="Keranjang Belanja">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span>Keranjang Belanja {{ $total_cart_items > 0 ? "($total_cart_items)" : "" }}</span>
                </a>
                <a href="{{ route('user.pesanan_saya') }}" class="nav-item {{ request()->routeIs('user.pesanan_saya') ? 'active' : '' }}" title="Pesanan Saya">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <span>Pesanan Saya</span>
                </a>
                <a href="{{ route('user.riwayat') }}" class="nav-item {{ request()->routeIs('user.riwayat') ? 'active' : '' }}" title="Peminjaman Pustaka">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>Peminjaman Pustaka</span>
                </a>
                <a href="{{ route('user.profil') }}" class="nav-item {{ request()->routeIs('user.profil') ? 'active' : '' }}" title="Profil Saya">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Profil Saya</span>
                </a>
                <a href="{{ route('user.about') }}" class="nav-item {{ request()->routeIs('user.about') ? 'active' : '' }}" title="About Us">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span>About Us</span>
                </a>
                <a href="{{ route('user.contact') }}" class="nav-item {{ request()->routeIs('user.contact') ? 'active' : '' }}" title="Contact to Admin">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <span>Contact to Admin</span>
                </a>
            </nav>
        </aside>

        <main class="main-content" id="main-content">
            @include('layouts.alerts')

            @yield('content')
        </main>
    </div>

<script>
const toggleBtn = document.getElementById('sidebar-toggle');
const layout    = document.getElementById('app-layout');

if (localStorage.getItem('bookora-user-sidebar') === 'true') {
    layout.classList.add('sidebar-collapsed');
}

toggleBtn.addEventListener('click', () => {
    layout.classList.toggle('sidebar-collapsed');
    localStorage.setItem('bookora-user-sidebar',
        layout.classList.contains('sidebar-collapsed') ? 'true' : 'false'
    );
});
</script>
</body>
</html>
