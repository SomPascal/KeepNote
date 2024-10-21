<?php

namespace Config\Forms;

use CodeIgniter\Config\BaseConfig;

class ForgotPassword extends BaseConfig
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
    public ?string $id = "forgot_password_form";

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
    public ?string $description = "%s";

    public array $formAttrs = 
    [
        "accept-charset" => "utf-8",
        "class" => "container box responsive-container center-1"
    ];

    public array $formInputs = 
    [
        "answer" => 
        [
            "type" => "text",
            "placeholder" => "For example: My favorite food is Mbongo'o",
            "title" => "Question's answer...",
            "name" => "answer",
            "id" => "answer",
            "required" => "required",
            "class" => "input is-warning",
            "minlength" => 3,
            "maxlength" => 150
        ]
    ];

    public array $formLabels =
    [
        "answer" =>
        [
            "text" => "Answer",
            "for" => "answer",
            "class" => "label"
        ]
    ];

    public array $formInputIcons =
    [
        "answer" => "fa-check-circle"
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
        "text" => "%s", 
        "onclick" => "%s"
    ];

    public function __construct()
    {
        parent::__construct();

        $this->setLang();
    }

    protected function setLang(): void
    {
        $this->submitButton["text"] = lang('Button.continue');
        $this->anotherButton["text"] = lang('Button.cancel');
        $this->anotherButton["onclick"] = sprintf("window.location='%s'", route_to("account.edit", session("user")["username"]));

        $this->formLabels["answer"]["text"] = lang('Button.answer');
        $this->formInputs["answer"]["placeholder"] = lang('Placeholder.answer');

        $this->formLabels["answer"]["title"] = lang('Button.answer');
        $this->formInputs["answer"]["title"] = lang('Placeholder.answer');

        $this->description = lang('Header.body.forgot-password');
    }
}
