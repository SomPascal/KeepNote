<?php 

/**
 * @var CodeIgniter\Entities\User $user The user
 */
?>
<?= $this->include("parts/head") ?>
<?= $this->include("parts/navbar") ?>

<section class="container box responsive-container mt-2 trans-1">
    <div class="center-2 is-flex-direction-column">
        <div class="center-2 is-flex-direction-column">
            <p class="user-profile-big is-uppercase title" style="background-color: <?= $user->color() ?>;">
                <?= esc($user->username()[0]) ?>
            </p>
            <h2 class="is-size-2"><?= esc($user->username()) ?></h2>
        </div>

        <div class="content is-large">
            <ul>
                <li class="field">
                    <a href="<?= site_url() ?>" class="is-black is-light is-bold is-fullwidth trans-1">
                        <?= lang('Button.home') ?>
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li class="field">
                    <a href="<?= route_to("account.home", $user->username()) ?>" class="is-black is-light is-bold is-fullwidth trans-1">
                        <?= lang('Button.open-kn') ?>
                    </a>
                </li>

                <li class="field">
                    <a href="<?= route_to("account.change_username") ?>" class="is-dark is-light is-bold trans-1 is-fullwidth">
                        <?= lang('Button.change-username') ?>
                        <i class="fa fa-user"></i>
                    </a>
                </li>

                <!-- <li class="field">
                    <a href="<?=''/* route_to("account.change_password") */?>" class="is-dark is-light is-bold trans-1 is-fullwidth">
                        <?=''/* lang('Button.change-password')*/ ?>
                        <i class="fa fa-key"></i>
                    </a>
                </li>
                -->

                <li class="field">
                    <a href="<?= route_to("signout") ?>" class="is-dark is-light is-bold trans-1 is-fullwidth">
                        <?= lang('Button.signout') ?>
                        <i class="fa fa-sign-out"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</section>

<?= $this->include("parts/footer") ?>