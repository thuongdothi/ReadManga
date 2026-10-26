@extends('layouts.app')

@section('title', 'Cổng Thanh Toán Mô Phỏng - ' . $transaction->transaction_code)

@section('content')
<div class="container" style="max-width: 680px;">

    <div class="card card-custom p-4 text-center my-4 shadow-lg border-primary">
        <div class="mb-3">
            <span class="badge bg-primary px-3 py-2 rounded-pill fs-6">
                <i class="fa-solid fa-credit-card me-1"></i> CỔNG THANH TOÁN SANDBOX
            </span>
        </div>

        <h3 class="fw-bold text-light mb-1">Xác Nhận Nạp Xu</h3>
        <p class="text-secondary small mb-4">Giao dịch được bảo vệ và mã hóa SSL 256-bit</p>

        <!-- QR Code Simulation Box -->
        <div class="p-3 bg-white rounded-4 d-inline-block shadow mx-auto mb-4">
            <!-- Dynamic QR Code pointing to transaction code -->
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(route('wallet.simulateSuccess', $transaction->transaction_code)) }}" width="200" height="200" alt="Payment QR Code">
            <div class="text-dark small fw-bold mt-2">Quét mã bằng ứng dụng ngân hàng / MoMo</div>
        </div>

        <!-- Bill Details -->
        <div class="card bg-dark border-secondary p-3 text-start mb-4">
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-secondary">Mã đơn giao dịch:</span>
                <span class="text-light fw-bold"><code>{{ $transaction->transaction_code }}</code></span>
            </div>
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-secondary">Gói nạp:</span>
                <span class="text-light fw-bold">{{ $package->name }} (+{{ number_format($package->total_coins) }} Xu)</span>
            </div>
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-secondary">Phương thức:</span>
                <span class="badge bg-secondary text-uppercase">{{ $transaction->payment_method }}</span>
            </div>
            <div class="border-top border-secondary pt-2 d-flex justify-content-between">
                <span class="fw-bold text-light">Tổng thanh toán:</span>
                <span class="fs-5 fw-bold text-warning">{{ number_format($transaction->amount_vnd) }} VNĐ</span>
            </div>
        </div>

        <!-- Simulated Instant Confirm Button (Highlight for Teacher Demo) -->
        <div class="alert alert-info border-info text-start small mb-4">
            <i class="fa-solid fa-circle-question me-1"></i> <strong>Chế độ Kiểm thử Demo Đồ án:</strong><br>
            Bạn có thể quét mã QR phía trên bằng điện thoại hoặc nhấn trực tiếp nút bên dưới để mô phỏng Webhook thành công từ Cổng thanh toán (Xu sẽ được cộng vào ví ngay lập tức).
        </div>

        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('wallet.simulateSuccess', $transaction->transaction_code) }}" class="btn btn-success btn-lg px-4 rounded-pill fw-bold">
                <i class="fa-solid fa-circle-check me-2"></i> Giả Lập Đã Thanh Toán Thành Công
            </a>
            <a href="{{ route('wallet.index') }}" class="btn btn-outline-secondary btn-lg px-4 rounded-pill">
                Hủy Giao Dịch
            </a>
        </div>
    </div>

</div>
@endsection
