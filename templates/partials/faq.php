<?php
/** Bloc FAQ réutilisable (accueil + contact).
 *  Variable attendue : $faqItems = [ ['question'=>..., 'answer'=>...], ... ]
 *  Accordéon natif <details>/<summary> : accessible, sans JavaScript. */
$faqItems = array_values(array_filter($faqItems ?? [], fn($f) => trim($f['question'] ?? '') !== '' && trim($f['answer'] ?? '') !== ''));
if ($faqItems):
?>
<div class="faq-liste">
  <?php foreach ($faqItems as $f): ?>
  <details class="faq-item">
    <summary><?= e($f['question']) ?></summary>
    <div class="faq-reponse"><p><?= nl2br(e($f['answer'])) ?></p></div>
  </details>
  <?php endforeach; ?>
</div>
<?php endif; ?>
