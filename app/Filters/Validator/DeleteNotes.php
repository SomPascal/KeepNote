<?php

namespace App\Filters\Validator;

use App\Entities\User;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Throttler;

class DeleteNotes implements FilterInterface
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

        if (url_is(route_to("note.delete"))) 
        {
            $response = Services::response();

            $status = Response::HTTP_METHOD_NOT_ALLOWED;
            $status_reason = "Method not allowed";
            $form_errors = [];

            $response->setContentType("application/json");
            
            if (! ($request->is("DELETE") || $request->is("POST"))) {
                goto send_response;
            }
            else if (! $request->isAJAX()) 
            {
                $status = Response::HTTP_BAD_REQUEST;
                $status_reason = "Should be an ajax request";

                goto send_response;
            }

            $user = User::setAll(session("user"));
            $throttler = Services::throttler();
            $config = (object) (new Throttler())->delete_notes;

            if (! $throttler->check(sprintf($config->key, md5($user->id())), $config->capacity, $config->seconds))
            {
                $status = Response::HTTP_TOO_MANY_REQUESTS;
                $status_reason = sprintf("Too many attempts. Try again in %s second(s)", $throttler->getTokenTime());

                goto send_response;
            }
            $notes_id = $request->getJsonVar("notes_id", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (count($notes_id) > 10)
            {
                $status = Response::HTTP_INSUFFICIENT_STORAGE;
                $status_reason = sprintf("The limit of delete per time has been reached (10)");

                goto send_response;
            }
            foreach ($notes_id as $note_id) 
            {
                if (! preg_match("/(note_)?[A-Za-z0-9]{8}/", $note_id)) 
                {
                    $status = Response::HTTP_BAD_REQUEST;
                    $status_reason = "Bad form of note's id";

                    goto send_response;
                }
            }

            return;
            send_response:

            return $response->setStatusCode($status, $status_reason)
            ->setJSON([
                "csrf_hash" => csrf_hash(),
                "http_reason" => $status_reason,
                "http_code" => $status
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
        // code there
    }
}
