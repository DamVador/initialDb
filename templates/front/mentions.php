<?php
/** Page Mentions légales. Variable : $settings */
$legal    = $settings['legal'] ?? [];
$email    = $settings['email'] ?? '';
$whatsapp = $settings['whatsapp'] ?? '';
$waLink   = $whatsapp ? 'https://wa.me/' . preg_replace('/\D/', '', $whatsapp) : '';
?>
<article class="page">
  <div class="wrap wrap-lecture">
    <header class="page-hero">
      <span class="eyebrow">Informations légales</span>
      <h1 class="page-titre">Mentions légales</h1>
    </header>

    <div class="mentions-liste">
      <div class="mention-item">
        <h2>Éditeur du site</h2>
        <p>
          <?= e($legal['editeur'] ?? '') ?><br>
          <?php if (!empty($legal['statut'])): ?><?= e($legal['statut']) ?><br><?php endif; ?>
          <?php if (!empty($legal['siret'])): ?>SIRET : <?= e($legal['siret']) ?><br><?php endif; ?>
          <?php if (!empty($legal['ville'])): ?><?= e($legal['ville']) ?><?php endif; ?>
        </p>
      </div>

      <div class="mention-item">
        <h2>Contact</h2>
        <p>
          <?php if ($email): ?><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a><br><?php endif; ?>
          <?php if ($waLink): ?><a href="<?= e($waLink) ?>" target="_blank" rel="noopener">WhatsApp</a><?php endif; ?>
        </p>
      </div>

      <div class="mention-item">
        <h2>Hébergement</h2>
        <p><?= e($legal['hebergeur'] ?? 'PlanetHoster') ?></p>
      </div>

      <div class="mention-item">
        <h2>Propriété intellectuelle</h2>
        <p>L'ensemble du contenu de ce site (textes, visuels, logo) est la propriété de <?= e($settings['site_name'] ?? 'Initial Db') ?>, sauf mention contraire. Toute reproduction sans autorisation est interdite.</p>
      </div>

      <div class="mention-item">
        <h2>Données personnelles</h2>
        <p>Les informations transmises via les formulaires ou par contact direct sont utilisées uniquement pour répondre à votre demande et ne sont jamais cédées à des tiers. Vous pouvez demander leur suppression à tout moment par email.</p>
      </div>
    </div>

    <p class="retour"><a href="<?= url('') ?>" class="cta-ligne">← Retour à l'accueil</a></p>
  </div>
</article>
