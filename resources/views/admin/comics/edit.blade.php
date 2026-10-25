@extends('layouts.app')

@section('title', 'Chỉnh Sửa Bộ Truyện - ' . $comic->title)

@section('content')
<div class="container" style="max-width: 860px;">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold m-0 text-light"><i class="fa-solid fa-pen-to-square text-primary me-2"></i> Chỉnh Sửa Bộ Truyện</h3>
            <p class="text-secondary small m-0">{{ $comic->title }}</p>
        </div>
        <a href="{{ route('admin.comics.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="fa-solid fa-arrow-left me-1"></i> Danh sách truyện
        </a>
    </div>

    <div class="card card-custom p-4 shadow">
        <form action="{{ route('admin.comics.update', $comic->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-3">
                <label class="form-label text-light fw-semibold small">Tên bộ truyện <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control bg-dark text-light border-secondary @error('title') is-invalid @enderror" value="{{ old('title', $comic->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label text-light fw-semibold small">Tên gọi khác</label>
                    <input type="text" name="alternative_names" class="form-control bg-dark text-light border-secondary" value="{{ old('alternative_names', $comic->alternative_names) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-light fw-semibold small">Tác giả</label>
                    <input type="text" name="author" class="form-control bg-dark text-light border-secondary" value="{{ old('author', $comic->author) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-light fw-semibold small">Họa sĩ</label>
                    <input type="text" name="artist" class="form-control bg-dark text-light border-secondary" value="{{ old('artist', $comic->artist) }}">
                </div>
            </div>

            <!-- Categories Checkboxes -->
            @php
                $currentCatIds = $comic->categories->pluck('id')->toArray();
            @endphp
            <div class="mb-3">
                <label class="form-label text-light fw-semibold small">Thể loại truyện <span class="text-danger">*</span></label>
                <div class="d-flex flex-wrap gap-3 p-3 rounded bg-dark border border-secondary">
                    @foreach($categories as $cat)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $cat->id }}" id="cat-{{ $cat->id }}" {{ in_array($cat->id, old('categories', $currentCatIds)) ? 'checked' : '' }}>
                            <label class="form-check-label text-secondary small" for="cat-{{ $cat->id }}">
                                {{ $cat->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Status & Is Featured -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label text-light fw-semibold small">Tình trạng</label>
                    <select name="status" class="form-select bg-dark text-light border-secondary">
                        <option value="ongoing" {{ old('status', $comic->status) === 'ongoing' ? 'selected' : '' }}>Đang tiến hành</option>
                        <option value="completed" {{ old('status', $comic->status) === 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured" {{ old('is_featured', $comic->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label text-light small fw-bold" for="isFeatured">
                            <i class="fa-solid fa-star text-warning me-1"></i> Đặt làm Truyện Đề Cử Nổi Bật
                        </label>
                    </div>
                </div>
            </div>

            <!-- Current Cover Preview & Change -->
            <div class="row g-3 mb-3 align-items-center">
                <div class="col-auto">
                    <img src="{{ $comic->cover_image }}" width="60" height="85" class="rounded object-fit-cover border border-secondary" alt="Cover">
                </div>
                <div class="col">
                    <label class="form-label text-light fw-semibold small">Thay đổi ảnh bìa (Upload file hoặc nhập URL)</label>
                    <input type="file" name="cover_image" class="form-control bg-dark text-light border-secondary mb-2" accept="image/*">
                    <input type="url" name="cover_url" class="form-control bg-dark text-light border-secondary" placeholder="Hoặc URL ảnh mới: https://...">
                </div>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="form-label text-light fw-semibold small">Tóm tắt nội dung</label>
                <textarea name="description" class="form-control bg-dark text-light border-secondary" rows="4">{{ old('description', $comic->description) }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.comics.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">Hủy</a>
                <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">
                    <i class="fa-solid fa-floppy-disk me-2"></i> Cập Nhật Thay Đổi
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
