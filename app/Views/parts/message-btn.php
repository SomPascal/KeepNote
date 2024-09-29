<?php helper('auth') ?>

<?php if (isset($anchor)): ?>
    <?= $anchor ?>
<?php else: ?>
    <div class="buttons">
        <?php if (isSignedIn()): ?>
            <a href="<?= route_to('account.home', $user->username()) ?>" class="button is-warning is-medium is-rounded">
                <?= lang('Button.open-kn') ?>
            </a>

            <a href="/" class="button is-warning is-light is-medium is-rounded">
                <?= lang('Button.home') ?>
                <i class="fa fa-home"></i>
            </a>
        <?php else: ?>
            <a href="/" class="button is-warning is-medium is-rounded">
                <?= lang('Button.home') ?>
                <i class="fa fa-home"></i>
            </a>

            <a href="<?= route_to('signup') ?>" class="button is-warning is-light is-medium is-rounded">
                <?= lang('Button.signup') ?>
            </a>
        <?php endif ?>
    </div>
<?php endif ?>