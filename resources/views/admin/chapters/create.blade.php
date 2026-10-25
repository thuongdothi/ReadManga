@extends('layouts.app')

@section('title', 'Đăng Tải Chương Mới - ' . $comic->title)

@section('content')
<div class="container" style="max-width: 800px;">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold m-0 text-light"><i class="fa-solid fa-cloud-arrow-up text-primary me-2"></i> Thêm Chương Mới</h3>
            <p class="text-secondary small m-0">{{ $comic->title }}</p>
        </div>
        <a href="{{ route('admin.chapters.index', $comic->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="fa-solid fa-arrow-left me-1"></i> Danh sách chương
        </a>
    </div>

    <div class="card card-custom p-4 shadow">
        <form action="{{ route('admin.chapters.store', $comic->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label text-light fw-semibold small">Số thứ tự chương <span class="text-danger">*</span></label>
                    <input type="number" step="0.1" name="chapter_number" class="form-control bg-dark text-light border-secondary @error('chapter_number') is-invalid @enderror" value="{{ old('chapter_number', $nextChapterNum) }}" required>
                    @error('chapter_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label text-light fw-semibold small">Tiêu đề chương (nếu có)</label>
                    <input type="text" name="title" class="form-control bg-dark text-light border-secondary" value="{{ old('title') }}" placeholder="Ví dụ: Lời phán quyết của Thần">
                </div>
            </div>

            <!-- VIP & Price Settings -->
            <div class="card bg-dark border-secondary p-3 mb-4">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" role="switch" name="is_vip" value="1" id="isVipSwitch" {{ old('is_vip') ? 'checked' : '' }}>
                    <label class="form-check-label text-light fw-bold" for="isVipSwitch">
                        <i class="fa-solid fa-lock text-warning me-1"></i> Đặt làm Chương Trả Phí VIP
                    </label>
                </div>
                <small class="text-secondary mb-3 d-block">
                    Nếu bật VIP, người dùng sẽ cần chi tiêu Xu ảo để mở khóa chương (hoặc phải có gói Hội viên VIP).
                </small>

                <div id="price-input-container" style="{{ old('is_vip') ? '' : 'display: none;' }}">
                    <label class="form-label text-light fw-semibold small">Giá Xu để mở khóa chương <span class="text-danger">*</span></label>
                    <div class="input-group" style="max-width: 250px;">
                        <span class="input-group-text bg-transparent border-secondary text-warning"><i class="fa-solid fa-coins"></i></span>
                        <input type="number" name="price_coins" class="form-control bg-dark text-light border-secondary" value="{{ old('price_coins', 15) }}" min="1">
                        <span class="input-group-text bg-transparent border-secondary text-secondary">Xu</span>
                    </div>
                </div>
            </div>

            <!-- Batch Image Upload (Multiple Files) -->
            <div class="mb-4">
                <label class="form-label text-light fw-semibold small">
                    <i class="fa-solid fa-images text-info me-1"></i> Tải lên các trang ảnh truyện (Chọn nhiều ảnh cùng lúc)
                </label>
                <input type="file" name="images[]" class="form-control bg-dark text-light border-secondary p-3" accept="image/*" multiple>
                <small class="text-secondary mt-1 d-block">
                    • Giữ phím <strong>Ctrl</strong> hoặc <strong>Shift</strong> khi chọn file để chọn toàn bộ các trang ảnh của chương.<br>
                    • Nếu không chọn file, hệ thống sẽ tự động tạo các trang ảnh mẫu để phục vụ demo!
                </small>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.chapters.index', $comic->id) }}" class="btn btn-outline-secondary px-4 rounded-pill">Hủy</a>
                <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">
                    <i class="fa-solid fa-cloud-arrow-up me-2"></i> Đăng Tải Chương
                </button>
            </div>

        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    $('#isVipSwitch').on('change', function() {
        if ($(this).is(':checked')) {
            $('#price-input-container').slideDown(200);
        } else {
            $('#price-input-container').slideUp(200);
        }
    });
</script>
@endpush
