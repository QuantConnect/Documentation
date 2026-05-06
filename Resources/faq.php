<script type="application/ld+json" class="aioseo-schema">
<?= $faqSchema ?>
</script>

<?php foreach (json_decode($faqSchema, true)["mainEntity"] as $q): ?>
<h4><?= $q["name"] ?></h4>
<p><?= $q["acceptedAnswer"]["text"] ?></p>
<?php endforeach; ?>
