
/************************Sản phẩm nổi bật**************************/
// Đảm bảo DOM và các dependencies đã load xong
document.addEventListener('DOMContentLoaded', function() {
    initializeProductCarousel();
    initializeButtonHoverEffects();
});

function initializeProductCarousel() {
    $('.product-carousel').slick({
        dots: true,
        infinite: true,
        speed: 500,
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        prevArrow: '<button type="button" class="slick-prev"><i class="bi bi-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="bi bi-chevron-right"></i></button>',
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false
                }
            }
        ]
    });
}

function initializeButtonHoverEffects() {
    const buttons = document.querySelectorAll('.product-item .btn');
    
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            toggleButtonClass(this);
        });

        button.addEventListener('mouseleave', function() {
            toggleButtonClass(this);
        });
    });
}

// Lazy loading cho hình ảnh
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.product-image img');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    observer.unobserve(img);
                }
            });
        });

        images.forEach(img => {
            imageObserver.observe(img);
        });
    }
});

//cuộn đầu trang
window.onscroll = function () {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("back-to-top").style.display = "block";
    } else {
        document.getElementById("back-to-top").style.display = "none";
    }
};

function scrollToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

// Hàm để chuyển đổi chế độ tối/sáng
function toggleTheme() {
    document.body.classList.toggle('dark-mode');
    const icon = document.querySelector('.icon-toggle i');
    if (document.body.classList.contains('dark-mode')) {
        icon.classList.replace('bi-brightness-high', 'bi-moon');
    } else {
        icon.classList.replace('bi-moon', 'bi-brightness-high');
    }
}

