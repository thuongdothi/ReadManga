@extends('layouts.app')

@section('title', 'Quản Lý Đơn Hàng Shop - Admin MangaStream')

@section('content')
<div class="container-fluid px-lg-5">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold m-0 text-light"><i class="fa-solid fa-cart-flatbed text-success me-2"></i> Quản Lý Đơn Hàng Manga Shop</h3>
            <p class="text-secondary small m-0">Theo dõi, duyệt và cập nhật tình trạng giao hàng cho các đơn hàng của khách</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fa-solid fa-gauge-high me-1"></i> Dashboard
        </a>
    </div>

    <div class="card card-custom p-3">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead>
                    <tr class="text-secondary small">
                        <th>Mã Đơn</th>
                        <th>Khách Hàng</th>
                        <th>Địa Chỉ Giao</th>
                        <th>Sản Phẩm Đã Mua</th>
                        <th>Tổng Đơn</th>
                        <th>Thanh Toán</th>
                        <th>Trạng Thái Giao Hàng</th>
                        <th>Cập Nhật</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <code>{{ $order->order_code }}</code>
                                <div class="text-secondary small">{{ $order->created_at->format('d/m H:i') }}</div>
                            </td>
                            <td>
                                <strong class="text-light">{{ $order->customer_name }}</strong>
                                <div class="text-secondary small">{{ $order->customer_phone }}</div>
                            </td>
                            <td>
                                <small class="text-secondary text-truncate d-block" style="max-width: 200px;" title="{{ $order->shipping_address }}">
                                    {{ $order->shipping_address }}
                                </small>
                            </td>
                            <td>
                                @foreach($order->items as $it)
                                    <div class="small text-truncate" style="max-width: 220px;">
                                        • {{ $it->product->name }} <span class="badge bg-secondary">x{{ $it->quantity }}</span>
                                    </div>
                                @endforeach
                            </td>
                            <td class="fw-bold text-danger">
                                {{ number_format($order->total_amount) }} đ
                                <small class="d-block text-secondary text-uppercase">{{ $order->payment_method }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusBadges = [
                                        'pending' => 'bg-secondary',
                                        'processing' => 'bg-primary',
                                        'shipping' => 'bg-info text-dark',
                                        'completed' => 'bg-success',
                                        'cancelled' => 'bg-danger'
                                    ];
                                @endphp
                                <span class="badge {{ $statusBadges[$order->shipping_status] ?? 'bg-secondary' }} text-capitalize">
                                    {{ $order->shipping_status }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-flex flex-column gap-1">
                                    @csrf
                                    <select name="shipping_status" class="form-select form-select-sm bg-dark text-light border-secondary" style="font-size: 0.8rem;">
                                        <option value="pending" {{ $order->shipping_status === 'pending' ? 'selected' : '' }}>Pending (Chờ duyệt)</option>
                                        <option value="processing" {{ $order->shipping_status === 'processing' ? 'selected' : '' }}>Processing (Đóng gói)</option>
                                        <option value="shipping" {{ $order->shipping_status === 'shipping' ? 'selected' : '' }}>Shipping (Đang giao)</option>
                                        <option value="completed" {{ $order->shipping_status === 'completed' ? 'selected' : '' }}>Completed (Đã giao)</option>
                                        <option value="cancelled" {{ $order->shipping_status === 'cancelled' ? 'selected' : '' }}>Cancelled (Hủy)</option>
                                    </select>
                                    <select name="payment_status" class="form-select form-select-sm bg-dark text-light border-secondary" style="font-size: 0.8rem;">
                                        <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid (Chưa trả)</option>
                                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid (Đã trả)</option>
                                    </select>
                                    <button type="submit" class="btn btn-outline-primary btn-sm py-0 rounded" style="font-size: 0.75rem;">Lưu</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-secondary">Chưa có đơn hàng nào tại cửa hàng.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>

</div>
@endsection
