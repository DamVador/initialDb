/* ============================================================
   main.js — petites interactions du site
   Pour l'instant : ouverture / fermeture du menu mobile (burger).
   ============================================================ */

(function () {
  'use strict';

  var burger = document.querySelector('.burger');
  var menu = document.querySelector('.navbar-links');

  // Si l'un des deux manque, on ne fait rien (page sans navbar par ex.).
  if (!burger || !menu) return;

  function ouvrirFermer(ouvrir) {
    // Si "ouvrir" n'est pas précisé, on inverse l'état actuel.
    var estOuvert = ouvrir === undefined
      ? !menu.classList.contains('open')
      : ouvrir;

    menu.classList.toggle('open', estOuvert);
    burger.setAttribute('aria-expanded', estOuvert ? 'true' : 'false');
  }

  // Clic sur le burger → bascule le menu.
  burger.addEventListener('click', function () {
    ouvrirFermer();
  });

  // Clic sur un lien du menu → on referme (utile pour les ancres #).
  menu.addEventListener('click', function (e) {
    if (e.target.closest('a')) ouvrirFermer(false);
  });

  // Touche Échap → on referme.
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') ouvrirFermer(false);
  });

  // Retour en grand écran → on referme pour repartir d'un état propre.
  window.addEventListener('resize', function () {
    if (window.innerWidth > 800) ouvrirFermer(false);
  });

  /* ---- Révélation douce au défilement ---- */
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (!reduce && 'IntersectionObserver' in window) {
    document.documentElement.classList.add('js-reveal');

    var selectors = [
      '.benef', '.atout', '.carte', '.carte-article', '.carte-simple',
      '.timeline li', '.grille-tarif', '.signature-tete', '.page-hero'
    ];
    var els = document.querySelectorAll(selectors.join(','));

    // Petit décalage en cascade entre éléments voisins d'un même bloc.
    var counters = new Map();
    els.forEach(function (el) {
      el.classList.add('reveal');
      var parent = el.parentElement;
      var n = counters.get(parent) || 0;
      counters.set(parent, n + 1);
      el.style.transitionDelay = Math.min(n * 0.07, 0.35) + 's';
    });

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('in');
          io.unobserve(entry.target);
        }
      });
    }, { rootMargin: '0px 0px -60px 0px', threshold: 0.08 });

    els.forEach(function (el) { io.observe(el); });
  }
})();
