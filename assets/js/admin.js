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

  /* ---- Contournement WAF : encode le contenu du formulaire à l'envoi ----
     Certains pare-feu (ModSecurity/Atomicorp) bloquent en 403 les POST qui
     contiennent des URLs externes (Calendly, TikTok…) ou du HTML. On envoie
     le formulaire encodé en Base64 dans un seul champ opaque « _payload » ;
     le serveur le décode (voir src/admin.php). Repli : sans JS, l'envoi reste
     normal (et n'est bloqué que si le contenu déclenche une règle). */
  document.querySelectorAll('form[data-encode]').forEach(function (form) {
    form.addEventListener('submit', function () {
      if (form.dataset.encoded === '1') return; // déjà encodé, laisse partir
      // Sérialise tous les champs AVANT de les neutraliser
      var params = new URLSearchParams();
      new FormData(form).forEach(function (value, key) { params.append(key, value); });
      // Base64 compatible UTF-8 (accents)
      var payload = btoa(unescape(encodeURIComponent(params.toString())));
      // On ne veut envoyer QUE le champ opaque : on désactive les champs d'origine
      Array.prototype.forEach.call(form.elements, function (el) { el.disabled = true; });
      var hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = '_payload';
      hidden.value = payload;
      form.appendChild(hidden);
      form.dataset.encoded = '1';
      form.submit();
    });
  });
})();
