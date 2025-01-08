function updateQuantity(action, productId, isBuyNow = false) {
    const inputId = isBuyNow ? `quantity-buy-${productId}` : `quantity-${productId}`;
    const priceId = isBuyNow ? `totalPrice-buy-${productId}` : `totalPrice-${productId}`;
    const quantityInput = document.getElementById(inputId);
    const pricePerUnit = parseFloat(quantityInput.dataset.pricePerUnit || 0);

    let quantity = parseInt(quantityInput.value) || 1;

    if (action === 'increase') {
        quantity++;
    } else if (action === 'decrease' && quantity > 1) {
        quantity--;
    }

    quantityInput.value = quantity;
    document.getElementById(priceId).textContent = (pricePerUnit * quantity).toLocaleString('vi-VN') + ' VNĐ';
}

function addToCart(productId) {
    const quantity = parseInt(document.getElementById(`quantity-${productId}`).value);

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId, quantity: quantity })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Thêm vào giỏ hàng thành công!');
            } else {
                alert('Có lỗi xảy ra!');
            }
        })
        .catch(error => console.error('Error:', error));
}

function buyNow(productId) {
    const quantity = parseInt(document.getElementById(`quantity-buy-${productId}`).value);

    fetch('/cart/buy-now', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId, quantity: quantity })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/checkout';
            } else {
                alert('Có lỗi xảy ra!');
            }
        })
        .catch(error => console.error('Error:', error));
}
