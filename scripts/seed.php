<?php
/* ============================================================
   scripts/seed.php : remplit le site avec des données de départ.

   À lancer UNE SEULE FOIS :  php scripts/seed.php
   ⚠️  Écrase le contenu existant (accueil, articles, offres…).
   Mot de passe admin par défaut : voir $DEFAULT_PASSWORD ci-dessous.
   ============================================================ */

require __DIR__ . '/../src/bootstrap.php';

$DEFAULT_PASSWORD = 'initialdb2026';

// Argument optionnel : « pages » = ne réécrire que la page d'accueil
// (laisse intacts les paramètres, les collections et le mot de passe).
$only = $argv[1] ?? '';

function seed_item(array $fields, int $order, bool $published = true): array
{
    return array_merge([
        'id'         => bin2hex(random_bytes(6)),
        'order'      => $order,
        'published'  => $published,
        'created_at' => date('c'),
        'updated_at' => date('c'),
    ], $fields);
}

/* ---- Mot de passe admin (créé une seule fois, jamais réécrasé) ---- */
if (!is_file(DATA_PATH . '/auth.json')) {
    Content::write('auth.json', ['password_hash' => password_hash($DEFAULT_PASSWORD, PASSWORD_DEFAULT)]);
    echo "   Mot de passe admin créé : {$DEFAULT_PASSWORD}\n";
} else {
    echo "   Mot de passe admin existant conservé.\n";
}

/* ---- Paramètres du site ---- */
if ($only === '') {
Content::saveSettings([
    'site_name' => 'Initial Db',
    'tagline'   => 'Studio digital de création de sites vitrines',
    'email'     => 'contact@damienbanville-dev.fr',
    'whatsapp'  => '33780942683',
    'calendly'  => 'https://calendly.com/contact-damienbanville-dev/30min',
    'tiktok'    => 'https://www.tiktok.com/@damien_dev',
    'legal'     => [
        'editeur'   => 'Damien Banville',
        'statut'    => 'Développeur web freelance',
        'siret'     => '89206207600013',
        'ville'     => 'Paris, France',
        'hebergeur' => 'PlanetHoster',
    ],
]);
}

/* ---- Page d'accueil ---- */
Content::savePage('accueil', [
    'hero' => [
        'eyebrow'   => 'Studio digital',
        'h1_before' => 'Studio digital de création de sites web',
        'h1_em'     => 'sur-mesure',
        'h1_after'  => ', adaptés à votre image.',
        'subtitle'  => 'Des sites vitrines rapides, pensés pour convertir.',
        'accroche'  => 'Pour les artisans, commerçants et les PME qui veulent une présence web irréprochable : design premium, référencement local, et une vraie mécanique de conversion.',
        'cta_label' => 'Démarrer le projet',
    ],
    'meta' => [
        ['value' => '100', 'label' => 'Score Lighthouse'],
        ['value' => '< 1s', 'label' => 'Temps de chargement'],
        ['value' => 'SEO', 'label' => 'Local optimisé'],
    ],
    'probleme' => [
        'eyebrow'      => 'Le constat',
        'titre_before' => 'Un beau commerce,',
        'titre_em'     => 'invisible',
        'titre_after'  => ' en ligne.',
    ],
    'benefices' => [
        ['num' => '01', 'title' => 'Acquisition', 'text' => 'Vous sortez de la page trois. Les clients du quartier vous trouvent enfin quand ils cherchent.'],
        ['num' => '02', 'title' => 'SEO local', 'text' => 'Données structurées, fiche Google, pages de service : la mécanique complète du référencement de proximité.'],
        ['num' => '03', 'title' => 'Conversion', 'text' => 'Un site rapide et clair qui transforme la visite en demande de devis ou en réservation.'],
    ],
    'offres' => [
        'eyebrow'        => 'Tarifs',
        'titre'          => 'Une formule claire, des options à la carte.',
        'sous'           => 'Le site vitrine complet, puis vous ajoutez seulement ce dont vous avez besoin.',
        'offer_name'     => 'Site vitrine',
        'offer_price'    => '997',
        'offer_desc'     => 'Le site vitrine professionnel, complet et prêt à convertir.',
        'offer_features' => [
            'Site vitrine sur-mesure',
            'Ultra-rapide, score Lighthouse 100',
            'SEO local optimisé',
            'Design premium responsive',
        ],
        'cta_label'      => 'Choisir cette formule',
    ],
    'options' => [
        ['title' => 'Animation 3D', 'price' => '500 €', 'note' => 'Expérience vidéo 3D au scroll, effet « waouh ».'],
        ['title' => 'Référencement (SEO)', 'price' => '180 €/mois', 'note' => '4 articles de blog optimisés rédigés par mois.'],
    ],
    'garage' => [
        'eyebrow' => 'Réalisations',
        'titre'   => 'Nos réalisations récentes.',
        'sous'    => 'Quelques projets récents.',
    ],
    'process' => [
        'eyebrow' => 'La méthode',
        'titre'   => 'En trois étapes.',
        'sous'    => 'De la première discussion à la mise en ligne, un parcours clair et sans surprise.',
    ],
    'etapes' => [
        ['step' => 'Étape 01', 'title' => 'Brief', 'text' => 'On cadre l\'objectif business, la cible et les mots-clés locaux qui comptent.'],
        ['step' => 'Étape 02', 'title' => 'Design & Code', 'text' => 'Conception, développement et optimisation. Vous suivez l\'avancée à chaque étape.'],
        ['step' => 'Étape 03', 'title' => 'Livraison', 'text' => 'Mise en ligne, transfert des accès, et vous êtes prêt à convertir.'],
    ],
    'signature' => [
        'eyebrow' => 'Pourquoi Initial Db',
        'titre'   => 'Ce qui fait la différence.',
        'texte'   => 'Un site, ce n\'est pas qu\'une carte de visite en ligne : c\'est votre crédibilité, une présence qui travaille pour vous en continu, et un outil taillé pour votre activité.',
    ],
    'atouts' => [
        ['titre' => 'Une image à la hauteur de votre travail', 'texte' => 'Un site soigné inspire confiance et crédibilité, souvent avant même le premier contact.'],
        ['titre' => 'Une présence qui vous appartient', 'texte' => 'Contrairement aux réseaux sociaux, votre site est à vous : pas d\'algorithme ni de règles qui changent du jour au lendemain.'],
        ['titre' => 'Disponible en permanence', 'texte' => 'Votre site informe et rassure vos visiteurs 24h/24, même quand vous êtes fermé.'],
        ['titre' => 'Du sur-mesure, pensé pour vous', 'texte' => 'Chaque site est conçu pour votre activité et votre image, jamais décliné d\'un modèle générique.'],
    ],
    'contact' => [
        'eyebrow'   => 'Ligne de départ',
        'titre'     => 'On lance votre projet ?',
        'sous'      => 'Réservez un appel de 30 minutes, ou écrivez-moi directement. Réponse rapide, sans engagement.',
        'cta_label' => 'Réserver un appel',
    ],
    'seo' => [
        'seo_title'       => 'Initial Db · Studio digital de création de sites vitrines',
        'seo_description' => 'Studio digital spécialisé dans les sites vitrines rapides et optimisés pour le référencement local des artisans, commerçants et PME.',
    ],
]);

