<div class="tab-pane fade" id="delete-content" role="tabpanel">
    <h1>Hồ sơ cá nhân</h1>
    <p>Các đơn hàng và đánh giá sẽ được giữ lại khi bạn xóa tài khoản.</p>
    <p>Hãy chắc chắn rằng bạn muốn thực hiện hành động này.</p>

    <button class="btn btn-danger" onclick="confirmDeleteAccount()">Xóa tài khoản</button>

    <form id="delete-account-form" action="{{ route('profile.deleteAccount') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>
<script>
    function confirmDeleteAccount() {
        Swal.fire({
            title: 'Xác nhận xóa tài khoản?',
            text: "Các đơn hàng và đánh giá của bạn sẽ được giữ lại, nhưng bạn không thể hoàn tác hành động này!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-account-form').submit();
            }
        });
    }
</script>
