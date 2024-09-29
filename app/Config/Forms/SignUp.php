<?php

namespace Config\Forms;

use CodeIgniter\Config\BaseConfig;

class SignUp extends BaseConfig
{
    /**
     * The form's method (GET, POST, PUT, DELETE...)
     */
    public string $method = 'POST';

    /**
     * The form's action
     */
    public string $action = '';

    /**
     * If set to 'true', the form implement the remember_me feature... 
     */
    public bool $handleRememberMe = true;

    /**
     * The id attribute of the form
     */
    public ?string $id = "signup_form";

    /**
     * If set to true, the browser will automatically fill the form
     * after the reload
     */
    public bool $autocomplete = false;

    /**
     *  If set to true, the form will get the enctype='multipart/form-data' key value as attribute
     */
    public bool $sendFile = false;

    /**
     * The form description...
     */
    public string $description = "";

    /**
     * Others attributes of the form
     */
    public array $formAttrs = 
    [
        "accept-charset" => "utf-8",
        "class" => "container box responsive-container center-1"
    ];

    public array $formInputs = 
    [
        "username" => 
        [
            "type" => "text",
            "placeholder" => "JohnDoe",
            "title" => "%s",
            "id" => "username",
            "required" => "required",
            "class" => "input is-warning",
            "minlength" => 3,
            "maxlength" => 24
        ],

        "password" => 
        [
            "type" => "password",
            "placeholder" => "Password",
            "title" => "Password...",
            "name" => "password",
            "id" => "password",
            "required" => "required",
            "class" => "input is-warning",
            "minlength" => 6,
            "maxlength" => 24
        ],

        "password_confirmation" => 
        [
            "type" => "password",
            "title" => "Password Confirmation...",
            "name" => "password_confirmation",
            "id" => "password_confirmation",
            "required" => "required",
            "class" => "input is-warning",
            "minlength" => 6,
            "maxlength" => 24
        ]
    ];

    public array $formLabels =
    [
        "username" =>
        [
            "text" => "Username",
            "for" => "username",
            "class" => "label"
        ],

        "password" =>
        [
            "text" => "Password",
            "for" => "password",
            "class" => "label"
        ],

        "password_confirmation" =>
        [
            "text" => "Password Confirmation",
            "for" => "password_confirmation",
            "class" => "label"
        ],

        "another" =>
        [
            "text" => "%s"
        ]
    ];

    public array $formInputIcons =
    [
        "username" => "fa-user",
        "password" => "fa-key",
        "password_confirmation" => "fa-key"
    ];

    public array $submitButton =
    [
        "class" => "button mb-1 is-warning is-rounded is-strong is-fullwidth",
        "type" => "submit",
        "id" => "submit",
        "text" => "%s", 
        "disabled" => "disabled"
    ];

    public array $anotherButton =
    [
        "class" => "button is-light is-warning is-bold capitalize is-rounded is-fullwidth",
        "type" => "button",
        "id" => "another_sign_btn",
        "text" => "%s"
    ];

    public function __construct() 
    {
        parent::__construct();

        $this->setLang();
        $this->action = route_to("auth.signup");
    }

    protected function setLang(): void
    {
        helper("text");

        $this->submitButton["text"] = lang('Button.signup');
        $this->anotherButton["text"] = lang('Button.signin');

        $this->formLabels["another"]["text"] = lang('Label.signin');
        $this->formLabels["username"]["text"] = lang('Button.username');
        $this->formLabels["password"]["text"] = lang('Button.password');
        $this->formLabels["password_confirmation"]["text"] = lang('Button.password_confirmation');

        $this->formInputs["username"]["placeholder"] = lang('Button.username');
        $this->formInputs["password"]["placeholder"] = lang('Button.password');
        $this->formInputs["password_confirmation"]["placeholder"] = lang('Button.password_confirmation');

        $this->formInputs["username"]["title"] = lang("Button.username");
        $this->formInputs["password"]["title"] = lang("Button.password");
        $this->formInputs["password_confirmation"]["title"] = lang("Button.password_confirmation");

        $this->description = lang('Header.body.signup');

    }
}
