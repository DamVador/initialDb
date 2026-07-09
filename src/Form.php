<?php
/* ============================================================
   Form.php — génération et lecture des formulaires d'admin
   à partir des schémas (src/Schema.php).

   - form_fields()    : dessine les champs (HTML)
   - form_normalize() : relit le POST et le range proprement
                        selon le schéma (types, listes, groupes…)
   ============================================================ */

/** Identifiant HTML lisible depuis un nom de champ à crochets. */
function field_id(string $name): string
{
    return trim(preg_replace('/[\[\]]+/', '-', $name) ?? '', '-');
}

/** Dessine tous les champs d'un schéma. */
function form_fields(array $fields, array $values, string $prefix): string
{
    $out = '';
    foreach ($fields as $key => $spec) {
        $name  = $prefix . '[' . $key . ']';
        $value = $values[$key] ?? null;
        $out  .= form_field($name, $spec, $value);
    }
    return $out;
}

/** Dessine un champ selon son type. */
function form_field(string $name, array $spec, $value): string
{
    $type  = $spec['type'] ?? 'text';
    $label = $spec['label'] ?? '';
    $help  = $spec['help'] ?? '';
    $id    = field_id($name);

    // Conteneurs (group / repeater) : rendu spécifique, sans <label> simple.
    if ($type === 'group') {
        $inner = form_fields($spec['fields'], is_array($value) ? $value : [], $name);
        return '<fieldset class="grp"><legend>' . e($label) . '</legend>' . $inner . '</fieldset>';
    }
    if ($type === 'repeater') {
        return form_repeater($name, $spec, is_array($value) ? $value : []);
    }

    // Champs simples.
    $helpHtml = $help ? '<span class="help">' . e($help) . '</span>' : '';
    $control  = '';

    switch ($type) {
        case 'textarea':
            $control = '<textarea id="' . $id . '" name="' . e($name) . '" rows="4">' . e((string) $value) . '</textarea>';
            break;

        case 'html':
            $control = '<textarea id="' . $id . '" name="' . e($name) . '" rows="12" class="code">' . e((string) $value) . '</textarea>'
                     . '<span class="help">Vous pouvez utiliser des balises HTML simples (&lt;p&gt;, &lt;h2&gt;, &lt;strong&gt;, &lt;a&gt;, &lt;ul&gt;&lt;li&gt;…).</span>';
            break;

        case 'lines':
            $text = is_array($value) ? implode("\n", $value) : (string) $value;
            $control = '<textarea id="' . $id . '" name="' . e($name) . '" rows="5">' . e($text) . '</textarea>';
            break;

        case 'bool':
            $checked = !empty($value) ? ' checked' : '';
            return '<div class="field field-bool"><label for="' . $id . '">'
                 . '<input type="checkbox" id="' . $id . '" name="' . e($name) . '" value="1"' . $checked . '> '
                 . e($label) . '</label>' . $helpHtml . '</div>';

        case 'select':
            $options = '';
            foreach (($spec['options'] ?? []) as $optVal => $optLabel) {
                $sel = ((string) $value === (string) $optVal) ? ' selected' : '';
                $options .= '<option value="' . e($optVal) . '"' . $sel . '>' . e($optLabel) . '</option>';
            }
            $control = '<select id="' . $id . '" name="' . e($name) . '">' . $options . '</select>';
            break;

        case 'date':
            $control = '<input type="date" id="' . $id . '" name="' . e($name) . '" value="' . e((string) $value) . '">';
            break;

        case 'number':
            $control = '<input type="number" id="' . $id . '" name="' . e($name) . '" value="' . e((string) $value) . '">';
            break;

        case 'image':
        case 'text':
        case 'slug':
        default:
            $control = '<input type="text" id="' . $id . '" name="' . e($name) . '" value="' . e((string) $value) . '">';
            break;
    }

    return '<div class="field"><label for="' . $id . '">' . e($label) . '</label>' . $control . $helpHtml . '</div>';
}

/** Dessine une liste répétable (ajout / suppression / réordonnancement). */
function form_repeater(string $name, array $spec, array $rows): string
{
    $itemLabel = $spec['item_label'] ?? 'Élément';
    $subFields = $spec['fields'];

    $rowsHtml = '';
    foreach (array_values($rows) as $i => $row) {
        $rowsHtml .= form_repeater_row($name, $subFields, $i, is_array($row) ? $row : [], $itemLabel);
    }

    // Modèle vierge cloné par admin.js (placeholder __i__ remplacé au clic).
    $template = form_repeater_row($name, $subFields, '__i__', [], $itemLabel);

    return '<fieldset class="rep" data-repeater>'
         . '<legend>' . e($spec['label'] ?? '') . '</legend>'
         . '<div class="rep-rows">' . $rowsHtml . '</div>'
         . '<template class="rep-template">' . $template . '</template>'
         . '<button type="button" class="btn-sm rep-add">+ Ajouter ' . e(mb_strtolower($itemLabel)) . '</button>'
         . '</fieldset>';
}

function form_repeater_row(string $name, array $subFields, $index, array $values, string $itemLabel): string
{
    $rowName = $name . '[' . $index . ']';
    $inner = form_fields($subFields, $values, $rowName);
    return '<div class="rep-row">'
         . '<div class="rep-row-head"><span>' . e($itemLabel) . '</span>'
         . '<div class="rep-row-actions">'
         . '<button type="button" class="btn-icon rep-up" title="Monter">↑</button>'
         . '<button type="button" class="btn-icon rep-down" title="Descendre">↓</button>'
         . '<button type="button" class="btn-icon rep-del" title="Supprimer">✕</button>'
         . '</div></div>'
         . '<div class="rep-row-body">' . $inner . '</div>'
         . '</div>';
}

/**
 * Relit les données POST selon le schéma et renvoie un tableau propre
 * (booléens en vrai/faux, listes en tableaux, groupes imbriqués…).
 */
function form_normalize(array $fields, array $input): array
{
    $out = [];
    foreach ($fields as $key => $spec) {
        $type = $spec['type'] ?? 'text';
        $val  = $input[$key] ?? null;

        switch ($type) {
            case 'bool':
                $out[$key] = !empty($val);
                break;

            case 'lines':
                $out[$key] = form_split_lines((string) ($val ?? ''));
                break;

            case 'group':
                $out[$key] = form_normalize($spec['fields'], is_array($val) ? $val : []);
                break;

            case 'repeater':
                $rows = is_array($val) ? array_values($val) : [];
                $clean = [];
                foreach ($rows as $row) {
                    $normRow = form_normalize($spec['fields'], is_array($row) ? $row : []);
                    if (form_row_not_empty($normRow)) {
                        $clean[] = $normRow;
                    }
                }
                $out[$key] = $clean;
                break;

            default:
                $out[$key] = is_string($val) ? trim($val) : '';
                break;
        }
    }
    return $out;
}

function form_split_lines(string $text): array
{
    $lines = explode("\n", str_replace("\r", '', $text));
    $lines = array_map('trim', $lines);
    return array_values(array_filter($lines, fn($l) => $l !== ''));
}

/** Une ligne de repeater est-elle non vide (au moins un champ rempli) ? */
function form_row_not_empty(array $row): bool
{
    foreach ($row as $v) {
        if (is_array($v)) {
            if (form_row_not_empty($v)) {
                return true;
            }
        } elseif (is_bool($v)) {
            // un booléen seul ne suffit pas à considérer la ligne remplie
            continue;
        } elseif (trim((string) $v) !== '') {
            return true;
        }
    }
    return false;
}
