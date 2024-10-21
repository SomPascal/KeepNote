<?php

namespace Config\Forms;

use CodeIgniter\Config\BaseConfig;

class SignIn extends BaseConfig
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
    public ?string $id = "signin_form";

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
            "required" => "required",
            "id" => "username",
            "type" => "text",
            "placeholder" => "JohnDoe",
            "title" => "%s",
            "minlength" => 3,
            "maxlength" => 24,
            "class" => "input is-warning"
        ],

        "password" =>
        [
            "required" => "required",
            "id" => "password",
            "type" => "password",
            "placeholder" => "%s",
            "minlength" => 6,
            "maxlength" => 24,
            "class" => "input is-warning"
        ]
    ];

    public array $formLabels =
    [
        "username" =>
        [
            "text" => "%s",
            "for" => "username",
            "class" => "label"
        ],

        "password" =>
        [
            "text" => "Password",
            "for" => "password",
            "class" => "label"
        ],
    ];

    public array $formInputIcons =
    [
        "username" => "fa-user",
        "password" => "fa-key"
    ];

    public array $submitButton =
    [
        "class" => "button mb-1 is-warning is-rounded is-strong is-fullwidth",
        "type" => "submit",
        "id" => "submit",
        "text" => "%s"
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

        $this->action = route_to("auth.signin");
        $this->setLang();
    }

    /**
     * Internationalize some form's attributes
     */
    protected function setLang(): void
    {
        $this->submitButton["text"] = lang('Button.signin');
        $this->anotherButton["text"] = lang('Button.signup');

        $this->formLabels["another"]["text"] = lang('Label.signup');
        $this->formLabels["username"]["text"] = lang('Button.username');
        $this->formLabels["password"]["text"] = lang('Button.password');
        
        $this->formInputs["username"]["title"] = lang("Button.username");
        $this->formInputs["password"]["title"] = lang("Button.pasword");
        $this->formInputs["password"]["placeholder"] = lang('Button.password');
    }
}
