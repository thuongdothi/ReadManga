@extends('layouts.app')

@section('title', 'Quản Lý Truyện Tranh - Admin MangaStream')

@section('content')
<div class="container-fluid px-lg-5">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold m-0 text-light"><i class="fa-solid fa-book-journal-whills text-primary me-2"></i> Quản Lý Danh Sách Truyện</h3>
            <p class="text-secondary small m-0">Quản lý nội dung, thể loại và danh sách các chương của từng bộ truyện</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.comics.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fa-solid fa-plus me-1"></i> Thêm Bộ Truyện Mới
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="fa-solid fa-gauge-high me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="card card-custom p-3">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead>
                    <tr class="text-secondary small">
                        <th>Ảnh Bìa</th>
                        <th>Tên Truyện</th>
                        <th>Thể Loại</th>
                        <th>Số Chương</th>
                        <th>Lượt Xem</th>
                        <th>Đánh Giá</th>
                        <th>Tình Trạng</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comics as $comic)
                        <tr>
                            <td>
                                <img src="{{ $comic->cover_image }}" width="45" height="60" class="rounded object-fit-cover shadow-sm" alt="{{ $comic->title }}">
                            </td>
                            <td>
                                <div class="fw-bold text-light text-truncate" style="max-width: 250px;">
                                    <a href="{{ route('comics.show', $comic->slug) }}" target="_blank" class="text-light text-decoration-none">
                                        {{ $comic->title }}
                                    </a>
                                </div>
                                <small class="text-secondary">{{ $comic->author ?? 'Tác giả' }}</small>
                            </td>
                            <td>
                                @foreach($comic->categories->take(2) as $cat)
                                    <span class="badge bg-secondary bg-opacity-25 text-secondary me-1">{{ $cat->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge bg-primary rounded-pill px-2">
                                    {{ $comic->chapters->count() }} chap
                                </span>
                            </td>
                            <td class="small">{{ number_format($comic->views_count) }}</td>
                            <td><span class="text-warning small"><i class="fa-solid fa-star"></i> {{ number_format($comic->rating_score, 1) }}</span></td>
                            <td>
                                <span class="badge {{ $comic->status === 'ongoing' ? 'bg-info text-dark' : 'bg-success' }}">
                                    {{ $comic->status === 'ongoing' ? 'Đang tiến hành' : 'Hoàn thành' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- Manage Chapters -->
                                    <a href="{{ route('admin.chapters.index', $comic->id) }}" class="btn btn-sm btn-outline-warning rounded-pill px-2" title="Quản lý các chương">
                                        <i class="fa-solid fa-list-ol me-1"></i> Quản lý Chap
                                    </a>
                                    <!-- Edit Comic -->
                                    <a href="{{ route('admin.comics.edit', $comic->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Chỉnh sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <!-- Delete Comic -->
                                    <form action="{{ route('admin.comics.destroy', $comic->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bộ truyện này và toàn bộ chương liên quan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-secondary">Chưa có bộ truyện nào được tạo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $comics->links('pagination::bootstrap-5') }}
        </div>
    </div>

</div>
@endsection
