<?php

namespace App\Filters\Validator;

use CodeIgniter\Config\Services;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Validation\Exceptions\ValidationException;
use Config\Throttler;

class ChangeUsername implements FilterInterface
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

        if (url_is(route_to("auth.change_username"))) 
        {
            $response = Services::response();
            $validator = Services::validation();

            $status = Response::HTTP_BAD_REQUEST;
            $status_reason = "should be a POST request";
            $form_errors = [];

            $response->setContentType("application/json");

            if (! $request->is("POST")) goto send_response;
            else if (! $request->isAJAX())
            {
                $status = Response::HTTP_BAD_REQUEST;
                $status_reason = "should be an ajax request";

                goto send_response;
            }
            $throttler = Services::throttler();
            $config = (object) (new Throttler())->change_username;

            if (! $throttler->check(sprintf($config->key, md5($request->getIPAddress())), $config->capacity, $config->seconds))
            {
                $status = Response::HTTP_TOO_MANY_REQUESTS;
                $status_reason = sprintf("Too many attempts. Try again in %s second(s)", $throttler->getTokenTime());

                goto send_response;
            }

            $new_username = $request->getJsonVar("new_username", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
            $password = $request->getJsonVar("password", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";

            $validate_data = 
            [
                "new_username" => $new_username,
                "password" => $password
            ];
            
            try
            {
                if ($validator->run($validate_data, "change_username") == false)
                {
                    $form_errors = $validator->getErrors();

                    $status = Response::HTTP_BAD_REQUEST;
                    $status_reason = "Bad form data";

                    $status = Response::HTTP_BAD_REQUEST;

                    goto send_response;
                }
            } catch (ValidationException) 
            {
                $status = Response::HTTP_INTERNAL_SERVER_ERROR;
                $status_reason = "An error ocurred when validating data";

                goto send_response;
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
