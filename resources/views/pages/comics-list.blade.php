@extends('layouts.app')

@section('title', 'Khám Phá Truyện Tranh - MangaStream')

@section('content')
<div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h3 class="fw-bold m-0 text-light"><i class="fa-solid fa-compass text-primary me-2"></i> Khám Phá Thư Viện Truyện</h3>
            <p class="text-secondary small m-0">Hàng ngàn bộ truyện tranh Manhwa, Manga, Manhua cập nhật nhanh nhất</p>
        </div>

        <!-- Filter / Sorting Form -->
        <form method="GET" action="{{ route('comics.index') }}" class="d-flex flex-wrap gap-2">
            <!-- Category Filter -->
            <select name="category" class="form-select form-select-sm bg-dark text-light border-secondary" onchange="this.form.submit()">
                <option value="">-- Tất cả thể loại --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->comics_count }})
                    </option>
                @endforeach
            </select>

            <!-- Status Filter -->
            <select name="status" class="form-select form-select-sm bg-dark text-light border-secondary" onchange="this.form.submit()">
                <option value="">-- Tình trạng --</option>
                <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Đang tiến hành</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
            </select>

            <!-- Sort By -->
            <select name="sort" class="form-select form-select-sm bg-dark text-light border-secondary" onchange="this.form.submit()">
                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Mới cập nhật</option>
                <option value="views" {{ request('sort') === 'views' ? 'selected' : '' }}>Nhiều lượt xem nhất</option>
                <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Đánh giá cao nhất</option>
                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Theo tên A-Z</option>
            </select>
        </form>
    </div>

    <!-- Comics Grid -->
    <div class="row row-cols-2 row-cols-md-4 row-cols-lg-4 g-4 mb-5">
        @forelse($comics as $comic)
            <div class="col">
                <div class="card card-custom h-100 overflow-hidden">
                    <div class="position-relative overflow-hidden" style="padding-top: 140%;">
                        <img src="{{ $comic->cover_image }}" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" alt="{{ $comic->title }}">
                        
                        <span class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-75 text-warning border border-warning rounded-pill">
                            <i class="fa-solid fa-star"></i> {{ number_format($comic->rating_score, 1) }}
                        </span>

                        <span class="position-absolute bottom-0 start-0 m-2 badge bg-dark bg-opacity-75 text-light">
                            <i class="fa-solid fa-eye me-1"></i> {{ number_format($comic->views_count) }}
                        </span>
                    </div>

                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <h6 class="fw-bold mb-1 text-clamp-2" title="{{ $comic->title }}" style="min-height: 2.7em;">
                            <a href="{{ route('comics.show', $comic->slug) }}" class="text-decoration-none text-light">
                                {{ $comic->title }}
                            </a>
                        </h6>
                        <small class="text-secondary mb-2 text-truncate">{{ $comic->author ?? 'Tác giả' }}</small>

                        <div class="mt-auto pt-2 border-top border-secondary border-opacity-25 small">
                            @forelse($comic->latestChapters->take(2) as $ch)
                                <div class="d-flex justify-content-between align-items-center mb-1 text-nowrap">
                                    <a href="{{ route('comics.read', [$comic->slug, $ch->slug]) }}" class="text-decoration-none text-secondary text-truncate me-2" style="max-width: 140px;">
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
        @empty
            <div class="col-12 py-5 text-center text-secondary">
                <i class="fa-solid fa-box-open fs-1 mb-3"></i>
                <h5>Không tìm thấy bộ truyện nào phù hợp với bộ lọc</h5>
                <a href="{{ route('comics.index') }}" class="btn btn-outline-primary btn-sm mt-2">Xóa bộ lọc</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $comics->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
