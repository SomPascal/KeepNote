<?php

namespace Config\Forms;

use CodeIgniter\Config\BaseConfig;

class ChangePassword extends BaseConfig
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
    public bool $handleRememberMe = false;

    /**
     * The id attribute of the form
     */
    public ?string $id = "change_password_form";

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
        "current_password" => 
        [
            "type" => "text",
            "placeholder" => "%s",
            "title" => "%s",
            "id" => "current_password",
            "required" => "required",
            "class" => "input is-warning",
            "minlength" => 3,
            "maxlength" => 24
        ],

        "new_password" => 
        [
            "type" => "password",
            "placeholder" => "%s",
            "title" => "%s",
            "id" => "new_password",
            "required" => "required",
            "class" => "input is-warning",
            "minlength" => 6,
            "maxlength" => 24
        ],

        "new_password_confirmation" => 
        [
            "type" => "password",
            "placeholder" => "%s",
            "title" => "%s",
            "id" => "new_password_confirmation",
            "required" => "required",
            "class" => "input is-warning",
            "minlength" => 6,
            "maxlength" => 24
        ]
    ];

    public array $formLabels =
    [
        "current_password" =>
        [
            "text" => "%s",
            "for" => "current_password",
            "class" => "label"
        ],

        "new_password" =>
        [
            "text" => "%s",
            "for" => "new_password",
            "class" => "label"
        ],

        "new_password_confirmation" =>
        [
            "text" => "%s",
            "for" => "new_password_confirmation",
            "class" => "label"
        ],
        "another" => [ "text" => "%s" ]
    ];

    public array $submitButton =
    [
        "class" => "button mb-1 is-warning is-rounded is-strong is-fullwidth",
        "type" => "submit",
        "id" => "submit",
        "text" => "%s",
    ];

    public array $formInputIcons =
    [
        "current_password" => "fa-key",
        "new_password" => "fa-key",
        "new_password_confirmation" => "fa-key"
    ];

    public array $anotherButton =
    [
        "class" => "button is-light is-warning is-bold capitalize is-rounded is-fullwidth",
        "type" => "button",
        "id" => "another_sign_btn",
        "text" => "%s",
        "onclick" => "%s"
    ];

    public function __construct() 
    {
        $this->action = route_to("auth.change_password");
        $this->setLang();
    }

    protected function setLang(): void
    {
        $this->formLabels["current_password"]["text"] = lang('Button.current-password');
        $this->formLabels["new_password"]["text"] = lang('Button.new-password');
        $this->formLabels["new_password_confirmation"]["text"] = lang('Button.new-password-confirmation');

        $this->formLabels["another"]["text"] = lang('Label.no-change-password');

        $this->submitButton["text"] = lang('Button.change-password');
        $this->anotherButton["text"] = lang('Button.cancel');
        $this->anotherButton["onclick"] = sprintf("window.location='%s'", session("user")["username"]);

        $this->formInputs["current_password"]["placeholder"] = lang('Button.current-password');
        $this->formInputs["new_password"]["placeholder"] = lang('Button.new-password');
        $this->formInputs["new_password_confirmation"]["placeholder"] = lang('Button.new-password-confirmation');

        $this->formInputs["current_password"]["title"] = lang("Button.current-password");
        $this->formInputs["new_password"]["title"] = lang("Button.new-password");
        $this->formInputs["new_password_confirmation"]["title"] = lang("Button.new-password-confirmation");
    }
}
