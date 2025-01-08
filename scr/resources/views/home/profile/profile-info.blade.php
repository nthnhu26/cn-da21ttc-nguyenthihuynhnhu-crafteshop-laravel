<div class="tab-pane fade show active" id="profile-content" role="tabpanel" aria-labelledby="profile-tab">
    <h3>Thông tin cá nhân</h3>
    <form action="{{ route('profile.update.info') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Họ và tên</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                id="name" name="name" value="{{ old('name', Auth::user()->name) }}">
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email"
                value="{{ Auth::user()->email }}" disabled>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
            @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
    </form>
</div>