<?php /** Changement de mot de passe. Variable : $error */ ?>
<header class="adm-head">
  <h1>Changer le mot de passe</h1>
  <p class="adm-sub">Saisissez votre mot de passe actuel pour en définir un nouveau.</p>
</header>

<?php if ($error): ?><div class="adm-flash adm-flash-error"><?= e($error) ?></div><?php endif; ?>

<form class="adm-form adm-form-etroit" method="post" action="<?= url('admin/password') ?>">
  <?= csrf_field() ?>
  <div class="field">
    <label for="old">Mot de passe actuel</label>
    <input type="password" id="old" name="old" autocomplete="current-password" required />
  </div>
  <div class="field">
    <label for="new">Nouveau mot de passe</label>
    <input type="password" id="new" name="new" autocomplete="new-password" required minlength="6" />
    <span class="help">Au moins 6 caractères.</span>
  </div>
  <div class="field">
    <label for="confirm">Confirmer le nouveau mot de passe</label>
    <input type="password" id="confirm" name="confirm" autocomplete="new-password" required />
  </div>
  <div class="adm-form-actions">
    <button type="submit" class="adm-btn">Mettre à jour</button>
  </div>
</form>
