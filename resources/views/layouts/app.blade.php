<!DOCTYPE html>
<html lang="vi" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MangaStream - Đọc Truyện Tranh Online & Manga Shop')</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts: Plus Jakarta Sans & Be Vietnam Pro (Clean, high legibility, perfect Vietnamese kerning) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --secondary-color: #ec4899;
            --accent-gold: #f59e0b;
            --bg-dark: #090d16;
            --card-dark: #131b2e;
            --card-dark-hover: #1b2640;
            --border-dark: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Be Vietnam Pro', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            letter-spacing: -0.01em;
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Light theme overrides when toggled */
        [data-bs-theme="light"] {
            --bg-dark: #f8fafc;
            --card-dark: #ffffff;
            --card-dark-hover: #f1f5f9;
            --border-dark: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        .navbar-custom {
            background: rgba(9, 13, 22, 0.92);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border-dark);
            z-index: 1030;
        }
        [data-bs-theme="light"] .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
        }

        .brand-logo {
            font-weight: 800;
            font-size: 1.38rem;
            background: linear-gradient(135deg, #6366f1 10%, #ec4899 90%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            letter-spacing: -0.02em;
        }

        .nav-link {
            font-weight: 500;
            white-space: nowrap;
            padding: 0.5rem 0.85rem !important;
            transition: color 0.15s ease;
        }

        .card-custom {
            background-color: var(--card-dark);
            border: 1px solid var(--border-dark);
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .card-custom:hover {
            border-color: rgba(99, 102, 241, 0.35);
        }

        .poster-card {
            transition: transform 0.22s ease, box-shadow 0.22s ease;
            border-radius: 12px;
        }
        .poster-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.45);
        }

        .aspect-poster {
            aspect-ratio: 3 / 4.2;
            object-fit: cover;
        }

        /* Typography & Line Clamping Helpers */
        .text-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .text-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.35;
        }

        .text-nowrap {
            white-space: nowrap !important;
        }

        .badge-vip {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #000;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            white-space: nowrap;
        }

        .badge-coin {
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid #f59e0b;
            color: #fbbf24;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }

        /* Live Search Dropdown */
        #search-results-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--card-dark);
            border: 1px solid var(--border-dark);
            border-radius: 10px;
            z-index: 1050;
            max-height: 420px;
            overflow-y: auto;
            box-shadow: 0 14px 30px rgba(0,0,0,0.55);
            display: none;
        }
        .search-result-item {
            padding: 10px 14px;
            border-bottom: 1px solid var(--border-dark);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-main);
            transition: background 0.15s;
        }
        .search-result-item:hover {
            background: rgba(99, 102, 241, 0.15);
        }

        .footer-custom {
            margin-top: auto;
            background: var(--card-dark);
            border-top: 1px solid var(--border-dark);
            padding: 45px 0 25px 0;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Main Navigation Bar (Clean, single-line, fully responsive) -->
    <nav class="navbar navbar-expand-xl navbar-custom sticky-top py-2">
        <div class="container-fluid px-xl-4 px-lg-3 px-2" style="max-width: 1440px;">
            <!-- Brand Logo -->
            <a class="brand-logo me-3" href="{{ route('home') }}">
                <i class="fa-solid fa-book-journal-whills text-primary"></i> MangaStream
            </a>

            <!-- Mobile Toggler -->
            <button class="navbar-toggler border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Collapsible Content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Navigation Links -->
                <ul class="navbar-nav me-auto mb-2 mb-xl-0 d-flex flex-wrap align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold text-primary' : '' }}" href="{{ route('home') }}">
                            <i class="fa-solid fa-house me-1"></i> Trang Chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('comics.*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('comics.index') }}">
                            <i class="fa-solid fa-compass me-1"></i> Khám Phá
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('shop.*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('shop.index') }}">
                            <i class="fa-solid fa-store me-1"></i> Manga Shop
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('wallet.*') ? 'active fw-bold text-warning' : '' }}" href="{{ route('wallet.index') }}">
                            <i class="fa-solid fa-coins text-warning me-1"></i> Nạp Xu
                        </a>
                    </li>
                </ul>

                <!-- Live Search Bar -->
                <div class="position-relative me-xl-3 my-2 my-xl-0 flex-grow-1" style="max-width: 300px; min-width: 200px;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent border-secondary text-secondary">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" id="live-search-input" class="form-control bg-transparent border-secondary text-light py-2" placeholder="Tìm tên truyện, tác giả..." autocomplete="off">
                    </div>
                    <div id="search-results-dropdown"></div>
                </div>

                <!-- Right Utility & User Options -->
                <div class="d-flex align-items-center gap-2 gap-lg-3 flex-wrap">
                    <!-- Theme Toggle Button -->
                    <button class="btn btn-sm btn-outline-secondary rounded-circle" id="theme-toggle-btn" title="Đổi giao diện Sáng/Tối" style="width: 34px; height: 34px;">
                        <i class="fa-solid fa-sun" id="theme-icon"></i>
                    </button>

                    <!-- Cart Button with Counter Badge -->
                    @php
                        $cartCount = array_sum(array_column(session('cart', []), 'quantity'));
                    @endphp
                    <a href="{{ route('cart.view') }}" class="btn btn-sm btn-outline-primary position-relative rounded-pill px-3 py-1 text-nowrap">
                        <i class="fa-solid fa-cart-shopping me-1"></i>
                        <span class="d-none d-sm-inline">Giỏ hàng</span>
                        <span id="cart-counter-badge" class="badge bg-danger rounded-pill ms-1 {{ $cartCount > 0 ? '' : 'd-none' }}">
                            {{ $cartCount }}
                        </span>
                    </a>

                    @auth
                        <!-- Coins Badge -->
                        <a href="{{ route('wallet.index') }}" class="badge-coin text-decoration-none text-nowrap">
                            <i class="fa-solid fa-coins text-warning me-1"></i>
                            <span id="navbar-coin-balance">{{ number_format(auth()->user()->coin_balance) }}</span> Xu
                        </a>

                        @if(auth()->user()->isVip())
                            <span class="badge-vip" title="Gói Hội viên VIP đang hoạt động">
                                <i class="fa-solid fa-crown me-1"></i> VIP
                            </span>
                        @endif

                        <!-- User Profile Dropdown -->
                        <div class="dropdown">
                            <a class="d-flex align-items-center text-decoration-none dropdown-toggle text-light text-nowrap" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ auth()->user()->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed='.urlencode(auth()->user()->name) }}" class="rounded-circle border border-primary me-2" width="32" height="32" alt="Avatar">
                                <span class="d-none d-sm-inline fw-semibold text-truncate" style="max-width: 120px;">{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <div class="px-3 py-2 border-bottom">
                                        <div class="fw-bold text-truncate" style="max-width: 200px;">{{ auth()->user()->name }}</div>
                                        <small class="text-secondary text-truncate d-block" style="max-width: 200px;">{{ auth()->user()->email }}</small>
                                    </div>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('profile') }}">
                                        <i class="fa-solid fa-user me-2 text-primary"></i> Trang Cá Nhân
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('wallet.index') }}">
                                        <i class="fa-solid fa-wallet me-2 text-warning"></i> Ví Tiền & Nạp Xu
                                    </a>
                                </li>
                                @if(auth()->user()->isAdmin())
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item py-2 text-danger fw-bold" href="{{ route('admin.dashboard') }}">
                                            <i class="fa-solid fa-gauge-high me-2"></i> Trang Quản Trị Admin
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng Xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <!-- Guest Auth Buttons -->
                        <div class="d-flex gap-2 text-nowrap">
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3 rounded-pill">Đăng Nhập</a>
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3 rounded-pill">Đăng Ký</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Body -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Modern Footer -->
    <footer class="footer-custom">
        <div class="container" style="max-width: 1400px;">
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="brand-logo mb-3">
                        <i class="fa-solid fa-book-journal-whills text-primary"></i> MangaStream
                    </h5>
                    <p class="text-secondary small">
                        Hệ thống đọc truyện tranh trực tuyến chuẩn quốc tế kết hợp phân phối ấn phẩm Manga Merch bản quyền. Đồ án môn học Lập trình Web PHP & MySQL thực hiện theo mô hình MVC hiện đại trên nền tảng Laravel.
                    </p>
                    <div class="d-flex gap-3 text-secondary">
                        <a href="#" class="text-secondary fs-5"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="fa-brands fa-discord"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="fa-brands fa-github"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <h6 class="fw-bold mb-3 text-light">Khám Phá</h6>
                    <ul class="list-unstyled small text-secondary">
                        <li class="mb-2"><a href="{{ route('comics.index') }}" class="text-decoration-none text-secondary">Truyện Mới Nhất</a></li>
                        <li class="mb-2"><a href="{{ route('comics.index', ['sort' => 'views']) }}" class="text-decoration-none text-secondary">Bảng Xếp Hạng</a></li>
                        <li class="mb-2"><a href="{{ route('shop.index') }}" class="text-decoration-none text-secondary">Cửa Hàng Merch</a></li>
                        <li class="mb-2"><a href="{{ route('wallet.index') }}" class="text-decoration-none text-secondary">Nạp Xu & VIP</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <h6 class="fw-bold mb-3 text-light">Chính Sách</h6>
                    <ul class="list-unstyled small text-secondary">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Điều Khoản Dịch Vụ</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Chính Sách Bảo Mật</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Bản Quyền Nội Dung</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Liên Hệ Nhóm Dịch</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-12">
                    <h6 class="fw-bold mb-3 text-light">Đồ Án Điểm A (Laravel 10 & MySQL)</h6>
                    <p class="small text-secondary mb-2">
                        • <strong>Đề tài:</strong> Hệ thống Website truyện tranh (Kiến trúc MVC & Thương Mại Điện Tử).<br>
                        • <strong>Công nghệ:</strong> Laravel 10 + MySQL 8.0 + Ajax / Fetch API + Bootstrap 5.3.<br>
                        • <strong>Điểm nhấn:</strong> Nạp ví xu, Mở khóa chương VIP, Giỏ hàng Merch, Đặt hàng & Quản trị Admin.
                    </p>
                </div>
            </div>
            <div class="border-top border-secondary border-opacity-25 pt-3 text-center small text-secondary">
                © {{ date('Y') }} MangaStream Project. All rights reserved. Xây dựng phục vụ mục đích học tập và bảo vệ đồ án.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Setup CSRF Token for jQuery Ajax
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Flash message popup via SweetAlert2
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                background: '#131b2e',
                color: '#fff'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: "{{ session('error') }}",
                background: '#131b2e',
                color: '#fff'
            });
        @endif

        @if(session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Thông báo',
                text: "{{ session('info') }}",
                background: '#131b2e',
                color: '#fff'
            });
        @endif

        // Theme Toggle (Dark / Light)
        const themeBtn = document.getElementById('theme-toggle-btn');
        const themeIcon = document.getElementById('theme-icon');
        const savedTheme = localStorage.getItem('manga_theme') || 'dark';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
        updateThemeIcon(savedTheme);

        themeBtn.addEventListener('click', function() {
            const current = document.documentElement.getAttribute('data-bs-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', next);
            localStorage.setItem('manga_theme', next);
            updateThemeIcon(next);
        });

        function updateThemeIcon(theme) {
            if (theme === 'light') {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            } else {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
        }

        // Live Search Ajax
        let searchTimer;
        const searchInput = document.getElementById('live-search-input');
        const searchDropdown = document.getElementById('search-results-dropdown');

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            const query = this.value.trim();
            if (query.length < 2) {
                searchDropdown.style.display = 'none';
                searchDropdown.innerHTML = '';
                return;
            }

            searchTimer = setTimeout(() => {
                fetch(`{{ route('api.search') }}?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) {
                            searchDropdown.innerHTML = '<div class="p-3 text-secondary text-center small">Không tìm thấy kết quả phù hợp</div>';
                        } else {
                            let html = '';
                            data.forEach(item => {
                                const coverSrc = (item.cover_image.startsWith('http') || item.cover_image.startsWith('/'))
                                    ? item.cover_image
                                    : '/' + item.cover_image;
                                html += `
                                    <a href="${item.url}" class="search-result-item">
                                        <img src="${coverSrc}" width="42" height="56" class="rounded object-fit-cover shadow-sm flex-shrink-0" alt="${item.title}">
                                        <div class="overflow-hidden">
                                            <div class="fw-bold small text-truncate" style="max-width: 210px;">${item.title}</div>
                                            <small class="text-secondary text-truncate d-block">${item.latest_chapter} • ${item.author || 'Tác giả'}</small>
                                        </div>
                                    </a>
                                `;
                            });
                            searchDropdown.innerHTML = html;
                        }
                        searchDropdown.style.display = 'block';
                    });
            }, 300);
        });

        // Close search dropdown on click outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
                searchDropdown.style.display = 'none';
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
