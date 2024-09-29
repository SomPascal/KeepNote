<?= $this->include("parts/head") ?>
<?= $this->include("parts/navbar") ?>

<header class="header">
    <h1 class="has-text-warning">
        <?php if (isSignedIn()): ?>
            <?= lang('Header.title.user', ['username' => $user->username(), 'color' => $user->color()]) ?>
        <?php else: ?>
            <?= esc(lang('Header.title.visitor')) ?>
        <?php endif ?>
    </h1>
    <p class="mt-3 mb-1">
        <?= esc(lang('Header.title.keepnote-desc')) ?>
    </p>

    <?php if (isSignedIn()): ?>
        <a href="<?= esc($header->icon_url ?? "/", "attr") ?>" type="button" class="button mt-2 is-warning is-medium is-rounded is-bold">
            <?= esc(lang('Button.open-kn')) ?>
        </a>
    <?php else: ?>
        <div class="buttons mt-1">
            <a href="<?= esc(route_to("signup"), "attr") ?>" class="button is-warning is-medium is-rounded is-bold">
                <?= lang('Button.signup') ?>
            </a>

            <a href="<?= esc(route_to("signin"), "attr") ?>" class="button is-warning is-medium is-light is-rounded is-bold">
                <?= esc(lang('Button.signin')) ?>
                <i class="fa fa-sign-in"></i>
            </a>
        </div>
    <?php endif ?>
</header>

<main class="main">
    <div class="part trans-1">
        <div class="content">
            <h2 class="is-size-3">
                <?= esc(lang('Content.title.why-keepnote')) ?>
            </h2>

            <p class="is-size-6">
                <?= lang('Content.body.why-keepnote') ?>
            </p>
        </div>
    </div>

    <div class="part is-flex is-flex-wrap-wrap is-justify-content-space-around  is-align-items-center trans-1">
        <div class="box has-background-info has-text-white">
            <h3 class="title has-text-white">
                <?= esc(lang('Content.boxes.title.share-notes')) ?>
                <i class="fa fa-link"></i>
            </h3>
            <p>
                <?= esc(lang('Content.boxes.body.share-notes')) ?>
            </p>
        </div>

        <div class="box has-background-grey-dark has-text-white">
            <h3 class="title has-text-white">
                <?= esc(lang('Content.boxes.title.download-notes')) ?>
                <i class="fa fa-download"></i>
            </h3>
            <p>
                <?= esc(lang('Content.boxes.body.download-notes')) ?>
            </p>
        </div>
    </div>

    <div class="part trans-1">
        <div class="content">
            <h2 class="is-size-3">
                <?= esc(lang('Content.title.keepnote-features')) ?>
            </h2>

            <p class="is-size-6">
                <?= lang('Content.body.keepnote-features') ?>
            </p>

            <?php if (! isSignedIn()): ?>
                <a href="<?= esc(route_to("signup"), "attr") ?>" class="button is-warning is-bold is-rounded is-medium">
                    <?= esc(lang('Button.signup')) ?>
                </a>
            <?php endif ?>
        </div>
    </div>

    <?= $this->include('parts/contact-us') ?>
</main>

<?= $this->include("parts/footer") ?>