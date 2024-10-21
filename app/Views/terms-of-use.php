<?= $this->include("parts/head") ?>
<?= $this->include("parts/navbar") ?>

<section class="content container mt-2 d-flex align-items flex-column center-1 is-medium">
    <h1 class="has-text-warning">
        Terms of use
    </h1>
    <p class="white-text">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus exercitationem, expedita sed dolorem velit ducimus possimus ad magni, saepe tempore 
        <br> hic et impedit corrupti iure officiis quod consequuntur voluptatibus quisquam?
    </p>
    <a href="<?= esc($button->url) ?>" class="button is-warning is-rounded strong">
        <?= $button->text ?>
    </a>
</section>

<?= $this->include("parts/footer") ?>