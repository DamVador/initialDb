<?php
/** Page d'accueil. Variables : $page, $projets, $settings */
$hero     = $page['hero'] ?? [];
$probleme = $page['probleme'] ?? [];
$offres   = $page['offres'] ?? [];
$garage   = $page['garage'] ?? [];
$process  = $page['process'] ?? [];
$contact  = $page['contact'] ?? [];
$email    = $settings['email'] ?? '';
$calendly = $settings['calendly'] ?? '';
$whatsapp = $settings['whatsapp'] ?? '';
$waLink   = $whatsapp ? 'https://wa.me/' . preg_replace('/\D/', '', $whatsapp) : '';
?>
<!-- HERO -->
<header class="dark hero">
  <div class="wrap hero-body">
    <span class="eyebrow"><?= e($hero['eyebrow'] ?? '') ?></span>
    <h1><?= e($hero['h1_before'] ?? '') ?><?php if (!empty($hero['h1_em'])): ?> <em><?= e($hero['h1_em']) ?></em><?php endif; ?><?= e($hero['h1_after'] ?? '') ?></h1>
    <?php if (!empty($hero['subtitle'])): ?><p class="sous-titre"><?= e($hero['subtitle']) ?></p><?php endif; ?>
    <?php if (!empty($hero['accroche'])): ?><p class="accroche"><?= e($hero['accroche']) ?></p><?php endif; ?>
    <div class="hero-actions">
      <a href="#contact" class="cta"><?= e($hero['cta_label'] ?? 'Démarrer le projet') ?></a>
      <a href="<?= url('projets') ?>" class="en-savoir">Voir les réalisations</a>
    </div>
  </div>
  <?php if (!empty($page['meta'])): ?>
  <div class="wrap hero-meta">
    <?php foreach ($page['meta'] as $stat): ?>
      <div><strong><?= e($stat['value'] ?? '') ?></strong><?= e($stat['label'] ?? '') ?></div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</header>

<!-- PROBLÈME -->
<section class="probleme">
  <div class="wrap">
    <span class="eyebrow"><?= e($probleme['eyebrow'] ?? '') ?></span>
    <h2 class="titre"><?= e($probleme['titre_before'] ?? '') ?><?php if (!empty($probleme['titre_em'])): ?> <em><?= e($probleme['titre_em']) ?></em><?php endif; ?><?= e($probleme['titre_after'] ?? '') ?></h2>
    <div class="benefices">
      <?php foreach (($page['benefices'] ?? []) as $b): ?>
      <div class="benef">
        <span class="num"><?= e($b['num'] ?? '') ?></span>
        <h3><?= e($b['title'] ?? '') ?></h3>
        <p><?= e($b['text'] ?? '') ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- TARIFS -->
<section id="offres" class="dark offres">
  <div class="wrap">
    <span class="eyebrow"><?= e($offres['eyebrow'] ?? '') ?></span>
    <h2 class="titre"><?= e($offres['titre'] ?? '') ?></h2>
    <?php if (!empty($offres['sous'])): ?><p class="sous"><?= e($offres['sous']) ?></p><?php endif; ?>
    <div class="grille-tarif">
      <div class="offre offre--principale fine-border">
        <span class="offre-nom"><?= e($offres['offer_name'] ?? '') ?></span>
        <span class="prix"><?= e($offres['offer_price'] ?? '') ?> <span>€</span></span>
        <?php if (!empty($offres['offer_desc'])): ?><p class="desc"><?= e($offres['offer_desc']) ?></p><?php endif; ?>
        <ul>
          <?php foreach (($offres['offer_features'] ?? []) as $f): ?><li><?= e($f) ?></li><?php endforeach; ?>
        </ul>
        <a href="#contact" class="cta"><?= e($offres['cta_label'] ?? 'Choisir cette formule') ?></a>
      </div>
      <?php if (!empty($page['options'])): ?>
      <aside class="options fine-border">
        <h3 class="options-titre">Options à la carte</h3>
        <ul class="options-liste">
          <?php foreach ($page['options'] as $opt): ?>
          <li>
            <div class="opt-tete">
              <span class="opt-nom"><?= e($opt['title'] ?? '') ?></span>
              <span class="opt-prix"><?= e($opt['price'] ?? '') ?></span>
            </div>
            <?php if (!empty($opt['note'])): ?><span class="opt-note"><?= e($opt['note']) ?></span><?php endif; ?>
          </li>
          <?php endforeach; ?>
        </ul>
        <a href="<?= url('offres') ?>" class="cta-ligne-clair">Voir toutes les offres</a>
      </aside>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- RÉALISATIONS -->
