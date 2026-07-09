<?php
/* ============================================================
   index.php — routeur unique du site (front + admin + sitemap)
   Toutes les requêtes qui ne sont pas un fichier existant
   arrivent ici (voir .htaccess).
   ============================================================ */

require __DIR__ . '/src/bootstrap.php';

/* -- Serveur de dev PHP : laisser passer les fichiers réels (assets) -- */
if (php_sapi_name() === 'cli-server') {
    $file = __DIR__ . urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (is_file($file)) {
        return false;
    }
}

/* -- Découpage de l'URL en segments -- */
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
if (BASE_URL !== '' && str_starts_with($path, BASE_URL)) {
    $path = substr($path, strlen(BASE_URL));
}
$path = trim(rawurldecode($path), '/');
$segments = $path === '' ? [] : explode('/', $path);

/* -- Espace admin : délégué à src/admin.php -- */
if (($segments[0] ?? '') === 'admin') {
    require __DIR__ . '/src/admin.php';
    exit;
}

/* -- Sitemap XML généré à la volée -- */
if ($path === 'sitemap.xml') {
    header('Content-Type: application/xml; charset=utf-8');
    echo Sitemap::xml();
    exit;
}

/* -- robots.txt (pointe vers le sitemap) -- */
if ($path === 'robots.txt') {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $base = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    header('Content-Type: text/plain; charset=utf-8');
    echo "User-agent: *\nAllow: /\nSitemap: {$base}" . url('sitemap.xml') . "\n";
    exit;
}

/* ------------------------------------------------------------
   Rendu d'une page front dans la mise en page commune.
   ------------------------------------------------------------ */
function front(string $template, array $vars, array $meta, array $breadcrumb = []): void
{
    $settings = Content::settings();
    $content = render('front/' . $template, $vars + ['settings' => $settings]);
    echo render('layout', [
        'settings'   => $settings,
        'meta'       => $meta,
        'breadcrumb' => $breadcrumb,
        'content'    => $content,
    ]);
}

function not_found(): void
{
    http_response_code(404);
    $settings = Content::settings();
    $content = render('front/404', ['settings' => $settings]);
    echo render('layout', [
        'settings'   => $settings,
        'meta'       => ['title' => 'Page introuvable — ' . ($settings['site_name'] ?? 'Initial Db')],
        'breadcrumb' => [],
        'content'    => $content,
    ]);
    exit;
}

/* ------------------------------------------------------------
   Routes du site public
   ------------------------------------------------------------ */
$route = $segments[0] ?? '';

switch ($route) {

    /* Accueil */
    case '':
        $page = Content::page('accueil');
        $seo = $page['seo'] ?? [];
        front('home', [
            'page'    => $page,
            'projets' => array_slice(Content::published('projets'), 0, 2),
        ], [
            'title'       => $seo['seo_title'] ?? 'Initial Db · Studio digital',
            'description' => $seo['seo_description'] ?? '',
        ]);
        break;

    /* Articles : liste + détail */
    case 'articles':
        if (isset($segments[1])) {
            $article = Content::findBySlug('articles', $segments[1]);
            if (!$article || empty($article['published'])) {
                not_found();
            }
            front('article', ['article' => $article], [
                'title'       => ($article['seo_title'] ?: $article['title']) . ' — Blog',
                'description' => $article['seo_description'] ?: excerpt($article['excerpt'] ?? ''),
            ], [
                ['label' => 'Accueil', 'url' => url('')],
                ['label' => 'Blog', 'url' => url('articles')],
                ['label' => $article['title']],
            ]);
        } else {
            $index = Content::page('articles-index');
            front('collection-index', [
                'index'  => $index,
                'items'  => Content::published('articles'),
                'route'  => 'articles',
                'kind'   => 'article',
            ], [
                'title'       => $index['seo_title'] ?? 'Blog',
                'description' => $index['seo_description'] ?? '',
            ], [
                ['label' => 'Accueil', 'url' => url('')],
                ['label' => $index['title'] ?? 'Blog'],
            ]);
        }
        break;

    /* Réalisations : liste + détail */
    case 'projets':
        if (isset($segments[1])) {
            $projet = Content::findBySlug('projets', $segments[1]);
            if (!$projet || empty($projet['published'])) {
                not_found();
            }
            front('projet', ['projet' => $projet], [
                'title'       => ($projet['seo_title'] ?: $projet['title']) . ' — Réalisation',
                'description' => $projet['seo_description'] ?: excerpt($projet['body'] ?? ''),
            ], [
                ['label' => 'Accueil', 'url' => url('')],
                ['label' => 'Réalisations', 'url' => url('projets')],
                ['label' => $projet['title']],
            ]);
        } else {
            $index = Content::page('projets-index');
            front('collection-index', [
                'index'  => $index,
                'items'  => Content::published('projets'),
                'route'  => 'projets',
                'kind'   => 'projet',
            ], [
                'title'       => $index['seo_title'] ?? 'Réalisations',
                'description' => $index['seo_description'] ?? '',
            ], [
                ['label' => 'Accueil', 'url' => url('')],
                ['label' => $index['title'] ?? 'Réalisations'],
            ]);
        }
        break;

    /* Offres : liste + détail */
    case 'offres':
        route_collection_page('offres', 'Nos offres', $segments[1] ?? null);
        break;

    /* Secteurs : liste + détail */
    case 'secteurs':
        route_collection_page('secteurs', 'Secteurs d\'activité', $segments[1] ?? null);
        break;

    /* Mentions légales */
    case 'mentions-legales':
        front('mentions', [], [
            'title' => 'Mentions légales — ' . (Content::settings()['site_name'] ?? 'Initial Db'),
        ], [
            ['label' => 'Accueil', 'url' => url('')],
            ['label' => 'Mentions légales'],
        ]);
        break;

    default:
        not_found();
}

/* ------------------------------------------------------------
   Route générique pour offres / secteurs (liste + détail simple)
   ------------------------------------------------------------ */
function route_collection_page(string $name, string $listTitle, ?string $slug): void
{
    $def = Schema::collection($name);
    if ($slug !== null) {
        $item = Content::findBySlug($name, $slug);
        if (!$item || empty($item['published'])) {
            not_found();
        }
        front('simple-page', ['item' => $item, 'route' => $name], [
            'title'       => ($item['seo_title'] ?? '') ?: $item['title'],
            'description' => ($item['seo_description'] ?? '') ?: excerpt($item['description'] ?? $item['intro'] ?? ''),
        ], [
            ['label' => 'Accueil', 'url' => url('')],
            ['label' => $listTitle, 'url' => url($name)],
            ['label' => $item['title']],
        ]);
    } else {
        front('collection-index', [
            'index'  => ['title' => $listTitle, 'eyebrow' => $def['label'] ?? '', 'intro' => ''],
            'items'  => Content::published($name),
            'route'  => $name,
            'kind'   => 'simple',
        ], [
            'title' => $listTitle,
        ], [
            ['label' => 'Accueil', 'url' => url('')],
            ['label' => $listTitle],
        ]);
    }
}
