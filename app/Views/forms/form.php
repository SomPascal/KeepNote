<?php 
/**
 * @var App\Libraries\Form $form The form object
 */
?>

<?= $form->open() ?>
    <h2 class="mb-1 capitalize">
        <?= esc($header->title ?? "Form") ?>
    </h2>
    
    <div class="field">
        <div class="notification is-danger is-light hide">
            <p></p>
            <button class="delete" type="button"></button>
        </div>
    </div>

    <?php if (isset($form->description)): ?>
        <p class="help"><?= esc($form->description) ?></p>
    <?php endif ?>

    <?php foreach($form->inputs() as $key => $input): ?>
        <div class="field">
            <?= $form->label($key) ?>

            <div class="control has-icons-left">
                <?= $input ?>

                <span class="icon is-small is-left">
                    <i class="fa <?= $input->icon ?>"></i>
                </span>
            </div>
            <p class="help is-danger"></p>
        </div>
    <?php endforeach ?>


    <?php if($form->attrs?->id == "signup_form"): ?>
        <label>
            <input type="checkbox" id="agree_terms">
            <?= lang('Label.signup-terms', ['link' => route_to('t-o-u')]) ?>
        </label>
    <?php endif ?>

    <br>
    
    <?php if($form->remember_me): ?>
        <label>
            <input type="checkbox" id="remember_me" checked>
            <?= esc(lang('Button.remember-me')) ?>
        </label>
    <?php endif ?>

    <div class="field">
        <?= $form->button("submit") ?>
    </div>
    
    <?php if ($form->button("another") !== null): ?>
        <div class="field mt-1">
            <p><?= @$form->label("another") ?? "" ?></p>
            
            <?= $form->button("another") ?>
        </div>
    <?php endif ?>
<?= $form->close() ?>

<!-- script js  -->
<script src="/assets/libs/validate.js/validate.min.js"></script>
<?= $this->include("parts/footer") ?>