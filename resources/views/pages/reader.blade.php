@extends('layouts.app')

@section('title', 'Đọc ' . $comic->title . ' - Chương ' . $chapter->chapter_number . ' - MangaStream')

@section('content')
<div class="container-fluid px-lg-5">

    <!-- Top Sticky Reader Control Bar -->
    <div class="card card-custom p-3 mb-4 sticky-top shadow" style="top: 70px; z-index: 1000; background: rgba(30, 41, 59, 0.95); backdrop-filter: blur(8px);">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            
            <!-- Left: Back & Title -->
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('comics.show', $comic->slug) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="fa-solid fa-arrow-left me-1"></i> <span class="d-none d-md-inline">Trở về</span>
                </a>
                <div>
                    <h6 class="m-0 fw-bold text-light text-truncate" style="max-width: 320px;">{{ $comic->title }}</h6>
                    <small class="text-secondary">Chương {{ $chapter->chapter_number }} @if($chapter->title) - {{ $chapter->title }} @endif</small>
                </div>
            </div>

            <!-- Center: Chapter Dropdown Selector & Navigation -->
            <div class="d-flex align-items-center gap-2 text-nowrap">
                @if($prevChapter)
                    <a href="{{ route('comics.read', [$comic->slug, $prevChapter->slug]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 text-nowrap" title="Chương trước">
                        <i class="fa-solid fa-chevron-left me-1"></i> <span class="d-none d-sm-inline">Trước</span>
                    </a>
                @else
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 text-nowrap" disabled>
                        <i class="fa-solid fa-chevron-left me-1"></i> <span class="d-none d-sm-inline">Trước</span>
                    </button>
                @endif

                <!-- Chapter Selector Dropdown -->
                <select id="chapter-quick-select" class="form-select form-select-sm bg-dark text-light border-secondary text-nowrap" style="width: 170px;">
                    @foreach($allChapters as $ch)
                        <option value="{{ route('comics.read', [$comic->slug, $ch->slug]) }}" {{ $ch->id === $chapter->id ? 'selected' : '' }}>
                            Chương {{ $ch->chapter_number }} {{ $ch->is_vip ? '🔒' : '' }}
                        </option>
                    @endforeach
                </select>

                @if($nextChapter)
                    <a href="{{ route('comics.read', [$comic->slug, $nextChapter->slug]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 text-nowrap" title="Chương tiếp">
                        <span class="d-none d-sm-inline">Sau</span> <i class="fa-solid fa-chevron-right ms-1"></i>
                    </a>
                @else
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 text-nowrap" disabled>
                        <span class="d-none d-sm-inline">Sau</span> <i class="fa-solid fa-chevron-right ms-1"></i>
                    </button>
                @endif
            </div>

            <!-- Right: Display Options -->
            <div class="d-flex align-items-center gap-2 text-nowrap">
                <button class="btn btn-sm btn-outline-secondary rounded-pill d-none d-md-inline" id="btn-zoom-in" title="Phóng to ảnh">
                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                </button>
                <button class="btn btn-sm btn-outline-secondary rounded-pill d-none d-md-inline" id="btn-zoom-out" title="Thu nhỏ ảnh">
                    <i class="fa-solid fa-magnifying-glass-minus"></i>
                </button>
                <a href="#reader-bottom" class="btn btn-sm btn-outline-secondary rounded-pill" title="Cuộn xuống cuối">
                    <i class="fa-solid fa-arrow-down"></i>
                </a>
            </div>

        </div>
    </div>

    <!-- Reader Main Content Container -->
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10" id="manga-pages-wrapper">

            @if($isVipLocked)
                <!-- VIP Locked Screen Card (Commercial Grade A Feature) -->
                <div class="card card-custom p-5 my-5 text-center shadow-lg border-warning position-relative overflow-hidden" style="background: radial-gradient(circle at center, #1e1b4b, #0f172a);">
                    <div class="mb-4">
                        <div class="d-inline-flex p-4 rounded-circle bg-warning bg-opacity-10 border border-warning text-warning mb-3">
                            <i class="fa-solid fa-lock fs-1"></i>
                        </div>
                        <h3 class="fw-bold text-light">Chương Truyện Trả Phí VIP</h3>
                        <p class="text-secondary max-w-md mx-auto">
                            Chương <strong>{{ $chapter->chapter_number }}</strong> là chương độc quyền VIP của nhóm dịch. Mở khóa để thưởng thức ngay nội dung đỉnh cao và ủng hộ tác giả!
                        </p>
                    </div>

                    <div class="card bg-dark border-secondary p-3 mx-auto mb-4" style="max-width: 420px;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-secondary">Chi phí mở khóa:</span>
                            <span class="badge-coin fs-6"><i class="fa-solid fa-coins text-warning me-1"></i> {{ $chapter->price_coins }} Xu</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary">Số dư ví của bạn:</span>
                            @auth
                                <span class="fw-bold text-light">{{ number_format(auth()->user()->coin_balance) }} Xu</span>
                            @else
                                <span class="text-danger">Chưa đăng nhập</span>
                            @endauth
                        </div>
                    </div>

                    @auth
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            @if(auth()->user()->coin_balance >= $chapter->price_coins)
                                <!-- Instant Unlock Ajax Button -->
                                <button class="btn btn-warning btn-lg px-5 rounded-pill fw-bold" id="btn-unlock-chapter" data-id="{{ $chapter->id }}">
                                    <i class="fa-solid fa-key me-2"></i> Mở Khóa Ngay ({{ $chapter->price_coins }} Xu)
                                </button>
                            @else
                                <!-- Insufficient Coins -> Recharge -->
                                <a href="{{ route('wallet.index') }}" class="btn btn-warning btn-lg px-5 rounded-pill fw-bold">
                                    <i class="fa-solid fa-coins me-2"></i> Nạp Thêm Xu Vào Ví
                                </a>
                            @endif

                            <a href="{{ route('wallet.index') }}" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                                <i class="fa-solid fa-crown text-warning me-2"></i> Nâng Cấp VIP Tháng
                            </a>
                        </div>
                    @else
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 rounded-pill fw-semibold">
                                <i class="fa-solid fa-right-to-bracket me-2"></i> Đăng Nhập Để Mở Khóa
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                                Đăng Ký
                            </a>
                        </div>
                    @endauth
                </div>
            @else
                <!-- Unlocked / Free Chapter Pages Stream (Continuous Vertical Scroll) -->
                <div class="d-flex flex-column align-items-center gap-1" id="manga-pages-list">
                    @forelse($chapter->images as $img)
                        <div class="w-100 text-center position-relative manga-page-container">
                            <img src="{{ $img->image_url }}" class="img-fluid manga-page-img shadow-sm" alt="Trang {{ $img->page_number }}" loading="lazy" style="max-width: 100%; transition: max-width 0.2s;">
                            <span class="position-absolute bottom-0 end-0 m-2 badge bg-dark bg-opacity-75 text-secondary small">
                                Trang {{ $img->page_number }}
                            </span>
                        </div>
                    @empty
                        <div class="alert alert-secondary text-center my-5">
                            Nội dung ảnh của chương này đang được đội ngũ cập nhật. Vui lòng quay lại sau!
                        </div>
                    @endforelse
                </div>
            @endif

            <!-- Bottom Navigation Bar -->
            <div class="card card-custom p-4 my-5 text-center" id="reader-bottom">
                <h5 class="fw-bold text-light mb-3">Bạn đã đọc xong Chương {{ $chapter->chapter_number }}!</h5>
                <div class="d-flex justify-content-center gap-3 mb-3">
                    @if($prevChapter)
                        <a href="{{ route('comics.read', [$comic->slug, $prevChapter->slug]) }}" class="btn btn-outline-primary px-4 rounded-pill">
                            <i class="fa-solid fa-arrow-left me-1"></i> Chương Trước
                        </a>
                    @endif

                    <a href="{{ route('comics.show', $comic->slug) }}" class="btn btn-secondary px-4 rounded-pill">
                        <i class="fa-solid fa-list me-1"></i> Mục Lục
                    </a>

                    @if($nextChapter)
                        <a href="{{ route('comics.read', [$comic->slug, $nextChapter->slug]) }}" class="btn btn-primary px-4 rounded-pill fw-bold">
                            Chương Tiếp <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    @endif
                </div>
                <small class="text-secondary">Mẹo: Bạn có thể dùng phím mũi tên ⬅️ ➡️ trên bàn phím để chuyển chương nhanh.</small>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Chapter Dropdown Quick Jump
    $('#chapter-quick-select').on('change', function() {
        window.location.href = $(this).val();
    });

    // Keyboard Hotkeys: Left Arrow = Prev, Right Arrow = Next
    $(document).on('keydown', function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
        @if($prevChapter)
            if (e.which === 37) { // Left arrow
                window.location.href = "{{ route('comics.read', [$comic->slug, $prevChapter->slug]) }}";
            }
        @endif
        @if($nextChapter)
            if (e.which === 39) { // Right arrow
                window.location.href = "{{ route('comics.read', [$comic->slug, $nextChapter->slug]) }}";
            }
        @endif
    });

    // Zoom In / Zoom Out Page Width
    let currentWidthPercent = 100;
    $('#btn-zoom-in').on('click', function() {
        if (currentWidthPercent < 130) {
            currentWidthPercent += 10;
            $('.manga-page-img').css('max-width', currentWidthPercent + '%');
        }
    });
    $('#btn-zoom-out').on('click', function() {
        if (currentWidthPercent > 70) {
            currentWidthPercent -= 10;
            $('.manga-page-img').css('max-width', currentWidthPercent + '%');
        }
    });

    // Ajax Unlock Chapter with Coins
    $('#btn-unlock-chapter').on('click', function() {
        const chapterId = $(this).data('id');
        const btn = $(this);

        Swal.fire({
            title: 'Xác nhận mở khóa?',
            text: 'Bạn có chắc chắn muốn dùng {{ $chapter->price_coins }} Xu để mở khóa chương này không?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Đồng ý mở khóa',
            cancelButtonText: 'Hủy',
            background: '#1e293b',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Đang mở khóa...');

                $.post("{{ route('api.chapters.unlock', $chapter->id) }}")
                    .done(function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false,
                                background: '#1e293b',
                                color: '#fff'
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    })
                    .fail(function(xhr) {
                        const err = xhr.responseJSON ? xhr.responseJSON.message : 'Có lỗi xảy ra.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Mở khóa thất bại',
                            text: err,
                            background: '#1e293b',
                            color: '#fff'
                        });
                        btn.prop('disabled', false).html('<i class="fa-solid fa-key me-2"></i> Mở Khóa Ngay ({{ $chapter->price_coins }} Xu)');
                    });
            }
        });
    });
</script>
@endpush
