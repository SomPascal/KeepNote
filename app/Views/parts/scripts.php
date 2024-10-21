<?php foreach (($scripts ?? []) as $script): ?>
	<script src="/assets/js/<?= esc($script, 'attr') ?>"></script>
<?php endforeach ?>