<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\User;
use App\Libraries\Form;
use App\Models\NoteModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Response;
use Config\Forms\ChangePassword;
use Config\Forms\ChangeUsername;
use Config\Forms\SignIn as SignInForm;
use Config\Forms\SignUp as SignUpForm;
use Config\Services;
use Config\Throttler;

class AccountController extends BaseController
{

    protected $helpers = ["form", "html", "text", "auth"];

    protected array $sign_form_attr = 
    [
        "method" => "POST",
        "class" => "container box responsive-container center-1",
        "autocomplete" => "of",
        "accept-charset" => "utf-8"
    ];

    protected array $submit_btn = 
    [
        "class" => "button mb-1 is-warning is-rounded is-strong is-fullwidth",
        "type" => "submit",
        "id" => "submit"
    ];

    protected array $another_btn = [
        "class" => "button is-light is-warning is-bold capitalize is-rounded is-fullwidth",
        "type" => "button",
        "id" => "another_sign_btn"
    ];

    protected $change_username_inputs = 
    [
        "new_username" => 
        [
            "attr" => 
            [
                "type" => "text",
                "placeholder" => "JohnDoe",
                "title" => "New Username",
                "id" => "new_username",
                "class" => "input is-warning",
                "minlength" => 3,
                "maxlength" => 24,
                "required" => "required"
            ],
            "label" => 
            [
                "text" => "New Username",
                "for" => "new_username",
                "class" => "label"
            ],
            "icon" => "fa-user"
        ],
        "password" => 
        [
            "attr" => 
            [
                "type" => "password",
                "placeholder" => "Password",
                "title" => "Your Password...",
                "name" => "password",
                "id" => "password",
                "required" => "required",
                "class" => "input is-warning",
                "minlength" => 6,
                "maxlength" => 24
            ],
            "label" => [
                "text" => "Password",
                "for" => "password",
                "class" => "label"
            ],
            "icon" => "fa-key"
        ]
    ];

    protected $change_pass_inputs = 
    [
        "current_password" => 
        [
            "attr" => 
            [
                "type" => "password",
                "placeholder" => "Current Password",
                "title" => "Current Password...",
                "name" => "current_password",
                "id" => "current_password",
                "required" => "required",
                "class" => "input is-warning",
                "minlength" => 6,
                "maxlength" => 24
            ],
            "label" => 
            [
                "text" => "Current Password",
                "for" => "current_password",
                "class" => "label"
            ],
            "icon" => "fa-key"
        ],
        "new_password" => 
        [
            "attr" => 
            [
                "type" => "password",
                "placeholder" => "New Password",
                "title" => "New Password...",
                "name" => "new_password",
                "id" => "password",
                "required" => "required",
                "class" => "input is-warning",
                "minlength" => 6,
                "maxlength" => 24
            ],
            "label" => 
            [
                "text" => "New Password",
                "for" => "password",
                "class" => "label"
            ],
            "icon" => "fa-key"
        ],
        "new_password_confirm" => 
        [
            "attr" => 
            [
                "type" => "password",
                "placeholder" => "New Password Confirmation",
                "title" => "New Password Confirmation...",
                "name" => "new_password_confirmation",
                "id" => "password_confirmation",
                "required" => "required",
                "class" => "input is-warning",
                "minlength" => 6,
                "maxlength" => 24
            ],
            "label" => 
            [
                "text" => "New Password Confirmation",
                "for" => "password_confirmation",
                "class" => "label"
            ],
            "icon" => "fa-key"
        ]
    ];

    protected $signin_inputs = 
    [
        "username" => 
        [
            "attr" => 
            [
                "type" => "text",
                "placeholder" => "JohnDoe",
                "title" => "Username",
                "name" => "username",
                "id" => "username",
                "required" => "required",
                "class" => "input is-warning",
                "minlength" => 3,
                "maxlength" => 24
            ],
            "label" => 
            [
                "text" => "Username",
                "for" => "username",
                "class" => "label"
            ],
            "icon" => "fa-user"
        ],
        "password" => 
        [
            "attr" => 
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
            "label" => 
            [
                "text" => "Password",
                "for" => "password",
                "class" => "label"
            ],
            "icon" => "fa-key"
        ]
    ];

