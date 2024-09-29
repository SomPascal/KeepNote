<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\User;
use App\Libraries\Form;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Response;

class AccountHomeController extends BaseController
{
    protected $helpers = ["form"];

    protected array $scripts = 
    [
        "i18n.js",
        "script.js", 
        "Note.class.js",
        "search/searchEngine.js", 
        "search/search-bar.js", 
        "responsive.js",
        "home.js",
        "append-notes.js"
    ];

    protected array $styles = 
    [
        "utilities.css", 
        "style.css", 
        "home.css"
    ];

    public function home(string $username): Response
    {
        $user = User::setAll(session("user"));
        if ($username !== $user->username()) throw new PageNotFoundException();
        
        $forms = new \stdClass();

        $forms->download = Form::create([
            "action" => url_to("note.active.download"),
            "method" => "GET",
            "auto-complete" => "off"
        ]);

        $forms->download->radio("json", [
            "type" => "radio",
            "name" => "config",
            "value" => "json",
            "required" => "required"
        ], ["text" => "<p>JSON</p>", "class" => "button is-warning is-rounded is-bold is-light"]);

        $forms->download->radio("xml", [
            "type" => "radio",
            "name" => "config",
            "value" => "xml",
            "required" => "required"
        ], ["text" => "<p>XML</p>", "class" => "button is-warning is-rounded is-bold is-light"]);

        $forms->download->submit("submit", 
        [
            "text" => lang('Button.download'),
            "id" => "submit-download",
            "class" => "button is-warning is-bold is-fullwidth is-rounded",
            "disabled" => "disabled"
        ]);

        $forms->download->button("cancel", [
            "text" => sprintf("%s <i class='fa fa-times-circle'></i>", lang('Button.cancel')),
            "id" => "cancel-download",
            "class" => "button is-warning is-light is-bold is-fullwidth is-rounded"
        ]);
        $header = new \stdClass(); 
        $head = new \stdClass();

        $header->icon_url = route_to("account.home", $user->username());
        // dd($header);

        $head->title = $user->username;
        $head->description = lang("Desc.account-home");
        $head->styles = $this->styles;

        $view = view("home", 
        [
            "head" => $head,
            "header" => $header,

            "user" => $user,
            "forms" => $forms,
            "scripts" => $this->scripts
        ]);

        return $this->response->setBody($view);
    }
}
