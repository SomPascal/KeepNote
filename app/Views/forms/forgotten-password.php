<?= form_open("", ["id" => "forgot_password_form", "method" => "POST", "autocomplete" => "off", "accept-charset" => "utf8", "class" => "container box responsive-container center-1"]) ?>
    <h2 class="mb-1 capitalize">Forgotten password</h2>

    <div class="field">
        <div class="notification is-danger is-light hide">
            <p></p>
            <button type="button" class="delete"></button>
        </div>
    </div>

    <p class="help mb-1">
        When you were signing up, you configured thea question-answer password recovery. Right now you should answer the question you saved
    </p>

    <div class="field">
        <label>
            Question :
        </label>
        <div class="h4">
            What's your favorite food
            <i class="fa fa-question-circle"></i>
        </div>
    </div>

    <div class="field">
        <label for="anwser" class="label">
            Answer
        </label>

        <p class="control has-icons-left">
            <input class="input is-warning" 
                type="text" 
                placeholder="Example: My fav..." 
                class title="Answer" 
                id="answer"
                minlength="3" 
                maxlength="150" 
                required="required"
            />

            <span class="icon is-small is-left">
                <i class="fa fa-check-circle"></i>
            </span>
        </p>
    </div>

    <div class="field">
        <button type="submit" class="button is-warning is-rounded is-bold is-fullwidth">
            Continue
        </button>
    </div>

    <div class="field">
        <a href="<?= esc(route_to("signup"), "attr") ?>" class="button is-warning is-light is-rounded is-bold is-fullwidth">
            Cancel
            <i class="fa fa-times-circle"></i>
        </a>
    </div>

<?= form_close() ?>

<script src="assets/libs/validate.js/validate.min.js"></script>
<script src="assets/js/script.js"></script>