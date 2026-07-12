<?php /** Page de connexion. Variable : $error */ ?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="robots" content="noindex, nofollow" />
<title>Connexion · Admin Initial Db</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= url('assets/css/admin.css') ?>" />
</head>
<body class="adm adm-login-page">
<form class="adm-login" method="post" action="<?= admin_url('login') ?>">
  <div class="adm-login-brand">INITIAL <span>Db</span></div>
  <h1>Espace d'administration</h1>
  <?php if ($error): ?><p class="adm-error"><?= e($error) ?></p><?php endif; ?>
  <?= csrf_field() ?>
  <label for="password">Mot de passe</label>
  <input type="password" id="password" name="password" autofocus autocomplete="current-password" required />
  <button type="submit" class="adm-btn">Se connecter</button>
</form>
</body>
</html>
