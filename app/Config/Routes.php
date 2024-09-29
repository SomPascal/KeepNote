<?php

use App\Controllers\AccountController;
use App\Controllers\AccountHomeController;
use App\Controllers\AuthController;
use App\Controllers\CronJobController;
use App\Controllers\DraftController;
use App\Controllers\ErrorHandlerController;
use App\Controllers\LoginHelpController;
use App\Controllers\NoteController;
use App\Controllers\RedirectController;
use App\Controllers\SignOutController;
use App\Filters\Validator\RecordPasswordHelperFilter;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
*/

$routes->addPlaceholder("username", "[a-zA-Z][a-zA-Z0-9_ ]{2,23}+");
$routes->addPlaceholder("id", "[a-zA-Z0-9]{8,8}+");
$routes->addPlaceholder("long_id", "[a-zA-Z0-9]{16,16}+");

$routes->environment("development", static function(RouteCollection $routes) 
{
    $routes->group("draft", static function(RouteCollection $routes)
    {
        $routes->get("/", [DraftController::class, "index"]);
        $routes->get("cookie", [DraftController::class, "cookie"]);
    });
});

$routes->get("/", [AccountController::class, "index"], ["as" => "home"]);
$routes->get("signin", [AccountController::class, "signin"], ["as" => "signin"]);
$routes->get("signup", [AccountController::class, "signup"], ["as" => "signup"]);
$routes->get("signout", [SignOutController::class, "signout"], ["as" => "signout"]);

$routes->group("account", static function(RouteCollection $routes)
{
    $routes->get("edit/(:username)", [AccountController::class, "edit_account"], ["as" => "account.edit"]);
    // $routes->get("change-password", [AccountController::class, "change_password"], ["as" => "account.change_password"]);
    $routes->get("change-username", [AccountController::class, "change_username"], ["as" => "account.change_username"]);
    $routes->get("(:username)", [AccountHomeController::class, "home"], ["as" => "account.home"]);
    $routes->post("delete", [AccountController::class, "delete"], ["as" => "account.delete"]);
});

// To create, update and delete users's notes...
$routes->group("note", static function(RouteCollection $routes)
{
    $routes->post("create", [NoteController::class, "create"], ["as" => "note.create"]);
    $routes->post("update", [NoteController::class, "update"], ["as" => "note.update"]);
    $routes->post("import", [NoteController::class, "import"], ["as" => "note.import"]);

    $routes->get("get", [NoteController::class, "get"], ["as" => "note.get"]);
    $routes->get("share/get-link", [NoteController::class, "get_share_link"], ["as" => "note.share.link"]);
    $routes->get("share/use-link/(:id)/(:long_id)", [NoteController::class, "use_share_link"], ["as" => "note.share.use"]);

    $routes->get("active/download", [NoteController::class, "active_download"], ["as" => "note.active.download"]);
    $routes->get("download/(:id)", [NoteController::class, "download"], ["as" => "note.download"]);
    $routes->post("delete", [NoteController::class, "delete"], ["as" => "note.delete"]);
});

// To handle connections to the notes's account
$routes->group("auth", static function(RouteCollection $routes)
{
    $routes->post("signin", [AuthController::class, "signin"], ["as" => "auth.signin"]);
    $routes->post("signup", [AuthController::class, "signup"], ["as" => "auth.signup"]);
    $routes->post("change-username", [AuthController::class, "change_username"], ["as" => "auth.change_username"]);
    $routes->post("change-password", [AuthController::class, "change_password"], ["as" => "auth.change_password"]);
});

$routes->group("cron-job", static function (RouteCollection $routes)
{
    $routes->add("clear-cache", [CronJobController::class, "clear_cache"]);
});

$routes->get("goto/(:alpha)", [RedirectController::class, "goto"], ["as" => "goto"]);

// Show terms of use
$routes->get("terms-of-use", [AccountController::class, "terms_of_use"], ["as" => "t-o-u"]);

// Show error message
$routes->get("error", [ErrorHandlerController::class, "handler"], ["as" => "error"]);