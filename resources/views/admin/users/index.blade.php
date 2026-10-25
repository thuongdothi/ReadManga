@extends('layouts.app')

@section('title', 'Quản Lý Người Dùng - Admin MangaStream')

@section('content')
<div class="container-fluid px-lg-5">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold m-0 text-light"><i class="fa-solid fa-users text-primary me-2"></i> Quản Lý Danh Sách Người Dùng</h3>
            <p class="text-secondary small m-0">Phân quyền chức vụ (Admin / Uploader / User), quản lý số dư Xu và trạng thái tài khoản</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fa-solid fa-gauge-high me-1"></i> Dashboard
        </a>
    </div>

    <div class="card card-custom p-3">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead>
                    <tr class="text-secondary small">
                        <th>Thành Viên</th>
                        <th>Email</th>
                        <th>Phân Quyền (Role)</th>
                        <th>Số Dư Ví Xu</th>
                        <th>Gói VIP</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $u->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed='.urlencode($u->name) }}" width="36" height="36" class="rounded-circle" alt="Avatar">
                                    <strong class="text-light">{{ $u->name }}</strong>
                                </div>
                            </td>
                            <td><span class="text-secondary">{{ $u->email }}</span></td>
                            
                            <form action="{{ route('admin.users.update', $u->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <td>
                                    <select name="role" class="form-select form-select-sm bg-dark text-light border-secondary" style="width: 120px;">
                                        <option value="user" {{ $u->role === 'user' ? 'selected' : '' }}>User</option>
                                        <option value="uploader" {{ $u->role === 'uploader' ? 'selected' : '' }}>Uploader</option>
                                        <option value="moderator" {{ $u->role === 'moderator' ? 'selected' : '' }}>Moderator</option>
                                        <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm" style="width: 130px;">
                                        <input type="number" name="coin_balance" class="form-control bg-dark text-light border-secondary" value="{{ $u->coin_balance }}" min="0">
                                        <span class="input-group-text bg-transparent border-secondary text-warning">Xu</span>
                                    </div>
                                </td>
                                <td>
                                    @if($u->isVip())
                                        <span class="badge-vip"><i class="fa-solid fa-crown me-1"></i> VIP</span>
                                    @else
                                        <span class="text-secondary small">Không</span>
                                    @endif
                                </td>
                                <td>
                                    <select name="status" class="form-select form-select-sm bg-dark text-light border-secondary" style="width: 110px;">
                                        <option value="active" {{ $u->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="banned" {{ $u->status === 'banned' ? 'selected' : '' }}>Banned</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                                        <i class="fa-solid fa-floppy-disk me-1"></i> Cập nhật
                                    </button>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>

</div>
@endsection
