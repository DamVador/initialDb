<?php
/* ============================================================
   Schema.php — description des types de contenu

   Un seul endroit décrit les champs de chaque page/collection.
   L'admin génère automatiquement ses formulaires à partir d'ici,
   et le front sait quoi afficher. Pour ajouter un champ : on le
   déclare ici, rien d'autre à câbler.

   Types de champ : text, textarea, html, image, date, number,
   bool, select, lines (liste, une par ligne), slug,
   group (objet de sous-champs), repeater (liste d'objets).
   ============================================================ */

class Schema
{
    /* ---- Collections : contenus répétables, gérés en CRUD ---- */
    public static function collections(): array
    {
        return [
            'articles' => [
                'label'     => 'Articles',
                'singular'  => 'Article',
                'route'     => 'articles',        // /articles/{slug}
                'title_field' => 'title',
                'fields' => [
                    'title'           => ['type' => 'text', 'label' => 'Titre', 'required' => true],
                    'slug'            => ['type' => 'slug', 'label' => 'Slug (URL)', 'from' => 'title'],
                    'date'            => ['type' => 'date', 'label' => 'Date de publication'],
                    'excerpt'         => ['type' => 'textarea', 'label' => 'Résumé (chapô)'],
                    'cover'           => ['type' => 'image', 'label' => 'Image de couverture (URL)'],
                    'body'            => ['type' => 'html', 'label' => 'Contenu de l\'article'],
                    'seo_title'       => ['type' => 'text', 'label' => 'Titre SEO (balise title)'],
                    'seo_description' => ['type' => 'textarea', 'label' => 'Description SEO'],
                    'published'       => ['type' => 'bool', 'label' => 'Publié (visible en ligne)'],
                ],
            ],
            'projets' => [
                'label'     => 'Réalisations',
                'singular'  => 'Réalisation',
                'route'     => 'projets',
                'title_field' => 'title',
                'fields' => [
                    'title'           => ['type' => 'text', 'label' => 'Nom du projet', 'required' => true],
                    'slug'            => ['type' => 'slug', 'label' => 'Slug (URL)', 'from' => 'title'],
                    'secteur'         => ['type' => 'text', 'label' => 'Secteur · Ville', 'help' => 'ex. Automobile · Annecy'],
                    'offre'           => ['type' => 'text', 'label' => 'Formule (badge)', 'help' => 'ex. Site animation 3D'],
                    'resultat'        => ['type' => 'text', 'label' => 'Résultat clé', 'help' => 'ex. +320 % de demandes de devis'],
                    'cover'           => ['type' => 'image', 'label' => 'Visuel (URL)'],
                    'body'            => ['type' => 'html', 'label' => 'Description du projet'],
                    'seo_title'       => ['type' => 'text', 'label' => 'Titre SEO'],
                    'seo_description' => ['type' => 'textarea', 'label' => 'Description SEO'],
                    'published'       => ['type' => 'bool', 'label' => 'Publié'],
                ],
            ],
            'secteurs' => [
                'label'     => 'Pages secteurs',
                'singular'  => 'Page secteur',
                'route'     => 'secteurs',
                'title_field' => 'title',
                'fields' => [
                    'title'           => ['type' => 'text', 'label' => 'Secteur d\'activité', 'required' => true, 'help' => 'ex. Restaurants'],
                    'slug'            => ['type' => 'slug', 'label' => 'Slug (URL)', 'from' => 'title'],
                    'intro'           => ['type' => 'textarea', 'label' => 'Accroche (haut de page)'],
                    'body'            => ['type' => 'html', 'label' => 'Contenu de la page'],
                    'seo_title'       => ['type' => 'text', 'label' => 'Titre SEO'],
                    'seo_description' => ['type' => 'textarea', 'label' => 'Description SEO'],
                    'published'       => ['type' => 'bool', 'label' => 'Publié'],
                ],
            ],
            'offres' => [
                'label'     => 'Pages offres',
                'singular'  => 'Page offre',
                'route'     => 'offres',
                'title_field' => 'title',
                'fields' => [
                    'title'           => ['type' => 'text', 'label' => 'Nom de l\'offre', 'required' => true, 'help' => 'ex. Site vitrine'],
                    'slug'            => ['type' => 'slug', 'label' => 'Slug (URL)', 'from' => 'title'],
                    'price'           => ['type' => 'text', 'label' => 'Prix affiché', 'help' => 'ex. 997 €'],
                    'tagline'         => ['type' => 'text', 'label' => 'Phrase d\'accroche'],
                    'description'     => ['type' => 'textarea', 'label' => 'Description courte'],
                    'features'        => ['type' => 'lines', 'label' => 'Ce qui est inclus (une ligne = un point)'],
                    'body'            => ['type' => 'html', 'label' => 'Contenu détaillé'],
                    'cta_label'       => ['type' => 'text', 'label' => 'Texte du bouton'],
                    'seo_title'       => ['type' => 'text', 'label' => 'Titre SEO'],
                    'seo_description' => ['type' => 'textarea', 'label' => 'Description SEO'],
                    'published'       => ['type' => 'bool', 'label' => 'Publié'],
                ],
            ],
        ];
    }

