<?php

namespace App\Filters\Validator;

use App\Entities\ShareNoteLink;
use App\Entities\User;
use App\Models\NoteModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Throttler;

class ShareNotesLink implements FilterInterface
{
    protected $helpers = ["text"];
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
        if (url_is(route_to("note.share.link"))) 
        {
            $response = Services::response();

            $status = Response::HTTP_METHOD_NOT_ALLOWED;
            $status_reason = "Should be a GET request";

            if (! $request->is("GET")) 
            {
                goto send_response;
            }
            else if (! $request->isAJAX()) 
            {
                $status = Response::HTTP_BAD_REQUEST;
                $status_reason = "Should be an ajax request";

                goto send_response;
            }
            $csrf = $request->getGet("csrf", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $ids = $request->getGet("ids", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $user = new User();
            $user->id(session("user")["id"]);

            $throttler = Services::throttler();
            $config = (object) (new Throttler())->share_notes_link;
            if (! $throttler->check(sprintf($config->key, md5($user->id())), $config->capacity, $config->seconds))
            {
                $status = Response::HTTP_TOO_MANY_REQUESTS;
                $status_reason = sprintf("Too many attempts. Try again in %s seconds", $throttler->getTokenTime());

                goto send_response;
            }

            foreach (explode(",", $ids) as $id) 
            {
                if (! ((bool) preg_match("/(note_)?[A-Za-z0-9]{8}/", $id)))
                {
                    $status = Response::HTTP_BAD_REQUEST;
                    $status_reason = "bad id";

                    goto send_response;
                }
            }
            return;

            send_response:

            return $response->setStatusCode($status, $status_reason)
            ->setJSON([
                "http_code" => $status,
                "http_reason" => $status_reason,
                "csrf_hash" => csrf_hash()
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
