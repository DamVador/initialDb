<?php
/* ============================================================
   Content.php — couche d'accès aux données (fichiers JSON)

   TOUTE lecture/écriture de contenu passe par ici. C'est le
   seul endroit à réécrire si un jour on migre vers une base de
   données ou un CMS : le reste du site ne connaît que ces méthodes.
   ============================================================ */

class Content
{
    private static function path(string $relative): string
    {
        return DATA_PATH . '/' . $relative;
    }

    /** Lit un fichier JSON et renvoie un tableau (ou $default si absent). */
    public static function read(string $relative, array $default = []): array
    {
        $file = self::path($relative);
        if (!is_file($file)) {
            return $default;
        }
        $data = json_decode((string) file_get_contents($file), true);
        return is_array($data) ? $data : $default;
    }

    /** Écrit un tableau en JSON (écriture atomique + verrou). */
    public static function write(string $relative, array $data): void
    {
        $file = self::path($relative);
        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $json = json_encode(
            $data,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        $tmp = $file . '.tmp';
        file_put_contents($tmp, $json, LOCK_EX);
        rename($tmp, $file); // remplacement atomique
    }

    /* ---- Paramètres du site (singleton global) ---- */

    public static function settings(): array
    {
        return self::read('settings.json');
    }

    public static function saveSettings(array $data): void
    {
        self::write('settings.json', $data);
    }

    /* ---- Pages principales (singletons : accueil, index…) ---- */

    public static function page(string $name): array
    {
        return self::read("pages/{$name}.json");
    }

    public static function savePage(string $name, array $data): void
    {
        self::write("pages/{$name}.json", $data);
    }

    /* ---- Collections (articles, projets, secteurs, offres…) ---- */

    /** Tous les éléments d'une collection, dans l'ordre du fichier. */
    public static function collection(string $name): array
    {
        $data = self::read("collections/{$name}.json", ['items' => []]);
        return $data['items'] ?? [];
    }

    public static function saveCollection(string $name, array $items): void
    {
        self::write("collections/{$name}.json", ['items' => array_values($items)]);
    }

    /** Éléments triés par ordre (pour l'admin). */
    public static function sorted(string $name): array
    {
        $items = self::collection($name);
        usort($items, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
        return $items;
    }

    /** Éléments publiés uniquement, triés par ordre (pour le site public). */
    public static function published(string $name): array
    {
        $items = array_filter(self::sorted($name), fn($i) => !empty($i['published']));
        return array_values($items);
    }

    public static function findBySlug(string $name, string $slug): ?array
    {
        foreach (self::collection($name) as $item) {
            if (($item['slug'] ?? '') === $slug) {
                return $item;
            }
        }
        return null;
    }

    public static function findById(string $name, string $id): ?array
    {
        foreach (self::collection($name) as $item) {
            if (($item['id'] ?? '') === $id) {
                return $item;
            }
        }
        return null;
    }

    /** Ajoute un élément et renvoie son id. */
    public static function addItem(string $name, array $item): string
    {
        $items = self::collection($name);
        $item['id'] = $item['id'] ?? bin2hex(random_bytes(6));
        $orders = array_column($items, 'order');
        $item['order'] = $orders ? (max($orders) + 1) : 0;
        $item['created_at'] = date('c');
        $item['updated_at'] = date('c');
        $items[] = $item;
        self::saveCollection($name, $items);
        return $item['id'];
    }

    public static function updateItem(string $name, string $id, array $fields): void
    {
        $items = self::collection($name);
        foreach ($items as &$item) {
            if (($item['id'] ?? '') === $id) {
                $item = array_merge($item, $fields);
                $item['updated_at'] = date('c');
            }
        }
        self::saveCollection($name, $items);
    }

    public static function deleteItem(string $name, string $id): void
    {
        $items = array_filter(
            self::collection($name),
            fn($i) => ($i['id'] ?? '') !== $id
        );
        self::saveCollection($name, $items);
    }

    /** Déplace un élément vers le haut ou le bas dans l'ordre d'affichage. */
    public static function move(string $name, string $id, string $direction): void
    {
        $items = self::sorted($name);
        $index = null;
        foreach ($items as $k => $item) {
            if (($item['id'] ?? '') === $id) {
                $index = $k;
                break;
            }
        }
        if ($index === null) {
            return;
        }
        $target = $direction === 'up' ? $index - 1 : $index + 1;
        if ($target < 0 || $target >= count($items)) {
            return;
        }
        [$items[$index], $items[$target]] = [$items[$target], $items[$index]];
        foreach ($items as $k => &$item) {
            $item['order'] = $k;
        }
        self::saveCollection($name, $items);
    }
}
