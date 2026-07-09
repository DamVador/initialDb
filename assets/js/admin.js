/* ============================================================
   admin.js — interactions du panneau d'administration
   - menu latéral (mobile)
   - listes répétables : ajout, suppression, réordonnancement
   ============================================================ */

(function () {
  'use strict';

  /* ---- Menu latéral sur mobile ---- */
  var burger = document.querySelector('.adm-burger');
  var side = document.getElementById('adm-nav');
  if (burger && side) {
    burger.addEventListener('click', function () {
      var open = side.classList.toggle('open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  /* ---- Listes répétables ---- */
  document.addEventListener('click', function (e) {
    // Ajouter une ligne
    var add = e.target.closest('.rep-add');
    if (add) {
      e.preventDefault();
      var fieldset = add.closest('[data-repeater]');
      var template = fieldset.querySelector('.rep-template');
      var rows = fieldset.querySelector('.rep-rows');
      var uid = 'n' + Date.now() + Math.floor(Math.random() * 1000);
      var html = template.innerHTML.split('__i__').join(uid);
      var holder = document.createElement('div');
      holder.innerHTML = html.trim();
      rows.appendChild(holder.firstElementChild);
      return;
    }

    // Supprimer une ligne
    var del = e.target.closest('.rep-del');
    if (del) {
      e.preventDefault();
      var row = del.closest('.rep-row');
      if (row) row.remove();
      return;
    }

    // Monter une ligne
    var up = e.target.closest('.rep-up');
    if (up) {
      e.preventDefault();
      var r = up.closest('.rep-row');
      var prev = r && r.previousElementSibling;
      if (prev) r.parentNode.insertBefore(r, prev);
      return;
    }

    // Descendre une ligne
    var down = e.target.closest('.rep-down');
    if (down) {
      e.preventDefault();
      var rr = down.closest('.rep-row');
      var next = rr && rr.nextElementSibling;
      if (next) rr.parentNode.insertBefore(next, rr);
      return;
    }
  });
})();
