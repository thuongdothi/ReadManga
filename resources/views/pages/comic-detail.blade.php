@extends('layouts.app')

@section('title', $comic->title . ' - Đọc Truyện Tranh Online MangaStream')

@section('content')
<div class="container">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-secondary text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('comics.index') }}" class="text-secondary text-decoration-none">Truyện tranh</a></li>
            <li class="breadcrumb-item active text-light" aria-current="page">{{ $comic->title }}</li>
        </ol>
    </nav>

    <!-- Comic Header Info Card -->
    <div class="card card-custom p-4 mb-4">
        <div class="row g-4">
            <!-- Cover Image Column -->
            <div class="col-md-3 col-sm-4 text-center">
                <img src="{{ $comic->cover_image }}" class="rounded shadow-lg w-100 object-fit-cover" style="max-height: 380px;" alt="{{ $comic->title }}">
            </div>

            <!-- Metadata Column -->
            <div class="col-md-9 col-sm-8 d-flex flex-column">
                <h2 class="fw-bold text-light mb-2">{{ $comic->title }}</h2>
                @if($comic->alternative_names)
                    <p class="text-secondary small mb-3"><i class="fa-solid fa-language me-1"></i> Tên khác: {{ $comic->alternative_names }}</p>
                @endif

                <div class="row row-cols-2 row-cols-md-3 g-2 mb-3 small text-secondary">
                    <div class="text-nowrap text-truncate"><strong class="text-light">Tác giả:</strong> {{ $comic->author ?? 'Đang cập nhật' }}</div>
                    <div class="text-nowrap text-truncate"><strong class="text-light">Họa sĩ:</strong> {{ $comic->artist ?? 'Đang cập nhật' }}</div>
                    <div class="text-nowrap">
                        <strong class="text-light">Tình trạng:</strong> 
                        <span class="badge {{ $comic->status === 'ongoing' ? 'bg-primary' : 'bg-success' }}">
                            {{ $comic->status === 'ongoing' ? 'Đang tiến hành' : 'Hoàn thành' }}
                        </span>
                    </div>
                    <div class="text-nowrap"><strong class="text-light">Lượt xem:</strong> {{ number_format($comic->views_count) }}</div>
                    <div class="text-nowrap"><strong class="text-light">Số chương:</strong> {{ $comic->chapters->count() }}</div>
                    <div class="text-nowrap">
                        <strong class="text-light">Đánh giá:</strong> 
                        <span class="text-warning fw-bold"><i class="fa-solid fa-star"></i> {{ number_format($comic->rating_score, 1) }}</span> 
                        ({{ $comic->rating_count }} lượt)
                    </div>
                </div>

                <!-- Categories -->
                <div class="mb-4">
                    @foreach($comic->categories as $cat)
                        <a href="{{ route('comics.index', ['category' => $cat->slug]) }}" class="btn btn-sm btn-outline-secondary rounded-pill me-1 mb-1">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>

                <!-- Action Buttons & Star Rating -->
                <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                    @if($comic->chapters->first())
                        <a href="{{ route('comics.read', [$comic->slug, $comic->chapters->first()->slug]) }}" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold">
                            <i class="fa-solid fa-book-open-reader me-2"></i> Đọc Từ Đầu
                        </a>
                    @endif

                    @if($comic->chapters->last() && $comic->chapters->count() > 1)
                        <a href="{{ route('comics.read', [$comic->slug, $comic->chapters->last()->slug]) }}" class="btn btn-outline-primary px-4 py-2 rounded-pill fw-semibold">
                            <i class="fa-solid fa-forward-step me-2"></i> Đọc Mới Nhất
                        </a>
                    @endif

                    <!-- Follow / Favorite Toggle Ajax Button -->
                    <button class="btn btn-outline-danger px-4 py-2 rounded-pill fw-semibold" id="btn-toggle-favorite" data-id="{{ $comic->id }}">
                        <i class="fa-heart {{ $isFavorited ? 'fa-solid' : 'fa-regular' }} me-2"></i>
                        <span id="favorite-text">{{ $isFavorited ? 'Đang Theo Dõi' : 'Theo Dõi' }}</span>
                    </button>
                </div>

                <!-- 5-Star Interactive Rating (Ajax) -->
                <div class="d-flex align-items-center gap-2 small text-secondary mt-auto">
                    <span>Đánh giá truyện này:</span>
                    <div class="star-rating d-flex gap-1" style="cursor: pointer; font-size: 1.2rem;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-star {{ $i <= round($comic->rating_score) ? 'fa-solid text-warning' : 'fa-regular text-secondary' }} star-item" data-score="{{ $i }}"></i>
                        @endfor
                    </div>
                    <span id="rating-feedback" class="ms-2 text-success small fw-semibold"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        <!-- Left: Synopsis & Chapters List (8 cols) -->
        <div class="col-lg-8">
            <!-- Synopsis -->
            <div class="card card-custom p-4 mb-4">
                <h5 class="fw-bold text-light mb-3"><i class="fa-solid fa-align-left text-primary me-2"></i> Tóm Tắt Nội Dung</h5>
                <p class="text-secondary lh-lg mb-0">
                    {{ $comic->description ?? 'Nội dung bộ truyện đang được cập nhật...' }}
                </p>
            </div>

            <!-- Chapters List -->
            <div class="card card-custom p-4 mb-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <h5 class="fw-bold text-light m-0">
                        <i class="fa-solid fa-list-ol text-primary me-2"></i> Danh Sách Chương ({{ $comic->chapters->count() }})
                    </h5>
                    <!-- Filter chapter input -->
                    <input type="text" id="chapter-search-box" class="form-control form-control-sm bg-dark text-light border-secondary" placeholder="Tìm số chương..." style="width: 180px;">
                </div>

                <div class="list-group list-group-flush" id="chapters-container" style="max-height: 520px; overflow-y: auto;">
                    @forelse($comic->chapters as $chap)
                        @php
                            $userUnlocked = auth()->check() ? auth()->user()->hasUnlocked($chap->id) : false;
                        @endphp
                        <a href="{{ route('comics.read', [$comic->slug, $chap->slug]) }}" class="list-group-item list-group-item-action bg-transparent border-secondary border-opacity-25 py-3 d-flex align-items-center justify-content-between chapter-item" data-number="{{ $chap->chapter_number }}">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold text-light">Chương {{ $chap->chapter_number }}</span>
                                @if($chap->title && $chap->title !== 'Chương ' . $chap->chapter_number)
                                    <span class="text-secondary small d-none d-sm-inline">- {{ $chap->title }}</span>
                                @endif

                                @if($chap->is_vip)
                                    @if($userUnlocked)
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success">
                                            <i class="fa-solid fa-lock-open me-1"></i> Đã mở
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="fa-solid fa-lock me-1"></i> VIP ({{ $chap->price_coins }} Xu)
                                        </span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary bg-opacity-25 text-secondary">Free</span>
                                @endif
                            </div>

                            <div class="small text-secondary">
                                <span class="me-3 d-none d-md-inline"><i class="fa-solid fa-eye me-1"></i> {{ number_format($chap->views_count) }}</span>
                                <span>{{ $chap->published_at ? $chap->published_at->format('d/m/Y') : '' }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="text-secondary text-center py-4">Chưa có chương nào được đăng tải.</div>
                    @endforelse
                </div>
            </div>

            <!-- Comments Section (Ajax) -->
            <div class="card card-custom p-4">
                <h5 class="fw-bold text-light mb-3">
                    <i class="fa-solid fa-comments text-primary me-2"></i> Bình Luận & Thảo Luận (<span id="comments-count">{{ $comic->comments->count() }}</span>)
                </h5>

                @auth
                    <!-- Comment Input Box -->
                    <form id="comment-form" class="mb-4">
                        <div class="d-flex gap-3">
                            <img src="{{ auth()->user()->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed='.urlencode(auth()->user()->name) }}" class="rounded-circle" width="40" height="40" alt="Avatar">
                            <div class="flex-grow-1">
                                <textarea id="comment-content" class="form-control bg-dark text-light border-secondary" rows="3" placeholder="Chia sẻ cảm nghĩ của bạn về bộ truyện này..." required></textarea>
                                <div class="d-flex justify-content-end mt-2">
                                    <button type="submit" class="btn btn-primary btn-sm px-4 rounded-pill fw-semibold">
                                        <i class="fa-solid fa-paper-plane me-1"></i> Gửi Bình Luận
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-dark border-secondary text-center py-3 mb-4">
                        <i class="fa-solid fa-circle-info me-2 text-primary"></i> Vui lòng <a href="{{ route('login') }}" class="text-primary fw-bold">Đăng nhập</a> để tham gia bình luận thảo luận.
                    </div>
                @endauth

                <!-- Comments Stream -->
                <div id="comments-list" class="d-flex flex-column gap-3">
                    @forelse($comic->comments as $cm)
                        <div class="d-flex gap-3 p-3 rounded bg-dark border border-secondary border-opacity-25">
                            <img src="{{ $cm->user->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed='.urlencode($cm->user->name) }}" class="rounded-circle" width="38" height="38" alt="Avatar">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="fw-bold text-light small">{{ $cm->user->name }}</span>
                                    <small class="text-secondary">{{ $cm->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="text-light small m-0">{{ $cm->content }}</p>
                            </div>
                        </div>
                    @empty
                        <div id="no-comments-msg" class="text-secondary text-center py-4">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right: Related Comics (4 cols) -->
        <div class="col-lg-4">
            <div class="card card-custom p-3">
                <h5 class="fw-bold text-light mb-3"><i class="fa-solid fa-shuffle text-primary me-2"></i> Truyện Cùng Thể Loại</h5>
                <div class="d-flex flex-column gap-3">
                    @foreach($relatedComics as $rel)
                        <div class="d-flex gap-3 align-items-center">
                            <img src="{{ $rel->cover_image }}" width="55" height="75" class="rounded object-fit-cover shadow-sm" alt="{{ $rel->title }}">
                            <div class="overflow-hidden">
                                <h6 class="text-truncate fw-semibold mb-1">
                                    <a href="{{ route('comics.show', $rel->slug) }}" class="text-decoration-none text-light">
                                        {{ $rel->title }}
                                    </a>
                                </h6>
                                <small class="text-secondary d-block"><i class="fa-solid fa-star text-warning me-1"></i> {{ number_format($rel->rating_score, 1) }}</small>
                                <small class="text-secondary"><i class="fa-solid fa-eye me-1"></i> {{ number_format($rel->views_count) }} lượt xem</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // Chapter Live Filter
    $('#chapter-search-box').on('keyup', function() {
        const val = $(this).val().toLowerCase().trim();
        $('.chapter-item').each(function() {
            const num = $(this).data('number').toString();
            const text = $(this).text().toLowerCase();
            if (num.includes(val) || text.includes(val)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Toggle Favorite (Ajax)
    $('#btn-toggle-favorite').on('click', function(e) {
        e.preventDefault();
        const comicId = $(this).data('id');
        const btn = $(this);

        $.post("{{ route('api.comics.favorite', $comic->id) }}")
            .done(function(res) {
                if (res.success) {
                    if (res.is_favorited) {
                        btn.find('i').removeClass('fa-regular').addClass('fa-solid');
                        $('#favorite-text').text('Đang Theo Dõi');
                    } else {
                        btn.find('i').removeClass('fa-solid').addClass('fa-regular');
                        $('#favorite-text').text('Theo Dõi');
                    }
                    Swal.fire({
                        icon: 'success',
                        title: res.message,
                        timer: 1500,
                        showConfirmButton: false,
                        background: '#1e293b',
                        color: '#fff'
                    });
                }
            })
            .fail(function(xhr) {
                if (xhr.status === 401) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Chưa đăng nhập',
                        text: 'Vui lòng đăng nhập để lưu truyện vào danh sách theo dõi!',
                        showCancelButton: true,
                        confirmButtonText: 'Đăng nhập',
                        cancelButtonText: 'Đóng',
                        background: '#1e293b',
                        color: '#fff'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('login') }}";
                        }
                    });
                }
            });
    });

    // Interactive Star Rating (Ajax)
    $('.star-item').on('click', function() {
        const score = $(this).data('score');
        $.post("{{ route('api.comics.rate', $comic->id) }}", { score: score })
            .done(function(res) {
                if (res.success) {
                    $('#rating-feedback').text('Đã gửi đánh giá: ' + score + ' sao!');
                    // Update stars visual
                    $('.star-item').each(function(index) {
                        if (index < score) {
                            $(this).removeClass('fa-regular text-secondary').addClass('fa-solid text-warning');
                        } else {
                            $(this).removeClass('fa-solid text-warning').addClass('fa-regular text-secondary');
                        }
                    });
                }
            });
    });

    // Post Comment (Ajax)
    $('#comment-form').on('submit', function(e) {
        e.preventDefault();
        const content = $('#comment-content').val().trim();
        if (!content) return;

        $.post("{{ route('api.comics.comment', $comic->id) }}", { content: content })
            .done(function(res) {
                if (res.success) {
                    $('#comment-content').val('');
                    $('#no-comments-msg').remove();
                    const c = res.comment;
                    const html = `
                        <div class="d-flex gap-3 p-3 rounded bg-dark border border-secondary border-opacity-25" style="animation: fadeIn 0.3s ease;">
                            <img src="${c.avatar}" class="rounded-circle" width="38" height="38" alt="Avatar">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="fw-bold text-light small">${c.user_name}</span>
                                    <small class="text-secondary">${c.created_at}</small>
                                </div>
                                <p class="text-light small m-0">${c.content}</p>
                            </div>
                        </div>
                    `;
                    $('#comments-list').prepend(html);
                    const currentCount = parseInt($('#comments-count').text()) || 0;
                    $('#comments-count').text(currentCount + 1);
                }
            });
    });
</script>
@endpush
