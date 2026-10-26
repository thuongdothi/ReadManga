@extends('layouts.app')

@section('title', 'Đặt Hàng Thành Công - ' . $order->order_code)

@section('content')
<div class="container" style="max-width: 720px;">

    <div class="card card-custom p-4 p-md-5 text-center my-4 shadow-lg border-success">
        <div class="mb-3">
            <div class="d-inline-flex p-3 rounded-circle bg-success bg-opacity-10 text-success mb-2">
                <i class="fa-solid fa-circle-check fs-1"></i>
            </div>
            <h3 class="fw-bold text-light">Đặt Hàng Thành Công!</h3>
            <p class="text-secondary small">Cảm ơn bạn đã mua sắm tại MangaStream Shop. Đơn hàng của bạn đang được xử lý.</p>
        </div>

        <div class="card bg-dark border-secondary p-3 text-start mb-4">
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-secondary">Mã đơn hàng:</span>
                <span class="text-light fw-bold"><code>{{ $order->order_code }}</code></span>
            </div>
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-secondary">Người nhận:</span>
                <span class="text-light fw-bold">{{ $order->customer_name }} ({{ $order->customer_phone }})</span>
            </div>
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-secondary">Địa chỉ giao:</span>
                <span class="text-light fw-bold">{{ $order->shipping_address }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-secondary">Phương thức thanh toán:</span>
                <span class="badge bg-secondary text-uppercase">{{ $order->payment_method }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-secondary">Trạng thái thanh toán:</span>
                <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                    {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                </span>
            </div>
            <div class="border-top border-secondary pt-2 d-flex justify-content-between">
                <span class="fw-bold text-light">Tổng đơn hàng:</span>
                <span class="fs-5 fw-extrabold text-danger">{{ number_format($order->total_amount) }} VNĐ</span>
            </div>
        </div>

        <!-- Ordered Items list -->
        <div class="text-start mb-4">
            <h6 class="fw-bold text-light mb-2">Danh sách sản phẩm đã đặt:</h6>
            <div class="d-flex flex-column gap-2">
                @foreach($order->items as $item)
                    <div class="d-flex align-items-center justify-content-between p-2 rounded bg-dark border border-secondary border-opacity-25 small">
                        <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                        <span class="fw-bold text-light">{{ number_format($item->subtotal) }} đ</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('home') }}" class="btn btn-primary px-4 rounded-pill">
                <i class="fa-solid fa-house me-1"></i> Trở Về Trang Chủ
            </a>
            @auth
                <a href="{{ route('profile') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                    Xem Lịch Sử Đơn Hàng
                </a>
            @endauth
        </div>
    </div>

</div>
@endsection
