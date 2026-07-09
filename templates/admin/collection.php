<?php
/** Liste des éléments d'une collection. Variables : $schema, $name, $items */
$titleField = $schema['title_field'] ?? 'title';
$route = $schema['route'] ?? $name;
$total = count($items);
?>
<header class="adm-head adm-head-row">
  <div>
    <h1><?= e($schema['label']) ?></h1>
    <p class="adm-sub"><?= $total ?> élément<?= $total > 1 ? 's' : '' ?></p>
  </div>
  <a class="adm-btn" href="<?= url('admin/collection/' . $name . '/new') ?>">+ Ajouter</a>
</header>

<?php if (!$items): ?>
  <p class="adm-vide">Aucun élément pour l'instant. Cliquez sur « Ajouter » pour commencer.</p>
<?php else: ?>
<table class="adm-table">
  <thead>
    <tr><th>Ordre</th><th>Titre</th><th>État</th><th class="adm-ta-r">Actions</th></tr>
  </thead>
  <tbody>
    <?php foreach ($items as $i => $item):
      $id = $item['id'] ?? ''; ?>
    <tr>
      <td class="adm-order">
        <form method="post" action="<?= url('admin/collection/' . $name . '/move/' . $id . '/up') ?>"><?= csrf_field() ?><button class="adm-icon"<?= $i === 0 ? ' disabled' : '' ?> title="Monter">↑</button></form>
        <form method="post" action="<?= url('admin/collection/' . $name . '/move/' . $id . '/down') ?>"><?= csrf_field() ?><button class="adm-icon"<?= $i === $total - 1 ? ' disabled' : '' ?> title="Descendre">↓</button></form>
      </td>
      <td>
        <strong><?= e($item[$titleField] ?? '(sans titre)') ?></strong>
        <?php if (!empty($item['slug'])): ?><span class="adm-slug">/<?= e($route . '/' . $item['slug']) ?></span><?php endif; ?>
      </td>
      <td>
        <?php if (!empty($item['published'])): ?>
          <span class="adm-badge adm-badge-on">Publié</span>
        <?php else: ?>
          <span class="adm-badge">Brouillon</span>
        <?php endif; ?>
      </td>
      <td class="adm-ta-r adm-actions">
        <?php if (!empty($item['published']) && !empty($item['slug'])): ?>
          <a class="adm-lien" href="<?= url($route . '/' . $item['slug']) ?>" target="_blank" rel="noopener">Voir</a>
        <?php endif; ?>
        <a class="adm-lien" href="<?= url('admin/collection/' . $name . '/edit/' . $id) ?>">Modifier</a>
        <form method="post" action="<?= url('admin/collection/' . $name . '/delete/' . $id) ?>" onsubmit="return confirm('Supprimer définitivement cet élément ?');">
          <?= csrf_field() ?><button class="adm-lien adm-lien-danger">Supprimer</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
