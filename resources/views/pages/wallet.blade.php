@extends('layouts.app')

@section('title', 'Ví Tiền & Nạp Xu - MangaStream')

@section('content')
<div class="container">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold m-0 text-light"><i class="fa-solid fa-wallet text-warning me-2"></i> Ví Tiền & Nạp Xu</h3>
            <p class="text-secondary small m-0">Quản lý số dư Xu ảo, nâng cấp Hội viên VIP và mở khóa truyện không giới hạn</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="fa-solid fa-arrow-left me-1"></i> Trang chủ
        </a>
    </div>

    <!-- Balance & VIP Status Cards Row -->
    <div class="row g-4 mb-5">
        
        <!-- Coin Balance Card -->
        <div class="col-md-6">
            <div class="card card-custom p-4 h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-color: #f59e0b;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="text-secondary small text-uppercase fw-bold">Số Dư Khả Dụng</span>
                        <div class="display-5 fw-extrabold text-warning my-1">
                            <i class="fa-solid fa-coins me-2"></i>{{ number_format($user->coin_balance) }} <span class="fs-4">Xu</span>
                        </div>
                        <small class="text-secondary">Dùng để mở khóa các chương VIP và thanh toán đơn hàng tại Shop.</small>
                    </div>
                    <span class="badge bg-warning bg-opacity-25 text-warning px-3 py-2 rounded-pill">
                        <i class="fa-solid fa-shield-halved me-1"></i> Ví An Toàn
                    </span>
                </div>
            </div>
        </div>

        <!-- VIP Membership Card -->
        <div class="col-md-6">
            <div class="card card-custom p-4 h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #2e1065, #1e1b4b); border-color: #a855f7;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="text-secondary small text-uppercase fw-bold">Trạng Thái Hội Viên</span>
                        <h4 class="fw-bold text-light my-1">
                            @if($user->isVip())
                                <span class="text-warning"><i class="fa-solid fa-crown me-2"></i> VIP Member Đang Hoạt Động</span>
                            @else
                                <span class="text-secondary"><i class="fa-solid fa-user me-2"></i> Thành Viên Thường</span>
                            @endif
                        </h4>
                    </div>
                    @if($user->isVip())
                        <span class="badge-vip"><i class="fa-solid fa-check me-1"></i> ĐÃ KÍCH HOẠT</span>
                    @endif
                </div>

                @if($user->isVip())
                    <p class="small text-secondary mb-3">
                        Hạn dùng đến: <strong class="text-light">{{ $user->vip_expires_at->format('d/m/Y H:i') }}</strong> (còn {{ $user->vip_expires_at->diffForHumans() }}).
                    </p>
                @else
                    <p class="small text-secondary mb-3">
                        Nâng cấp VIP 30 ngày chỉ với <strong>500 Xu</strong> để đọc toàn bộ truyện VIP không giới hạn!
                    </p>
                @endif

                <form action="{{ route('wallet.buyVip') }}" method="POST" class="mt-auto">
                    @csrf
                    <button type="submit" class="btn btn-warning fw-bold px-4 rounded-pill" onclick="return confirm('Bạn có muốn dùng 500 Xu để gia hạn/kích hoạt 30 ngày VIP?')">
                        <i class="fa-solid fa-crown me-1"></i> {{ $user->isVip() ? 'Gia Hạn Thêm 30 Ngày (500 Xu)' : 'Kích Hoạt VIP 30 Ngày (500 Xu)' }}
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- Top-up Coin Packages Selection -->
    <div class="card card-custom p-4 mb-5">
        <h4 class="fw-bold text-light mb-3"><i class="fa-solid fa-bolt text-warning me-2"></i> Chọn Gói Nạp Xu</h4>
        <p class="text-secondary small mb-4">
            Hỗ trợ thanh toán nhanh qua MoMo, VNPay và Quét mã QR Ngân hàng (Chuyển khoản tức thì).
        </p>

        <form action="{{ route('wallet.topup') }}" method="POST">
            @csrf

            <!-- Packages Cards -->
            <div class="row g-3 mb-4">
                @foreach($packages as $idx => $pkg)
                    <div class="col-md-3 col-sm-6">
                        <label class="w-100 h-100 cursor-pointer">
                            <input type="radio" name="package_id" value="{{ $pkg->id }}" class="btn-check" {{ $idx == 1 ? 'checked' : '' }}>
                            <div class="card card-custom p-3 text-center h-100 border-secondary position-relative pkg-card">
                                @if($pkg->bonus_coins > 0)
                                    <span class="position-absolute top-0 start-50 translate-middle badge bg-danger rounded-pill">
                                        +{{ $pkg->bonus_coins }} Xu Thưởng
                                    </span>
                                @endif

                                <h6 class="fw-bold text-light mt-2">{{ $pkg->name }}</h6>
                                <div class="my-2">
                                    <span class="fs-4 fw-extrabold text-warning">{{ number_format($pkg->coins_awarded) }}</span>
                                    <small class="text-secondary">Xu</small>
                                </div>
                                <div class="badge bg-dark border border-secondary text-light py-2 rounded-pill mt-auto fw-bold">
                                    {{ number_format($pkg->price_vnd) }} đ
                                </div>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>

            <!-- Payment Method Selection -->
            <h6 class="fw-bold text-light mb-3">Phương thức thanh toán:</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="w-100 cursor-pointer">
                        <input type="radio" name="payment_method" value="momo" class="btn-check" checked>
                        <div class="card card-custom p-3 d-flex flex-row align-items-center gap-3 method-card">
                            <i class="fa-solid fa-mobile-screen text-danger fs-3"></i>
                            <div>
                                <div class="fw-bold text-light">Ví MoMo</div>
                                <small class="text-secondary">Quét mã QR MoMo Sandbox</small>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="col-md-4">
                    <label class="w-100 cursor-pointer">
                        <input type="radio" name="payment_method" value="vnpay" class="btn-check">
                        <div class="card card-custom p-3 d-flex flex-row align-items-center gap-3 method-card">
                            <i class="fa-solid fa-credit-card text-primary fs-3"></i>
                            <div>
                                <div class="fw-bold text-light">VNPay / ATM</div>
                                <small class="text-secondary">Thẻ nội địa & Quốc tế</small>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="col-md-4">
                    <label class="w-100 cursor-pointer">
                        <input type="radio" name="payment_method" value="bank_qr" class="btn-check">
                        <div class="card card-custom p-3 d-flex flex-row align-items-center gap-3 method-card">
                            <i class="fa-solid fa-qrcode text-success fs-3"></i>
                            <div>
                                <div class="fw-bold text-light">Chuyển Khoản QR</div>
                                <small class="text-secondary">VietQR mọi ngân hàng</small>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-warning btn-lg px-5 rounded-pill fw-bold">
                    <i class="fa-solid fa-lock me-2"></i> Tiến Hành Nạp Tiền
                </button>
            </div>
        </form>
    </div>

    <!-- Recent Topup Transactions -->
    <div class="card card-custom p-4">
        <h5 class="fw-bold text-light mb-3"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i> Lịch Sử Nạp Xu Gần Đây</h5>
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr class="text-secondary">
                        <th>Mã Giao Dịch</th>
                        <th>Số Tiền</th>
                        <th>Xu Nhận</th>
                        <th>Phương Thức</th>
                        <th>Trạng Thái</th>
                        <th>Thời Gian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                        <tr>
                            <td><code>{{ $tx->transaction_code }}</code></td>
                            <td class="fw-bold">{{ number_format($tx->amount_vnd) }} đ</td>
                            <td class="text-warning fw-bold">+{{ number_format($tx->coins) }} Xu</td>
                            <td><span class="badge bg-secondary text-uppercase">{{ $tx->payment_method }}</span></td>
                            <td>
                                @if($tx->status === 'completed')
                                    <span class="badge bg-success">Thành công</span>
                                @elseif($tx->status === 'pending')
                                    <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                @else
                                    <span class="badge bg-danger">Thất bại</span>
                                @endif
                            </td>
                            <td class="text-secondary small">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-secondary">Chưa có giao dịch nạp xu nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    .cursor-pointer { cursor: pointer; }
    .btn-check:checked + .pkg-card {
        border-color: #f59e0b !important;
        background-color: rgba(245, 158, 11, 0.1) !important;
    }
    .btn-check:checked + .method-card {
        border-color: #6366f1 !important;
        background-color: rgba(99, 102, 241, 0.1) !important;
    }
</style>
@endpush
