@extends('layouts.app')

@section('title', 'Admin Dashboard - Bảng Điều Khiển Quản Trị')

@section('content')
<div class="container-fluid px-lg-5">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold m-0 text-light"><i class="fa-solid fa-gauge-high text-danger me-2"></i> Hệ Thống Quản Trị Admin</h3>
            <p class="text-secondary small m-0">Tổng quan số liệu thống kê hệ thống, nội dung truyện và doanh thu thương mại</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.comics.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fa-solid fa-plus me-1"></i> Thêm Truyện Mới
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="fa-solid fa-globe me-1"></i> Xem Website
            </a>
        </div>
    </div>

    <!-- Quick Navigation Pills for Admin -->
    <div class="card card-custom p-3 mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                <i class="fa-solid fa-chart-pie me-1"></i> Báo Cáo Thống Kê
            </a>
            <a href="{{ route('admin.comics.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="fa-solid fa-book me-1"></i> Quản Lý Truyện & Chương
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="fa-solid fa-cart-flatbed me-1"></i> Quản Lý Đơn Hàng Shop
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="fa-solid fa-users me-1"></i> Quản Lý Người Dùng
            </a>
        </div>
    </div>

    <!-- 6 Metric Cards Row -->
    <div class="row g-3 mb-4">
        <!-- Total Users -->
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card card-custom p-3 text-center h-100">
                <div class="d-inline-flex p-3 rounded-circle bg-primary bg-opacity-10 text-primary mb-2 mx-auto">
                    <i class="fa-solid fa-users fs-4"></i>
                </div>
                <div class="fs-4 fw-extrabold text-light">{{ number_format($totalUsers) }}</div>
                <small class="text-secondary">Thành Viên</small>
            </div>
        </div>

        <!-- Total Comics -->
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card card-custom p-3 text-center h-100">
                <div class="d-inline-flex p-3 rounded-circle bg-info bg-opacity-10 text-info mb-2 mx-auto">
                    <i class="fa-solid fa-book-journal-whills fs-4"></i>
                </div>
                <div class="fs-4 fw-extrabold text-light">{{ number_format($totalComics) }}</div>
                <small class="text-secondary">Bộ Truyện</small>
            </div>
        </div>

        <!-- Total Chapters -->
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card card-custom p-3 text-center h-100">
                <div class="d-inline-flex p-3 rounded-circle bg-secondary bg-opacity-10 text-secondary mb-2 mx-auto">
                    <i class="fa-solid fa-layer-group fs-4"></i>
                </div>
                <div class="fs-4 fw-extrabold text-light">{{ number_format($totalChapters) }}</div>
                <small class="text-secondary">Chương Truyện</small>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card card-custom p-3 text-center h-100">
                <div class="d-inline-flex p-3 rounded-circle bg-success bg-opacity-10 text-success mb-2 mx-auto">
                    <i class="fa-solid fa-truck-fast fs-4"></i>
                </div>
                <div class="fs-4 fw-extrabold text-light">{{ number_format($totalOrders) }}</div>
                <small class="text-secondary">Đơn Hàng Shop</small>
            </div>
        </div>

        <!-- Coin Recharges Revenue -->
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card card-custom p-3 text-center h-100">
                <div class="d-inline-flex p-3 rounded-circle bg-warning bg-opacity-10 text-warning mb-2 mx-auto">
                    <i class="fa-solid fa-coins fs-4"></i>
                </div>
                <div class="fs-4 fw-extrabold text-warning">{{ number_format($totalCoinRecharge) }} đ</div>
                <small class="text-secondary">Doanh Thu Nạp Xu</small>
            </div>
        </div>

        <!-- Shop Merchandise Revenue -->
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card card-custom p-3 text-center h-100">
                <div class="d-inline-flex p-3 rounded-circle bg-danger bg-opacity-10 text-danger mb-2 mx-auto">
                    <i class="fa-solid fa-money-bill-trend-up fs-4"></i>
                </div>
                <div class="fs-4 fw-extrabold text-danger">{{ number_format($totalRevenue) }} đ</div>
                <small class="text-secondary">Doanh Thu Bán Hàng</small>
            </div>
        </div>
    </div>

    <!-- Data Tables Row (Recent Orders & Transactions) -->
    <div class="row g-4 mb-4">
        
        <!-- Recent Orders (6 cols) -->
        <div class="col-lg-6">
            <div class="card card-custom p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold text-light m-0"><i class="fa-solid fa-box-open text-primary me-2"></i> Đơn Hàng Mới Nhất</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">Xem tất cả</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 small">
                        <thead>
                            <tr class="text-secondary">
                                <th>Mã Đơn</th>
                                <th>Khách Hàng</th>
                                <th>Tổng Tiền</th>
                                <th>Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td><code>{{ $order->order_code }}</code></td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td class="fw-bold text-danger">{{ number_format($order->total_amount) }} đ</td>
                                    <td>
                                        <span class="badge bg-info text-dark text-capitalize">{{ $order->shipping_status }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-3 text-secondary">Chưa có đơn hàng nào.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Coin Topups (6 cols) -->
        <div class="col-lg-6">
            <div class="card card-custom p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold text-light m-0"><i class="fa-solid fa-coins text-warning me-2"></i> Giao Dịch Nạp Xu Gần Nhất</h5>
                    <span class="badge bg-warning bg-opacity-25 text-warning">Realtime Log</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 small">
                        <thead>
                            <tr class="text-secondary">
                                <th>Thành Viên</th>
                                <th>Số Tiền</th>
                                <th>Xu Nhận</th>
                                <th>Phương Thức</th>
                                <th>Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $tx)
                                <tr>
                                    <td>{{ $tx->user->name ?? 'Người dùng' }}</td>
                                    <td class="fw-bold">{{ number_format($tx->amount_vnd) }} đ</td>
                                    <td class="text-warning fw-bold">+{{ number_format($tx->coins) }} Xu</td>
                                    <td><span class="badge bg-secondary text-uppercase">{{ $tx->payment_method }}</span></td>
                                    <td>
                                        <span class="badge {{ $tx->status === 'completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $tx->status === 'completed' ? 'Thành công' : 'Chờ xử lý' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-3 text-secondary">Chưa có giao dịch nạp xu nào.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
