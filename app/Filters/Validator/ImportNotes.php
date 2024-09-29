<?php

namespace App\Filters\Validator;

use App\Entities\User;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Throttler;

class ImportNotes implements FilterInterface
{
    /**
     * @var array $rules The validation file rules
     */
    protected array $rules = [
        "file size" => [
            "required",
            "numeric",
            "less_than_equal_to[200000]"
        ],
        "file extension" => [
            "required",
            "alpha",
            "in_list[json,xml]"
        ],
        "file mime" => [
            "required",
            "in_list[application/json,application/xml,text/xml,text/json]"
        ]
    ];

    /**
     * @param array|object $data The array data to check
     * @param string $config The config file: xml or json
     * @return bool Return true if is valid download note and false otherwise
     */
    public function is_valid_download(array|object $data, string $config): bool
    {
        // /(note_)?[A-Za-z0-9]{8}/
        $download_id_re = "/(note_)?[a-zA-Z0-9]{8}/";

        if ($config == "json")
        {
            if (!(! empty($data["properties"]) && is_array($data["properties"])))
                return false;
            else if (!(! empty($data["properties"]["download_id"]) && preg_match($download_id_re, $data["properties"]["download_id"])))
                return false;
            else if (!(! empty($data["properties"]["created_at"])))
                return false;
            else if (!(! empty($data["notes"]) && is_array($data["notes"])) && count($data["notes"]) > 0)
                return false;

            return true;
        }
        else if ($config == "xml")
        {
            if (!(! empty($data->properties) && is_object($data->properties)))
                return false;
            else if (!(! empty($data->properties->download_id) && preg_match($download_id_re, $data->properties->download_id)))
                return false;
            else if (!(! empty($data->properties->created_at)))
                return false;
            else if (!(! empty($data->notes) && is_object($data->notes) && count((array) $data->notes)))
                return false;

            return true;
        }
        else 
        {
            throw new \ValueError("\$config should 'xml' or 'json' but '$config' given", 1);
        }
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

        if (url_is(route_to("note.import")))
        {
            $response = Services::response();
            $response->setContentType("application/json");

            $status = Response::HTTP_METHOD_NOT_ALLOWED;
            $status_reason = "should be a POST request";
            $upload_errors = "";

            if (! $request->is("POST")) goto send_response;
            else if (! $request->isAjax())
            {
                $status = Response::HTTP_BAD_REQUEST;
                $status_reason = "should be an ajax request";

                goto send_response;
            }
            
            $user = User::setAll(session("user"));
            $throttler = Services::throttler();
            $config = (object) (new Throttler())->import_notes;

            if (! $throttler->check(sprintf($config->key, md5($user->id())), $config->capacity, $config->seconds))
            {
                $status = Response::HTTP_TOO_MANY_REQUESTS;
                $status_reason = sprintf("Too many attempts. Try again in %s second(s)", $throttler->getTokenTime());

                goto send_response;
            }
            
            if (count($request->getFiles()) === 0)
            {
                $status = Response::HTTP_BAD_REQUEST;
                $status_reason = "Files are required";
                $upload_errors = "No file uploaded!";

                goto send_response;
            }
            else if (count($request->getFiles()) > 10)
            {
                $status = Response::HTTP_BAD_REQUEST;
                $status_reason = "The limit of imports per uploads reached";
                $upload_errors = sprintf("The limit of imports per upload is 10 but %s uploaded.", count($request->getFiles()));

                goto send_response;
            }

            $files = $request->getFiles();
            $validator = Services::validation();

            foreach ($files as $file) 
            {
                if (! $file->isValid())
                {
                    $status = Response::HTTP_BAD_REQUEST;
                    $status_reason = "An error occured when uploading...";
                    $upload_errors = $file->getError();

                    goto send_response;
                }
                else if ($file->hasMoved())
                {
                    $status = Response::HTTP_BAD_REQUEST;
                    $status_reason = "an error occured about files.";
                    $upload_errors = "";

                    goto send_response;
                }
                $validator->setRules($this->rules);
                $data = [
                    "file size" => $file->getSize(),
                    "file mime" => $file->getClientMimeType(),
                    "file extension" => mb_strtolower($file->getClientExtension())
                ];

                if ($validator->run($data) === false)
                {
                    $status = Response::HTTP_BAD_REQUEST;
                    $status_reason = "an error occured when validating file(s).";
                    $upload_errors = $validator->getErrors()[array_keys($validator->getErrors())[0]];

                    goto send_response;
                }
            }

            foreach ($files as $file)
            {
                $file_content = file_get_contents($file->getTempName());

                if (mb_strtolower($file->getClientExtension()) == "json")
                {
                    $json_content = json_decode($file_content, true);

                    if (json_last_error() !== JSON_ERROR_NONE)
                    {
                        $status = Response::HTTP_BAD_REQUEST;
                        $status_reason = "Error when parsing json";
                        $upload_errors = "There was a '" . json_last_error_msg() . "' when parsing the file";

                        goto send_response;
                    }
                    else if (! $this->is_valid_download($json_content, "json"))
                    {
                        $status = Response::HTTP_BAD_REQUEST;
                        $status_reason = "Error when parsing json file";
                        $upload_errors = "File content doesn't match with the download notes file config";

                        goto send_response;
                    }

                }
                else if (mb_strtolower($file->getClientExtension()) == "xml")
                {
                    try 
                    {
                        $xml_content = simplexml_load_string($file_content);
                        
                    } catch (\ErrorException $e) 
                    {
                        $status = Response::HTTP_BAD_REQUEST;
                        $status_reason = "Error when parsing xml file";
                        $upload_errors = "An error occured during the parsing";

                        var_dump($e->getMessage());
                        exit;
                        goto send_response;
                    }
                    if ($xml_content === false)
                    {
                        $status = Response::HTTP_BAD_REQUEST;
                        $status_reason = "Error when parsion xml";
                        $upload_errors = "Bad xml content file";

                        goto send_response;
                    }
                    else if (! $this->is_valid_download($xml_content, "xml"))
                    {
                        $status = Response::HTTP_BAD_REQUEST;
                        $status_reason = "Error when parsing json file";
                        $upload_errors = "File content doesn't with the download notes file config";

                        goto send_response;
                    }
                }
            }

            return;
            send_response:

            return $response->setStatusCode($status, $status_reason)
            ->setJSON([
                "csrf_hash" => csrf_hash(),
                "http_code" => $status,
                "http_reason" => $status_reason,
                "upload_errors" => $upload_errors
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
