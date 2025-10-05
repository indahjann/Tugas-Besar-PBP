// Footer JavaScript for BUKUKU Bookstore

class BukukuFooter {
    constructor() {
        this.init();
        this.setupEventListeners();
        this.setupBackToTop();
        this.setupNewsletter();
    }

    init() {
        this.footer = document.querySelector('.bukuku-footer');
        this.backToTopBtn = document.querySelector('.back-to-top');
        this.newsletterForm = document.querySelector('.newsletter-form');
        this.newsletterInput = document.querySelector('.newsletter-input');
        this.newsletterBtn = document.querySelector('.newsletter-btn');
    }

    setupEventListeners() {
        // Back to top button
        if (this.backToTopBtn) {
            this.backToTopBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.scrollToTop();
            });
        }

        // Newsletter subscription
        if (this.newsletterForm) {
            this.newsletterForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleNewsletterSubmit();
            });
        }

        // Social media links tracking
        this.setupSocialTracking();

        // Footer links smooth scrolling
        this.setupSmoothScrolling();
    }

    setupBackToTop() {
        // Show/hide back to top button based on scroll position
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                this.showBackToTop();
            } else {
                this.hideBackToTop();
            }
        });
    }

    setupNewsletter() {
        // Newsletter input validation and formatting
        if (this.newsletterInput) {
            this.newsletterInput.addEventListener('input', (e) => {
                this.validateEmail(e.target.value);
            });

            // Enter key submit
            this.newsletterInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.handleNewsletterSubmit();
                }
            });
        }
    }

    setupSocialTracking() {
        // Track social media clicks for analytics
        const socialLinks = document.querySelectorAll('.social-link');
        
        socialLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                const platform = this.getSocialPlatform(link.href);
                this.trackSocialClick(platform);
            });
        });
    }

    setupSmoothScrolling() {
        // Smooth scrolling for footer links that link to page sections
        const footerLinks = document.querySelectorAll('.footer-links a[href^="#"]');
        
        footerLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    this.smoothScrollTo(targetElement);
                }
            });
        });
    }

    showBackToTop() {
        if (this.backToTopBtn) {
            this.backToTopBtn.classList.add('show');
        }
    }

    hideBackToTop() {
        if (this.backToTopBtn) {
            this.backToTopBtn.classList.remove('show');
        }
    }

    scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

        // Track back to top usage
        this.trackEvent('back_to_top', 'clicked');
    }

    smoothScrollTo(element) {
        const offsetTop = element.offsetTop - 80; // Account for fixed navbar
        
        window.scrollTo({
            top: offsetTop,
            behavior: 'smooth'
        });
    }

    async handleNewsletterSubmit() {
        const email = this.newsletterInput?.value.trim();
        
        if (!email) {
            this.showNewsletterMessage('Please enter your email address', 'error');
            return;
        }

        if (!this.isValidEmail(email)) {
            this.showNewsletterMessage('Please enter a valid email address', 'error');
            return;
        }

        try {
            this.setNewsletterLoading(true);
            
            const response = await fetch('/newsletter/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ email })
            });

            const data = await response.json();

            if (response.ok) {
                this.showNewsletterMessage('Thank you for subscribing! Check your email for confirmation.', 'success');
                this.newsletterInput.value = '';
                this.trackEvent('newsletter', 'subscribed', email);
            } else {
                throw new Error(data.message || 'Subscription failed');
            }
        } catch (error) {
            console.error('Newsletter subscription error:', error);
            this.showNewsletterMessage(error.message || 'Something went wrong. Please try again.', 'error');
        } finally {
            this.setNewsletterLoading(false);
        }
    }

    validateEmail(email) {
        const isValid = this.isValidEmail(email);
        
        if (this.newsletterInput) {
            if (email && !isValid) {
                this.newsletterInput.classList.add('invalid');
            } else {
                this.newsletterInput.classList.remove('invalid');
            }
        }

        return isValid;
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    setNewsletterLoading(loading) {
        if (this.newsletterBtn) {
            if (loading) {
                this.newsletterBtn.disabled = true;
                this.newsletterBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subscribing...';
            } else {
                this.newsletterBtn.disabled = false;
                this.newsletterBtn.innerHTML = 'Subscribe';
            }
        }
    }

    showNewsletterMessage(message, type = 'info') {
        // Remove existing message
        const existingMessage = document.querySelector('.newsletter-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        // Create new message
        const messageEl = document.createElement('div');
        messageEl.className = `newsletter-message newsletter-message-${type}`;
        messageEl.textContent = message;

        // Insert after newsletter form
        if (this.newsletterForm) {
            this.newsletterForm.parentNode.insertBefore(messageEl, this.newsletterForm.nextSibling);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (messageEl.parentNode) {
                    messageEl.remove();
                }
            }, 5000);
        }
    }

    getSocialPlatform(url) {
        if (url.includes('facebook')) return 'facebook';
        if (url.includes('twitter')) return 'twitter';
        if (url.includes('instagram')) return 'instagram';
        if (url.includes('linkedin')) return 'linkedin';
        if (url.includes('youtube')) return 'youtube';
        if (url.includes('tiktok')) return 'tiktok';
        return 'other';
    }

    trackSocialClick(platform) {
        this.trackEvent('social', 'click', platform);
    }

    trackEvent(category, action, label = '') {
        // Analytics tracking (Google Analytics, Mixpanel, etc.)
        if (typeof gtag !== 'undefined') {
            gtag('event', action, {
                event_category: category,
                event_label: label
            });
        }

        // Custom analytics
        if (window.analytics && typeof window.analytics.track === 'function') {
            window.analytics.track(`${category}_${action}`, {
                label: label,
                timestamp: new Date().toISOString()
            });
        }

        // Console log for development
        if (process.env.NODE_ENV === 'development') {
            console.log('Event tracked:', { category, action, label });
        }
    }

    // Trust badges animation
    animateTrustBadges() {
        const trustBadges = document.querySelectorAll('.trust-badge');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });

        trustBadges.forEach(badge => {
            badge.style.opacity = '0';
            badge.style.transform = 'translateY(20px)';
            badge.style.transition = 'all 0.6s ease';
            observer.observe(badge);
        });
    }

    // Payment methods hover effects
    setupPaymentMethods() {
        const paymentMethods = document.querySelectorAll('.payment-method');
        
        paymentMethods.forEach(method => {
            method.addEventListener('mouseenter', () => {
                method.style.transform = 'scale(1.05)';
            });
            
            method.addEventListener('mouseleave', () => {
                method.style.transform = 'scale(1)';
            });
        });
    }

    // Lazy load footer content
    lazyLoadFooterContent() {
        const footer = this.footer;
        if (!footer) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Load any lazy content here
                    this.loadFooterWidgets();
                    observer.unobserve(entry.target);
                }
            });
        }, {
            rootMargin: '100px'
        });

        observer.observe(footer);
    }

    async loadFooterWidgets() {
        // Load dynamic footer content like recent blog posts, testimonials, etc.
        try {
            const response = await fetch('/api/footer-widgets', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.updateFooterWidgets(data);
            }
        } catch (error) {
            console.error('Error loading footer widgets:', error);
        }
    }

    updateFooterWidgets(data) {
        // Update footer with dynamic content
        if (data.recentPosts) {
            this.updateRecentPosts(data.recentPosts);
        }

        if (data.testimonials) {
            this.updateTestimonials(data.testimonials);
        }
    }

    updateRecentPosts(posts) {
        const container = document.querySelector('.footer-recent-posts');
        if (container && posts.length > 0) {
            container.innerHTML = posts.map(post => `
                <div class="recent-post">
                    <a href="${post.url}" class="recent-post-title">${post.title}</a>
                    <div class="recent-post-date">${post.date}</div>
                </div>
            `).join('');
        }
    }

    updateTestimonials(testimonials) {
        const container = document.querySelector('.footer-testimonials');
        if (container && testimonials.length > 0) {
            const randomTestimonial = testimonials[Math.floor(Math.random() * testimonials.length)];
            container.innerHTML = `
                <blockquote>
                    <p>"${randomTestimonial.message}"</p>
                    <cite>- ${randomTestimonial.author}</cite>
                </blockquote>
            `;
        }
    }
}

// Initialize footer when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.bukukuFooter = new BukukuFooter();
});

// Initialize animations when footer comes into view
window.addEventListener('load', () => {
    if (window.bukukuFooter) {
        window.bukukuFooter.animateTrustBadges();
        window.bukukuFooter.setupPaymentMethods();
        window.bukukuFooter.lazyLoadFooterContent();
    }
});