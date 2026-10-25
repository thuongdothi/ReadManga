@extends('layouts.app')

@section('title', 'Quản Lý Chương: ' . $comic->title)

@section('content')
<div class="container-fluid px-lg-5">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ $comic->cover_image }}" width="50" height="70" class="rounded object-fit-cover shadow-sm" alt="Cover">
            <div>
                <h4 class="fw-bold m-0 text-light">{{ $comic->title }}</h4>
                <p class="text-secondary small m-0">Quản lý các chương truyện, thiết lập VIP/Xu và tải lên trang truyện</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.chapters.create', $comic->id) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fa-solid fa-plus me-1"></i> Thêm Chương Mới
            </a>
            <a href="{{ route('admin.comics.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="fa-solid fa-arrow-left me-1"></i> Danh sách truyện
            </a>
        </div>
    </div>

    <div class="card card-custom p-3">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead>
                    <tr class="text-secondary small">
                        <th>Số Chương</th>
                        <th>Tiêu Đề Chương</th>
                        <th>Thuộc Tính VIP / Giá Xu</th>
                        <th>Số Trang Ảnh</th>
                        <th>Lượt Đọc</th>
                        <th>Ngày Phát Hành</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comic->chapters as $chap)
                        <tr>
                            <td>
                                <strong class="text-light">Chương {{ $chap->chapter_number }}</strong>
                            </td>
                            <td>
                                <span class="text-secondary">{{ $chap->title ?? 'Không có' }}</span>
                            </td>
                            <td>
                                @if($chap->is_vip)
                                    <span class="badge bg-warning text-dark fw-bold">
                                        <i class="fa-solid fa-lock me-1"></i> VIP ({{ $chap->price_coins }} Xu)
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-25 text-secondary">
                                        Miễn Phí (Free)
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    <i class="fa-regular fa-images me-1"></i> {{ $chap->images_count }} trang
                                </span>
                            </td>
                            <td class="small">{{ number_format($chap->views_count) }}</td>
                            <td class="text-secondary small">{{ $chap->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('comics.read', [$comic->slug, $chap->slug]) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle" title="Xem trước chương">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.chapters.destroy', [$comic->id, $chap->id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa chương này? Toàn bộ ảnh sẽ bị xóa vĩnh viễn.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Xóa chương">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-secondary">
                                Bộ truyện này chưa có chương nào. Hãy nhấn <strong>Thêm Chương Mới</strong> để đăng tải!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
