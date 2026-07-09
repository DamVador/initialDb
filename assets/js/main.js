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
})();