    public static function collection(string $name): ?array
    {
        return self::collections()[$name] ?? null;
    }

    /* ---- Singletons : pages uniques, un fichier JSON chacune ---- */
    public static function singletons(): array
    {
        return [
            'accueil' => [
                'label' => 'Page d\'accueil',
                'fields' => [
                    'hero' => ['type' => 'group', 'label' => 'Hero (haut de page)', 'fields' => [
                        'eyebrow'   => ['type' => 'text', 'label' => 'Sur-titre'],
                        'h1_before' => ['type' => 'text', 'label' => 'Titre — début'],
                        'h1_em'     => ['type' => 'text', 'label' => 'Titre — mot en rouge'],
                        'h1_after'  => ['type' => 'text', 'label' => 'Titre — fin'],
                        'subtitle'  => ['type' => 'text', 'label' => 'Sous-titre'],
                        'accroche'  => ['type' => 'textarea', 'label' => 'Accroche'],
                        'cta_label' => ['type' => 'text', 'label' => 'Bouton principal'],
                    ]],
                    'meta' => ['type' => 'repeater', 'label' => 'Chiffres clés', 'item_label' => 'Chiffre', 'fields' => [
                        'value' => ['type' => 'text', 'label' => 'Valeur', 'help' => 'ex. 100'],
                        'label' => ['type' => 'text', 'label' => 'Libellé', 'help' => 'ex. Score Lighthouse'],
                    ]],
                    'probleme' => ['type' => 'group', 'label' => 'Section « Le constat »', 'fields' => [
                        'eyebrow'      => ['type' => 'text', 'label' => 'Sur-titre'],
                        'titre_before' => ['type' => 'text', 'label' => 'Titre — début'],
                        'titre_em'     => ['type' => 'text', 'label' => 'Titre — mot en rouge'],
                        'titre_after'  => ['type' => 'text', 'label' => 'Titre — fin'],
                    ]],
                    'benefices' => ['type' => 'repeater', 'label' => 'Bénéfices', 'item_label' => 'Bénéfice', 'fields' => [
                        'num'   => ['type' => 'text', 'label' => 'Numéro', 'help' => 'ex. 01'],
                        'title' => ['type' => 'text', 'label' => 'Titre'],
                        'text'  => ['type' => 'textarea', 'label' => 'Texte'],
                    ]],
                    'offres' => ['type' => 'group', 'label' => 'Section tarifs', 'fields' => [
                        'eyebrow'        => ['type' => 'text', 'label' => 'Sur-titre'],
                        'titre'          => ['type' => 'text', 'label' => 'Titre'],
                        'sous'           => ['type' => 'text', 'label' => 'Sous-titre'],
                        'offer_name'     => ['type' => 'text', 'label' => 'Nom de la formule principale'],
                        'offer_price'    => ['type' => 'text', 'label' => 'Prix', 'help' => 'ex. 997'],
                        'offer_desc'     => ['type' => 'textarea', 'label' => 'Description'],
                        'offer_features' => ['type' => 'lines', 'label' => 'Inclus (une ligne = un point)'],
                        'cta_label'      => ['type' => 'text', 'label' => 'Bouton'],
                    ]],
                    'options' => ['type' => 'repeater', 'label' => 'Options (à droite du tarif)', 'item_label' => 'Option', 'fields' => [
                        'title' => ['type' => 'text', 'label' => 'Nom de l\'option', 'help' => 'ex. Animation 3D'],
                        'price' => ['type' => 'text', 'label' => 'Prix', 'help' => 'ex. 500 €'],
                        'note'  => ['type' => 'text', 'label' => 'Précision', 'help' => 'ex. pour 4 articles / mois'],
                    ]],
                    'garage' => ['type' => 'group', 'label' => 'Section réalisations', 'fields' => [
                        'eyebrow' => ['type' => 'text', 'label' => 'Sur-titre'],
                        'titre'   => ['type' => 'text', 'label' => 'Titre'],
                        'sous'    => ['type' => 'text', 'label' => 'Sous-titre'],
                    ]],
                    'process' => ['type' => 'group', 'label' => 'Section méthode', 'fields' => [
                        'eyebrow' => ['type' => 'text', 'label' => 'Sur-titre'],
                        'titre'   => ['type' => 'text', 'label' => 'Titre'],
                    ]],
                    'etapes' => ['type' => 'repeater', 'label' => 'Étapes de la méthode', 'item_label' => 'Étape', 'fields' => [
                        'step'  => ['type' => 'text', 'label' => 'Numéro', 'help' => 'ex. Étape 01'],
                        'title' => ['type' => 'text', 'label' => 'Titre'],
                        'text'  => ['type' => 'textarea', 'label' => 'Texte'],
                    ]],
                    'signature' => ['type' => 'group', 'label' => 'Section « Ce qui fait la différence »', 'fields' => [
                        'eyebrow' => ['type' => 'text', 'label' => 'Sur-titre'],
                        'titre'   => ['type' => 'text', 'label' => 'Titre'],
                        'texte'   => ['type' => 'textarea', 'label' => 'Texte d\'introduction'],
                    ]],
                    'atouts' => ['type' => 'repeater', 'label' => 'Points forts', 'item_label' => 'Point fort', 'fields' => [
                        'titre' => ['type' => 'text', 'label' => 'Titre'],
                        'texte' => ['type' => 'textarea', 'label' => 'Texte'],
                    ]],
                    'contact' => ['type' => 'group', 'label' => 'Section contact (bas de page)', 'fields' => [
                        'eyebrow'   => ['type' => 'text', 'label' => 'Sur-titre'],
                        'titre'     => ['type' => 'text', 'label' => 'Titre'],
                        'sous'      => ['type' => 'textarea', 'label' => 'Texte'],
                        'cta_label' => ['type' => 'text', 'label' => 'Bouton'],
                    ]],
                    'seo' => ['type' => 'group', 'label' => 'Référencement (SEO)', 'fields' => [
                        'seo_title'       => ['type' => 'text', 'label' => 'Titre SEO'],
                        'seo_description' => ['type' => 'textarea', 'label' => 'Description SEO'],
                    ]],
                ],
            ],
            'articles-index' => [
                'label' => 'Page liste des articles',
                'fields' => [
                    'eyebrow'         => ['type' => 'text', 'label' => 'Sur-titre'],
                    'title'           => ['type' => 'text', 'label' => 'Titre de la page'],
                    'intro'           => ['type' => 'textarea', 'label' => 'Introduction'],
                    'seo_title'       => ['type' => 'text', 'label' => 'Titre SEO'],
                    'seo_description' => ['type' => 'textarea', 'label' => 'Description SEO'],
                ],
            ],
            'projets-index' => [
                'label' => 'Page liste des réalisations',
                'fields' => [
                    'eyebrow'         => ['type' => 'text', 'label' => 'Sur-titre'],
                    'title'           => ['type' => 'text', 'label' => 'Titre de la page'],
                    'intro'           => ['type' => 'textarea', 'label' => 'Introduction'],
                    'seo_title'       => ['type' => 'text', 'label' => 'Titre SEO'],
                    'seo_description' => ['type' => 'textarea', 'label' => 'Description SEO'],
                ],
            ],
        ];
    }

