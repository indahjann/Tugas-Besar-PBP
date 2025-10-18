/**
 * Promo Carousel - Gramedia Style
 * Auto-play carousel with navigation controls
 */

class PromoCarousel {
    constructor() {
        this.currentIndex = 0;
        this.slides = [];
        this.indicators = [];
        this.autoplayInterval = null;
        this.autoplayDelay = 5000; // 5 seconds
        this.isTransitioning = false;
        
        this.init();
    }

    init() {
        // Get DOM elements
        this.track = document.getElementById('promoTrack');
        this.prevBtn = document.getElementById('promoPrev');
        this.nextBtn = document.getElementById('promoNext');
        this.indicatorsContainer = document.getElementById('promoIndicators');
        
        if (!this.track) return;
        
        // Get all slides and indicators
        this.slides = Array.from(this.track.querySelectorAll('.promo-slide'));
        this.indicators = Array.from(this.indicatorsContainer?.querySelectorAll('.indicator') || []);
        
        if (this.slides.length === 0) return;
        
        // Set up event listeners
        this.setupEventListeners();
        
        // Mark first slide as active
        this.updateSlideState();
        
        // Start autoplay
        this.startAutoplay();
    }

    setupEventListeners() {
        // Navigation buttons
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.prev());
        }
        
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.next());
        }
        
        // Indicator dots
        this.indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => this.goToSlide(index));
        });
        
        // Pause autoplay on hover
        const carousel = document.getElementById('promoCarousel');
        if (carousel) {
            carousel.addEventListener('mouseenter', () => this.stopAutoplay());
            carousel.addEventListener('mouseleave', () => this.startAutoplay());
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.prev();
            if (e.key === 'ArrowRight') this.next();
        });
        
        // Touch/swipe support
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
                this.next(); // Swipe left
            } else {
                this.prev(); // Swipe right
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
        
        // Update track position
        const offset = -this.currentIndex * 100;
        this.track.style.transform = `translateX(${offset}%)`;
        
        // Update indicators
        this.updateIndicators();
        
        // Update slide states for animations
        this.updateSlideState();
        
        // Reset transition lock
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
        this.stopAutoplay(); // Clear any existing interval
        
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

// Initialize promo carousel when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    const promoCarousel = new PromoCarousel();
    
    // Store instance globally if needed
    window.PromoCarousel = promoCarousel;
});

// Re-initialize after AJAX navigation (if using Turbo/Livewire)
document.addEventListener('turbo:load', () => {
    if (window.PromoCarousel) {
        window.PromoCarousel.stopAutoplay();
    }
    window.PromoCarousel = new PromoCarousel();
});

export default PromoCarousel;
