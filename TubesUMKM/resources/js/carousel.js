document.addEventListener('DOMContentLoaded', function() {
  const carousel = document.getElementById('bookCarousel');
  const track = document.getElementById('carouselTrack');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
    
  if (!carousel || !track || !prevBtn || !nextBtn) return;
    
  const originalItems = Array.from(track.querySelectorAll('.carousel-item-custom'));
  const itemWidth = 240; // 220px + 20px gap
  const totalItems = originalItems.length;
    
  // Clone items for infinite loop
  // Clone first few items and append to end
  const clonesToAppend = Math.min(4, totalItems);
  for (let i = 0; i < clonesToAppend; i++) {
    const clone = originalItems[i].cloneNode(true);
    clone.classList.add('carousel-clone');
    track.appendChild(clone);
  }
    
  // Clone last few items and prepend to beginning
  const clonesToPrepend = Math.min(4, totalItems);
  for (let i = clonesToPrepend - 1; i >= 0; i--) {
    const clone = originalItems[totalItems - 1 - i].cloneNode(true);
    clone.classList.add('carousel-clone');
    track.insertBefore(clone, track.firstChild);
  }
    
  // Update all items array to include clones
  const allItems = Array.from(track.querySelectorAll('.carousel-item-custom'));
  const startIndex = clonesToPrepend;
  let currentIndex = startIndex;
    
  // Set initial position (start at first real item, after prepended clones)
  let currentTranslate = startIndex * itemWidth;
  track.style.transform = `translateX(-${currentTranslate}px)`;
    
  let autoSlideInterval;
  let isTransitioning = false;
  let lastSlideTime = 0;
  const slideThrottle = 1000; // Minimum 1 second between slides
    
  function updateCarousel(animate = true) {
    if (animate) {
      track.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
    } else {
      track.style.transition = 'none';
    }
    track.style.transform = `translateX(-${currentTranslate}px)`;
        
    // Remove transition disable after a brief moment
    if (!animate) {
      setTimeout(() => {
        track.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
      }, 50);
    }
  }
    
  function nextSlide() {
    const now = Date.now();
    if (isTransitioning || (now - lastSlideTime) < slideThrottle) return;
        
    lastSlideTime = now;
    isTransitioning = true;
    currentIndex++;
    currentTranslate += itemWidth;
        
    updateCarousel(true);
        
    // Check if we need to loop back
    setTimeout(() => {
      if (currentIndex >= totalItems + startIndex) {
        // We're at a clone at the end, jump back to real start
        currentIndex = startIndex;
        currentTranslate = startIndex * itemWidth;
        updateCarousel(false);
      }
      isTransitioning = false;
    }, 600); // Match transition duration
  }
    
  function prevSlide() {
    const now = Date.now();
    if (isTransitioning || (now - lastSlideTime) < slideThrottle) return;
        
    lastSlideTime = now;
    isTransitioning = true;
    currentIndex--;
    currentTranslate -= itemWidth;
        
    updateCarousel(true);
        
    // Check if we need to loop forward
    setTimeout(() => {
      if (currentIndex < startIndex) {
        // We're at a clone at the beginning, jump to real end
        currentIndex = totalItems + startIndex - 1;
        currentTranslate = currentIndex * itemWidth;
        updateCarousel(false);
      }
      isTransitioning = false;
    }, 600); // Match transition duration
  }
    
  function startAutoSlide() {
    autoSlideInterval = setInterval(nextSlide, 6000); // 6 detik
  }
    
  function stopAutoSlide() {
    clearInterval(autoSlideInterval);
  }
    
  // Event listeners
  nextBtn.addEventListener('click', function() {
    stopAutoSlide();
    nextSlide();
    setTimeout(startAutoSlide, 8000); // Resume after 8 seconds
  });
    
  prevBtn.addEventListener('click', function() {
    stopAutoSlide();
    prevSlide();
    setTimeout(startAutoSlide, 8000); // Resume after 8 seconds
  });
    
  // Pause auto-slide on carousel hover (as backup)
  carousel.addEventListener('mouseenter', stopAutoSlide);
  carousel.addEventListener('mouseleave', startAutoSlide);
    
  // Individual card hover pause with debounce
  let hoverTimeout;
  let cardsWithListeners = new Set();
    
  function setupCardHoverPause() {
    const cards = document.querySelectorAll('.book-card-modern');
        
    cards.forEach(card => {
      // Skip if this card already has listeners
      if (cardsWithListeners.has(card)) return;
            
      cardsWithListeners.add(card);
            
      const mouseEnterHandler = function() {
        clearTimeout(hoverTimeout);
        stopAutoSlide();
        // Add visual feedback
        this.style.zIndex = '10';
        this.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
      };
            
      const mouseLeaveHandler = function() {
        clearTimeout(hoverTimeout);
        // Remove visual feedback
        this.style.zIndex = '1';
                
        // Delay restart to prevent flickering when moving between cards
        hoverTimeout = setTimeout(() => {
          startAutoSlide();
        }, 300);
      };
            
      card.addEventListener('mouseenter', mouseEnterHandler);
      card.addEventListener('mouseleave', mouseLeaveHandler);
            
      // Store handlers for potential cleanup
      card._hoverHandlers = {
        mouseenter: mouseEnterHandler,
        mouseleave: mouseLeaveHandler
      };
    });
  }
    
  // Initialize
  updateCarousel(false);
  startAutoSlide();
    
  // Setup hover pause for all cards (including clones)
  setTimeout(() => {
    setupCardHoverPause();
  }, 100);
    
  // Handle window resize
  let resizeTimeout;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function() {
      // Recalculate position on resize
      currentTranslate = currentIndex * itemWidth;
      updateCarousel(false);
    }, 250);
  });
    
  // Re-enable event handlers for cloned items
  setTimeout(() => {
    setupButtonHandlers();
    setupCardHoverPause(); // Re-setup hover for cloned items too
  }, 100);
});

