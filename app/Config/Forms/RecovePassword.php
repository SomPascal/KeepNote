<?php

namespace Config\Forms;

use CodeIgniter\Config\BaseConfig;

class RecovePassword extends BaseConfig
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
    public ?string $id = "recove_password_form";

    /**
     * If set to true, the browser will automatically fill the form
     * after the reload
     */
    public bool $autocomplete = true;

    /**
     *  If set to true, the form will get the enctype='multipart/form-data' key value as attribute
     */
    public bool $sendFile = false;

    public ?string $description = "%s";
    
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
        "question" => 
        [
            "type" => "text",
            "placeholder" => "For example: Which is your favorite food ?",
            "title" => "Question",
            "name" => "question",
            "id" => "question",
            "required" => "required",
            "class" => "input is-warning",
            "minlength" => 3,
            "maxlength" => 350
        ],

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
        ],
    ];

    public array $formLabels =
    [
        "question" =>
        [
            "text" => "Question",
            "for" => "question",
            "class" => "label"
        ],

        "answer" =>
        [
            "text" => "Answer",
            "for" => "answer",
            "class" => "label"
        ]
    ];

    public array $formInputIcons =
    [
        "question" => "fa-question-circle",
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
        $this->action = route_to("login-help.r-p");
        $this->anotherButton["onclick"] = "window.location='/'";
    }

    protected function setLang(): void
    {
        helper("text");

        $this->submitButton["text"] = lang('Button.continue');
        $this->anotherButton["text"] = lang('Button.skip');

        $this->formLabels["question"]["text"] = lang('Button.question');
        $this->formLabels["answer"]["text"] = lang('Button.answer');

        $this->formInputs["question"]["placeholder"] = lang('Placeholder.question');
        $this->formInputs["answer"]["placeholder"] = lang('Placeholder.answer');

        $this->formInputs["question"]["text"] = lang('Placeholder.question');
        $this->formInputs["answer"]["text"] = lang('Placeholder.answer');

        $this->description = lang('Header.body.password-recovery');
    }
}
