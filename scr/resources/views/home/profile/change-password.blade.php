<div class="tab-pane fade" id="password-content" role="tabpanel" aria-labelledby="password-tab">
    <h3>Đổi mật khẩu</h3>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <form action="{{ route('profile.change.password') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="current_password" class="form-label">Mật Khẩu Hiện Tại</label>
            <div class="password-input-group">
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" id="current_password" required>
                <span class="input-group-text toggle-password">
                    <i class="bi bi-eye-slash" id="toggleIcon1"></i>
                </span>
            </div>
            @error('current_password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">Mật Khẩu Mới</label>
            <div class="password-input-group">
                <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" id="new_password" required>
                <span class="input-group-text toggle-password">
                    <i class="bi bi-eye-slash" id="toggleIcon2"></i>
                </span>
            </div>
            @error('new_password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">Xác Nhận Mật Khẩu Mới</label>
            <div class="password-input-group">
                <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" name="new_password_confirmation" id="new_password_confirmation" required>
                <span class="input-group-text toggle-password">
                    <i class="bi bi-eye-slash" id="toggleIcon3"></i>
                </span>
            </div>
            @error('new_password_confirmation')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Đổi Mật Khẩu</button>
    </form>

</div>