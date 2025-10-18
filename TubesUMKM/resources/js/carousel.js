// Multiple Carousel Handler for Homepage with Original Logic
let carouselInstances = [];

function initializeCarousels() {
  // Clean up existing instances
  carouselInstances.forEach(instance => instance.destroy());
  carouselInstances = [];
  
  // Find all carousels on the page
  const carousels = document.querySelectorAll('.custom-carousel');
  
  carousels.forEach((carousel, index) => {
    const track = carousel.querySelector('.carousel-track');
    const prevBtn = document.getElementById(`prevBtn${index}`);
    const nextBtn = document.getElementById(`nextBtn${index}`);
    
    if (!carousel || !track || !prevBtn || !nextBtn) return;
    
    initializeSingleCarousel(carousel, track, prevBtn, nextBtn, index);
  });
}

function initializeSingleCarousel(carousel, track, prevBtn, nextBtn, index) {
  // Create instance object
  const instance = {
    carousel,
    track,
    prevBtn,
    nextBtn,
    index,
    autoSlideInterval: null,
    autoSlideResumeTimeout: null,
    carouselHoverTimeout: null,
    hoverTimeout: null,
    resizeTimeout: null,
    eventListeners: [],
    safetyCheck: null,
    
    destroy() {
      // Clear all timers
      clearInterval(this.autoSlideInterval);
      clearInterval(this.safetyCheck);
      clearTimeout(this.autoSlideResumeTimeout);
      clearTimeout(this.carouselHoverTimeout);
      clearTimeout(this.hoverTimeout);
      clearTimeout(this.resizeTimeout);
      
      // Reset flags
      this.autoSlideInterval = null;
      this.safetyCheck = null;
      
      // Remove event listeners
      this.eventListeners.forEach(({ element, event, handler }) => {
        if (element && element.removeEventListener) {
          element.removeEventListener(event, handler);
        }
      });
      this.eventListeners = [];
      
      // Reset carousel state
      if (this.track) {
        this.track.style.transition = 'none';
        this.track.style.transform = 'translateX(0)';
      }
    },
    
    addEventListener(element, event, handler) {
      element.addEventListener(event, handler);
      this.eventListeners.push({ element, event, handler });
    }
  };
  
  const originalItems = Array.from(track.querySelectorAll('.carousel-item-custom'));
  const itemWidth = 240; // 220px + 20px gap
  const totalItems = originalItems.length;
  
  // Clone items for infinite loop - original logic
  const clonesToAppend = Math.min(4, totalItems);
  for (let i = 0; i < clonesToAppend; i++) {
    const clone = originalItems[i].cloneNode(true);
    clone.classList.add('carousel-clone');
    track.appendChild(clone);
  }
  
  const clonesToPrepend = Math.min(4, totalItems);
  for (let i = clonesToPrepend - 1; i >= 0; i--) {
    const clone = originalItems[totalItems - 1 - i].cloneNode(true);
    clone.classList.add('carousel-clone');
    track.insertBefore(clone, track.firstChild);
  }
  
  // Update array items including clones
  const allItems = Array.from(track.querySelectorAll('.carousel-item-custom'));
  const startIndex = clonesToPrepend;
  let currentIndex = startIndex;
  
  // Set initial position (start from first real item)
  let currentTranslate = startIndex * itemWidth;
  track.style.transform = `translateX(-${currentTranslate}px)`;
  
  let isTransitioning = false;
  let lastSlideTime = 0;
  const slideThrottle = 300;
  
  function updateCarousel(animate = true) {
    if (animate) {
      track.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
    } else {
      track.style.transition = 'none';
    }
    track.style.transform = `translateX(-${currentTranslate}px)`;
    
    // Re-enable transition after a moment
    if (!animate) {
      setTimeout(() => {
        track.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
      }, 50);
    }
  }
  
  function nextSlide() {
    const now = Date.now();
    if (isTransitioning || (now - lastSlideTime) < slideThrottle) {
      return;
    }
    
    lastSlideTime = now;
    isTransitioning = true;
    currentIndex++;
    currentTranslate += itemWidth;
    
    updateCarousel(true);
    
    // Check if we need to loop back
    setTimeout(() => {
      if (currentIndex >= totalItems + startIndex) {
        // At last clone, jump to beginning
        currentIndex = startIndex;
        currentTranslate = startIndex * itemWidth;
        updateCarousel(false);
      }
      isTransitioning = false;
    }, 650);
  }
  
  function prevSlide() {
    const now = Date.now();
    if (isTransitioning || (now - lastSlideTime) < slideThrottle) {
      return;
    }
    
    lastSlideTime = now;
    isTransitioning = true;
    currentIndex--;
    currentTranslate -= itemWidth;
    
    updateCarousel(true);
    
    // Check if we need to loop forward
    setTimeout(() => {
      if (currentIndex < startIndex) {
        // At first clone, jump to real end
        currentIndex = totalItems + startIndex - 1;
        currentTranslate = currentIndex * itemWidth;
        updateCarousel(false);
      }
      isTransitioning = false;
    }, 650);
  }
  
  function startAutoSlide() {
    if (instance.autoSlideInterval) {
      clearInterval(instance.autoSlideInterval);
    }
    instance.autoSlideInterval = setInterval(nextSlide, 6000);
  }
  
  function stopAutoSlide() {
    if (instance.autoSlideInterval) {
      clearInterval(instance.autoSlideInterval);
      instance.autoSlideInterval = null;
    }
  }
  
  // Event listeners with improved handling from original code
  function handleManualSlide(slideFunction) {
    clearTimeout(instance.autoSlideResumeTimeout);
    stopAutoSlide();
    slideFunction();
    instance.autoSlideResumeTimeout = setTimeout(() => {
      startAutoSlide();
    }, 8000);
  }
  
  const nextClickHandler = function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    handleManualSlide(nextSlide);
  };
  
  const prevClickHandler = function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    handleManualSlide(prevSlide);
  };
  
  instance.addEventListener(nextBtn, 'click', nextClickHandler);
  instance.addEventListener(prevBtn, 'click', prevClickHandler);
  
  // Simple hover handling - only pause auto-slide when hovering carousel
  const simpleMouseEnterHandler = function() {
    stopAutoSlide();
  };
  
  const simpleMouseLeaveHandler = function() {
    setTimeout(() => {
      startAutoSlide();
    }, 500);
  };
  
  instance.addEventListener(carousel, 'mouseenter', simpleMouseEnterHandler);
  instance.addEventListener(carousel, 'mouseleave', simpleMouseLeaveHandler);
  
  // Initialize carousel
  updateCarousel(false);
  startAutoSlide();
  
  // Handle window resize
  const resizeHandler = function() {
    clearTimeout(instance.resizeTimeout);
    instance.resizeTimeout = setTimeout(function() {
      // Recalculate position on resize
      currentTranslate = currentIndex * itemWidth;
      updateCarousel(false);
    }, 250);
  };
  
  instance.addEventListener(window, 'resize', resizeHandler);
  
  // Safety mechanism: periodically check if auto-slide is running
  const safetyCheck = setInterval(() => {
    if (instance && 
        carousel.closest('body') && // Check if still in DOM
        !instance.autoSlideInterval && 
        !isTransitioning) {
      startAutoSlide();
    }
  }, 10000);
  
  instance.safetyCheck = safetyCheck;
  
  // Add to instances array
  carouselInstances.push(instance);
}

