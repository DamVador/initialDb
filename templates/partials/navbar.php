<?php
/** Barre de navigation (variable : $settings) */
$cur = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '', '/');
$seg = explode('/', $cur)[0] ?? '';
$on = fn(string $s): string => $seg === $s ? ' class="on" aria-current="page"' : '';
?>
<nav class="navbar">
  <div class="wrap navbar-inner">
    <a href="<?= url('') ?>" class="brand-logo" aria-label="<?= e($settings['site_name'] ?? 'Initial Db') ?>, accueil"></a>
    <div class="navbar-links" id="menu-mobile">
      <a href="<?= url('offres') ?>"<?= $on('offres') ?>>Offres</a>
      <a href="<?= url('projets') ?>"<?= $on('projets') ?>>Réalisations</a>
      <a href="<?= url('') ?>#process">Méthode</a>
      <a href="<?= url('articles') ?>"<?= $on('articles') ?>>Blog</a>
      <a href="<?= url('') ?>#contact" class="navbar-cta">Démarrer un projet</a>
    </div>
    <button class="burger" aria-label="Ouvrir le menu" aria-controls="menu-mobile" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>
