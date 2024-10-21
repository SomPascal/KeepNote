<?php

namespace App\Filters\Validator;

use App\Entities\User;
use App\Models\NoteModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Validation\Exceptions\ValidationException;
use Config\Services;
use Config\Throttler;

class CreateNotes implements FilterInterface
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

        if (url_is(route_to("note.create"))) 
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

            $user = User::setAll(session()->get("user"));
            $throttler = Services::throttler();
            $config = (object) (new Throttler())->create_note;

            if (! $throttler->check(sprintf($config->key, md5($user->id())), $config->capacity, $config->seconds))
            {
                $status = Response::HTTP_TOO_MANY_REQUESTS;
                $status_reason = sprintf("Too many attempts. Try again in %s second(s)", $throttler->getTokenTime());

                goto send_response;
            }

            $note_body = $request->getJsonVar("note_body") ?? "";
            $note_title = $request->getJsonVar("note_title") ?? "";
            $note_color = $request->getJsonVar("note_color") ?? "";
            $note_font = $request->getJsonVar("note_font") ?? "";

            $note_data = 
            [
                "note_body" => $note_body,
                "note_title" => $note_title,
                "note_color" => $note_color,
                "note_font" => $note_font
            ];

            try {
                if ( $validator->run($note_data, "create_note") == false) 
                {
                    $form_errors = $validator->getErrors();
                    $status = Response::HTTP_BAD_REQUEST;
                    $status_reason = "bad note's data";

                    goto send_response;
                }
            } catch (ValidationException) 
            {
                $status = Response::HTTP_INTERNAL_SERVER_ERROR;
                $status_reason = "There was a system error when validating your data ";

                goto send_response;
            }
            $user = User::setAll(session()->get("user"));
            $num_notes = count(model(NoteModel::class)->where("id", $user->id())->findAll(30));

            if ($num_notes > 30) 
            {
                $status = Response::HTTP_TOO_MANY_REQUESTS;
                $status_reason = "You reached your limit of 30 notes.";

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
