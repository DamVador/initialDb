<?php
/** Pied de page commun + mentions légales (variable : $settings) */
$email    = $settings['email'] ?? '';
$whatsapp = $settings['whatsapp'] ?? '';
$calendly = $settings['calendly'] ?? '';
$tiktok   = $settings['tiktok'] ?? '';
$legal    = $settings['legal'] ?? [];
$waLink   = $whatsapp ? 'https://wa.me/' . preg_replace('/\D/', '', $whatsapp) : '';
?>
<footer class="site-footer">
  <div class="wrap">
    <div class="footer-grid">
      <div class="footer-brand">
        <span class="brand-logo" role="img" aria-label="<?= e($settings['site_name'] ?? 'Initial Db') ?>"></span>
        <p>Studio digital spécialisé dans les sites vitrines rapides et optimisés pour le référencement local des artisans et commerçants.</p>
        <span class="statut-badge"><span class="dot"></span> Disponible pour de nouveaux projets</span>
      </div>
      <div class="footer-col">
        <h4>Studio</h4>
        <ul>
          <li><a href="<?= url('projets') ?>">Réalisations</a></li>
          <li><a href="<?= url('articles') ?>">Blog</a></li>
          <li><a href="<?= url('offres') ?>">Offres</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Offres</h4>
        <ul>
          <?php foreach (Content::published('offres') as $offre): ?>
            <li><a href="<?= url('offres/' . $offre['slug']) ?>"><?= e($offre['title']) ?></a></li>
          <?php endforeach; ?>
          <li><a href="<?= url('') ?>#contact" class="rouge">Démarrer un projet</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Contact</h4>
        <ul>
          <?php if ($email): ?><li><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></li><?php endif; ?>
          <?php if ($waLink): ?><li><a href="<?= e($waLink) ?>" target="_blank" rel="noopener">WhatsApp</a></li><?php endif; ?>
          <?php if ($calendly): ?><li><a href="<?= e($calendly) ?>" target="_blank" rel="noopener" class="rouge">Réserver un appel</a></li><?php endif; ?>
          <?php if ($tiktok): ?><li><a href="<?= e($tiktok) ?>" target="_blank" rel="noopener">TikTok</a></li><?php endif; ?>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span class="copy">© <?= date('Y') ?> <?= e($settings['site_name'] ?? 'Initial Db') ?> — <?= e($legal['editeur'] ?? '') ?> · Tous droits réservés</span>
      <div class="legal">
        <a href="<?= url('') ?>#mentions">Mentions légales</a>
      </div>
    </div>
  </div>
</footer>

<section id="mentions" class="mentions">
  <div class="wrap">
    <span class="eyebrow">Informations légales</span>
    <h2 class="mentions-titre">Mentions légales</h2>
    <div class="mentions-grid">
      <div class="mention-bloc">
        <h4>Éditeur du site</h4>
        <p><?= e($legal['editeur'] ?? '') ?><br><?= e($legal['statut'] ?? '') ?><br>SIRET : <?= e($legal['siret'] ?? '') ?><br><?= e($legal['ville'] ?? '') ?></p>
      </div>
      <div class="mention-bloc">
        <h4>Contact</h4>
        <p><?php if ($email): ?><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a><?php endif; ?><?php if ($waLink): ?><br><a href="<?= e($waLink) ?>" target="_blank" rel="noopener">WhatsApp</a><?php endif; ?></p>
      </div>
      <div class="mention-bloc">
        <h4>Hébergeur</h4>
        <p><?= e($legal['hebergeur'] ?? 'PlanetHoster') ?></p>
      </div>
    </div>
  </div>
</section>