// Button interaction handlers
function setupButtonHandlers() {
  // Buy Now buttons
  document.querySelectorAll('.btn-buy-now').forEach(btn => {
    // Remove existing listeners to avoid duplicates
    btn.replaceWith(btn.cloneNode(true));
  });
    
  document.querySelectorAll('.btn-buy-now').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      alert('Redirecting to checkout... (Demo)');
    });
  });
    
  // Add to Cart buttons
  document.querySelectorAll('.btn-cart').forEach(btn => {
    btn.replaceWith(btn.cloneNode(true));
  });
    
  document.querySelectorAll('.btn-cart').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const card = this.closest('.book-card-modern');
      const title = card.querySelector('.book-title').textContent;
      alert(`"${title}" added to cart! (Demo)`);
    });
  });
    
  // Favorite buttons
  document.querySelectorAll('.btn-favorite').forEach(btn => {
    btn.replaceWith(btn.cloneNode(true));
  });
    
  document.querySelectorAll('.btn-favorite').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const icon = this.querySelector('i');
      if (icon.classList.contains('far')) {
        icon.classList.remove('far');
        icon.classList.add('fas');
        this.style.color = '#e74c3c';
      } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
        this.style.color = 'white';
      }
    });
  });
}

document.addEventListener('DOMContentLoaded', function() {
  // Initial setup of button handlers
  setupButtonHandlers();
    
  // View More button
  const viewMoreBtn = document.querySelector('.view-more-btn');
  if (viewMoreBtn) {
    viewMoreBtn.addEventListener('click', function(e) {
      e.preventDefault();
      alert('Redirecting to full catalog... (Demo)');
    });
  }
    
  // Legacy Buy Now buttons for other sections
  document.querySelectorAll('.btn-primary').forEach(btn => {
    if (!btn.classList.contains('btn-buy-now')) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        alert('Buku ditambahkan ke cart! (Demo)');
      });
    }
  });
});
