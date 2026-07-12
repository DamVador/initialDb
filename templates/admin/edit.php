<?php
/** Formulaire d'édition générique (page, collection ou paramètres).
 *  Variables : $schema, $values, $action, $heading, $backUrl? */
?>
<header class="adm-head">
  <?php if (!empty($backUrl)): ?><a class="adm-retour" href="<?= e($backUrl) ?>">← Retour</a><?php endif; ?>
  <h1><?= e($heading) ?></h1>
</header>

<form class="adm-form" method="post" action="<?= e($action) ?>" data-encode>
  <?= csrf_field() ?>
  <?= form_fields($schema['fields'], $values, 'f') ?>
  <div class="adm-form-actions">
    <button type="submit" class="adm-btn">Enregistrer</button>
    <?php if (!empty($backUrl)): ?><a class="adm-btn-ghost" href="<?= e($backUrl) ?>">Annuler</a><?php endif; ?>
  </div>
</form>
