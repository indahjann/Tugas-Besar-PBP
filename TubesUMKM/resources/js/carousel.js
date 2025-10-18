let carouselInstances = [];

function initializeCarousels() {
  carouselInstances.forEach(instance => {
    if (instance && instance.destroy) {
      instance.destroy();
    }
  });
  carouselInstances = [];
  
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
  const instance = {
    carousel,
    track,
    prevBtn,
    nextBtn,
    index,
    autoSlideInterval: null,
    autoSlideResumeTimeout: null,
    resizeTimeout: null,
    eventListeners: [],
    safetyCheck: null,
    isDestroyed: false,
    
    destroy() {
      if (this.isDestroyed) return;
      this.isDestroyed = true;
      
      clearInterval(this.autoSlideInterval);
      clearInterval(this.safetyCheck);
      clearTimeout(this.autoSlideResumeTimeout);
      clearTimeout(this.resizeTimeout);
      
      this.autoSlideInterval = null;
      this.safetyCheck = null;
      
      this.eventListeners.forEach(({ element, event, handler }) => {
        if (element && element.removeEventListener) {
          element.removeEventListener(event, handler);
        }
      });
      this.eventListeners = [];
      
      if (this.track) {
        this.track.style.transition = 'none';
        this.track.style.transform = 'translateX(0)';
      }
    },
    
    addEventListener(element, event, handler) {
      if (!element) return;
      element.addEventListener(event, handler);
      this.eventListeners.push({ element, event, handler });
    }
  };
  
  track.querySelectorAll('.carousel-clone').forEach(el => el.remove());
  
  const originalItems = Array.from(track.querySelectorAll('.carousel-item-custom'));
  const itemWidth = 215;
  const totalItems = originalItems.length;
  
  if (totalItems === 0) return;
  
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
  
  const startIndex = clonesToPrepend;
  let currentIndex = startIndex;
  let currentTranslate = startIndex * itemWidth;
  let isTransitioning = false;
  let lastSlideTime = 0;
  const slideThrottle = 300;
  
  track.style.transform = `translateX(-${currentTranslate}px)`;
  track.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
  
  function updateCarousel(animate = true) {
    if (instance.isDestroyed) return;
    
    if (animate) {
      track.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
    } else {
      track.style.transition = 'none';
    }
    track.style.transform = `translateX(-${currentTranslate}px)`;
    
    if (!animate) {
      setTimeout(() => {
        if (!instance.isDestroyed) {
          track.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        }
      }, 50);
    }
  }
  
  function nextSlide() {
    if (instance.isDestroyed) return;
    
    const now = Date.now();
    if (isTransitioning || (now - lastSlideTime) < slideThrottle) {
      return;
    }
    
    lastSlideTime = now;
    isTransitioning = true;
    currentIndex++;
    currentTranslate += itemWidth;
    
    updateCarousel(true);
    
    setTimeout(() => {
      if (instance.isDestroyed) return;
      
      if (currentIndex >= totalItems + startIndex) {
        currentIndex = startIndex;
        currentTranslate = startIndex * itemWidth;
        updateCarousel(false);
      }
      isTransitioning = false;
    }, 650);
  }
  
  function prevSlide() {
    if (instance.isDestroyed) return;
    
    const now = Date.now();
    if (isTransitioning || (now - lastSlideTime) < slideThrottle) {
      return;
    }
    
    lastSlideTime = now;
    isTransitioning = true;
    currentIndex--;
    currentTranslate -= itemWidth;
    
    updateCarousel(true);
    
    setTimeout(() => {
      if (instance.isDestroyed) return;
      
      if (currentIndex < startIndex) {
        currentIndex = totalItems + startIndex - 1;
        currentTranslate = currentIndex * itemWidth;
        updateCarousel(false);
      }
      isTransitioning = false;
    }, 650);
  }
  
  function startAutoSlide() {
    if (instance.isDestroyed) return;
    
    if (instance.autoSlideInterval) {
      clearInterval(instance.autoSlideInterval);
    }
    instance.autoSlideInterval = setInterval(() => {
      if (!instance.isDestroyed) nextSlide();
    }, 6000);
  }
  
  function stopAutoSlide() {
    if (instance.autoSlideInterval) {
      clearInterval(instance.autoSlideInterval);
      instance.autoSlideInterval = null;
    }
  }
  
  function handleManualSlide(slideFunction) {
    if (instance.isDestroyed) return;
    
    clearTimeout(instance.autoSlideResumeTimeout);
    stopAutoSlide();
    slideFunction();
    instance.autoSlideResumeTimeout = setTimeout(() => {
      if (!instance.isDestroyed) startAutoSlide();
    }, 8000);
  }
  
  const nextClickHandler = (e) => {
    e.preventDefault();
    e.stopImmediatePropagation();
    handleManualSlide(nextSlide);
  };
  
  const prevClickHandler = (e) => {
    e.preventDefault();
    e.stopImmediatePropagation();
    handleManualSlide(prevSlide);
  };
  
  const mouseEnterHandler = () => {
    if (!instance.isDestroyed) stopAutoSlide();
  };
  
  const mouseLeaveHandler = () => {
    if (!instance.isDestroyed) {
      setTimeout(() => {
        if (!instance.isDestroyed) startAutoSlide();
      }, 500);
    }
  };
  
  const resizeHandler = () => {
    clearTimeout(instance.resizeTimeout);
    instance.resizeTimeout = setTimeout(() => {
      if (!instance.isDestroyed) {
        currentTranslate = currentIndex * itemWidth;
        updateCarousel(false);
      }
    }, 250);
  };
  
  instance.addEventListener(nextBtn, 'click', nextClickHandler);
  instance.addEventListener(prevBtn, 'click', prevClickHandler);
  instance.addEventListener(carousel, 'mouseenter', mouseEnterHandler);
  instance.addEventListener(carousel, 'mouseleave', mouseLeaveHandler);
  instance.addEventListener(window, 'resize', resizeHandler);
  
  instance.safetyCheck = setInterval(() => {
    if (instance.isDestroyed) {
      clearInterval(instance.safetyCheck);
      return;
    }
    
    if (carousel.closest('body') && 
        !instance.autoSlideInterval && 
        !isTransitioning) {
      startAutoSlide();
    }
  }, 10000);
  
  updateCarousel(false);
  startAutoSlide();
  
  carouselInstances.push(instance);
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeCarousels);
} else {
  initializeCarousels();
}