    protected $signup_inputs =
    [
        "username" =>
        [
            "attr" => 
            [
                "type" => "text",
                "placeholder" => "JohnDoe",
                "title" => "Username",
                "name" => "username",
                "id" => "username",
                "required" => "required",
                "class" => "input is-warning",
                "minlength" => 3,
                "maxlength" => 24
            ],
            "label" => 
            [
                "text" => "Username",
                "for" => "username",
                "class" => "label"
            ],
            "icon" => "fa-user"
        ],

        "password" =>
        [
            "attr" => 
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
            "label" => 
            [
                "text" => "Password",
                "for" => "password",
                "class" => "label"
            ],
            "icon" => "fa-key"
        ],
        "password_confirmation" =>
        [
            "attr" =>
            [
                "type" => "password",
                "placeholder" => "Password Confirmation",
                "title" => "Password Confirmation...",
                "name" => "password_confirmation",
                "id" => "password_confirmation",
                "required" => "required",
                "class" => "input is-warning",
                "minlength" => 6,
                "maxlength" => 24
            ],
            "label" => 
            [
                "text" => "Password Confirmation",
                "for" => "password_confirmation",
                "class" => "label"
            ],
            "icon" => "fa-key"
        ]
    ];

    public function index(): Response
    {
        $head = new \stdClass();
        $head->styles = ["utilities.css", "style.css", "home.css", "index.css"];
        $head->title = lang('Button.home');
        $head->description = lang("Desc.home");

        $header = new \stdClass();
        $header->icon_url = "/";

        $user = null;
        $cache = ["cache" => 5*MINUTE, "cache_name" => sprintf("index.visitor.%s.cache", $this->request->getLocale())];
        
        if (isSignedIn())
        {
            $user = User::setAll(session("user"));
            $header->icon_url = route_to("account.home", $user->username());

            $cache = [];
        }

        $view = view("index", [
            "locale" => $this->request->getLocale(),
            "head" => $head,
            "header" => $header,

            "user" => $user,
            "scripts" => ["script.js", "responsive.js"]
        ], $cache);

        return $this->response->setBody($view);
    }

    public function home($username)
    {   
        $user = User::setAll(session()->get("user"));
        if ($username != $user->username()) throw new PageNotFoundException();
        
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

        $head->title = $user->username;
        $head->description = lang("Desc.account-home");
        $head->styles = ["utilities.css", "style.css", "home.css"];

        $cacher = Services::cache();
        $cache_key = sprintf("notes.usuer.%s.cache", md5($user->id()));

        $throttler = Services::throttler();
        $config = (object) (new Throttler())->get_notes;

        return view("home", 
        [
            "head" => $head,
            "header" => $header,

            "user" => $user,
            "forms" => $forms,
            "scripts" => ["script.js", "search/searchEngine.js", "search/search-bar.js", "responsive.js", "home.js"]
        ]);
    }

    public function terms_of_use(): Response
    {
        $session = session();

        $header = new \stdClass();
        $head = new \stdClass();
        $head->description = lang("Desc.t-o-u");
        $head->styles = ["utilities.css", "style.css"];

        $button = new \stdClass();

        $user = null;
        if ($session->has("user"))
        {
            $user = User::setAll($session->get("user"));
            $button->url = route_to("account.home", $user->username());
            $button->text = lang('Button.home');
        }
        else 
        {
            $button->url = route_to("signup");
            $button->text = lang('Button.signup');
        }

        $view = view("terms-of-use", 
        [
            "head" => $head,
            "header" => $header,
            "button" => $button,
            "user" => $user,
            "scripts" => ["responsive.js"]

        ], [
            "cache" => 5*MINUTE, 
            "cache_name" => sprintf("terms-of-use.%s.cache", $this->request->getLocale())
        ]);
        return $this->response->setBody($view);
    }