<section id="garage" class="garage">
  <div class="wrap">
    <span class="eyebrow"><?= e($garage['eyebrow'] ?? '') ?></span>
    <h2 class="titre"><?= e($garage['titre'] ?? '') ?></h2>
    <?php if (!empty($garage['sous'])): ?><p class="sous"><?= e($garage['sous']) ?></p><?php endif; ?>
    <div class="grille-projets">
      <?php foreach ($projets as $p): ?>
      <a href="<?= url('projets/' . $p['slug']) ?>" class="carte">
        <div class="carte-visuel"<?php if (!empty($p['cover'])): ?> style="background-image:url('<?= e($p['cover']) ?>');background-size:cover"<?php endif; ?>>
          <?php if (!empty($p['offre'])): ?><span class="carte-offre"><?= e($p['offre']) ?></span><?php endif; ?>
        </div>
        <div class="carte-info">
          <h3><?= e($p['title']) ?></h3>
          <?php if (!empty($p['secteur'])): ?><p class="carte-secteur"><?= e($p['secteur']) ?></p><?php endif; ?>
          <?php if (!empty($p['resultat'])): ?><p class="carte-resultat"><span>▸</span> <?= e($p['resultat']) ?></p><?php endif; ?>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php if (count($projets)): ?>
    <p style="margin-top:2.5rem"><a href="<?= url('projets') ?>" class="cta-ligne">Toutes les réalisations</a></p>
    <?php endif; ?>
  </div>
</section>

<!-- MÉTHODE (effet vidéo au scroll) -->
<?php $etapes = $page['etapes'] ?? []; ?>
<section id="process" class="methode" data-methode data-frames="<?= url('assets/video/frames-out') ?>" data-count="192">
  <div class="methode-scroll">
    <div class="methode-sticky">
      <canvas class="methode-canvas" aria-hidden="true"></canvas>
      <div class="methode-scrim" aria-hidden="true"></div>
      <div class="methode-stage wrap">

        <div class="methode-titre" data-methode-titre>
          <span class="eyebrow"><?= e($process['eyebrow'] ?? '') ?></span>
          <h2 class="titre"><?= e($process['titre'] ?? '') ?></h2>
          <?php if (!empty($process['sous'])): ?><p class="methode-sous"><?= e($process['sous']) ?></p><?php endif; ?>
        </div>

        <?php foreach ($etapes as $i => $et): ?>
        <article class="methode-etape pos-<?= $i + 1 ?>" data-methode-etape="<?= $i ?>">
          <span class="step"><?= e($et['step'] ?? '') ?></span>
          <span class="t-titre"><?= e($et['title'] ?? '') ?></span>
          <p><?= e($et['text'] ?? '') ?></p>
        </article>
        <?php endforeach; ?>

      </div>
    </div>
  </div>
</section>

<!-- CE QUI FAIT LA DIFFÉRENCE -->
<?php $signature = $page['signature'] ?? []; ?>
<section class="signature">
  <div class="wrap">
    <div class="signature-tete">
      <span class="eyebrow"><?= e($signature['eyebrow'] ?? '') ?></span>
      <h2 class="titre"><?= e($signature['titre'] ?? '') ?></h2>
      <?php if (!empty($signature['texte'])): ?><p class="signature-intro"><?= e($signature['texte']) ?></p><?php endif; ?>
    </div>
    <?php if (!empty($page['atouts'])): ?>
    <div class="signature-grid">
      <?php foreach ($page['atouts'] as $a): ?>
      <div class="atout">
        <h3><?= e($a['titre'] ?? '') ?></h3>
        <p><?= e($a['texte'] ?? '') ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- CONTACT -->
<section id="contact" class="dark pied">
  <div class="wrap">
    <span class="eyebrow"><?= e($contact['eyebrow'] ?? '') ?></span>
    <h2 class="titre"><?= e($contact['titre'] ?? '') ?></h2>
    <?php if (!empty($contact['sous'])): ?><p class="pied-sous"><?= e($contact['sous']) ?></p><?php endif; ?>
    <div class="pied-actions">
      <?php if ($calendly): ?><a href="<?= e($calendly) ?>" class="cta" target="_blank" rel="noopener"><?= e($contact['cta_label'] ?? 'Réserver un appel') ?></a><?php endif; ?>
      <?php if ($waLink): ?><a href="<?= e($waLink) ?>" class="cta-ligne-clair" target="_blank" rel="noopener">WhatsApp</a><?php endif; ?>
      <?php if ($email): ?><a href="mailto:<?= e($email) ?>" class="cta-ligne-clair">Email</a><?php endif; ?>
    </div>
  </div>
</section>

<script src="<?= url('assets/js/methode.js') ?>" defer></script>
