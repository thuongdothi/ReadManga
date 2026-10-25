@extends('layouts.app')

@section('title', 'Thêm Bộ Truyện Mới - Admin')

@section('content')
<div class="container" style="max-width: 860px;">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold m-0 text-light"><i class="fa-solid fa-plus-circle text-primary me-2"></i> Thêm Bộ Truyện Mới</h3>
            <p class="text-secondary small m-0">Điền đầy đủ thông tin để tạo bộ truyện tranh mới lên hệ thống</p>
        </div>
        <a href="{{ route('admin.comics.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="fa-solid fa-arrow-left me-1"></i> Danh sách truyện
        </a>
    </div>

    <div class="card card-custom p-4 shadow">
        <form action="{{ route('admin.comics.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Title -->
            <div class="mb-3">
                <label class="form-label text-light fw-semibold small">Tên bộ truyện <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control bg-dark text-light border-secondary @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="Ví dụ: Solo Leveling: Tôi Thăng Cấp Một Mình">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label text-light fw-semibold small">Tên gọi khác / Tên gốc</label>
                    <input type="text" name="alternative_names" class="form-control bg-dark text-light border-secondary" value="{{ old('alternative_names') }}" placeholder="Na Honjaman Rebeleop...">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-light fw-semibold small">Tác giả</label>
                    <input type="text" name="author" class="form-control bg-dark text-light border-secondary" value="{{ old('author') }}" placeholder="Chugong">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-light fw-semibold small">Họa sĩ</label>
                    <input type="text" name="artist" class="form-control bg-dark text-light border-secondary" value="{{ old('artist') }}" placeholder="REDICE Studio">
                </div>
            </div>

            <!-- Categories Checkboxes -->
            <div class="mb-3">
                <label class="form-label text-light fw-semibold small">Thể loại truyện (chọn ít nhất 1) <span class="text-danger">*</span></label>
                <div class="d-flex flex-wrap gap-3 p-3 rounded bg-dark border border-secondary">
                    @foreach($categories as $cat)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $cat->id }}" id="cat-{{ $cat->id }}" {{ is_array(old('categories')) && in_array($cat->id, old('categories')) ? 'checked' : '' }}>
                            <label class="form-check-label text-secondary small" for="cat-{{ $cat->id }}">
                                {{ $cat->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('categories')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Status & Is Featured -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label text-light fw-semibold small">Tình trạng phát hành</label>
                    <select name="status" class="form-select bg-dark text-light border-secondary">
                        <option value="ongoing" {{ old('status') === 'ongoing' ? 'selected' : '' }}>Đang tiến hành (Ongoing)</option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Đã hoàn thành (Completed)</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label text-light small fw-bold" for="isFeatured">
                            <i class="fa-solid fa-star text-warning me-1"></i> Đặt làm Truyện Đề Cử Nổi Bật (Slider trang chủ)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Cover Image Options -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label text-light fw-semibold small">Upload file ảnh bìa (JPG/PNG)</label>
                    <input type="file" name="cover_image" class="form-control bg-dark text-light border-secondary" accept="image/*">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-light fw-semibold small">Hoặc URL ảnh bìa trực tiếp</label>
                    <input type="url" name="cover_url" class="form-control bg-dark text-light border-secondary" placeholder="https://example.com/cover.jpg">
                </div>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="form-label text-light fw-semibold small">Tóm tắt nội dung cốt truyện</label>
                <textarea name="description" class="form-control bg-dark text-light border-secondary" rows="4" placeholder="Giới thiệu bối cảnh, nhân vật chính và nội dung mở đầu...">{{ old('description') }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.comics.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">Hủy</a>
                <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">
                    <i class="fa-solid fa-floppy-disk me-2"></i> Lưu Bộ Truyện
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
