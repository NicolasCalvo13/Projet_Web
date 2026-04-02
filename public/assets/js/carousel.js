(function () {
  'use strict';

  function createCarousel(grid) {
    if (!grid) return;

    const originalCards = Array.from(grid.querySelectorAll('.offer-card'));
    if (originalCards.length <= 1) return;

    grid.setAttribute('data-carousel-ready', 'true');

    const viewport = document.createElement('div');
    viewport.className = 'offers-carousel-viewport';

    const track = document.createElement('div');
    track.className = 'offers-carousel-track';

    grid.innerHTML = '';

    let visibleCards = 1;
    let currentIndex = 0;
    let cards = [];
    let isTransitioning = false;

    const prevBtn = document.createElement('button');
    prevBtn.type = 'button';
    prevBtn.className = 'offers-carousel-btn offers-carousel-btn-prev';
    prevBtn.setAttribute('aria-label', 'Voir les offres précédentes');
    prevBtn.innerHTML = '←';

    const nextBtn = document.createElement('button');
    nextBtn.type = 'button';
    nextBtn.className = 'offers-carousel-btn offers-carousel-btn-next';
    nextBtn.setAttribute('aria-label', 'Voir les offres suivantes');
    nextBtn.innerHTML = '→';

    function updateVisibleCards() {
      const width = window.innerWidth;
      if (width >= 1100) visibleCards = 3;
      else if (width >= 700) visibleCards = 2;
      else visibleCards = 1;
    }

    function getGap() {
      return parseFloat(window.getComputedStyle(track).gap) || 0;
    }

    function getCardWidth() {
      const firstCard = track.querySelector('.offer-card');
      return firstCard ? firstCard.getBoundingClientRect().width : 0;
    }

    function createClones() {
      updateVisibleCards();

      track.innerHTML = '';

      const clonesBefore = originalCards
        .slice(-visibleCards)
        .map((card) => {
          const clone = card.cloneNode(true);
          clone.setAttribute('data-clone', 'true');
          return clone;
        });

      const clonesAfter = originalCards
        .slice(0, visibleCards)
        .map((card) => {
          const clone = card.cloneNode(true);
          clone.setAttribute('data-clone', 'true');
          return clone;
        });

      const allCards = [...clonesBefore, ...originalCards, ...clonesAfter];
      allCards.forEach((card) => track.appendChild(card));

      cards = Array.from(track.querySelectorAll('.offer-card'));
      currentIndex = visibleCards;
      jumpTo(currentIndex);
    }

    function moveTo(index, withTransition = true) {
      const offset = index * (getCardWidth() + getGap());
      track.style.transition = withTransition ? 'transform 0.35s ease' : 'none';
      track.style.transform = `translateX(-${offset}px)`;
    }

    function jumpTo(index) {
      moveTo(index, false);
    }

    function next() {
      if (isTransitioning) return;
      isTransitioning = true;
      currentIndex += 1;
      moveTo(currentIndex, true);
    }

    function prev() {
      if (isTransitioning) return;
      isTransitioning = true;
      currentIndex -= 1;
      moveTo(currentIndex, true);
    }

    track.addEventListener('transitionend', function () {
      const totalOriginal = originalCards.length;

      if (currentIndex >= totalOriginal + visibleCards) {
        currentIndex = visibleCards;
        jumpTo(currentIndex);
      }

      if (currentIndex < visibleCards) {
        currentIndex = totalOriginal + visibleCards - 1;
        jumpTo(currentIndex);
      }

      isTransitioning = false;
    });

    nextBtn.addEventListener('click', next);
    prevBtn.addEventListener('click', prev);

    let startX = 0;
    let currentX = 0;
    let isDragging = false;

    viewport.addEventListener('pointerdown', function (event) {
      isDragging = true;
      startX = event.clientX;
      currentX = startX;
      viewport.setPointerCapture(event.pointerId);
    });

    viewport.addEventListener('pointermove', function (event) {
      if (!isDragging) return;
      currentX = event.clientX;
    });

    function endDrag() {
      if (!isDragging) return;
      const delta = currentX - startX;
      isDragging = false;

      if (delta < -50) next();
      else if (delta > 50) prev();
    }

    viewport.addEventListener('pointerup', endDrag);
    viewport.addEventListener('pointercancel', endDrag);
    viewport.addEventListener('lostpointercapture', endDrag);

    window.addEventListener('resize', function () {
      createClones();
    });

    viewport.appendChild(track);
    grid.appendChild(viewport);
    grid.appendChild(prevBtn);
    grid.appendChild(nextBtn);

    createClones();
  }

  document.addEventListener('DOMContentLoaded', function () {
    const offersGrid = document.querySelector('.offers-grid');
    createCarousel(offersGrid);
  });
})();