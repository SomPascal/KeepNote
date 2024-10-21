<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use Config\Auth;
use Config\RememberMeCookie;

class SignOutController extends BaseController
{
    public function signout()
    {
        $this->deleteSession();
        $this->deleteRememberMe();

        try 
        {
            $redirection = redirect()->route((new Auth())->signOutRedirection);
        } catch (HTTPException $e) 
        {
            $redirection = redirect()->to("/");
            $this->logger->warning($e->getMessage());
        }

        return $redirection;
    }

    protected function deleteRememberMe(): void
    {
        $remember_me_config = new RememberMeCookie;

        setcookie(
            name: $remember_me_config->name,
            value: "",
            expires_or_options: time() - 3600,
            path: $remember_me_config->path,
            secure: $remember_me_config->secure,
            httponly: $remember_me_config->httponly
        );
    }

    protected function deleteSession(): void
    {
        session()->destroy();
        session()->close();
        $_SESSION = [];
    }
}
