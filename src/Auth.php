<?php
/* ============================================================
   Auth.php — authentification de l'admin (mot de passe unique)
   Le hash est stocké dans data/auth.json (jamais le mot de passe en clair).
   ============================================================ */

class Auth
{
    public static function isLoggedIn(): bool
    {
        return !empty($_SESSION['admin']);
    }

    /** À appeler en tête de toute page admin protégée. */
    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            redirect(url('admin/login'));
        }
    }

    /** Tente une connexion. Renvoie true si le mot de passe est bon. */
    public static function attempt(string $password): bool
    {
        $auth = Content::read('auth.json');
        $hash = $auth['password_hash'] ?? '';
        if ($hash !== '' && password_verify($password, $hash)) {
            session_regenerate_id(true); // nouvelle session = anti fixation
            $_SESSION['admin'] = true;
            return true;
        }
        return false;
    }

    public static function logout(): void
    {
        unset($_SESSION['admin']);
        session_regenerate_id(true);
    }

    /**
     * Change le mot de passe après validation de l'ancien.
     * Renvoie true si OK, sinon un message d'erreur (string).
     */
    public static function changePassword(string $old, string $new, string $confirm)
    {
        $auth = Content::read('auth.json');
        if (!password_verify($old, $auth['password_hash'] ?? '')) {
            return 'Ancien mot de passe incorrect.';
        }
        if (mb_strlen($new) < 6) {
            return 'Le nouveau mot de passe doit faire au moins 6 caractères.';
        }
        if ($new !== $confirm) {
            return 'La confirmation ne correspond pas au nouveau mot de passe.';
        }
        $auth['password_hash'] = password_hash($new, PASSWORD_DEFAULT);
        Content::write('auth.json', $auth);
        return true;
    }
}