// Initialize carousels on DOM ready
document.addEventListener('DOMContentLoaded', initializeCarousels);

// Global function to manually reinitialize carousels
window.reinitializeCarousels = initializeCarousels;
window.carouselInstances = carouselInstances;

// Re-initialize carousel when returning to homepage or when DOM changes
document.addEventListener('visibilitychange', function() {
  if (!document.hidden) {
    // Page became visible, check if carousel needs reinitialization
    setTimeout(() => {
      const carousel = document.getElementById('bookCarousel');
      // Only initialize if carousel exists and no working instance
      if (carousel && 
          (!carouselInstance || 
           carouselInstance.carousel !== carousel ||
           !carouselInstance.autoSlideInterval)) {
        initializeCarousel();
      }
    }, 200);
  }
});

// Listen for navigation events (for SPA-like navigation)
window.addEventListener('popstate', function() {
  setTimeout(() => {
    const carousel = document.getElementById('bookCarousel');
    if (carousel) {
      initializeCarousel();
    }
  }, 100);
});

// Watch for DOM changes - OPTIMIZED to prevent favorites button lag
if ('MutationObserver' in window) {
  let mutationTimeout;
  
  const observer = new MutationObserver(function(mutations) {
    // Filter mutations to only relevant ones
    const relevantMutations = mutations.filter(mutation => {
      // Ignore class changes to buttons (favorites toggle)
      if (mutation.type === 'attributes' && 
          mutation.attributeName === 'class' && 
          (mutation.target.classList.contains('btn-favorites') ||
           mutation.target.classList.contains('btn-add-to-cart'))) {
        return false;
      }
      
      // Ignore mutations inside book cards (avoid carousel reinit on button clicks)
      if (mutation.target.closest && 
          (mutation.target.closest('.book-card-modern') ||
           mutation.target.closest('.carousel-item-custom'))) {
        return false;
      }
      
      // Only care about significant structural changes
      return mutation.type === 'childList' && 
             mutation.addedNodes.length > 0 &&
             Array.from(mutation.addedNodes).some(node => 
               node.nodeType === 1 && // Element nodes only
               (node.id === 'bookCarousel' || 
                node.querySelector && node.querySelector('#bookCarousel'))
             );
    });
    
    // Skip if no relevant mutations
    if (relevantMutations.length === 0) {
      return;
    }
    
    // Debounce relevant mutations only
    clearTimeout(mutationTimeout);
    mutationTimeout = setTimeout(() => {
      const carousel = document.getElementById('bookCarousel');
      
      // More strict reinitialization check
      if (carousel && 
          (!carouselInstance || 
           carouselInstance.carousel !== carousel || 
           !document.querySelector('#bookCarousel .carousel-track'))) {
        initializeCarousel();
      }
    }, 300); // Longer debounce to prevent rapid reinitialization
  });
  
  // More specific observation - avoid watching attributes that cause lag
  observer.observe(document.body, {
    childList: true,
    subtree: true,
    attributes: false // Completely disable attribute watching
  });
}

