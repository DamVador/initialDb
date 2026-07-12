<?php
/** Mise en page de l'admin (menu latéral + contenu).
 *  Variables : $content, $pageTitle */
$here = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
$active = fn(string $path): string => (rtrim($here, '/') === rtrim($path, '/')) ? ' class="on"' : '';
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="robots" content="noindex, nofollow" />
<title><?= e($pageTitle) ?> · Admin Initial Db</title>
<link rel="icon" href="<?= url('assets/img/favicon/favicon.ico') ?>" sizes="any" />
<link rel="icon" type="image/png" sizes="32x32" href="<?= url('assets/img/favicon/favicon-32x32.png') ?>" />
<link rel="apple-touch-icon" sizes="180x180" href="<?= url('assets/img/favicon/apple-touch-icon.png') ?>" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= url('assets/css/admin.css') ?>" />
<script src="<?= url('assets/js/admin.js') ?>" defer></script>
</head>
<body class="adm">
<button class="adm-burger" aria-label="Menu" aria-controls="adm-nav" aria-expanded="false"><span></span><span></span><span></span></button>
<aside class="adm-side" id="adm-nav">
  <div class="adm-brand">INITIAL <span>Db</span> · Admin</div>
  <nav class="adm-nav">
    <a href="<?= url('admin') ?>"<?= $active(url('admin')) ?>>Tableau de bord</a>

    <span class="adm-nav-titre">Pages</span>
    <?php foreach (Schema::singletons() as $key => $s): ?>
      <a href="<?= url('admin/page/' . $key) ?>"<?= $active(url('admin/page/' . $key)) ?>><?= e($s['label']) ?></a>
    <?php endforeach; ?>

    <span class="adm-nav-titre">Contenus</span>
    <?php foreach (Schema::collections() as $key => $c): ?>
      <a href="<?= url('admin/collection/' . $key) ?>"<?= $active(url('admin/collection/' . $key)) ?>><?= e($c['label']) ?></a>
    <?php endforeach; ?>

    <span class="adm-nav-titre">Réglages</span>
    <a href="<?= url('admin/settings') ?>"<?= $active(url('admin/settings')) ?>>Paramètres du site</a>
    <a href="<?= url('admin/password') ?>"<?= $active(url('admin/password')) ?>>Mot de passe</a>
  </nav>
  <div class="adm-side-bas">
    <a href="<?= url('') ?>" target="_blank" rel="noopener">↗ Voir le site</a>
    <a href="<?= url('admin/logout') ?>" class="adm-logout">Déconnexion</a>
  </div>
</aside>

<main class="adm-main">
  <?php foreach (flash() as $f): ?>
    <div class="adm-flash adm-flash-<?= e($f['type']) ?>"><?= e($f['text']) ?></div>
  <?php endforeach; ?>
  <?= $content ?>
</main>
</body>
</html>
