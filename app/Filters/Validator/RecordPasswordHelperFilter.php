<?php

namespace App\Filters\Validator;

use App\Entities\User;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\ResponseTrait;
use CodeIgniter\Validation\ValidationInterface;
use Config\Services;
use Config\Throttler;

class RecordPasswordHelperFilter implements FilterInterface
{
    protected Response $response;
    protected ValidationInterface $validation;

    use ResponseTrait;

    public function __construct()
    {
        $this->response = Services::response();
        $this->validation = Services::validation();
    }
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
        if (! $request->is('POST'))
        {
            $this->response
            ->setStatusCode(code: Response::HTTP_METHOD_NOT_ALLOWED)
            ->setJSON(["csrf_hash" => csrf_hash()]);
            goto send_response;
        }
        else if (! $request->isAJAX())
        {
            $this->response
            ->setStatusCode(code: Response::HTTP_BAD_REQUEST)
            ->setJSON([
                "csrf_hash" => csrf_hash(),
                "message" => "Should be an ajax request"
            ]);
            goto send_response;
        }
        else if (! isSignedUp()) return redirect()->to("/");

        $user = new User(session()->get("user"));
        $throttler = Services::throttler();
        $throttlerConfig = (object) (new Throttler())->recove_password;

        if (! $throttler->check(
            key: sprintf($throttlerConfig->key, $user->id()),
            capacity: $throttlerConfig->capacity,
            seconds: $throttlerConfig->seconds
        ))
        {
            $this->response->setStatusCode(Response::HTTP_TOO_MANY_REQUESTS)
            ->setJSON([
                "status" => Response::HTTP_TOO_MANY_REQUESTS,
                "messages" => [
                    "csrf_hash" => csrf_hash(),
                    "message" => lang('Label.too-many-requests', ["sec" => $throttler->getTokenTime()])
                ]
            ]);
            goto send_response;
        }

        $data = 
        [
            "question" => trim((string) $request->getJsonVar("question")),
            "answer" => trim((string) $request->getJsonVar("answer"))
        ];

        if (! $this->validation->run($data, 'password_recovery_by_question'))
        {
            $this->response->setStatusCode(Response::HTTP_BAD_REQUEST, "data aren't follow rules")
            ->setJSON(array_merge(
                [
                "csrf_hash" => csrf_hash(),
                "status" => Response::HTTP_BAD_REQUEST
                ],
                ["errors" => $this->validation->getErrors()]
            ));
            goto send_response;
        }

        return;

        send_response:
        return $this->response;
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