// Global function to manually reinitialize carousel
window.reinitializeCarousel = function() {
  initializeCarousel();
};

// Event delegation for carousel buttons - OPTIMIZED
let carouselEventsDelegated = false;

function setupButtonHandlers() {
  // Skip if already set up to prevent duplicates
  if (carouselEventsDelegated) return;
  
  // Don't interfere with book-card.js event handling
  // Just ensure carousel pauses during interactions
  carouselEventsDelegated = true;
}

// Logo click handling only - button clicks handled by book-card.js
document.addEventListener('click', function(e) {
  // Check if it's a logo link click
  const logoLink = e.target.closest('.logo-text, .logo-section a');
  if (logoLink) {
    // If we're already on home and carousel exists, don't reinitialize
    if (window.location.pathname === '/' || window.location.pathname === '/index.html') {
      const carousel = document.getElementById('bookCarousel');
      if (carousel && carouselInstance && carouselInstance.autoSlideInterval) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return false;
      }
    }
  }
}); // Use normal bubbling phase

// Additional initialization for other page elements
document.addEventListener('DOMContentLoaded', function() {
  // Only run on home page
  const isHomePage = window.location.pathname === '/' || document.querySelector('.carousel-section');
  
  if (!isHomePage) {
    console.log('Carousel: Not on home page, skipping initialization');
    return;
  }
  
  // Initial setup of button handlers for non-carousel elements
  setupButtonHandlers();
    
  // View More button
  const viewMoreBtn = document.querySelector('.view-more-btn');
  if (viewMoreBtn) {
    viewMoreBtn.addEventListener('click', function(e) {
      e.preventDefault();
      alert('Redirecting to full catalog... (Demo)');
    });
  }
    
  // Legacy Buy Now buttons ONLY in carousel/featured sections
  const carouselSection = document.querySelector('.carousel-section');
  const featuredSection = document.querySelector('.featured-section');
  
  if (carouselSection) {
    carouselSection.querySelectorAll('.btn-primary').forEach(btn => {
      if (!btn.classList.contains('btn-buy-now')) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          alert('Buku ditambahkan ke cart! (Demo)');
        });
      }
    });
  }
  
  if (featuredSection) {
    featuredSection.querySelectorAll('.btn-primary').forEach(btn => {
      if (!btn.classList.contains('btn-buy-now')) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          alert('Buku ditambahkan ke cart! (Demo)');
        });
      }
    });
  }
});
