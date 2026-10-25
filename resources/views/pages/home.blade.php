@extends('layouts.app')

@section('title', 'MangaStream - Đọc Truyện Tranh Online Hay Nhất & Manga Merch Shop')

@section('content')
<div class="container" style="max-width: 1400px;">

    <!-- Webtoon / MangaDex Spotlight Hero Section -->
    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="fw-bold m-0 text-light d-flex align-items-center gap-2">
                <i class="fa-solid fa-fire text-danger"></i> <span>Truyện Đề Cử Nổi Bật</span>
            </h4>
            <a href="{{ route('comics.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 text-nowrap">
                Xem tất cả <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>

        @if($featuredComics->isNotEmpty())
            @php $spotlight = $featuredComics->first(); @endphp
            <div class="row g-4 align-items-stretch">
                <!-- Large Spotlight Hero Card (Left 7 Cols) -->
                <div class="col-lg-7">
                    <div class="card card-custom h-100 overflow-hidden position-relative p-4 d-flex flex-column justify-content-end" style="min-height: 380px; background: linear-gradient(180deg, rgba(15,23,42,0.1) 0%, rgba(9,13,22,0.95) 85%), url('{{ $spotlight->cover_image }}') center/cover no-repeat; border-color: rgba(99, 102, 241, 0.4);">
                        <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                            <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold text-nowrap">
                                <i class="fa-solid fa-crown me-1"></i> #1 NỔI BẬT NHẤT
                            </span>
                            <span class="badge bg-dark bg-opacity-75 text-warning border border-warning rounded-pill px-3 py-2 fw-bold text-nowrap">
                                <i class="fa-solid fa-star me-1"></i> {{ number_format($spotlight->rating_score, 1) }}
                            </span>
                        </div>

                        <div class="mt-auto">
                            <div class="d-flex flex-wrap gap-1 mb-2">
                                @foreach($spotlight->categories->take(3) as $cat)
                                    <span class="badge bg-primary bg-opacity-25 border border-primary text-light px-2 py-1 text-nowrap">{{ $cat->name }}</span>
                                @endforeach
                            </div>
                            <h2 class="fw-extrabold text-light mb-2 text-clamp-2" title="{{ $spotlight->title }}">
                                {{ $spotlight->title }}
                            </h2>
                            <p class="text-secondary small text-clamp-2 mb-3">
                                {{ $spotlight->description }}
                            </p>
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                @if($spotlight->chapters->first())
                                    <a href="{{ route('comics.read', [$spotlight->slug, $spotlight->chapters->first()->slug]) }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold text-nowrap">
                                        <i class="fa-solid fa-book-open-reader me-2"></i> Đọc Ngay Chương 1
                                    </a>
                                @endif
                                <a href="{{ route('comics.show', $spotlight->slug) }}" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold text-nowrap">
                                    <i class="fa-solid fa-circle-info me-1"></i> Chi Tiết Truyện
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trending Quick Cards (Right 5 Cols - 4 Posters) -->
                <div class="col-lg-5">
                    <div class="row row-cols-2 g-3 h-100">
                        @foreach($featuredComics->skip(1)->take(4) as $idx => $fComic)
                            <div class="col">
                                <div class="card card-custom poster-card h-100 overflow-hidden d-flex flex-column">
                                    <div class="position-relative overflow-hidden" style="padding-top: 120%;">
                                        <img src="{{ $fComic->cover_image }}" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" alt="{{ $fComic->title }}">
                                        <span class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-80 text-warning border border-warning rounded-pill small">
                                            <i class="fa-solid fa-star"></i> {{ number_format($fComic->rating_score, 1) }}
                                        </span>
                                    </div>
                                    <div class="p-2 p-md-3 d-flex flex-column flex-grow-1">
                                        <h6 class="fw-bold mb-1 text-clamp-2" title="{{ $fComic->title }}" style="font-size: 0.9rem;">
                                            <a href="{{ route('comics.show', $fComic->slug) }}" class="text-decoration-none text-light stretched-link">
                                                {{ $fComic->title }}
                                            </a>
                                        </h6>
                                        <div class="mt-auto d-flex justify-content-between align-items-center text-secondary small pt-1 border-top border-secondary border-opacity-25 text-nowrap">
                                            <span><i class="fa-solid fa-eye me-1"></i> {{ number_format($fComic->views_count) }}</span>
                                            <span class="text-primary fw-semibold">{{ $fComic->chapters->count() }} chap</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Categories Pills Carousel -->
    <div class="mb-5 py-3 px-4 card-custom">
        <div class="d-flex align-items-center gap-2 overflow-x-auto pb-2" style="white-space: nowrap;">
            <span class="fw-bold me-2 text-secondary text-nowrap"><i class="fa-solid fa-tags text-primary me-1"></i> Thể Loại:</span>
            <a href="{{ route('comics.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 text-nowrap">Tất cả</a>
            @foreach($categories as $cat)
                <a href="{{ route('comics.index', ['category' => $cat->slug]) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 text-nowrap">
                    {{ $cat->name }} <span class="badge bg-secondary rounded-pill ms-1">{{ $cat->comics_count }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Main Content Layout (Left: Latest Comics 8 cols, Right: Rankings & Shop 4 cols) -->
    <div class="row g-4">
        
        <!-- Left: Latest Comics Grid -->
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="fw-bold m-0 text-light d-flex align-items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-primary"></i> <span>Mới Cập Nhật</span>
                </h4>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('comics.index', ['sort' => 'latest']) }}" class="btn btn-outline-secondary active text-nowrap">Mới nhất</a>
                    <a href="{{ route('comics.index', ['sort' => 'views']) }}" class="btn btn-outline-secondary text-nowrap">Lượt xem</a>
                </div>
            </div>

            <div class="row row-cols-2 row-cols-md-3 g-3 mb-4">
                @foreach($latestComics as $comic)
                    <div class="col">
                        <div class="card card-custom poster-card h-100 overflow-hidden d-flex flex-column">
                            <div class="position-relative overflow-hidden" style="padding-top: 135%;">
                                <img src="{{ $comic->cover_image }}" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" alt="{{ $comic->title }}">
                                
                                <span class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-80 text-warning border border-warning rounded-pill text-nowrap">
                                    <i class="fa-solid fa-star"></i> {{ number_format($comic->rating_score, 1) }}
                                </span>

                                <span class="position-absolute bottom-0 start-0 m-2 badge bg-dark bg-opacity-80 text-light text-nowrap">
                                    <i class="fa-solid fa-eye me-1"></i> {{ number_format($comic->views_count) }}
                                </span>
                            </div>

                            <div class="p-3 d-flex flex-column flex-grow-1">
                                <h6 class="fw-bold mb-1 text-clamp-2" title="{{ $comic->title }}" style="min-height: 2.7em;">
                                    <a href="{{ route('comics.show', $comic->slug) }}" class="text-decoration-none text-light">
                                        {{ $comic->title }}
                                    </a>
                                </h6>

                                <div class="mt-auto pt-2 border-top border-secondary border-opacity-25 small">
                                    @forelse($comic->latestChapters->take(2) as $ch)
                                        <div class="d-flex justify-content-between align-items-center mb-1 text-nowrap">
                                            <a href="{{ route('comics.read', [$comic->slug, $ch->slug]) }}" class="text-decoration-none text-secondary text-truncate me-2" style="max-width: 130px;">
                                                Chương {{ $ch->chapter_number }}
                                            </a>
                                            @if($ch->is_vip)
                                                <span class="badge bg-warning text-dark text-nowrap"><i class="fa-solid fa-lock"></i> VIP</span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-50 text-light text-nowrap">Free</span>
                                            @endif
                                        </div>
                                    @empty
                                        <span class="text-secondary text-nowrap">Đang cập nhật</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $latestComics->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <!-- Right Sidebar: Rankings & Manga Merch Shop Preview -->
        <div class="col-lg-4">
            
            <!-- Top Rankings Card -->
            <div class="card card-custom p-3 mb-4">
                <h5 class="fw-bold mb-3 text-warning d-flex align-items-center gap-2">
                    <i class="fa-solid fa-trophy"></i> <span>Bảng Xếp Hạng Đọc</span>
                </h5>
                <div class="d-flex flex-column gap-3">
                    @foreach($topComics->take(5) as $idx => $comic)
                        <div class="d-flex align-items-center gap-3">
                            <span class="fw-extrabold fs-4 {{ $idx == 0 ? 'text-warning' : ($idx == 1 ? 'text-secondary' : ($idx == 2 ? 'text-danger' : 'text-muted')) }}" style="width: 25px; text-align: center;">
                                0{{ $idx + 1 }}
                            </span>
                            <img src="{{ $comic->cover_image }}" width="46" height="62" class="rounded object-fit-cover shadow-sm flex-shrink-0" alt="{{ $comic->title }}">
                            <div class="flex-grow-1 overflow-hidden">
                                <h6 class="mb-0 text-truncate fw-semibold">
                                    <a href="{{ route('comics.show', $comic->slug) }}" class="text-decoration-none text-light">
                                        {{ $comic->title }}
                                    </a>
                                </h6>
                                <small class="text-secondary text-nowrap d-block"><i class="fa-solid fa-eye me-1"></i> {{ number_format($comic->views_count) }} lượt xem</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Manga Merch Store Preview (Grade A E-Commerce Showcase) -->
            <div class="card card-custom p-3 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold m-0 text-primary d-flex align-items-center gap-2">
                        <i class="fa-solid fa-shop"></i> <span>Manga Merch Shop</span>
                    </h5>
                    <a href="{{ route('shop.index') }}" class="btn btn-sm btn-outline-primary rounded-pill text-nowrap">Vào Shop</a>
                </div>
                <p class="text-secondary small mb-3">
                    Ấn phẩm truyện tranh giấy bản quyền, figure chính hãng và phụ kiện anime cao cấp.
                </p>

                <div class="row g-2">
                    @foreach($featuredProducts as $product)
                        <div class="col-6">
                            <div class="card bg-dark border-secondary p-2 h-100 d-flex flex-column">
                                <img src="{{ $product->image }}" class="rounded w-100 object-fit-cover mb-2" style="height: 120px;" alt="{{ $product->name }}">
                                <h6 class="small fw-semibold text-clamp-2 mb-1 text-light" style="min-height: 2.6em;" title="{{ $product->name }}">{{ $product->name }}</h6>
                                <div class="text-danger fw-bold small mb-2 text-nowrap">{{ number_format($product->price) }} đ</div>
                                <button class="btn btn-sm btn-primary w-100 mt-auto btn-add-cart text-nowrap" data-id="{{ $product->id }}">
                                    <i class="fa-solid fa-cart-plus me-1"></i> Mua
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- VIP Promotion Banner -->
            <div class="card card-custom p-4 mt-4 text-center position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e1b4b, #31104b); border-color: #8b5cf6;">
                <i class="fa-solid fa-crown text-warning fs-1 mb-2"></i>
                <h5 class="fw-bold text-light">Gói Hội Viên VIP</h5>
                <p class="small text-secondary mb-3">
                    Chỉ với 500 Xu (hoặc 50.000 đ)/tháng để mở khóa toàn bộ chương VIP và không còn giới hạn đọc!
                </p>
                <a href="{{ route('wallet.index') }}" class="btn btn-warning fw-bold rounded-pill px-4 text-nowrap">
                    <i class="fa-solid fa-bolt me-1"></i> Khám Phá Ngay
                </a>
            </div>

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // Ajax Quick Add-to-Cart from Homepage
    $(document).on('click', '.btn-add-cart', function(e) {
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
                        timer: 2000,
                        showConfirmButton: false,
                        background: '#131b2e',
                        color: '#fff'
                    });
                }
            })
            .always(function() {
                btn.prop('disabled', false).html('<i class="fa-solid fa-cart-plus me-1"></i> Mua');
            });
    });
</script>
@endpush