/* ---- Le reste (listes, collections) : uniquement en install complète ---- */
if ($only === '') {

/* ---- Pages listes ---- */
Content::savePage('articles-index', [
    'eyebrow'         => 'Le blog',
    'title'           => 'Conseils pour votre présence en ligne',
    'intro'           => 'Des articles concrets pour attirer plus de clients grâce à votre site : visibilité, référencement local et conversion.',
    'seo_title'       => 'Blog : conseils web pour commerçants et PME · Initial Db',
    'seo_description' => 'Articles pratiques sur les sites vitrines, le SEO local et la conversion, pour les artisans, commerçants et PME.',
]);
Content::savePage('projets-index', [
    'eyebrow'         => 'Portfolio',
    'title'           => 'Nos réalisations',
    'intro'           => 'Une sélection de sites conçus pour des commerces et PME, avec de vrais résultats à la clé.',
    'seo_title'       => 'Réalisations : sites vitrines sur-mesure · Initial Db',
    'seo_description' => 'Découvrez les sites vitrines réalisés par Initial Db pour des commerçants et PME, et leurs résultats.',
]);

/* ---- Articles (3) ---- */
Content::saveCollection('articles', [
    seed_item([
        'title'   => 'Pourquoi la vitesse de votre site fait gagner des clients',
        'slug'    => 'vitesse-site-gagner-clients',
        'date'    => '2026-06-12',
        'excerpt' => 'Un site lent, ce sont des visiteurs qui partent avant même de vous découvrir. Voici pourquoi chaque seconde compte.',
        'cover'   => '',
        'body'    => "<p>La majorité des visiteurs quittent une page qui met plus de trois secondes à s'afficher. Pour un commerce local, c'est une porte qui se referme avant même le bonjour.</p><h2>La vitesse, c'est de la confiance</h2><p>Un site rapide envoie un signal simple : c'est du sérieux. À l'inverse, un site qui rame donne l'impression d'un commerce négligé, même si la réalité est tout autre.</p><h2>Google récompense la rapidité</h2><p>Les moteurs de recherche placent plus haut les sites rapides. Résultat : plus de vitesse, c'est aussi plus de visibilité locale.</p><p>Chez Initial Db, chaque site vise un score de performance maximal, sans compromis sur le design.</p>",
        'seo_title'       => 'Vitesse d\'un site web : pourquoi ça vous fait gagner des clients',
        'seo_description' => 'Un site lent fait fuir vos visiteurs et vous pénalise sur Google. Découvrez pourquoi la vitesse est décisive pour un commerce local.',
    ], 0, true),
    seed_item([
        'title'   => 'SEO local : apparaître quand votre quartier vous cherche',
        'slug'    => 'seo-local-quartier',
        'date'    => '2026-05-28',
        'excerpt' => 'Vos futurs clients tapent « près de moi » sur Google. Voici comment faire pour que ce soit vous qu\'ils trouvent.',
        'cover'   => '',
        'body'    => "<p>Le référencement local, c'est l'art d'être trouvé par les gens proches de vous, au moment où ils cherchent ce que vous proposez.</p><h2>La fiche Google, votre vitrine n°1</h2><p>Une fiche d'établissement complète et à jour est souvent le premier contact avec un client. Photos, horaires, avis : tout compte.</p><h2>Des pages pensées pour votre ville</h2><p>Un site bien structuré, avec les bons mots-clés locaux, indique clairement à Google où et pour qui vous travaillez.</p><p>C'est un travail de fond, mais c'est lui qui fait sonner le téléphone.</p>",
        'seo_title'       => 'SEO local : comment être trouvé par les clients de votre quartier',
        'seo_description' => 'Fiche Google, mots-clés locaux, pages de service : le guide clair du référencement de proximité pour les commerces.',
    ], 1, true),
    seed_item([
        'title'   => 'Site vitrine ou réseaux sociaux : par où commencer ?',
        'slug'    => 'site-vitrine-ou-reseaux-sociaux',
        'date'    => '2026-05-10',
        'excerpt' => 'Faut-il un site quand on a déjà Instagram ? La réponse tient en un mot : vous.',
        'cover'   => '',
        'body'    => "<p>Les réseaux sociaux sont loués : vous construisez sur un terrain qui ne vous appartient pas, avec des règles qui changent sans prévenir.</p><h2>Votre site, votre maison</h2><p>Un site vitrine est le seul espace que vous possédez vraiment. Il travaille pour vous 24h/24, sans algorithme entre vous et vos clients.</p><h2>Les deux, en réalité</h2><p>Le bon réflexe : les réseaux pour attirer et créer du lien, le site pour rassurer et convertir. L'un nourrit l'autre.</p><p>Commencez par la base solide, le site, puis faites-y converger votre audience.</p>",
        'seo_title'       => 'Site vitrine ou réseaux sociaux : où investir en premier ?',
        'seo_description' => 'Instagram ne suffit pas. Découvrez pourquoi un site vitrine reste la base d\'une présence en ligne qui vous appartient.',
    ], 2, true),
]);

/* ---- Réalisations (2) ---- */
Content::saveCollection('projets', [
    seed_item([
        'title'    => 'Garage Martin',
        'slug'     => 'garage-martin',
        'secteur'  => 'Automobile · Annecy',
        'offre'    => 'Site animation 3D',
        'resultat' => '+320 % de demandes de devis en trois mois',
        'cover'    => '',
        'body'     => "<p>Refonte complète du site d'un garage automobile, avec une expérience immersive 3D et un parcours de prise de rendez-vous simplifié.</p><p>Résultat : une hausse spectaculaire des demandes de devis dès les premières semaines.</p>",
        'seo_title'       => 'Réalisation : site du Garage Martin à Annecy',
        'seo_description' => 'Site immersif pour un garage automobile à Annecy : +320 % de demandes de devis en trois mois.',
    ], 0, true),
    seed_item([
        'title'    => 'Maison Levain',
        'slug'     => 'maison-levain',
        'secteur'  => 'Boulangerie · Chambéry',
        'offre'    => 'Site vitrine',
        'resultat' => '1ʳᵉ position locale sur sa requête cible',
        'cover'    => '',
        'body'     => "<p>Site vitrine pour une boulangerie artisanale, optimisé pour le référencement local et la mise en avant des produits.</p><p>La boulangerie occupe désormais la première position sur sa principale requête locale.</p>",
        'seo_title'       => 'Réalisation : site de la boulangerie Maison Levain',
        'seo_description' => 'Site vitrine SEO pour une boulangerie à Chambéry, hissée en 1ʳᵉ position locale.',
    ], 1, true),
]);

/* ---- Offres (3) ---- */
Content::saveCollection('offres', [
    seed_item([
        'title'       => 'Site vitrine',
        'slug'        => 'site-vitrine',
        'price'       => '997 €',
        'tagline'     => 'Le site professionnel complet, prêt à convertir.',
        'description' => 'La base solide : un site vitrine rapide, élégant et optimisé pour le référencement local.',
        'features'    => [
            'Site vitrine sur-mesure',
            'Ultra-rapide, score Lighthouse 100',
            'SEO local optimisé',
            'Design premium responsive',
            'Mise en ligne et formation incluses',
        ],
        'body'        => "<p>Idéal pour les artisans, commerçants et professions libérales qui veulent une présence en ligne irréprochable et efficace, sans complexité.</p>",
        'cta_label'   => 'Démarrer mon site',
        'seo_title'   => 'Site vitrine sur-mesure à 997 € · Initial Db',
        'seo_description' => 'Un site vitrine rapide, premium et optimisé SEO pour les commerçants et PME, à partir de 997 €.',
    ], 0, true),
    seed_item([
        'title'       => 'Site avec CMS',
        'slug'        => 'site-avec-cms',
        'price'       => 'à partir de 1 497 €',
        'tagline'     => 'Gérez votre contenu vous-même, en toute autonomie.',
        'description' => 'Un site vitrine complet avec un espace d\'administration pour modifier vos textes, articles et réalisations sans toucher au code.',
        'features'    => [
            'Tout le Site vitrine',
            'Espace d\'administration sur-mesure',
            'Gestion des articles de blog',
            'Gestion des réalisations',
            'Formation à la prise en main',
        ],
        'body'        => "<p>Parfait si vous publiez régulièrement (articles, actualités, nouveaux projets) et souhaitez rester autonome au quotidien.</p>",
        'cta_label'   => 'Discuter de mon projet',
        'seo_title'   => 'Site vitrine avec CMS et espace d\'administration · Initial Db',
        'seo_description' => 'Un site que vous gérez vous-même : blog, réalisations et contenus modifiables sans code, à partir de 1 497 €.',
    ], 1, true),
    seed_item([
        'title'       => 'Site animation 3D',
        'slug'        => 'site-animation-3d',
        'price'       => 'à partir de 1 497 €',
        'tagline'     => 'L\'effet « waouh » qui vous démarque vraiment.',
        'description' => 'Un site vitrine augmenté d\'une expérience 3D immersive au défilement, pour marquer durablement les esprits.',
        'features'    => [
            'Tout le Site vitrine',
            'Expérience vidéo 3D au scroll',
            'Animations sur-mesure',
            'Direction artistique premium',
        ],
        'body'        => "<p>Recommandé aux marques qui veulent sortir du lot et transformer leur site en véritable signature visuelle.</p>",
        'cta_label'   => 'Voir ce que c\'est possible',
        'seo_title'   => 'Site vitrine avec animation 3D immersive · Initial Db',
        'seo_description' => 'Un site vitrine avec expérience 3D au scroll et animations sur-mesure pour un effet « waouh » mémorable.',
    ], 2, true),
]);

/* ---- Secteurs (2) ---- */
Content::saveCollection('secteurs', [
    seed_item([
        'title'  => 'Restaurants',
        'slug'   => 'restaurants',
        'intro'  => 'Un site qui donne faim et remplit vos tables : menu à jour, réservation facile et présence locale au top.',
        'body'   => "<p>Pour un restaurant, le site est souvent le premier contact. Il doit donner envie, informer en un coup d'œil et faciliter la réservation.</p><h2>Ce qui compte pour vous</h2><ul><li>Un menu toujours à jour, consultable sur mobile</li><li>La réservation en quelques secondes</li><li>Une fiche Google impeccable pour le « restaurant près de moi »</li></ul>",
        'seo_title'       => 'Création de site internet pour restaurant · Initial Db',
        'seo_description' => 'Sites vitrines pour restaurants : menu à jour, réservation simple et référencement local pour remplir vos tables.',
    ], 0, true),
    seed_item([
        'title'  => 'Artisans du bâtiment',
        'slug'   => 'artisans-batiment',
        'intro'  => 'Rassurez vos futurs clients et décrochez plus de chantiers grâce à un site qui met en avant votre savoir-faire.',
        'body'   => "<p>Plombier, électricien, menuisier, maçon : vos clients cherchent en ligne avant d'appeler. Un site clair et rassurant fait la différence.</p><h2>Ce qui compte pour vous</h2><ul><li>Vos réalisations en photos, preuve de votre sérieux</li><li>Vos zones d'intervention et vos services</li><li>Un formulaire de demande de devis simple</li></ul>",
        'seo_title'       => 'Création de site internet pour artisan du bâtiment · Initial Db',
        'seo_description' => 'Sites vitrines pour artisans du bâtiment : réalisations, zones d\'intervention et demande de devis pour plus de chantiers.',
    ], 1, true),
]);

} // fin du bloc « install complète »

if ($only === 'pages') {
    echo "✅ Page d'accueil mise à jour (le reste est inchangé).\n";
} else {
    echo "✅ Données de départ créées.\n";
    echo "   Pensez à changer le mot de passe dans l'admin (rubrique « Mot de passe »).\n";
}
