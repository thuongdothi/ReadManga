@extends('layouts.app')

@section('title', 'Thanh Toán Đơn Hàng - MangaStream Shop')

@section('content')
<div class="container">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold m-0 text-light"><i class="fa-solid fa-file-invoice text-primary me-2"></i> Thanh Toán Đơn Hàng</h3>
            <p class="text-secondary small m-0">Điền thông tin nhận hàng và hoàn tất đơn đặt hàng</p>
        </div>
        <a href="{{ route('cart.view') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại giỏ hàng
        </a>
    </div>

    <form action="{{ route('checkout.placeOrder') }}" method="POST">
        @csrf

        <div class="row g-4">
            <!-- Left: Shipping Information & Payment Methods (7 cols) -->
            <div class="col-lg-7">
                <div class="card card-custom p-4 mb-4">
                    <h5 class="fw-bold text-light mb-3"><i class="fa-solid fa-location-dot text-primary me-2"></i> Thông Tin Nhận Hàng</h5>

                    <div class="mb-3">
                        <label class="form-label text-light small fw-semibold">Họ và tên người nhận <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control bg-dark text-light border-secondary @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', auth()->check() ? auth()->user()->name : '') }}" required placeholder="Ví dụ: Nguyễn Văn A">
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-light small fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="customer_phone" class="form-control bg-dark text-light border-secondary @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone') }}" required placeholder="Ví dụ: 0912345678">
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light small fw-semibold">Email nhận thông báo</label>
                            <input type="email" name="customer_email" class="form-control bg-dark text-light border-secondary" value="{{ old('customer_email', auth()->check() ? auth()->user()->email : '') }}" placeholder="email@example.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light small fw-semibold">Địa chỉ giao hàng chi tiết <span class="text-danger">*</span></label>
                        <textarea name="shipping_address" class="form-control bg-dark text-light border-secondary @error('shipping_address') is-invalid @enderror" rows="2" required placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label class="form-label text-light small fw-semibold">Ghi chú cho đơn hàng</label>
                        <textarea name="note" class="form-control bg-dark text-light border-secondary" rows="2" placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao...">{{ old('note') }}</textarea>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="card card-custom p-4">
                    <h5 class="fw-bold text-light mb-3"><i class="fa-solid fa-wallet text-primary me-2"></i> Phương Thức Thanh Toán</h5>

                    <div class="d-flex flex-column gap-3">
                        <!-- Option 1: COD -->
                        <label class="card bg-dark border-secondary p-3 d-flex flex-row align-items-center gap-3 cursor-pointer">
                            <input type="radio" name="payment_method" value="cod" class="form-check-input mt-0" checked>
                            <div>
                                <div class="fw-bold text-light">Thanh toán khi nhận hàng (COD)</div>
                                <small class="text-secondary">Nhận hàng, kiểm tra sản phẩm rồi thanh toán tiền mặt cho shipper.</small>
                            </div>
                        </label>

                        <!-- Option 2: Coins from Wallet -->
                        @php
                            $coinsNeeded = ceil($subtotal / 100);
                            $userCoins = auth()->check() ? auth()->user()->coin_balance : 0;
                        @endphp
                        <label class="card bg-dark border-secondary p-3 d-flex flex-row align-items-center gap-3 cursor-pointer">
                            <input type="radio" name="payment_method" value="coins" class="form-check-input mt-0" {{ !auth()->check() || $userCoins < $coinsNeeded ? 'disabled' : '' }}>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="fw-bold text-warning"><i class="fa-solid fa-coins me-1"></i> Thanh toán bằng Ví Xu Ảo</div>
                                    <span class="badge bg-warning bg-opacity-25 text-warning">{{ $coinsNeeded }} Xu</span>
                                </div>
                                <small class="text-secondary d-block">
                                    Trừ trực tiếp vào số dư ví tài khoản của bạn (Hiện có: <strong>{{ number_format($userCoins) }} Xu</strong>).
                                    @if(!auth()->check())
                                        <span class="text-danger fw-bold">(Cần đăng nhập)</span>
                                    @elseif($userCoins < $coinsNeeded)
                                        <span class="text-danger fw-bold">(Không đủ Xu - <a href="{{ route('wallet.index') }}" class="text-warning">Nạp ngay</a>)</span>
                                    @endif
                                </small>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary (5 cols) -->
            <div class="col-lg-5">
                <div class="card card-custom p-4">
                    <h5 class="fw-bold text-light mb-3">Kiểm Tra Đơn Hàng</h5>

                    <div class="d-flex flex-column gap-2 mb-3" style="max-height: 280px; overflow-y: auto;">
                        @foreach($cart as $item)
                            <div class="d-flex align-items-center gap-3 py-2 border-bottom border-secondary border-opacity-25">
                                <img src="{{ $item['image'] }}" width="45" height="45" class="rounded object-fit-cover" alt="{{ $item['name'] }}">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="text-light small text-truncate mb-0">{{ $item['name'] }}</h6>
                                    <small class="text-secondary">{{ number_format($item['price']) }} đ x {{ $item['quantity'] }}</small>
                                </div>
                                <div class="fw-bold text-light small">
                                    {{ number_format($item['price'] * $item['quantity']) }} đ
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mb-2 small text-secondary">
                        <span>Tạm tính:</span>
                        <span class="text-light fw-bold">{{ number_format($subtotal) }} đ</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 small text-secondary">
                        <span>Phí giao hàng:</span>
                        <span class="text-success fw-bold">MIỄN PHÍ</span>
                    </div>

                    <hr class="border-secondary my-2">

                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <span class="fw-bold text-light">Tổng thanh toán:</span>
                        <span class="fs-4 fw-extrabold text-danger">{{ number_format($subtotal) }} đ</span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">
                        <i class="fa-solid fa-check me-2"></i> Xác Nhận Đặt Hàng
                    </button>
                </div>
            </div>
        </div>

    </form>

</div>
@endsection
