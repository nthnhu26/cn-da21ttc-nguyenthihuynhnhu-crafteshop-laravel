document.addEventListener('DOMContentLoaded', function () {
    // Lấy các phần tử cần thao tác
    const appliesToSelect = document.querySelector('#applies_to'); // Chọn loại áp dụng
    const categorySelect = document.querySelector('#category_id'); // Chọn danh mục
    const productSelect = document.querySelector('#product_id'); // Chọn sản phẩm
    const promoTypeSelect = document.querySelector('#promo_type'); // Chọn loại giảm giá
    const discountValueInput = document.querySelector('#discount_value'); // Giá trị giảm

    // Hiển thị hoặc ẩn trường chọn sản phẩm
    const toggleProductFields = () => {
        const productFields = document.querySelector('.product-fields');
        if (appliesToSelect.value === 'specific_products') {
            productFields.classList.remove('d-none');
        } else {
            productFields.classList.add('d-none');
            productSelect.innerHTML = ''; // Xóa sản phẩm đã chọn
        }
    };

    // Lấy danh sách sản phẩm theo danh mục (AJAX)
    const loadProductsByCategory = (categoryId) => {
        if (!categoryId) {
            productSelect.innerHTML = '<option value="">-- Không có sản phẩm --</option>';
            return;
        }

        fetch(`/admin/promotions/products-by-category/${categoryId}`)
            .then(response => response.json())
            .then(data => {
                productSelect.innerHTML = ''; // Xóa danh sách cũ
                data.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = product.name;
                    productSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Lỗi khi tải sản phẩm:', error));
    };

    // Kiểm tra giá trị giảm (Validation)
    const validateDiscountValue = () => {
        const promoType = promoTypeSelect.value;
        const discountValue = parseFloat(discountValueInput.value);

        if (promoType === 'percentage' && (discountValue < 0 || discountValue > 100)) {
            alert('Giá trị giảm theo % phải nằm trong khoảng từ 0 đến 100.');
            discountValueInput.value = '';
        } else if (promoType === 'fixed_amount' && discountValue <= 0) {
            alert('Giá trị giảm cố định phải lớn hơn 0.');
            discountValueInput.value = '';
        }
    };

    // Event Listeners
    if (appliesToSelect) {
        appliesToSelect.addEventListener('change', toggleProductFields);
    }

    if (categorySelect) {
        categorySelect.addEventListener('change', function () {
            const categoryId = this.value;
            loadProductsByCategory(categoryId);
        });
    }

    if (discountValueInput && promoTypeSelect) {
        discountValueInput.addEventListener('blur', validateDiscountValue);
    }

    // Khởi chạy hiển thị ban đầu
    toggleProductFields();
});