window.reinitializeCarousels = initializeCarousels;
window.carouselInstances = carouselInstances;

document.addEventListener('visibilitychange', () => {
  if (!document.hidden) {
    setTimeout(() => {
      const carousels = document.querySelectorAll('.custom-carousel');
      if (carousels.length > 0 && carouselInstances.length === 0) {
        initializeCarousels();
      }
    }, 200);
  }
});

window.addEventListener('popstate', () => {
  setTimeout(() => {
    const carousels = document.querySelectorAll('.custom-carousel');
    if (carousels.length > 0) {
      initializeCarousels();
    }
  }, 100);
});

// AJAX Navigation Handler - untuk logo BUKUKU
document.addEventListener('click', (e) => {
  const ajaxLink = e.target.closest('[data-ajax-link]');
  if (ajaxLink) {
    const href = ajaxLink.getAttribute('href');
    if (href === '/' || href === '') {
      setTimeout(() => {
        const carousels = document.querySelectorAll('.custom-carousel');
        if (carousels.length > 0) {
          initializeCarousels();
        }
      }, 300);
    }
  }
});

// Custom event untuk AJAX navigation (jika ada)
document.addEventListener('contentLoaded', () => {
  setTimeout(() => {
    const carousels = document.querySelectorAll('.custom-carousel');
    if (carousels.length > 0) {
      initializeCarousels();
    }
  }, 100);
});

// Additional listener untuk hash change
window.addEventListener('hashchange', () => {
  if (window.location.pathname === '/' || window.location.pathname === '') {
    setTimeout(() => {
      const carousels = document.querySelectorAll('.custom-carousel');
      if (carousels.length > 0) {
        initializeCarousels();
      }
    }, 200);
  }
});

if ('MutationObserver' in window) {
  let mutationTimeout;
  
  const observer = new MutationObserver((mutations) => {
    const relevantMutations = mutations.filter(mutation => {
      if (mutation.type === 'attributes' && 
          mutation.attributeName === 'class' && 
          (mutation.target.classList?.contains('btn-favorites') ||
           mutation.target.classList?.contains('btn-add-to-cart'))) {
        return false;
      }
      
      if (mutation.target.closest && 
          (mutation.target.closest('.book-card-modern') ||
           mutation.target.closest('.carousel-item-custom'))) {
        return false;
      }
      
      return mutation.type === 'childList' && 
             mutation.addedNodes.length > 0 &&
             Array.from(mutation.addedNodes).some(node => 
               node.nodeType === 1 && 
               (node.classList?.contains('custom-carousel') || 
                node.querySelector?.('.custom-carousel'))
             );
    });
    
    if (relevantMutations.length === 0) return;
    
    clearTimeout(mutationTimeout);
    mutationTimeout = setTimeout(() => {
      const carousels = document.querySelectorAll('.custom-carousel');
      if (carousels.length > 0 && carouselInstances.length === 0) {
        initializeCarousels();
      }
    }, 500);
  });
  
  observer.observe(document.body, {
    childList: true,
    subtree: true,
    attributes: false
  });
}