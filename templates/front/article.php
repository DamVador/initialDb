<?php /** Détail d'un article. Variable : $article */ ?>
<article class="page">
  <div class="wrap wrap-lecture">
    <header class="page-hero">
      <span class="eyebrow">Blog</span>
      <h1 class="page-titre"><?= e($article['title']) ?></h1>
      <?php if (!empty($article['date'])): ?><p class="page-meta"><?= e(date_fr($article['date'])) ?></p><?php endif; ?>
    </header>
    <?php if (!empty($article['cover'])): ?>
      <img class="page-cover" src="<?= e($article['cover']) ?>" alt="<?= e($article['title']) ?>" />
    <?php endif; ?>
    <?php if (!empty($article['excerpt'])): ?><p class="chapo"><?= e($article['excerpt']) ?></p><?php endif; ?>
    <div class="prose">
      <?= $article['body'] ?? '' ?>
    </div>
    <p class="retour"><a href="<?= url('articles') ?>" class="cta-ligne">← Tous les articles</a></p>
  </div>
</article>
