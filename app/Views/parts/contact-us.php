<div class="part">
    <div class="content">
        <h2 class="is-size-3" id="contact-us">
            <?= lang('Content.title.contact-us') ?>
        </h2>

        <p class="is-size-6">
            <?= lang('Content.body.contact-us') ?>
        </p>
    </div>
</div>

<div class="part is-flex is-flex-wrap-wrap is-justify-content-space-around  is-align-items-center">
    <div class="box has-background-success has-text-white">
        <h3 class="title has-text-white">
            WhatsApp 
            <i class="fa-brands fa-whatsapp"></i>
        </h3>
        <button onclick="window.open('<?= route_to('goto', 'whatsapp') ?>');" class="button is-black is-light is-bold is-rounded is-fullwidth">
            <?= lang('Button.wa-us') ?>
        </button>
    </div>

    <div class="box has-background-dark has-text-white">
        <h3 class="title has-text-white">
            Email
            <i class="fa fa-envelope"></i>
        </h3>
        <button onclick="window.open('<?= route_to('goto', 'email') ?>');" class="button is-black is-light is-bold is-rounded is-fullwidth">
            <?= lang('Button.email-us') ?>
        </button>
    </div>
</div>