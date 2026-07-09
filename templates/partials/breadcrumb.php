<?php /** Fil d'Ariane (variable : $breadcrumb = liste de ['label', 'url'?]) */ ?>
<nav class="fil-ariane" aria-label="Fil d'Ariane">
  <div class="wrap">
    <ol>
      <?php foreach ($breadcrumb as $i => $crumb): ?>
        <li>
          <?php if (!empty($crumb['url']) && $i < count($breadcrumb) - 1): ?>
            <a href="<?= e($crumb['url']) ?>"><?= e($crumb['label']) ?></a>
          <?php else: ?>
            <span aria-current="page"><?= e($crumb['label']) ?></span>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</nav>
