<script <?= csp_script_nonce() ?> async>
    document.addEventListener("DOMContentLoaded",()=> window.location = "<?= esc($url, 'js') ?>")
</script>