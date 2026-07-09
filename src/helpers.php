<?php
/* ============================================================
   helpers.php — petites fonctions utilitaires
   ============================================================ */

/** Échappe une valeur pour l'affichage HTML (protection XSS). */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Construit une URL absolue depuis la racine du site. */
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/** Redirige puis stoppe le script. */
function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

/** Transforme un texte en slug propre pour les URLs. */
function slugify(string $text): string
{
    $text = trim($text);
    if (function_exists('iconv')) {
        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        if ($converted !== false) {
            $text = $converted;
        }
    }
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    return trim($text, '-');
}

/** Rend un template et renvoie le HTML produit. */
function render(string $template, array $vars = []): string
{
    extract($vars, EXTR_SKIP);
    ob_start();
    include TEMPLATES_PATH . '/' . $template . '.php';
    return (string) ob_get_clean();
}

/** Jeton anti-CSRF pour sécuriser les formulaires. */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

/** Champ caché à insérer dans chaque formulaire. */
function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

/** Vérifie le jeton anti-CSRF d'une requête POST. */
function csrf_check(): void
{
    $sent = $_POST['_csrf'] ?? '';
    if (!is_string($sent) || !hash_equals($_SESSION['csrf'] ?? '', $sent)) {
        http_response_code(419);
        exit('Session expirée. Revenez en arrière et rechargez la page.');
    }
}

/** Enregistre (ou récupère et vide) les messages flash. */
function flash(?string $message = null, string $type = 'ok')
{
    if ($message !== null) {
        $_SESSION['flash'][] = ['type' => $type, 'text' => $message];
        return null;
    }
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

/** Extrait un résumé texte depuis du HTML (pour meta description, cartes…). */
function excerpt(string $html, int $length = 160): string
{
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($html)) ?? '');
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length - 1) . '…';
}

/** Formate une date ISO en date française lisible. */
function date_fr(?string $iso): string
{
    if (!$iso) {
        return '';
    }
    $ts = strtotime($iso);
    if ($ts === false) {
        return '';
    }
    $mois = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin',
             'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    return (int) date('j', $ts) . ' ' . $mois[(int) date('n', $ts)] . ' ' . date('Y', $ts);
}
