<?php
/** Pied de page commun (variable : $settings) */
$siteName = $settings['site_name'] ?? 'Initial Db';
$email    = $settings['email'] ?? '';
$whatsapp = $settings['whatsapp'] ?? '';
$calendly = $settings['calendly'] ?? '';
$tiktok   = $settings['tiktok'] ?? '';
$waLink   = $whatsapp ? 'https://wa.me/' . preg_replace('/\D/', '', $whatsapp) : '';
?>
<footer class="site-footer">
  <div class="wrap">
    <div class="footer-grid">
      <div class="footer-brand">
        <span class="brand-logo" role="img" aria-label="<?= e($siteName) ?>"></span>
        <p>Studio digital spécialisé dans les sites vitrines rapides et optimisés pour le référencement local des artisans, commerçants et PME.</p>
        <span class="statut-badge"><span class="dot"></span> Disponible pour de nouveaux projets</span>
      </div>
      <div class="footer-col">
        <h4>Studio</h4>
        <ul>
          <li><a href="<?= url('offres') ?>">Offres</a></li>
          <li><a href="<?= url('projets') ?>">Réalisations</a></li>
          <li><a href="<?= url('') ?>#process">Méthode</a></li>
          <li><a href="<?= url('articles') ?>">Blog</a></li>
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
      <span class="copy">© <?= date('Y') ?> <?= e($siteName) ?> · Tous droits réservés</span>
      <div class="legal">
        <a href="<?= url('mentions-legales') ?>">Mentions légales</a>
      </div>
    </div>
  </div>
</footer>
