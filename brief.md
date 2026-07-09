# Brief projet — Site vitrine « Initial Db »

Tu es mon assistant de développement sur ce projet. Lis ce brief en entier avant d'agir,
puis guide-moi pas à pas. Je ne veux pas que tu fasses tout d'un coup : propose les étapes,
attends ma validation, explique ce que tu fais au fur et à mesure.

---

## 1. Contexte

« Initial Db » est mon studio digital (freelance). Je conçois des sites vitrines pour des
clients. Ce projet est **le site vitrine du studio lui-même** — ma propre carte de visite.

L'identité s'inspire visuellement de l'univers *Initial D* (course automobile, vitesse),
d'où le logo « INITIAL _Db » avec le « D » rouge. **L'hommage porte sur la vitesse et la
performance, PAS sur la culture geek/dev.**

### Ma cible (IMPORTANT pour le ton)
TPE, PME, artisans, commerçants locaux, professions libérales, mais aussi entreprises tech,
petites startups et porteurs de projet. **Ce ne sont PAS des développeurs.** Le discours et
le visuel doivent parler à des chefs d'entreprise non-techniques : rassurant, premium,
orienté bénéfices business (visibilité, clients, conversion) — jamais jargonnant.

---

## 2. État actuel & point de départ

J'ai **une seule page HTML** déjà conçue (`initial-db-visuel.html`), qui contient :
- Une navbar sticky
- Un hero (titre SEO + sous-titre + accroche + CTA)
- Une section « problème / bénéfices »
- Une section offres (2 formules : 997 € et 1 497 €)
- Un portfolio (« Le Garage »)
- Une section process (« La Mécanique », 3 étapes)
- Un CTA final + footer complet + mentions légales
- Les logos (2 versions, fond clair / fond sombre) intégrés

Cette page est la **référence visuelle** à conserver. On part de là.

---

## 3. Correction de direction artistique à appliquer EN PREMIER

Le design actuel contient des références « geek » qui ne parlent pas à ma cible.
**À retirer / remplacer :**

- ❌ **Police monospace (type machine à écrire / terminal)** partout où elle est utilisée
  (petits labels « eyebrow », prix, navigation, boutons). → La remplacer par la même
  police géométrique que le reste du site (Inter ou Space Grotesk). Rendu voulu : premium,
  éditorial, pas « console de développeur ».
- ❌ Le logo texte de secours écrit `// initialDb` → utiliser uniquement l'image du vrai logo.
- ❌ Toute mention type `[ 200 OK ]`, `</>`, `< / >`, `200 OK`, ou clin d'œil au code HTTP.
- ❌ Vocabulaire technique visible par le visiteur (status codes, balises, etc.).

**À CONSERVER :**
- ✅ L'esthétique vitesse / automobile (contraste, dynamisme, l'accent rouge).
- ✅ La palette : blanc cassé `#FAFAFA`, noir profond, rouge `#E10600` réservé aux
  appels à l'action et petits accents.
- ✅ L'alternance sections claires / sections sombres.
- ✅ Les titres avec un mot en italique rouge comme accent ponctuel (pas partout).
- ✅ Le vrai logo (fichiers fournis).

### Recommandations de thème (à respecter)
- **Typographie** : une police display avec du caractère pour les titres (ex. Space Grotesk,
  ou une géométrique italique premium), et Inter pour le corps de texte. Une seule famille
  pour tout ce qui était en monospace.
- **Couleurs** : noir `#0C0C0E`, blanc cassé `#FAFAFA`, rouge `#E10600` (action uniquement),
  gris de texte secondaire. Définis-les en variables CSS / tokens Tailwind.
- **Ton** : sobre, premium, rassurant. Beaucoup d'espace. Le rouge est rare et précieux.
- **Accessibilité** : respect de `prefers-reduced-motion`, contrastes AA, focus visibles.

---

## 4. Ce que je te demande MAINTENANT (étape 1 : structurer)

