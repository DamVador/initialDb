/* ============================================================
   methode.js — section « Méthode » pilotée au défilement.

   Le scroll fait défiler les frames d'une vidéo (séquence WebP)
   dessinées dans un <canvas>, et fait apparaître / disparaître
   les étapes. Repli propre si mobile / sans mouvement.
   ============================================================ */

(function () {
  'use strict';

  var section = document.querySelector('[data-methode]');
  if (!section) return;

  var scrollEl = section.querySelector('.methode-scroll');
  var stickyEl = section.querySelector('.methode-sticky');
  var canvas   = section.querySelector('.methode-canvas');
  var titre    = section.querySelector('[data-methode-titre]');
  var etapes   = Array.prototype.slice.call(section.querySelectorAll('[data-methode-etape]'));
  var ctx      = canvas.getContext('2d');

  var FRAMES = parseInt(section.dataset.count, 10) || 192;
  var BASE   = section.dataset.frames;

  var images = [];
  var lastFrame = -1;
  var active = false;
  var preloadStarted = false;
  var ticking = false;

  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)');

  function enabled() {
    return !reduce.matches && window.innerWidth > 900;
  }

  /* ---- Utilitaires ---- */
  function clamp(v, a, b) { return v < a ? a : (v > b ? b : v); }

  // Opacité « trapèze » sur une fenêtre [a,b] : apparaît, tient, disparaît.
  function soloOpacity(p, a, b) {
    if (p <= a || p >= b) return 0;
    var w = b - a, fade = w * 0.28;
    if (p < a + fade) return (p - a) / fade;
    if (p > b - fade) return (b - p) / fade;
    return 1;
  }

  // Réapparition finale (monte à 1 et y reste).
  function finalOpacity(p, start) {
    return clamp((p - start) / 0.08, 0, 1);
  }

  function frameUrl(i) {
    var n = ('0000' + (i + 1)).slice(-4);
    return BASE + '/frame-' + n + '.webp';
  }

  /* ---- Préchargement des frames ---- */
  function preload() {
    if (preloadStarted) return;
    preloadStarted = true;
    for (var i = 0; i < FRAMES; i++) {
      (function (i) {
        var img = new Image();
        img.decoding = 'async';
        img.onload = function () { if (i === lastFrame || lastFrame < 0) draw(Math.max(lastFrame, 0)); };
        img.src = frameUrl(i);
        images[i] = img;
      })(i);
    }
  }

  /* ---- Canvas ---- */
  function sizeCanvas() {
    var dpr = Math.min(window.devicePixelRatio || 1, 2);
    canvas.width  = stickyEl.clientWidth * dpr;
    canvas.height = stickyEl.clientHeight * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    draw(Math.max(lastFrame, 0));
  }

  function draw(idx) {
    var img = images[idx];
    var cw = stickyEl.clientWidth, ch = stickyEl.clientHeight;
    if (!img || !img.complete || !img.naturalWidth) { lastFrame = idx; return; }
    var iw = img.naturalWidth, ih = img.naturalHeight;
    var scale = Math.max(cw / iw, ch / ih);
    var w = iw * scale, h = ih * scale;
    ctx.clearRect(0, 0, cw, ch);
    ctx.drawImage(img, (cw - w) / 2, (ch - h) / 2, w, h);
    lastFrame = idx;
  }

  /* ---- Progression du scroll (0 → 1) ---- */
  function progress() {
    var total = scrollEl.offsetHeight - window.innerHeight;
    if (total <= 0) return 0;
    return clamp(-scrollEl.getBoundingClientRect().top / total, 0, 1);
  }

  /* ---- Chorégraphie des étapes ---- */
  function layout(p) {
    // Titre + sous-titre : toujours affichés pendant toute la section.
    if (titre) titre.style.opacity = 1;

    var n = etapes.length || 1;
    var startPhase = 0.08, endPhase = 0.72;
    var span = (endPhase - startPhase) / n;

    etapes.forEach(function (el, i) {
      var a = startPhase + i * span;
      var solo = soloOpacity(p, a, a + span);
      var fin = finalOpacity(p, 0.78 + i * 0.04);
      var op = Math.max(solo, fin);
      el.style.opacity = op;
      el.style.transform = 'translateY(' + ((1 - op) * 26) + 'px)';
    });
  }

  function render() {
    ticking = false;
    var p = progress();
    draw(Math.round(p * (FRAMES - 1)));
    layout(p);
  }

  function onScroll() {
    if (!ticking) { ticking = true; requestAnimationFrame(render); }
  }

  /* ---- Activation / désactivation ---- */
  function activate() {
    if (active) return;
    active = true;
    section.classList.add('is-scrolly');
    sizeCanvas();
    preload();
    window.addEventListener('scroll', onScroll, { passive: true });
    render();
  }

  function deactivate() {
    if (!active) return;
    active = false;
    section.classList.remove('is-scrolly');
    window.removeEventListener('scroll', onScroll);
    // Nettoie les styles inline pour laisser le repli CSS s'appliquer.
    if (titre) titre.style.opacity = '';
    etapes.forEach(function (el) { el.style.opacity = ''; el.style.transform = ''; });
  }

  function update() {
    if (enabled()) { activate(); } else { deactivate(); }
  }

  /* ---- Démarrage : précharge quand la section approche ---- */
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      if (entries[0].isIntersecting && enabled()) preload();
    }, { rootMargin: '600px 0px' });
    io.observe(section);
  }

  window.addEventListener('resize', function () {
    if (active) sizeCanvas();
    update();
  });
  reduce.addEventListener && reduce.addEventListener('change', update);

  update();
})();
