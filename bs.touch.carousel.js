document.querySelectorAll('.carousel').forEach(function(carousel) {
        carousel.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        }, { passive: true });

        carousel.addEventListener('touchend', (e) => {
            const diff = e.changedTouches[0].clientX - startX;
            if (Math.abs(diff) > 40) {
                const bsCarousel = bootstrap.Carousel.getInstance(carousel);
                if (diff < 0) {
                    bsCarousel.next();
                } else {
                    bsCarousel.prev();
                }
            }
        });

    });
