<?php

namespace App\Filters\Validator;

use App\Validation\UserRules;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Validation\Exceptions\ValidationException;
use Config\Services;
use Config\Throttler;

class SignIn implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        /**
         * @var \Codeigniter\HTTP\IncomingRequest $request The Request object...
         */

        if (url_is(route_to("auth.signin")))
        {
            $status = Response::HTTP_BAD_REQUEST;
            $status_reason = "should be a POST request";
            $form_errors = [];

            $response = Services::response();
            $validator = Services::validation();

            $response->setContentType("application/json");

            if (! $request->is("POST")) goto send_response;
            elseif (! $request->isAJAX()) 
            {
                $status = Response::HTTP_BAD_REQUEST;
                $status_reason = "The request should be ajax";

                goto send_response;
            }

            $throttler = Services::throttler();
            $config = (object) (new Throttler())->signin;

            if (! $throttler->check(sprintf($config->key, md5($request->getIPAddress())), $config->capacity, $config->seconds))
            {
                $status = Response::HTTP_TOO_MANY_REQUESTS;
                $status_reason = sprintf("Too many attempts. Try again in %s second(s)", $throttler->getTokenTime());

                goto send_response;
            }

            $username = $request->getJsonVar("username", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
            $password = $request->getJsonVar("password", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";

            $validate_data = 
            [
                "username" => $username,
                "password" => $password
            ];

            try
            {
                if ($validator->run($validate_data, "signin") === false)
                {
                    $form_errors = $validator->getErrors();

                    $status = Response::HTTP_BAD_REQUEST;
                    $status_reason = "Bad form data";

                    goto send_response;
                }
            } catch (ValidationException $ve) 
            {
                $status = Response::HTTP_INTERNAL_SERVER_ERROR;
                $status_reason = "An error ocurred when validating data";

                Services::logger()->error(sprintf(
                    "MESSAGE: %s, FILE: %, LINE: %s",
                    $ve->getMessage(),
                    $ve->getFile(),
                    $ve->getLine()
                ));

                goto send_response;
            }

            if (! UserRules::isCorrectLogIn($validate_data["username"], $validate_data["password"]))
            {
                return $response->setStatusCode(Response::HTTP_UNAUTHORIZED, "Connection_Unauthorized")
                ->setJSON([
                    "csrf_hash" => csrf_hash(),
                    "http_code" => Response::HTTP_UNAUTHORIZED,
                    "message" => lang("Validation.login-unauthorized")
                ]);
            }

            return;
            send_response:

            return $response->setStatusCode($status, $status_reason)
            ->setJSON([
                "csrf_hash" => csrf_hash(),
                "http_reason" => $status_reason,
                "http_code" => $status,
                "form_errors" => $form_errors
            ]);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
