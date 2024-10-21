<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\User;
use CodeIgniter\HTTP\Response;

class ErrorHandlerController extends BaseController
{

    protected $helpers = ["auth"];

    public function index(): Response
    {
        if (! $this->request->is("GET")) 
        {
            $this->response->setHeader("Content-Type", "application/json");

            return $this->response
            ->setStatusCode(
                Response::HTTP_NOT_FOUND,
                "The ressource you were looking for doesn't exist!"
            )
            ->setJSON([
                "http_code" => Response::HTTP_NOT_FOUND,
                "csrf_hash" => csrf_hash()
            ]);
        }
        else 
        {
            return redirect()->to("/error")
            ->with("error_info", [
                "error_code" => Response::HTTP_NOT_FOUND,
                "error_title" => lang('Error.title.http', ['code' => Response::HTTP_NOT_FOUND]),
                "error_message" => lang('Error.title.404.no-page'),

                "previous_url" => (string) $this->request->getUri(),
                "error_redirect_btn" => 
                [
                    "text" => lang('Button.signup'),
                    "url" => route_to("signup")
                ]
            ]);
        }
    }

    public function handler(): Response
    {
        $session = session();

        if (isSignedIn()) $user = User::setAll($session->get("user"));
        else $user = null;

        $error_info = $session->getFlashdata("error_info");
        
        $error_code = $error_info["error_code"] ?? Response::HTTP_NOT_FOUND;
        $error_title = $error_info["error_title"] ?? lang('Error.title.default');
        $error_message = $error_info["error_message"] ?? lang('Error.body.default');
        
        $head = new \stdClass();
        $head->description = $error_title;
        $head->title = $error_title;
        $head->styles = ["utilities.css", "style.css"];

        $header = new \stdClass();
        $header->title = $error_title;

        return $this->response->setStatusCode($error_code)
        ->setBody(view("message", [
            "head" => $head,
            "header" => $header,
            "message" => $error_message,
            "anchor" => null,
            "user" => $user,
            "scripts" => ["responsive.js"]
        ]));
    }
}
