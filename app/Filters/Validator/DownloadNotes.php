<?php

namespace App\Filters\Validator;

use App\Entities\User;
use CodeIgniter\Config\Services;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Throttler;

class DownloadNotes implements FilterInterface
{
    /**
     * @var array $messages The validator error messages
     */
    protected array $allowed_configs = ["xml", "json"];
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
        if (url_is(route_to("note.active.download")))
        {
            $session = session();
            $response = Services::response();
            $request = Services::request();

            $status = Response::HTTP_METHOD_NOT_ALLOWED;
            $status_reason = "should be a POST request";

            if (! $request->is("GET")) goto send_response;

            $user = User::setAll($session->get("user"));
            $csrf = $request->getGet("csrf", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $config = $request->getGet("config", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $ids = explode(",", $request->getGet("ids", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS));

            if ($session->has("old_csrf") && $session->get("old_csrf") == $csrf)
            return redirect()->route("account.home", [$user->username()]);

            $session->set("old_csrf", $csrf);
            
            if (! $csrf === csrf_hash())
            {
                $status = Response::HTTP_FORBIDDEN;
                $status_reason = "You haven't the right to perform this action";
                
                goto send_response;
            }
            $throttler = Services::throttler();
            $throttler_config = (object) (new Throttler())->download_notes;

            if (! $throttler->check(sprintf($throttler_config->key, md5($user->id())), $throttler_config->capacity, $throttler_config->seconds))
            {
                $status = Response::HTTP_TOO_MANY_REQUESTS;
                $status_reason = sprintf("Too many attempts. Try again in %s second(s)", $throttler->getTokenTime());

                $head = new \stdClass();
                $head->styles = ["utilities.css", "style.css"];
                $head->title = lang('Error.http', ["code" => Response::HTTP_TOO_MANY_REQUESTS]);
                $head->description = lang('Error.desc.download-expired');

                $header = new \stdClass();
                $header->title = lang('Error.http', ["code" => Response::HTTP_TOO_MANY_REQUESTS]);

                $view = view("message", [
                    "head" => $head,
                    "header" => $header,
                    "user" => $user,
                    "message" => $status_reason,
                    "anchor" => null,
                    "scripts" => ["responsive.js"]
                ]);
                $response->setStatusCode($status, $status_reason)
                ->setBody($view);

                return $response;
            }
            
            if (! in_array($config, $this->allowed_configs))
            {
                $status = Response::HTTP_BAD_REQUEST;
                $status_reason = "Http too many requests";

                $head = new \stdClass();
                $head->styles = ["utilities.css", "style.css"];
                $head->title = lang('Error.http', ["code" => Response::HTTP_BAD_REQUEST]);
                $head->description = lang('Error.desc.wrong-config');

                $header = new \stdClass();
                $header->title = lang('Error.title.wrong-config');

                $view = view("message", [
                    "head" => $head,
                    "header" => $header,
                    "user" => $user,
                    "message" => $status_reason,
                    "anchor" => null,
                    "scripts" => ["responsive.js"]
                ]);
                $response->setStatusCode($status, $status_reason)
                ->setBody($view);

                return $response;
            }
            foreach ($ids as $id)
            {
                if (! ((bool) preg_match("/(note_)?[a-zA-Z0-9]{8}/", $id)))
                {
                    $status = Response::HTTP_BAD_REQUEST;
                    $status_reason = "Wrong id";

                    $head = new \stdClass();
                    $head->styles = ["utilities.css", "style.css"];
                    $head->title = lang('Error.http', ["code" => Response::HTTP_BAD_REQUEST]);
                    $head->description = lang('Error.desc.wrong-id');

                    $header = new \stdClass();
                    $header->title = lang('Error.title.wrong-id');

                    $view = view("message", [
                        "head" => $head,
                        "header" => $header,
                        "user" => $user,
                        "message" => $status_reason,
                        "anchor" => null,
                        "scripts" => ["responsive.js"]
                    ]);
                    $response->setStatusCode($status, $status_reason)
                    ->setBody($view);

                    return $response;
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
        //
    }
}
