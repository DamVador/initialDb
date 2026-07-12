<?php
/** Mise en page commune du site public.
 *  Variables : $settings, $meta, $breadcrumb, $content */
$siteName = $settings['site_name'] ?? 'Initial Db';
$title = $meta['title'] ?? $siteName;
$description = $meta['description'] ?? ($settings['tagline'] ?? '');
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$canonical = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/');
$origin = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
$ogImage = $origin . url('assets/img/og-image.png'); // aperçu au partage (lien social)
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?= e($title) ?></title>
<?php if ($description): ?><meta name="description" content="<?= e($description) ?>" /><?php endif; ?>
<link rel="canonical" href="<?= e($canonical) ?>" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="<?= e($siteName) ?>" />
<meta property="og:url" content="<?= e($canonical) ?>" />
<meta property="og:title" content="<?= e($title) ?>" />
<?php if ($description): ?><meta property="og:description" content="<?= e($description) ?>" /><?php endif; ?>
<meta property="og:image" content="<?= e($ogImage) ?>" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta property="og:image:alt" content="Initial Db — studio digital de création de sites vitrines" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="<?= e($title) ?>" />
<?php if ($description): ?><meta name="twitter:description" content="<?= e($description) ?>" /><?php endif; ?>
<meta name="twitter:image" content="<?= e($ogImage) ?>" />
<link rel="icon" href="<?= url('assets/img/favicon/favicon.ico') ?>" sizes="any" />
<link rel="icon" type="image/png" sizes="32x32" href="<?= url('assets/img/favicon/favicon-32x32.png') ?>" />
<link rel="icon" type="image/png" sizes="16x16" href="<?= url('assets/img/favicon/favicon-16x16.png') ?>" />
<link rel="apple-touch-icon" sizes="180x180" href="<?= url('assets/img/favicon/apple-touch-icon.png') ?>" />
<link rel="manifest" href="<?= url('assets/img/favicon/site.webmanifest') ?>" />
<meta name="theme-color" content="#0C0C0E" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= url('assets/css/tokens.css') ?>" />
<link rel="stylesheet" href="<?= url('assets/css/style.css') ?>" />
<script src="<?= url('assets/js/main.js') ?>" defer></script>
</head>
<body>

<?php include TEMPLATES_PATH . '/partials/navbar.php'; ?>

<?php if (!empty($breadcrumb)) {
    include TEMPLATES_PATH . '/partials/breadcrumb.php';
} ?>

<?= $content ?>

<?php include TEMPLATES_PATH . '/partials/footer.php'; ?>

</body>
</html>
