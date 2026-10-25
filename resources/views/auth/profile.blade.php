@extends('layouts.app')

@section('title', 'Trang Cá Nhân - ' . $user->name)

@section('content')
<div class="container">

    <!-- Profile Header Card -->
    <div class="card card-custom p-4 mb-4">
        <div class="row align-items-center g-3">
            <div class="col-auto">
                <img src="{{ $user->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed='.urlencode($user->name) }}" class="rounded-circle border border-primary p-1" width="90" height="90" alt="Avatar">
            </div>
            <div class="col">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <h3 class="fw-bold text-light m-0">{{ $user->name }}</h3>
                    <span class="badge bg-primary text-uppercase">{{ $user->role }}</span>
                    @if($user->isVip())
                        <span class="badge-vip"><i class="fa-solid fa-crown me-1"></i> VIP</span>
                    @endif
                </div>
                <p class="text-secondary small mb-2">{{ $user->email }} • Tham gia từ: {{ $user->created_at->format('d/m/Y') }}</p>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge-coin fs-6"><i class="fa-solid fa-coins text-warning me-1"></i> {{ number_format($user->coin_balance) }} Xu</span>
                    <a href="{{ route('wallet.index') }}" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold">
                        <i class="fa-solid fa-bolt me-1"></i> Nạp Thêm Xu
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills mb-4 gap-2" id="profileTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active rounded-pill px-3" data-bs-toggle="pill" data-bs-target="#tab-history">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> Lịch Sử Đọc ({{ $readingHistories->count() }})
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link rounded-pill px-3" data-bs-toggle="pill" data-bs-target="#tab-favorites">
                <i class="fa-solid fa-heart me-1"></i> Truyện Theo Dõi ({{ $favorites->count() }})
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link rounded-pill px-3" data-bs-toggle="pill" data-bs-target="#tab-unlocked">
                <i class="fa-solid fa-key me-1"></i> Chương Đã Mua ({{ $unlockedChapters->count() }})
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link rounded-pill px-3" data-bs-toggle="pill" data-bs-target="#tab-orders">
                <i class="fa-solid fa-box-archive me-1"></i> Đơn Hàng Shop ({{ $orders->count() }})
            </button>
        </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content" id="profileTabsContent">

        <!-- Tab 1: Reading History -->
        <div class="tab-pane fade show active" id="tab-history">
            <div class="card card-custom p-4">
                <h5 class="fw-bold text-light mb-3">Lịch Sử Đọc Gần Đây</h5>
                <div class="row row-cols-1 row-cols-md-2 g-3">
                    @forelse($readingHistories as $hist)
                        <div class="col">
                            <div class="card bg-dark border-secondary p-3 d-flex flex-row align-items-center gap-3">
                                <img src="{{ $hist->comic->cover_image }}" width="60" height="80" class="rounded object-fit-cover" alt="{{ $hist->comic->title }}">
                                <div class="overflow-hidden flex-grow-1">
                                    <h6 class="text-truncate fw-bold mb-1">
                                        <a href="{{ route('comics.show', $hist->comic->slug) }}" class="text-light text-decoration-none">
                                            {{ $hist->comic->title }}
                                        </a>
                                    </h6>
                                    <small class="text-secondary d-block">Đang đọc: <strong>Chương {{ $hist->chapter->chapter_number }}</strong></small>
                                    <small class="text-secondary">{{ $hist->last_read_at->diffForHumans() }}</small>
                                </div>
                                <a href="{{ route('comics.read', [$hist->comic->slug, $hist->chapter->slug]) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                    Đọc tiếp
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-secondary py-4">Bạn chưa có lịch sử đọc truyện nào.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Tab 2: Favorites -->
        <div class="tab-pane fade" id="tab-favorites">
            <div class="card card-custom p-4">
                <h5 class="fw-bold text-light mb-3">Danh Sách Truyện Đang Theo Dõi</h5>
                <div class="row row-cols-2 row-cols-md-4 g-3">
                    @forelse($favorites as $fav)
                        <div class="col">
                            <div class="card bg-dark border-secondary p-2 h-100">
                                <img src="{{ $fav->cover_image }}" class="rounded w-100 object-fit-cover mb-2" style="height: 160px;" alt="{{ $fav->title }}">
                                <h6 class="small fw-bold text-truncate text-light mb-1">
                                    <a href="{{ route('comics.show', $fav->slug) }}" class="text-light text-decoration-none">{{ $fav->title }}</a>
                                </h6>
                                <small class="text-secondary mb-2">{{ $fav->latestChapters->first() ? 'Chương ' . $fav->latestChapters->first()->chapter_number : '' }}</small>
                                <a href="{{ route('comics.show', $fav->slug) }}" class="btn btn-primary btn-sm rounded-pill mt-auto">Xem truyện</a>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-secondary py-4">Bạn chưa theo dõi bộ truyện nào.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Tab 3: Unlocked Chapters -->
        <div class="tab-pane fade" id="tab-unlocked">
            <div class="card card-custom p-4">
                <h5 class="fw-bold text-light mb-3">Các Chương VIP Đã Mở Khóa Bằng Xu</h5>
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead>
                            <tr class="text-secondary small">
                                <th>Bộ Truyện</th>
                                <th>Chương Đã Mua</th>
                                <th>Xu Đã Chi</th>
                                <th>Ngày Mở Khóa</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($unlockedChapters as $unlocked)
                                <tr>
                                    <td><strong class="text-light">{{ $unlocked->chapter->comic->title }}</strong></td>
                                    <td>Chương {{ $unlocked->chapter->chapter_number }}</td>
                                    <td class="text-warning fw-bold">-{{ $unlocked->coins_spent }} Xu</td>
                                    <td class="text-secondary small">{{ $unlocked->unlocked_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('comics.read', [$unlocked->chapter->comic->slug, $unlocked->chapter->slug]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            Đọc lại
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-secondary">Bạn chưa mở khóa chương VIP nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 4: Orders -->
        <div class="tab-pane fade" id="tab-orders">
            <div class="card card-custom p-4">
                <h5 class="fw-bold text-light mb-3">Lịch Sử Mua Hàng Tại Shop</h5>
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead>
                            <tr class="text-secondary small">
                                <th>Mã Đơn</th>
                                <th>Sản Phẩm</th>
                                <th>Tổng Tiền</th>
                                <th>Thanh Toán</th>
                                <th>Vận Chuyển</th>
                                <th>Ngày Đặt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td><code>{{ $order->order_code }}</code></td>
                                    <td>
                                        @foreach($order->items as $it)
                                            <div class="small">{{ $it->product->name }} (x{{ $it->quantity }})</div>
                                        @endforeach
                                    </td>
                                    <td class="fw-bold text-danger">{{ number_format($order->total_amount) }} đ</td>
                                    <td>
                                        <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark text-capitalize">{{ $order->shipping_status }}</span>
                                    </td>
                                    <td class="text-secondary small">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-secondary">Bạn chưa có đơn đặt hàng nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
