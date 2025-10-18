class PromoCarousel {
    constructor() {
        this.currentIndex = 0;
        this.slides = [];
        this.indicators = [];
        this.autoplayInterval = null;
        this.autoplayDelay = 5000;
        this.isTransitioning = false;
        
        this.init();
    }

    init() {
        this.track = document.getElementById('promoTrack');
        this.prevBtn = document.getElementById('promoPrev');
        this.nextBtn = document.getElementById('promoNext');
        this.indicatorsContainer = document.getElementById('promoIndicators');
        
        if (!this.track) {
            return;
        }
        
        this.slides = Array.from(this.track.querySelectorAll('.promo-slide'));
        this.indicators = Array.from(this.indicatorsContainer?.querySelectorAll('.indicator') || []);
        
        if (this.slides.length === 0) {
            return;
        }
        
        this.setupEventListeners();
        this.updateSlideState();
        this.startAutoplay();
    }

    setupEventListeners() {
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.prev());
        }
        
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.next());
        }
        
        this.indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => this.goToSlide(index));
        });
        
        const carousel = document.getElementById('promoCarousel');
        if (carousel) {
            carousel.addEventListener('mouseenter', () => this.stopAutoplay());
            carousel.addEventListener('mouseleave', () => this.startAutoplay());
        }
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.prev();
            if (e.key === 'ArrowRight') this.next();
        });
        
        this.setupTouchEvents();
    }

    setupTouchEvents() {
        let touchStartX = 0;
        let touchEndX = 0;
        
        const carousel = document.getElementById('promoCarousel');
        if (!carousel) return;
        
        carousel.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        carousel.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            this.handleSwipe(touchStartX, touchEndX);
        }, { passive: true });
    }

    handleSwipe(startX, endX) {
        const swipeThreshold = 50;
        const diff = startX - endX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                this.next();
            } else {
                this.prev();
            }
        }
    }

    next() {
        if (this.isTransitioning) return;
        
        this.currentIndex = (this.currentIndex + 1) % this.slides.length;
        this.updateCarousel();
        this.resetAutoplay();
    }

    prev() {
        if (this.isTransitioning) return;
        
        this.currentIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length;
        this.updateCarousel();
        this.resetAutoplay();
    }

    goToSlide(index) {
        if (this.isTransitioning || index === this.currentIndex) return;
        
        this.currentIndex = index;
        this.updateCarousel();
        this.resetAutoplay();
    }

    updateCarousel() {
        this.isTransitioning = true;
        
        const offset = -this.currentIndex * 100;
        this.track.style.transform = `translateX(${offset}%)`;
        
        this.updateIndicators();
        this.updateSlideState();
        
        setTimeout(() => {
            this.isTransitioning = false;
        }, 500);
    }

    updateIndicators() {
        this.indicators.forEach((indicator, index) => {
            if (index === this.currentIndex) {
                indicator.classList.add('active');
                indicator.setAttribute('aria-current', 'true');
            } else {
                indicator.classList.remove('active');
                indicator.removeAttribute('aria-current');
            }
        });
    }

    updateSlideState() {
        this.slides.forEach((slide, index) => {
            if (index === this.currentIndex) {
                slide.classList.add('active');
                slide.setAttribute('aria-hidden', 'false');
            } else {
                slide.classList.remove('active');
                slide.setAttribute('aria-hidden', 'true');
            }
        });
    }

    startAutoplay() {
        this.stopAutoplay();
        
        this.autoplayInterval = setInterval(() => {
            this.next();
        }, this.autoplayDelay);
    }

    stopAutoplay() {
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
            this.autoplayInterval = null;
        }
    }

    resetAutoplay() {
        this.startAutoplay();
    }
}

// Initialize on page load
let promoCarouselInstance = null;

function initializePromoCarousel() {
    const promoTrack = document.getElementById('promoTrack');
    if (promoTrack && !promoCarouselInstance) {
        promoCarouselInstance = new PromoCarousel();
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePromoCarousel);
} else {
    initializePromoCarousel();
}

// Reinitialize on AJAX navigation (untuk logo BUKUKU)
document.addEventListener('click', (e) => {
    const ajaxLink = e.target.closest('[data-ajax-link]');
    if (ajaxLink) {
        const href = ajaxLink.getAttribute('href');
        if (href === '/' || href === '') {
            setTimeout(() => {
                promoCarouselInstance = null;
                initializePromoCarousel();
            }, 300);
        }
    }
});

export default PromoCarousel;