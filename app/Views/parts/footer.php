<section class="blurred-wall trans-1 hide"></section>
<footer></footer>

<?= $this->include("parts/loader") ?>

<script <?= csp_script_nonce() ?> >
    document.addEventListener("DOMContentLoaded", ()=>
    {
        document.querySelector(".loader-side")?.classList.add("hide")
    })
</script>
<?= $this->include('parts/scripts') ?>

</body>
</html>