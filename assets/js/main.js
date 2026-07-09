// ============================================================
// Sticky Topbar
// ============================================================
(function () {
  const topbar = document.getElementById('sticky-topbar');
  if (!topbar) return;

  let ticking = false;
  const THRESHOLD = 600;

  function updateTopbar() {
    if (window.scrollY > THRESHOLD) {
      topbar.classList.remove('-translate-y-full');
    } else {
      topbar.classList.add('-translate-y-full');
    }
    ticking = false;
  }

  window.addEventListener('scroll', function () {
    if (!ticking) {
      requestAnimationFrame(updateTopbar);
      ticking = true;
    }
  }, { passive: true });
})();

// ============================================================
// Mobile Drawer
// ============================================================
(function () {
  const btn      = document.getElementById('hamburger-btn');
  const drawer   = document.getElementById('mobile-drawer');
  const overlay  = document.getElementById('drawer-overlay');
  const closeBtn = document.getElementById('drawer-close');
  const navLinks = document.querySelectorAll('.mobile-nav-link');
  if (!btn || !drawer || !overlay) return;

  function openDrawer() {
    drawer.classList.remove('translate-x-full');
    overlay.classList.remove('hidden');
    requestAnimationFrame(function () {
      overlay.classList.remove('opacity-0');
      overlay.classList.add('opacity-100');
    });
    drawer.setAttribute('aria-hidden', 'false');
    btn.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }

  function closeDrawer() {
    drawer.classList.add('translate-x-full');
    overlay.classList.remove('opacity-100');
    overlay.classList.add('opacity-0');
    setTimeout(function () {
      overlay.classList.add('hidden');
    }, 300);
    drawer.setAttribute('aria-hidden', 'true');
    btn.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  btn.addEventListener('click', openDrawer);
  if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
  overlay.addEventListener('click', closeDrawer);

  navLinks.forEach(function (link) {
    link.addEventListener('click', closeDrawer);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeDrawer();
  });
})();

// ============================================================
// FAQ Accordion
// ============================================================
(function () {
  const items = document.querySelectorAll('.faq-item');
  items.forEach(function (item) {
    const btn    = item.querySelector('.faq-question');
    const answer = item.querySelector('.faq-answer');
    const chevron = item.querySelector('.faq-chevron');
    if (!btn || !answer) return;

    btn.addEventListener('click', function () {
      const isOpen = !answer.classList.contains('hidden');

      // Close all
      items.forEach(function (other) {
        const otherAnswer  = other.querySelector('.faq-answer');
        const otherChevron = other.querySelector('.faq-chevron');
        const otherBtn     = other.querySelector('.faq-question');
        if (otherAnswer) otherAnswer.classList.add('hidden');
        if (otherChevron) otherChevron.style.transform = '';
        if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');
      });

      // Toggle clicked
      if (!isOpen) {
        answer.classList.remove('hidden');
        if (chevron) chevron.style.transform = 'rotate(180deg)';
        btn.setAttribute('aria-expanded', 'true');
      }
    });
  });
})();

// ============================================================
// Smooth Scroll (for older browsers without native support)
// ============================================================
(function () {
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });
})();

// ============================================================
// Intersection Observer — fade-in-up animations
// ============================================================
(function () {
  if (!('IntersectionObserver' in window)) {
    // Fallback: show all immediately
    document.querySelectorAll('.fade-in-up').forEach(function (el) {
      el.classList.add('visible');
    });
    return;
  }

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

  document.querySelectorAll('.fade-in-up').forEach(function (el) {
    observer.observe(el);
  });
})();
