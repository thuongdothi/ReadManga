@extends('layouts.app')

@section('title', 'Giỏ Hàng - MangaStream Shop')

@section('content')
<div class="container">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold m-0 text-light"><i class="fa-solid fa-cart-shopping text-primary me-2"></i> Giỏ Hàng Của Bạn</h3>
            <p class="text-secondary small m-0">Kiểm tra số lượng và danh sách sản phẩm trước khi tiến hành thanh toán</p>
        </div>
        <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="fa-solid fa-arrow-left me-1"></i> Tiếp tục mua sắm
        </a>
    </div>

    @if(count($cart) > 0)
        <div class="row g-4">
            
            <!-- Cart Items Table (8 cols) -->
            <div class="col-lg-8">
                <div class="card card-custom p-3">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0">
                            <thead>
                                <tr class="text-secondary small">
                                    <th>Sản Phẩm</th>
                                    <th>Đơn Giá</th>
                                    <th style="width: 140px;">Số Lượng</th>
                                    <th>Thành Tiền</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $item)
                                    <tr id="cart-row-{{ $id }}">
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $item['image'] }}" width="55" height="55" class="rounded object-fit-cover" alt="{{ $item['name'] }}">
                                                <div>
                                                    <h6 class="mb-0 text-light small fw-bold text-truncate" style="max-width: 220px;">
                                                        <a href="{{ route('shop.show', $item['slug']) }}" class="text-decoration-none text-light">
                                                            {{ $item['name'] }}
                                                        </a>
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="small text-nowrap">{{ number_format($item['price']) }} đ</td>
                                        <td>
                                            <div class="input-group input-group-sm text-nowrap" style="width: 110px;">
                                                <button class="btn btn-outline-secondary btn-update-qty" data-id="{{ $id }}" data-delta="-1">-</button>
                                                <input type="text" class="form-control bg-dark text-light border-secondary text-center qty-input-{{ $id }}" value="{{ $item['quantity'] }}" readonly>
                                                <button class="btn btn-outline-secondary btn-update-qty" data-id="{{ $id }}" data-delta="1">+</button>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-danger text-nowrap item-subtotal-{{ $id }}">
                                            {{ number_format($item['price'] * $item['quantity']) }} đ
                                        </td>
                                        <td>
                                            <button class="btn btn-link text-secondary p-0 btn-remove-item" data-id="{{ $id }}" title="Xóa khỏi giỏ">
                                                <i class="fa-solid fa-trash-can text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Cart Summary (4 cols) -->
            <div class="col-lg-4">
                <div class="card card-custom p-4">
                    <h5 class="fw-bold text-light mb-3">Tóm Tắt Đơn Hàng</h5>

                    <div class="d-flex justify-content-between mb-2 small text-secondary">
                        <span>Tạm tính:</span>
                        <span class="text-light fw-bold" id="cart-summary-total">{{ number_format($total) }} đ</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 small text-secondary">
                        <span>Phí vận chuyển:</span>
                        <span class="text-success fw-bold">MIỄN PHÍ</span>
                    </div>

                    <hr class="border-secondary my-3">

                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <span class="fw-bold text-light">Tổng cộng:</span>
                        <span class="fs-4 fw-extrabold text-danger" id="cart-final-total">{{ number_format($total) }} đ</span>
                    </div>

                    <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">
                        <i class="fa-solid fa-credit-card me-2"></i> Tiến Hành Đặt Hàng
                    </a>

                    <div class="mt-3 text-center small text-secondary">
                        <i class="fa-solid fa-truck-fast text-primary me-1"></i> Giao hàng toàn quốc - Đồng kiểm khi nhận
                    </div>
                </div>
            </div>

        </div>
    @else
        <!-- Empty Cart -->
        <div class="card card-custom p-5 text-center my-4">
            <i class="fa-solid fa-cart-arrow-down text-secondary fs-1 mb-3"></i>
            <h4 class="fw-bold text-light">Giỏ hàng của bạn đang trống</h4>
            <p class="text-secondary small mb-4">Hãy khám phá các sản phẩm ấn phẩm truyện tranh và mô hình hấp dẫn tại cửa hàng!</p>
            <div>
                <a href="{{ route('shop.index') }}" class="btn btn-warning rounded-pill px-4 fw-bold">
                    Khám Phá Cửa Hàng Ngay
                </a>
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    // Update quantity via Ajax
    $('.btn-update-qty').on('click', function() {
        const id = $(this).data('id');
        const delta = parseInt($(this).data('delta'));
        let currentQty = parseInt($('.qty-input-' + id).val()) || 1;
        let newQty = currentQty + delta;
        if (newQty < 1) newQty = 1;

        updateCartAjax(id, newQty);
    });

    // Remove item from cart via Ajax
    $('.btn-remove-item').on('click', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Xóa sản phẩm?',
            text: 'Bạn có chắc muốn bỏ sản phẩm này khỏi giỏ hàng?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Giữ lại',
            background: '#1e293b',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                updateCartAjax(id, 0);
            }
        });
    });

    function updateCartAjax(productId, quantity) {
        $.post("{{ route('cart.update') }}", { product_id: productId, quantity: quantity })
            .done(function(res) {
                if (res.success) {
                    if (quantity <= 0) {
                        $('#cart-row-' + productId).fadeOut(300, function() {
                            $(this).remove();
                            if (res.cart_count === 0) {
                                window.location.reload();
                            }
                        });
                    } else {
                        $('.qty-input-' + productId).val(quantity);
                        $('.item-subtotal-' + productId).text(res.item_subtotal);
                    }
                    $('#cart-summary-total').text(res.subtotal);
                    $('#cart-final-total').text(res.subtotal);
                    $('#cart-counter-badge').text(res.cart_count);
                    if (res.cart_count === 0) {
                        $('#cart-counter-badge').addClass('d-none');
                    }
                }
            });
    }
</script>
@endpush
