<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\URI;
use Config\Services;

class DraftController extends BaseController
{
    /**
     * @var array $helpers DraftController allowed helpers
     */
    protected $helpers = ["form", "auth", "text", "cookie"];

    public function index()
    {
        dd(Services::email()->setFrom("rubenndjengwes@gmail.com", "ruben")
        ->setReplyTo("rubenndjengwes@gmail.com")
        ->setTo("somfuncky8@gmail.com")
        ->setSubject("An attemp")
        ->setMessage("Hi from Codeigniter 4 App")
        ->send());
        
    }

    public function cli(string $name): string
    {
        return sprintf("Hello %s !", mb_strtolower($name));
    }

    public function cookie()
    {
        setcookie(
            name: "remember_me_token",
            value: "joe",
            expires_or_options: time() - 120,
            path: "/",
            domain: (new URI(current_url()))->getHost(),
            httponly: true
        );
    }

    public function form(): string
    {
        return "hello world";
    }
}