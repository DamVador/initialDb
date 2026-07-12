<?php /** Tableau de bord. Variables : $collections, $singletons */ ?>
<header class="adm-head">
  <h1>Tableau de bord</h1>
  <p class="adm-sub">Gérez le contenu de votre site. Sélectionnez une page ou un contenu à modifier.</p>
</header>

<h2 class="adm-section-titre">Pages du site</h2>
<div class="adm-cards">
  <?php foreach ($singletons as $key => $s): ?>
    <a class="adm-card" href="<?= admin_url('page/' . $key) ?>">
      <span class="adm-card-tag">Page</span>
      <strong><?= e($s['label']) ?></strong>
      <span class="adm-card-go">Modifier →</span>
    </a>
  <?php endforeach; ?>
  <a class="adm-card" href="<?= admin_url('settings') ?>">
    <span class="adm-card-tag">Réglages</span>
    <strong>Paramètres du site</strong>
    <span class="adm-card-go">Modifier →</span>
  </a>
</div>

<h2 class="adm-section-titre">Contenus</h2>
<div class="adm-cards">
  <?php foreach ($collections as $key => $c):
    $count = count(Content::collection($key)); ?>
    <a class="adm-card" href="<?= admin_url('collection/' . $key) ?>">
      <span class="adm-card-tag"><?= (int) $count ?> élément<?= $count > 1 ? 's' : '' ?></span>
      <strong><?= e($c['label']) ?></strong>
      <span class="adm-card-go">Gérer →</span>
    </a>
  <?php endforeach; ?>
</div>
