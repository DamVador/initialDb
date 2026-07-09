<?php
/** Page simple : offre ou secteur. Variables : $item, $route, $settings */
$isOffre = ($route === 'offres');
?>
<article class="page">
  <div class="wrap wrap-lecture">
    <header class="page-hero">
      <span class="eyebrow"><?= $isOffre ? 'Offre' : 'Secteur d\'activité' ?></span>
      <h1 class="page-titre"><?= e($item['title']) ?></h1>
      <?php if ($isOffre && !empty($item['price'])): ?>
        <p class="page-prix"><?= e($item['price']) ?></p>
      <?php endif; ?>
      <?php if (!empty($item['tagline'])): ?><p class="page-meta"><?= e($item['tagline']) ?></p><?php endif; ?>
      <?php if (!empty($item['intro'])): ?><p class="chapo"><?= e($item['intro']) ?></p><?php endif; ?>
      <?php if (!empty($item['description'])): ?><p class="chapo"><?= e($item['description']) ?></p><?php endif; ?>
    </header>

    <?php if (!empty($item['features'])): ?>
    <ul class="liste-inclus">
      <?php foreach ($item['features'] as $f): ?><li><?= e($f) ?></li><?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <?php if (!empty($item['body'])): ?>
    <div class="prose"><?= $item['body'] ?></div>
    <?php endif; ?>

    <div class="page-cta">
      <a href="<?= url('') ?>#contact" class="cta"><?= e($item['cta_label'] ?? 'Démarrer un projet') ?></a>
      <a href="<?= url($route) ?>" class="cta-ligne"><?= $isOffre ? '← Toutes les offres' : '← Tous les secteurs' ?></a>
    </div>
  </div>
</article>
