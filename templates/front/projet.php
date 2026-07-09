<?php /** Détail d'une réalisation. Variable : $projet */ ?>
<article class="page">
  <div class="wrap wrap-lecture">
    <header class="page-hero">
      <span class="eyebrow"><?= e($projet['offre'] ?? 'Réalisation') ?></span>
      <h1 class="page-titre"><?= e($projet['title']) ?></h1>
      <?php if (!empty($projet['secteur'])): ?><p class="page-meta"><?= e($projet['secteur']) ?></p><?php endif; ?>
      <?php if (!empty($projet['resultat'])): ?><p class="page-resultat"><span>▸</span> <?= e($projet['resultat']) ?></p><?php endif; ?>
    </header>
    <?php if (!empty($projet['cover'])): ?>
      <img class="page-cover" src="<?= e($projet['cover']) ?>" alt="<?= e($projet['title']) ?>" />
    <?php endif; ?>
    <div class="prose">
      <?= $projet['body'] ?? '' ?>
    </div>
    <p class="retour"><a href="<?= url('projets') ?>" class="cta-ligne">← Toutes les réalisations</a></p>
  </div>
</article>
