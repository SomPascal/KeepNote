<?= $this->include("parts/head") ?>
<?= $this->include("parts/navbar") ?>

<section class="content d-flex align-items flex-column w-100 center-1 is-medium mt-2 p-2">
    <h1 class="is-capitalized has-text-warning" style="font-size: 2.5em;text-transform:capitalize;text-align:center; " >
        <?= esc($header->title) ?>
    </h1>
    <p class="is-medium white-text">
        <?= esc($message) ?>
    </p>
    
    <?= $this->include('parts/message-btn') ?>
</section>

<?= $this->include("parts/footer") ?>