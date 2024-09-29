<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\User;
use App\Entities\VisitorEntity;
use App\Models\AccountModel;
use App\Models\RememberMeModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;
use CodeIgniter\I18n\Time;

class AuthController extends BaseController
{
    protected $helpers = ["form", "text", "auth"];

    private string $content_type = "application/json";

    use ResponseTrait;

    public function signup(): Response
    {
        $this->response->setContentType($this->content_type);
        
        $status = Response::HTTP_OK;
        $status_reason = "";
        $redirectTo = "";
        
        if (! $this->request->is("POST")) 
        {
            return $this->response
            ->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED, "Method not allowed!")
            ->setJSON([
                "csrf_hash" => csrf_hash(),
                "http_code" => Response::HTTP_METHOD_NOT_ALLOWED
            ]);
        }
        elseif (! $this->request->isAJAX()) 
        {
            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST, "The request should be ajax")
            ->setJSON([
                "csrf_hash" => csrf_hash(),
                "http_code" => Response::HTTP_BAD_REQUEST
            ]);
        }
        $visitor = VisitorEntity::setAll(session()->get('visitor'));
        $user = new User();
        
        $user->id($visitor->id());
        $user->username($this->request->getJsonVar("username", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $user->randColor();
        $user->password($this->request->getJsonVar("password_confirmation", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $user->created_at(Time::now()->format("Y-m-d H:i:s"));
        
        if (model(AccountModel::class)->insert($user, false) == false)
        {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $status_reason = "Account creation failed";
            
            goto send_response;
        }
        login($user);
        
        $rememberMe = $this->request->getJsonVar("remember_me", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? true;
        if ($rememberMe) model(RememberMeModel::class)->setRememberMe($user);
        
        $status = Response::HTTP_OK;
        $status_reason = "The account has been successfuly";
        $redirectTo = route_to("account.home", $user->username());
        
        send_response:

        return $this->response->setStatusCode($status, $status_reason)
        ->setJSON([
            "csrf_hash" => csrf_hash(),
            "http_code" => $status,
            "http_reason" => $status_reason,
            "redirectTo" => $redirectTo
        ]);
    }

    public function signin(): Response
    {
        $this->response->setContentType($this->content_type);

        $status = Response::HTTP_METHOD_NOT_ALLOWED;
        $status_reason = "Method not allowed";
        $redirectTo = "";

        if (! $this->request->is("POST")) goto send_response;
        elseif (! $this->request->isAJAX())
        {
            $status = Response::HTTP_BAD_REQUEST;
            $status_reason = "The request should be ajax";

            goto send_response;
        }
        elseif (isSignedIn()) 
        {
            $status = Response::HTTP_FORBIDDEN;
            $status_reason = "Should be signed in";

            goto send_response;
        }
        $username = $this->request->getJsonVar("username", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $user_info = model(AccountModel::class)
        ->where("username", $username)
        ->select(["id", "color", "username"])->first();

        $session = session();

        $user = User::setAll($user_info);
        $visitor = VisitorEntity::setAll($session->get('visitor'));
        $visitor->id($user->id());
        $session->set('visitor', $visitor->getAll());

        login($user);

        $rememberMe = $this->request->getJsonVar("remember_me", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? true;
        if ($rememberMe) model(RememberMeModel::class)->setRememberMe($user);
        
        $status = Response::HTTP_OK;
        $status_reason = "you successfuly signed in";
        $redirectTo = route_to("account.home", $user->username());

        send_response:

        return $this->response
        ->setStatusCode($status, $status_reason)
        ->setJSON([
            "http_code" => $status,
            "http_reason" => $status_reason,
            "redirectTo" => $redirectTo,
            "csrf_hash" => csrf_hash()
        ]);
    }

    public function change_password(): Response
    {
        $this->request->setHeader("Content-Type", $this->content_type);

        $status = Response::HTTP_METHOD_NOT_ALLOWED;
        $status_reason = "Method not allowed";

        if (! $this->request->is("POST")) goto send_response;
        elseif (! $this->request->isAJAX()) 
        {
            $response_code = Response::HTTP_BAD_REQUEST;
            $response_reason_code = "The request should be ajax";

            goto send_response;
        }
        elseif (! isSignedIn()) 
        {
            $response_code = Response::HTTP_FORBIDDEN;
            $response_code = "Should be signed in";

            goto send_response;
        }
        $session = session();
        $password = $this->request->getJsonVar("password_confirmation", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
        $user = User::setAll($session->get("user"));

        try 
        {
            $result = model(AccountModel::class)->limit(1)
            ->update($user->id(), ["password" => password_hash($password, PASSWORD_DEFAULT)]);
        } catch (\ReflectionException)
        {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $status_reason = "An error ocurred when validating data";

            goto send_response;
        }

        if ($result === true) 
        {
            $status = Response::HTTP_OK;
            $status_reason = "Password Successfuly changed";
            $redirectTo = route_to("account.home", $user->username());

            goto send_response;
        }
        else
        {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $status_reason = "Unable to change your password";

            goto send_response;
        }

        send_response:

        return $this->response->setStatusCode($status, $status_reason)
        ->setJSON([
            "http_code" => $status,
            "http_reason" => $status_reason,
            "redirectTo" => $redirectTo,
            "csrf_hash" => csrf_hash()
        ]);
    }

    public function change_username(): Response
    {
        $this->request->setHeader("Content-Type", $this->content_type);

        $session = session();

        $redirectTo = "";
        $status = Response::HTTP_BAD_REQUEST;
        $status_reason = "should be a POST request";

        if (! $this->request->is("POST")) 
        {
            goto send_response;
        }
        elseif (! $this->request->isAJAX()) 
        {
            $status = Response::HTTP_BAD_REQUEST;
            $status_reason = "The request should be ajax";

            goto send_response;
        }
        $new_username = $this->request->getJsonVar("new_username", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $user = User::setAll($session->get("user"));
        $user->username($new_username);
        
        try {
            $result = model(AccountModel::class)->limit(1)
            ->update($user->id(), ["username" => $user->username()]);

        } catch (\ReflectionException) 
        {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $status_reason = "An error ocurred when validating data";

            goto send_response;
        }

        if ($result == true) 
        {
            login($user);

            $status = Response::HTTP_OK;
            $status_reason = "Username successfully edited";
            $redirectTo = route_to("account.home", $user->username());

            goto send_response;
        }
        else 
        {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $status_reason = "Unable to edit your username";
        }

        send_response:

        return $this->response->setStatusCode($status, $status_reason)
        ->setJSON([
            "http_code" => $status,
            "http_reason" => $status_reason,
            "redirectTo" => $redirectTo,
            "csrf_hash" => csrf_hash()
        ]);
    }
}
