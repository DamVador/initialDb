<?php
/* ============================================================
   admin.php — contrôleur de l'espace d'administration.
   Chemin d'accès défini par la constante ADMIN_ROUTE (voir bootstrap.php).
   Inclus par index.php quand le 1er segment de l'URL vaut ADMIN_ROUTE.
   Le tableau $segments est déjà découpé par index.php.
   ============================================================ */

/** Rend une page d'admin dans la mise en page du dashboard. */
function admin_render(string $template, array $vars = [], string $pageTitle = 'Admin'): void
{
    $content = render('admin/' . $template, $vars);
    echo render('admin/layout', [
        'content'   => $content,
        'pageTitle' => $pageTitle,
    ]);
}

/** Génère un slug unique dans une collection (hors id courant). */
function admin_unique_slug(string $collection, string $base, string $ignoreId = ''): string
{
    $base = slugify($base) ?: 'element';
    $slug = $base;
    $n = 2;
    while (true) {
        $found = Content::findBySlug($collection, $slug);
        if (!$found || ($found['id'] ?? '') === $ignoreId) {
            return $slug;
        }
        $slug = $base . '-' . $n++;
    }
}

/* ---- Décodage du payload encodé par admin.js (contournement WAF) ----
   Les formulaires de contenu sont envoyés en Base64 dans le champ _payload
   pour que le pare-feu de l'hébergeur (ModSecurity/Atomicorp) n'inspecte pas
   — et ne bloque pas en 403 — leurs URLs externes (Calendly, TikTok…) ou leur
   HTML. On reconstitue $_POST à l'identique. Repli transparent si le POST
   arrive en clair (JavaScript désactivé). */
if (isset($_POST['_payload']) && is_string($_POST['_payload'])) {
    $raw = base64_decode($_POST['_payload'], true);
    if ($raw !== false) {
        parse_str($raw, $decoded);
        $_POST = $decoded;
    }
}

$action = $segments[1] ?? '';

/* ---- Connexion ---- */
if ($action === 'login') {
    if (Auth::isLoggedIn()) {
        redirect(admin_url());
    }
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        if (Auth::attempt($_POST['password'] ?? '')) {
            redirect(admin_url());
        }
        $error = 'Mot de passe incorrect.';
    }
    echo render('admin/login', ['error' => $error]);
    exit;
}

/* ---- Déconnexion ---- */
if ($action === 'logout') {
    Auth::logout();
    redirect(admin_url('login'));
}

/* ===== À partir d'ici, tout exige d'être connecté ===== */
Auth::requireLogin();

/* ---- Changement de mot de passe ---- */
if ($action === 'password') {
    $msg = '';
    $type = 'error';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        $result = Auth::changePassword(
            $_POST['old'] ?? '',
            $_POST['new'] ?? '',
            $_POST['confirm'] ?? ''
        );
        if ($result === true) {
            flash('Mot de passe modifié avec succès.');
            redirect(admin_url());
        }
        $msg = $result;
    }
    admin_render('password', ['error' => $msg], 'Mot de passe');
    exit;
}

/* ---- Paramètres du site ---- */
if ($action === 'settings') {
    $schema = Schema::settings();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        Content::saveSettings(form_normalize($schema['fields'], $_POST['f'] ?? []));
        flash('Paramètres enregistrés.');
        redirect(admin_url('settings'));
    }
    admin_render('edit', [
        'schema'  => $schema,
        'values'  => Content::settings(),
        'action'  => admin_url('settings'),
        'heading' => $schema['label'],
    ], $schema['label']);
    exit;
}

/* ---- Édition d'une page (singleton) ---- */
if ($action === 'page') {
    $name = $segments[2] ?? '';
    $schema = Schema::singleton($name);
    if (!$schema) {
        not_found();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        Content::savePage($name, form_normalize($schema['fields'], $_POST['f'] ?? []));
        flash('Page enregistrée.');
        redirect(admin_url('page/' . $name));
    }
    admin_render('edit', [
        'schema'  => $schema,
        'values'  => Content::page($name),
        'action'  => admin_url('page/' . $name),
        'heading' => $schema['label'],
    ], $schema['label']);
    exit;
}

/* ---- Collections (articles, projets, offres, secteurs) ---- */
if ($action === 'collection') {
    $name = $segments[2] ?? '';
    $sub  = $segments[3] ?? '';
    $id   = $segments[4] ?? '';
    $schema = Schema::collection($name);
    if (!$schema) {
        not_found();
    }

    /* Ajout / édition */
    if ($sub === 'new' || $sub === 'edit') {
        $isNew = ($sub === 'new');
        $item = $isNew ? [] : (Content::findById($name, $id) ?? null);
        if (!$isNew && $item === null) {
            not_found();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            $data = form_normalize($schema['fields'], $_POST['f'] ?? []);
            // Slug : depuis le champ, sinon depuis le titre ; toujours unique.
            $titleField = $schema['title_field'] ?? 'title';
            $slugSource = $data['slug'] ?: ($data[$titleField] ?? '');
            $data['slug'] = admin_unique_slug($name, $slugSource, $isNew ? '' : $id);

            if ($isNew) {
                Content::addItem($name, $data);
            } else {
                Content::updateItem($name, $id, $data);
            }
            flash($schema['singular'] . ' enregistré' . '.');
            redirect(admin_url('collection/' . $name));
        }

        admin_render('edit', [
            'schema'  => $schema,
            'values'  => $item ?? [],
            'action'  => $isNew
                ? admin_url('collection/' . $name . '/new')
                : admin_url('collection/' . $name . '/edit/' . $id),
            'heading' => ($isNew ? 'Nouveau : ' : 'Modifier : ') . $schema['singular'],
            'backUrl' => admin_url('collection/' . $name),
        ], $schema['singular']);
        exit;
    }

    /* Suppression */
    if ($sub === 'delete') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            Content::deleteItem($name, $id);
            flash($schema['singular'] . ' supprimé.');
        }
        redirect(admin_url('collection/' . $name));
    }

    /* Réordonnancement */
    if ($sub === 'move') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            Content::move($name, $id, $segments[5] ?? 'up');
        }
        redirect(admin_url('collection/' . $name));
    }

    /* Liste (par défaut) */
    admin_render('collection', [
        'schema' => $schema,
        'name'   => $name,
        'items'  => Content::sorted($name),
    ], $schema['label']);
    exit;
}

/* ---- Tableau de bord (par défaut) ---- */
admin_render('dashboard', [
    'collections' => Schema::collections(),
    'singletons'  => Schema::singletons(),
], 'Tableau de bord');
