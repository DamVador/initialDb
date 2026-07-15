<?php
/** Page Contact (money page). Variables : $cpage, $settings */
$eyebrow  = $cpage['eyebrow'] ?? 'Contact';
$titre    = $cpage['titre'] ?? 'Parlons de votre projet';
$intro    = $cpage['intro'] ?? '';
$reponse  = $cpage['reponse'] ?? '';
$faq      = $cpage['faq'] ?? [];
$email    = $settings['email'] ?? '';
$calendly = $settings['calendly'] ?? '';
$whatsapp = $settings['whatsapp'] ?? '';
$waLink   = $whatsapp ? 'https://wa.me/' . preg_replace('/\D/', '', $whatsapp) : '';
?>
<header class="dark contact-hero">
  <div class="wrap">
    <span class="eyebrow"><?= e($eyebrow) ?></span>
    <h1 class="titre"><?= e($titre) ?></h1>
    <?php if ($intro): ?><p class="contact-intro"><?= e($intro) ?></p><?php endif; ?>
    <?php if ($reponse): ?><span class="statut-badge"><span class="dot"></span> <?= e($reponse) ?></span><?php endif; ?>
  </div>
</header>

<section class="contact-canaux">
  <div class="wrap">
    <div class="canaux-grille">
      <?php if ($calendly): ?>
      <a class="canal canal-fort" href="<?= e($calendly) ?>" target="_blank" rel="noopener">
        <span class="canal-titre">Réserver un appel</span>
        <span class="canal-note">30 min pour cadrer votre projet, sans engagement.</span>
        <span class="canal-fleche">Choisir un créneau →</span>
      </a>
      <?php endif; ?>
      <?php if ($waLink): ?>
      <a class="canal" href="<?= e($waLink) ?>" target="_blank" rel="noopener">
        <span class="canal-titre">WhatsApp</span>
        <span class="canal-note">Une question rapide ? Écrivez-nous directement.</span>
        <span class="canal-fleche">Ouvrir WhatsApp →</span>
      </a>
      <?php endif; ?>
      <?php if ($email): ?>
      <a class="canal" href="mailto:<?= e($email) ?>">
        <span class="canal-titre">Email</span>
        <span class="canal-note"><?= e($email) ?></span>
        <span class="canal-fleche">Écrire un mail →</span>
      </a>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php if (!empty($faq)): ?>
<section id="faq" class="faq">
  <div class="wrap">
    <span class="eyebrow">Questions fréquentes</span>
    <h2 class="titre">Avant de vous lancer</h2>
    <?php $faqItems = $faq; include TEMPLATES_PATH . '/partials/faq.php'; ?>
  </div>
</section>
<?php endif; ?>