Pour l'instant on reste en **HTML/CSS/JS statique** (pas encore de framework), mais je veux
**sortir de la page monolithique** et séparer proprement les responsabilités. Crée cette
structure de projet :

```
initial-db/
├── index.html                 # le HTML, sans <style> ni <script> inline
├── assets/
│   ├── css/
│   │   ├── tokens.css         # variables : couleurs, polices, espacements
│   │   └── style.css          # styles du site
│   ├── js/
│   │   └── main.js            # menu burger mobile, petites interactions
│   ├── img/
│   │   ├── logo-fond-sombre.png
│   │   └── logo-fond-clair.png
│   └── fonts/                 # si polices auto-hébergées plus tard
├── README.md                  # comment lancer / structure du projet
└── .gitignore
```

Consignes pour cette étape :
1. Extrais tout le CSS inline vers `assets/css/` (sépare les tokens du reste).
2. Extrais le JS vers `assets/js/main.js` et **fais fonctionner le menu burger mobile**
   (il est actuellement décoratif, il faut qu'il ouvre/ferme le menu au clic).
3. Sors les logos en vrais fichiers image (ils sont en base64 dans le HTML pour l'instant) —
   je te les fournirai, référence-les via `<img>` ou `background-image`.
4. Applique la correction de DA du point 3 (retrait monospace + refs geek) pendant ce refactor.
5. Écris un `README.md` clair et un `.gitignore` adapté.
6. Initialise un dépôt git avec un premier commit propre.

Explique-moi chaque étape simplement. Guide-moi pour lancer la page en local
(ex. un petit serveur type `python3 -m http.server` ou l'extension Live Server).

---

## 5. Où on va ensuite (étape 2 : la vraie stack — NE PAS faire tout de suite)

Une fois la structure validée, on migrera vers la stack cible :

- **Front** : HTML / CSS / JS avec **Tailwind CSS**.
- **CMS** : **Cockpit** (auto-hébergé), en headless, pour gérer le contenu éditable :
  les réalisations (portfolio), les articles de blog (SEO), et un bloc « paramètres du site »
  (coordonnées, mentions légales) que je pourrai modifier sans toucher au code.
- **Hébergement** : j'ai déjà un hébergement mutualisé/serveur chez **PlanetHoster**.

Quand on y sera, j'aurai besoin que tu me guides précisément pour :
1. **Installer Tailwind** proprement (avec un build, pas le CDN) et migrer les styles actuels
   vers des classes Tailwind + un thème (couleurs/polices dans `tailwind.config`).
2. **Installer et configurer Cockpit** (v2) sur mon environnement : prérequis, mise en place,
   accès à l'admin.
3. **Modéliser le contenu dans Cockpit** : une collection « Réalisations », une collection
   « Articles », un singleton « Paramètres du site ». Définir les champs de chacun.
4. **Consommer l'API Cockpit** depuis le front pour afficher réalisations et articles,
   avec génération des pages de détail.
5. Gérer les **points de vigilance Cockpit connus** : servir les images sans exposer la clé
   d'API (prévoir un petit proxy côté serveur), et déclencher la mise à jour du site à la
   publication.

Pour l'instant, contente-toi de **garder cette cible en tête** dans la façon dont tu
structures le code à l'étape 1 (nommage clair, contenu du portfolio/articles facilement
« débranchable » du HTML en dur vers des données), pour que la migration soit indolore.

---

## 6. Méthode de travail que j'attends de toi

- Procède **étape par étape**, en attendant ma validation entre chaque.
- **Explique en langage simple** (je ne veux pas de jargon inutile, comme mes clients).
- Préfère des solutions **simples et maintenables** à des solutions clever.
- Ne fais **pas** de refonte visuelle non demandée : la page existante est la référence,
  on la nettoie (DA) et on la structure, on ne la réinvente pas.
- Quand tu proposes une commande à lancer, dis-moi **où** la lancer et **ce qu'elle fait**.

Commence par : (a) me confirmer que tu as compris le périmètre, (b) me proposer le plan
de l'étape 1 découpé en sous-étapes, puis attends mon feu vert.