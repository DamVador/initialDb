<?php
/* ============================================================
   admin.php — contrôleur de l'espace d'administration (/admin)
   Inclus par index.php quand l'URL commence par « admin ».
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

$action = $segments[1] ?? '';

/* ---- Connexion ---- */
if ($action === 'login') {
    if (Auth::isLoggedIn()) {
        redirect(url('admin'));
    }
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        if (Auth::attempt($_POST['password'] ?? '')) {
            redirect(url('admin'));
        }
        $error = 'Mot de passe incorrect.';
    }
    echo render('admin/login', ['error' => $error]);
    exit;
}

/* ---- Déconnexion ---- */
if ($action === 'logout') {
    Auth::logout();
    redirect(url('admin/login'));
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
            redirect(url('admin'));
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
        redirect(url('admin/settings'));
    }
    admin_render('edit', [
        'schema'  => $schema,
        'values'  => Content::settings(),
        'action'  => url('admin/settings'),
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
        redirect(url('admin/page/' . $name));
    }
    admin_render('edit', [
        'schema'  => $schema,
        'values'  => Content::page($name),
        'action'  => url('admin/page/' . $name),
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
            redirect(url('admin/collection/' . $name));
        }

        admin_render('edit', [
            'schema'  => $schema,
            'values'  => $item ?? [],
            'action'  => $isNew
                ? url('admin/collection/' . $name . '/new')
                : url('admin/collection/' . $name . '/edit/' . $id),
            'heading' => ($isNew ? 'Nouveau : ' : 'Modifier : ') . $schema['singular'],
            'backUrl' => url('admin/collection/' . $name),
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
        redirect(url('admin/collection/' . $name));
    }

    /* Réordonnancement */
    if ($sub === 'move') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            Content::move($name, $id, $segments[5] ?? 'up');
        }
        redirect(url('admin/collection/' . $name));
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
