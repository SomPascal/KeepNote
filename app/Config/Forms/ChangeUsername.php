<?php

namespace Config\Forms;

use CodeIgniter\Config\BaseConfig;

class ChangeUsername extends BaseConfig
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
    public ?string $id = "change_username_form";

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
        "new_username" => 
        [
            "type" => "text",
            "placeholder" => "JohnDoe",
            "title" => "%s",
            "id" => "new_username",
            "required" => "required",
            "class" => "input is-warning",
            "minlength" => 3,
            "maxlength" => 24
        ],

        "password" => 
        [
            "type" => "password",
            "placeholder" => "Password",
            "title" => "%s",
            "id" => "password",
            "required" => "required",
            "class" => "input is-warning",
            "minlength" => 6,
            "maxlength" => 24
        ]
    ];

    public array $formLabels =
    [
        "new_username" =>
        [
            "text" => "%s",
            "for" => "new_username",
            "class" => "label"
        ],

        "password" =>
        [
            "text" => "%s",
            "for" => "password",
            "class" => "label"
        ],
        "another" => [ "text" => "%s" ]
    ];

    public array $formInputIcons =
    [
        "new_username" => "fa-user",
        "password" => "fa-key",
    ];

    public array $submitButton =
    [
        "class" => "button mb-1 is-warning is-rounded is-strong is-fullwidth",
        "type" => "submit",
        "id" => "submit",
        "text" => "%s",
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
        $this->action = route_to("auth.change_username");
        $this->setLang();
    }

    protected function setLang(): void
    {
        $this->formLabels["new_username"]["text"] = lang('Button.new-username');
        $this->formLabels["password"]["text"] = lang('Button.password');
        $this->formLabels["another"]["text"] = lang('Label.no-change-username');

        $this->submitButton["text"] = lang('Button.change-username');
        $this->anotherButton["text"] = lang('Button.cancel');
        $this->anotherButton["onclick"] = sprintf("window.location='%s'", route_to("account.edit", session("user")["username"]));

        $this->formInputs["new_username"]["placeholder"] = lang('Button.new-username');
        $this->formInputs["password"]["placeholder"] = lang('Button.password');

        $this->formInputs["new_username"]["title"] = lang("Button.username");
        $this->formInputs["password"]["title"] = lang("Button.password");
    }
}
