<?php
/** Liste d'une collection. Variables : $index, $items, $route, $kind */
?>
<section class="page liste">
  <div class="wrap">
    <header class="page-hero">
      <?php if (!empty($index['eyebrow'])): ?><span class="eyebrow"><?= e($index['eyebrow']) ?></span><?php endif; ?>
      <h1 class="page-titre"><?= e($index['title'] ?? '') ?></h1>
      <?php if (!empty($index['intro'])): ?><p class="chapo"><?= e($index['intro']) ?></p><?php endif; ?>
    </header>

    <?php if (empty($items)): ?>
      <p class="vide">Rien à afficher pour le moment.</p>
    <?php elseif ($kind === 'article'): ?>
      <div class="grille-articles">
        <?php foreach ($items as $a): ?>
        <a href="<?= url('articles/' . $a['slug']) ?>" class="carte-article">
          <?php if (!empty($a['cover'])): ?><div class="carte-article-img" style="background-image:url('<?= e($a['cover']) ?>')"></div><?php endif; ?>
          <div class="carte-article-corps">
            <?php if (!empty($a['date'])): ?><span class="carte-date"><?= e(date_fr($a['date'])) ?></span><?php endif; ?>
            <h2><?= e($a['title']) ?></h2>
            <?php if (!empty($a['excerpt'])): ?><p><?= e($a['excerpt']) ?></p><?php endif; ?>
            <span class="lire">Lire l'article →</span>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    <?php elseif ($kind === 'projet'): ?>
      <div class="grille-projets">
        <?php foreach ($items as $p): ?>
        <a href="<?= url('projets/' . $p['slug']) ?>" class="carte">
          <div class="carte-visuel"<?php if (!empty($p['cover'])): ?> style="background-image:url('<?= e($p['cover']) ?>');background-size:cover"<?php endif; ?>>
            <?php if (!empty($p['offre'])): ?><span class="carte-offre"><?= e($p['offre']) ?></span><?php endif; ?>
          </div>
          <div class="carte-info">
            <h2><?= e($p['title']) ?></h2>
            <?php if (!empty($p['secteur'])): ?><p class="carte-secteur"><?= e($p['secteur']) ?></p><?php endif; ?>
            <?php if (!empty($p['resultat'])): ?><p class="carte-resultat"><span>▸</span> <?= e($p['resultat']) ?></p><?php endif; ?>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    <?php else: /* offres / secteurs */ ?>
      <div class="grille-cartes">
        <?php foreach ($items as $it): ?>
        <a href="<?= url($route . '/' . $it['slug']) ?>" class="carte-simple">
          <h2><?= e($it['title']) ?></h2>
          <?php if (!empty($it['price'])): ?><span class="carte-prix"><?= e($it['price']) ?></span><?php endif; ?>
          <?php if (!empty($it['tagline'])): ?><p><?= e($it['tagline']) ?></p>
          <?php elseif (!empty($it['description'])): ?><p><?= e($it['description']) ?></p>
          <?php elseif (!empty($it['intro'])): ?><p><?= e($it['intro']) ?></p><?php endif; ?>
          <span class="lire">En savoir plus →</span>
        </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
