@extends('layouts.app')

@section('title', 'Manga Merch Shop - Cửa Hàng Ấn Phẩm & Phụ Kiện Anime')

@section('content')
<div class="container">

    <!-- Shop Hero Banner -->
    <div class="card card-custom p-4 p-md-5 mb-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e1b4b, #431407); border-color: #f97316;">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill mb-2">
                    <i class="fa-solid fa-gift me-1"></i> OFFICIAL MANGA MERCHANDISE
                </span>
                <h2 class="display-6 fw-bold text-light mb-2">Manga & Anime Merch Store</h2>
                <p class="text-secondary mb-3">
                    Bộ sưu tập ấn phẩm truyện tranh bản quyền, Boxset giới hạn, Mô hình Figure và Phụ kiện Otaku chính hãng. Hỗ trợ thanh toán tiền mặt COD hoặc bằng <strong>Ví Xu ảo</strong>.
                </p>
                <a href="#products-list" class="btn btn-warning fw-bold px-4 py-2 rounded-pill">
                    <i class="fa-solid fa-bag-shopping me-1"></i> Mua Sắm Ngay
                </a>
            </div>
        </div>
    </div>

    <!-- Filter & Sort Bar -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4" id="products-list">
        <!-- Categories Pills -->
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('shop.index') }}" class="btn btn-sm {{ !request('category') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">
                Tất Cả Sản Phẩm
            </a>
            <a href="{{ route('shop.index', ['category' => 'manga_book']) }}" class="btn btn-sm {{ request('category') === 'manga_book' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">
                <i class="fa-solid fa-book me-1"></i> Truyện Tranh Giấy
            </a>
            <a href="{{ route('shop.index', ['category' => 'figure']) }}" class="btn btn-sm {{ request('category') === 'figure' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">
                <i class="fa-solid fa-child me-1"></i> Mô Hình Figure
            </a>
            <a href="{{ route('shop.index', ['category' => 'apparel']) }}" class="btn btn-sm {{ request('category') === 'apparel' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">
                <i class="fa-solid fa-shirt me-1"></i> Thời Trang Anime
            </a>
            <a href="{{ route('shop.index', ['category' => 'accessory']) }}" class="btn btn-sm {{ request('category') === 'accessory' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">
                <i class="fa-solid fa-gem me-1"></i> Phụ Kiện & Bookmark
            </a>
        </div>

        <!-- Sort -->
        <form method="GET" action="{{ route('shop.index') }}">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <select name="sort" class="form-select form-select-sm bg-dark text-light border-secondary" onchange="this.form.submit()">
                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
            </select>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
        @forelse($products as $product)
            <div class="col">
                <div class="card card-custom h-100 overflow-hidden d-flex flex-column">
                    <div class="position-relative overflow-hidden" style="padding-top: 100%;">
                        <img src="{{ $product->image }}" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                        @if($product->stock_quantity <= 0)
                            <span class="position-absolute top-0 end-0 m-2 badge bg-danger">Hết hàng</span>
                        @else
                            <span class="position-absolute top-0 end-0 m-2 badge bg-success">Còn hàng ({{ $product->stock_quantity }})</span>
                        @endif
                    </div>

                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <h6 class="fw-bold mb-1 text-clamp-2" title="{{ $product->name }}" style="min-height: 2.7em;">
                            <a href="{{ route('shop.show', $product->slug) }}" class="text-decoration-none text-light">
                                {{ $product->name }}
                            </a>
                        </h6>
                        <p class="text-secondary small text-clamp-2 mb-3" style="min-height: 2.8em;">{{ $product->description }}</p>

                        <div class="mt-auto">
                            <div class="d-flex flex-wrap align-items-baseline gap-1 mb-2 text-nowrap">
                                <span class="fs-5 fw-extrabold text-danger">{{ number_format($product->price) }} đ</span>
                                <small class="text-secondary">(~ {{ ceil($product->price / 100) }} Xu)</small>
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1 rounded-pill btn-add-cart text-nowrap" data-id="{{ $product->id }}">
                                    <i class="fa-solid fa-cart-plus me-1"></i> Thêm Giỏ
                                </button>
                                <a href="{{ route('shop.show', $product->slug) }}" class="btn btn-outline-secondary btn-sm rounded-circle px-2" title="Chi tiết">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-secondary">
                <i class="fa-solid fa-box-open fs-1 mb-3"></i>
                <h5>Chưa có sản phẩm nào trong danh mục này.</h5>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Ajax Add to cart
    $('.btn-add-cart').on('click', function(e) {
        e.preventDefault();
        const productId = $(this).data('id');
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');

        $.post("{{ route('cart.add') }}", { product_id: productId, quantity: 1 })
            .done(function(res) {
                if (res.success) {
                    $('#cart-counter-badge').removeClass('d-none').text(res.cart_count);
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã thêm vào giỏ!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false,
                        background: '#1e293b',
                        color: '#fff'
                    });
                }
            })
            .always(function() {
                btn.prop('disabled', false).html('<i class="fa-solid fa-cart-plus me-1"></i> Thêm Giỏ');
            });
    });
</script>
@endpush