    public static function singleton(string $name): ?array
    {
        return self::singletons()[$name] ?? null;
    }

    /* ---- Paramètres du site ---- */
    public static function settings(): array
    {
        return [
            'label' => 'Paramètres du site',
            'fields' => [
                'site_name' => ['type' => 'text', 'label' => 'Nom du site'],
                'tagline'   => ['type' => 'text', 'label' => 'Slogan'],
                'email'     => ['type' => 'text', 'label' => 'Email de contact'],
                'whatsapp'  => ['type' => 'text', 'label' => 'Numéro WhatsApp', 'help' => 'format international sans + ni espaces, ex. 33780942683'],
                'calendly'  => ['type' => 'text', 'label' => 'Lien Calendly'],
                'tiktok'    => ['type' => 'text', 'label' => 'Lien TikTok'],
                'legal' => ['type' => 'group', 'label' => 'Mentions légales', 'fields' => [
                    'editeur'   => ['type' => 'text', 'label' => 'Éditeur'],
                    'statut'    => ['type' => 'text', 'label' => 'Statut', 'help' => 'ex. Développeur web freelance'],
                    'siret'     => ['type' => 'text', 'label' => 'SIRET'],
                    'ville'     => ['type' => 'text', 'label' => 'Ville'],
                    'hebergeur' => ['type' => 'text', 'label' => 'Hébergeur'],
                ]],
            ],
        ];
    }
}
