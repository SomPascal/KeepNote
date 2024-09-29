<?php if (isSignedIn() || url_is("/")): ?>
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="<?= esc(($header->icon_url) ?? "/") ?>">
                <img src="/assets/img/icons/keepnote-logo.png" width="25" height="50">
            </a>

            <h2 class="navbar-item is-strong fs-3">
                <?= esc(config("Config\App")->appName) ?>
            </h2>

            <?php if (isSignedIn() && url_is(str_replace(" ", "%20", route_to("account.home", $user->username)))): ?>
                <form class="w-100" id="search_form" autocomplete="on">
                    <div class="navbar-item field">
                        <div class="control has-icons-left has-icons-right is-medium">
                            <input type="text" placeholder="<?= esc(lang('Placeholder.find-notes'), 'attr') ?>" class="input is-warning is-medium is-rounded w-100 trans-1" id="search_notes" required="required">

                            <span class="icon is-medium is-left">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
            <?php endif ?>
            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample" id="navbar_burger">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu">

        <div class="navbar-end">
            <?php if (url_is("/")): ?>
                <a href="/" class="navbar-item is-bold">
                    <?= esc(lang('Button.home')) ?>
                    <i class="fa fa-home"></i>
                </a>

                <a href="#contact-us" class="navbar-item is-bold">
                    <?= esc(lang('Button.contact-us')) ?>
                </a>
            <?php endif ?>

            <div class="navbar-item buttons">
                <?php if (! isSignedIn()): ?>
                    <a href="<?= route_to("signup") ?>" class=" is-bold button is-rounded is-warning">
                        <?= esc(lang('Button.signup')) ?>
                    </a>

                    <a href="<?= route_to("signin") ?>" class="is-bold button is-rounded is-warning is-light">
                        <?= esc(lang('Button.signin')) ?>
                        <i class="fa fa-sign-in"></i>
                    </a>
                <?php endif ?>

                <a href="<?= route_to("goto", "email") ?>" class=" is-bold button is-rounded is-black is-light">
                    <?= esc(lang('Button.email-us')) ?>
                    <i class="fa fa-envelope"></i>
                </a>
            </div>


            <div class="navbar-item is-warning has-dropdown is-hoverable is-strong mr-2">
                <?php if (isSignedIn()): ?>
                    <a class="navbar-link">
                        <div class="user-profile is-uppercase" style="background-color: <?= esc($user->color, "attr") ?>;">
                            <?= esc($user->username[0]) ?>
                        </div>
                        <p class="is-uppercase">
                            <?= esc($user->username) ?>
                        </p>
                    </a>

                    <div class="navbar-dropdown">

                        <a href="/" class="navbar-item">
                            <?= esc(lang('Button.home')) ?>
                            <i class="fa fa-home"></i>
                        </a>
                        <hr class="navbar-divider">

                        <?php if (current_url() != url_to("account.home", $user->username)): ?>
                            <a href="<?= esc(url_to("account.home", $user->username), "attr") ?>" class="navbar-item">
                                <?= esc(lang('Button.open-kn')) ?>
                                <i class="fa fa-book"></i>
                            </a>
                            <hr class="navbar-divider">
                        <?php endif ?>

                        <a href="<?= esc(url_to("account.edit", $user->username), "attr") ?>" class="navbar-item">
                            <?= esc(lang('Button.my-account')) ?>
                            <i class="fa fa-user-circle"></i>
                        </a>
                        <hr class="navbar-divider">

                        <a href="/signout" class="navbar-item bg-warning">
                            <?= esc(lang('Button.signout')) ?>
                            <i class="fa fa-sign-out"></i>
                        </a>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    </nav>
<?php endif ?>