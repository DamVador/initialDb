<?php
/* ============================================================
   bootstrap.php — initialisation commune (front + admin)
   ============================================================ */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_PATH', dirname(__DIR__));
define('DATA_PATH', BASE_PATH . '/data');
define('TEMPLATES_PATH', BASE_PATH . '/templates');

// Le site est servi à la racine du domaine. Si un jour il est déployé
// dans un sous-dossier, ajuster BASE_URL (ex. '/mon-dossier').
define('BASE_URL', '');

require __DIR__ . '/helpers.php';
require __DIR__ . '/Content.php';
require __DIR__ . '/Auth.php';
require __DIR__ . '/Schema.php';
require __DIR__ . '/Form.php';
require __DIR__ . '/Sitemap.php';