    public function edit_account(string $username): Response
    {
        $user = User::setAll(session("user"));

        if ($username != $user->username()) throw new PageNotFoundException();

        $head = new \stdClass();
        $header = new \stdClass();

        $head->title = lang('Button.my-account');
        $head->description = lang("Desc.edit-account");
        $head->color = "#ffe08a";
        $head->styles = ["utilities.css", "style.css", "home.css"];

        $header->title = lang("Header.title.edit-account");

        $view = view("edit-account", [
            "head" => $head,
            "header" => $header,
            "scripts" => ["script.js", "responsive.js"],
            "user" => $user
        ]);

        return $this->response->setBody($view);
    }

    public function signin(): Response
    {
        $head = new \stdClass();
        $head->title = lang('Button.signin');
        $head->description = lang("Desc.signin");
        $head->styles = ["style.css", "utilities.css"];

        $header = new \stdClass();
        $header->title = lang('Button.signin');

        $signin = Form::createFromConfig(new SignInForm());

        $cache = 
        [
            "cache" => 5*MINUTE,
            "cache_name" => sprintf("form/signin.%s.cache", $this->request->getLocale())
        ];

        $view = view("parts/head", ["head" => $head]) 
        .view("forms/form", 
        [
            "header" => $header,
            "form" => $signin,
            "scripts" => ["i18n.js", "script.js", "form/signin.js"],
        ], $cache);
        return $this->response->setBody($view);
    }

    public function signup(): Response
    {
        $head = new \stdClass();

        $head->title = lang('Button.signup');
        $head->description = lang("Desc.signup");
        $head->color = "#ffe08a";
        $head->styles = ["style.css", "utilities.css"];
        
        $header = new \stdClass();
        $header->title = lang('Button.signup');

        $signup = Form::createFromConfig(new SignUpForm());

        $cache = 
        [
            "cache" => 5*MINUTE,
            "cache_name" => sprintf("form/signup.%s.cache", $this->request->getLocale())
        ];

        $view = view("parts/head", ["head" => $head])
        . view("forms/form", 
        [
            "header" => $header,
            "scripts" => ["i18n.js", "script.js", "form/signup.js"],

            "form" => $signup
        ], $cache);

        return $this->response->setBody($view);
    }

    public function change_password(): Response
    {
        $head = new \stdClass();

        $head->title = lang('Button.change-password');
        $head->description = lang("Desc.change_password");
        $head->color = "#ffe08a";
        $head->styles = ["style.css", "utilities.css"];

        $header = new \stdClass();
        $header->title = lang('Button.change-password');

        $change_password = Form::create(array_merge(
            $this->sign_form_attr,
            ["id" => "change_password_form", "action" => route_to("auth.change_password")]
        ));
        $change_password = Form::createFromConfig(new ChangePassword());

        $cache = 
        [
            "cache" => 5*MINUTE,
            "cache_name" => sprintf("form/change-password.%s.cache", $this->request->getLocale())
        ];

        $view = view("parts/head", ["head" => $head])
        . view("forms/form", 
        [
            "header" => $header,
            "form" => $change_password,
            "scripts" => ["script.js", "form/change_password.js"]
        ], $cache);

        return $this->response->setBody($view);
    }

    public function change_username(): Response
    {
        $head = new \stdClass();

        $head->title = lang('Button.change-username');
        $head->description = lang("Desc.change_username");
        $head->color = "#ffe08a";
        $head->styles = ["style.css", "utilities.css"];

        $header = new \stdClass();
        $header->title = lang('Button.change-username');

        $change_username = Form::createFromConfig(new ChangeUsername());

        $cache = 
        [
            "cache" => 5*MINUTE,
            "cache_name" => sprintf("form/change-username.%s.cache", $this->request->getLocale())
        ];

        $view = 
        view("parts/head", ["head" => $head])
        . view(
            "forms/form", 
            [
                "header" => $header,
                "form" => $change_username,
                "scripts" => ["script.js", "form/change_username.js"]
            ], 
            $cache
        );

        return $this->response->setBody($view);
    }
}