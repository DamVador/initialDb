<?php
/* ============================================================
   Sitemap.php — génère le sitemap.xml automatiquement à partir
   du contenu publié. Rien à mettre à jour à la main.
   ============================================================ */

class Sitemap
{
    /** Base absolue du site (https://domaine). */
    private static function base(): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme . '://' . $host;
    }

    /** Renvoie le XML complet du sitemap. */
    public static function xml(): string
    {
        $base = self::base();
        $urls = [];

        // Pages fixes principales.
        $urls[] = ['loc' => url('')];
        $urls[] = ['loc' => url('articles')];
        $urls[] = ['loc' => url('projets')];

        // Collections publiées → une URL par entrée.
        foreach (Schema::collections() as $name => $def) {
            foreach (Content::published($name) as $item) {
                if (!empty($item['slug'])) {
                    $urls[] = [
                        'loc' => url($def['route'] . '/' . $item['slug']),
                        'lastmod' => $item['updated_at'] ?? null,
                    ];
                }
            }
        }

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . e($base . $u['loc']) . '</loc>' . "\n";
            if (!empty($u['lastmod'])) {
                $xml .= '    <lastmod>' . e(date('Y-m-d', strtotime($u['lastmod']))) . '</lastmod>' . "\n";
            }
            $xml .= '  </url>' . "\n";
        }
        $xml .= '</urlset>' . "\n";

        return $xml;
    }
}
