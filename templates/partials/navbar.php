<?php /** Barre de navigation (variable : $settings) */ ?>
<nav class="navbar">
  <div class="wrap navbar-inner">
    <a href="<?= url('') ?>" class="brand-logo" aria-label="<?= e($settings['site_name'] ?? 'Initial Db') ?> — accueil"></a>
    <div class="navbar-links" id="menu-mobile">
      <a href="<?= url('offres') ?>">Offres</a>
      <a href="<?= url('projets') ?>">Réalisations</a>
      <a href="<?= url('articles') ?>">Blog</a>
      <a href="<?= url('') ?>#contact" class="navbar-cta">Démarrer</a>
    </div>
    <button class="burger" aria-label="Ouvrir le menu" aria-controls="menu-mobile" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>
