@extends('layouts.app')

@section('title', $product->name . ' - MangaStream Shop')

@section('content')
<div class="container">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-secondary text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-secondary text-decoration-none">Manga Shop</a></li>
            <li class="breadcrumb-item active text-light" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Details Card -->
    <div class="card card-custom p-4 mb-5">
        <div class="row g-4">
            <div class="col-md-5 text-center">
                <img src="{{ $product->image }}" class="rounded shadow w-100 object-fit-cover" style="max-height: 420px;" alt="{{ $product->name }}">
            </div>
            <div class="col-md-7 d-flex flex-column">
                <span class="badge bg-primary d-inline-block mb-2" style="width: fit-content;">
                    {{ strtoupper(str_replace('_', ' ', $product->category)) }}
                </span>
                <h3 class="fw-bold text-light mb-2">{{ $product->name }}</h3>
                
                <div class="d-flex flex-wrap align-items-baseline gap-3 my-3 text-nowrap">
                    <span class="display-6 fw-extrabold text-danger">{{ number_format($product->price) }} đ</span>
                    <span class="badge-coin fs-6 text-nowrap"><i class="fa-solid fa-coins text-warning me-1"></i> Hoặc {{ ceil($product->price / 100) }} Xu</span>
                </div>

                <div class="mb-3 small text-secondary">
                    <div>Tình trạng: <strong class="text-light">{{ $product->stock_quantity > 0 ? 'Còn hàng (' . $product->stock_quantity . ' sản phẩm)' : 'Hết hàng' }}</strong></div>
                    <div>Giao hàng: <strong class="text-light">Toàn quốc (2-3 ngày làm việc)</strong></div>
                    <div>Đổi trả: <strong class="text-light">Miễn phí đổi trả trong 7 ngày nếu lỗi sản phẩm</strong></div>
                </div>

                <hr class="border-secondary my-3">

                <!-- Quantity & Add to Cart -->
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="input-group" style="width: 130px;">
                        <button class="btn btn-outline-secondary" type="button" id="btn-qty-minus">-</button>
                        <input type="number" id="input-product-qty" class="form-control bg-dark text-light border-secondary text-center" value="1" min="1" max="{{ $product->stock_quantity }}">
                        <button class="btn btn-outline-secondary" type="button" id="btn-qty-plus">+</button>
                    </div>

                    <button class="btn btn-primary btn-lg px-4 rounded-pill fw-semibold flex-grow-1" id="btn-add-to-cart-detail" data-id="{{ $product->id }}">
                        <i class="fa-solid fa-cart-shopping me-2"></i> Thêm Vào Giỏ Hàng
                    </button>
                </div>

                <div class="mt-auto">
                    <h6 class="fw-bold text-light mb-2">Mô tả sản phẩm:</h6>
                    <p class="text-secondary small lh-lg mb-0">
                        {{ $product->description ?? 'Sản phẩm manga anime chất lượng cao được kiểm định cẩn thận trước khi xuất kho.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <h4 class="fw-bold text-light mb-3"><i class="fa-solid fa-fire text-danger me-2"></i> Sản Phẩm Liên Quan</h4>
        <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
            @foreach($relatedProducts as $rel)
                <div class="col">
                    <div class="card card-custom h-100 p-2">
                        <img src="{{ $rel->image }}" class="rounded w-100 object-fit-cover mb-2" style="height: 140px;" alt="{{ $rel->name }}">
                        <h6 class="small fw-semibold text-truncate mb-1 text-light">{{ $rel->name }}</h6>
                        <div class="text-danger fw-bold small mb-2">{{ number_format($rel->price) }} đ</div>
                        <a href="{{ route('shop.show', $rel->slug) }}" class="btn btn-outline-primary btn-sm rounded-pill mt-auto">Xem chi tiết</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    $('#btn-qty-minus').on('click', function() {
        let val = parseInt($('#input-product-qty').val()) || 1;
        if (val > 1) $('#input-product-qty').val(val - 1);
    });
    $('#btn-qty-plus').on('click', function() {
        let val = parseInt($('#input-product-qty').val()) || 1;
        $('#input-product-qty').val(val + 1);
    });

    $('#btn-add-to-cart-detail').on('click', function() {
        const productId = $(this).data('id');
        const qty = parseInt($('#input-product-qty').val()) || 1;
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Đang thêm...');

        $.post("{{ route('cart.add') }}", { product_id: productId, quantity: qty })
            .done(function(res) {
                if (res.success) {
                    $('#cart-counter-badge').removeClass('d-none').text(res.cart_count);
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã thêm vào giỏ!',
                        text: res.message,
                        showCancelButton: true,
                        confirmButtonText: 'Đến Giỏ Hàng',
                        cancelButtonText: 'Tiếp Tục Mua Sắm',
                        background: '#1e293b',
                        color: '#fff'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('cart.view') }}";
                        }
                    });
                }
            })
            .always(function() {
                btn.prop('disabled', false).html('<i class="fa-solid fa-cart-shopping me-2"></i> Thêm Vào Giỏ Hàng');
            });
    });
</script>
@endpush
