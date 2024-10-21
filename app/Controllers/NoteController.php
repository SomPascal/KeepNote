<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\DownloadedNote;
use App\Entities\Note;
use App\Entities\ShareNoteLink;
use App\Entities\User;
use App\Models\DownloadModel;
use App\Models\NoteModel;
use App\Models\ShareLinksModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use CodeIgniter\I18n\Time;
use Config\Services;
use Config\Throttler;

class NoteController extends BaseController
{
    protected $helpers = ["text"];
    protected $content_type = "application/json";

    public function get(): Response
    {
        if (! $this->request->is('GET'))
        {
            return $this->response
            ->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)
            ->setJSON([ "http_code" => Response::HTTP_METHOD_NOT_ALLOWED ]);
        }
        else if (! $this->request->isAJAX())
        {
            return $this->response
            ->setStatusCode(Response::HTTP_BAD_REQUEST, "should be an ajax request")
            ->setJSON([
                "http_code" => Response::HTTP_METHOD_NOT_ALLOWED,
                "message" => "should be an ajax request"
            ]);
        }

        $user = User::setAll(session("user"));
        $cache = Services::cache();

        $throttler = Services::throttler();
        $throttlerConfig = (object) (new Throttler())->get_notes;
        $notes = [];

        if (! $throttler->check(
            key: sprintf($throttlerConfig->key, $user->id()),
            capacity: $throttlerConfig->capacity,
            seconds: $throttlerConfig->seconds
        ))
        {
            return $this->response
            ->setStatusCode(Response::HTTP_TOO_MANY_REQUESTS)
            ->setJSON([
                "http_code" => Response::HTTP_TOO_MANY_REQUESTS,
                "message" => sprintf("Try again in %s secs", $throttler->getTokenTime()),
                "old_notes" => $cache->get(sprintf("notes.get.%s", md5($user->id()))) ?? []
            ]);
        }
        
        $notes = model(NoteModel::class)->where(["user_id" => $user->id()])
        ->select(["id", "title", "body", "font", "color", "src", "created_at"])
        ->orderBy("created_at", "desc")
        ->findAll(50);

        foreach ($notes as &$note) 
        {
            $note->src = lang(sprintf("Label.%s", $note->src));

            $note->created_at = Time::createFromFormat(
                format: "Y-m-d H:i:s",
                datetime: $note->created_at
            )->humanize();
        }

        if ($cache->get(sprintf("notes.get.%s", md5($user->id()))) === null)
        {
            $cache->save(sprintf("notes.get.%s", md5($user->id())), $notes);
        }
        
        return $this->response
        ->setStatusCode(Response::HTTP_OK)
        ->setJSON([
            "http_code" => Response::HTTP_OK,
            "notes" => $notes
        ]);
    }

    public function import()
    {
        // Validated by the ImportNotes filter.
        $this->response->setContentType("application/json");
        $user = User::setAll(session("user"));

        $note_model = model(NoteModel::class);
        $download_model = model(DownloadModel::class);

        $now = Time::now();
        $imported = new Note();

        $imported->user_id($user->id());
        $imported->src(Note::SRC_IMPORT);
        $imported->created_at($now->format("Y-m-d H:i:s"));
        
        foreach ($this->request->getFiles() as $file)
        {
            if (mb_strtolower($file->getExtension()) == "json")
                $content = json_decode(file_get_contents($file->getTempName()));
            else if (mb_strtolower($file->getExtension()) == "xml")
                $content = simplexml_load_string(file_get_contents($file->getTempName()));

            /* if (! $download_model->exist((string) $content->properties->download_id))
            {
                $status_code = Response::HTTP_NOT_FOUND;
                $status_reason = sprintf("The download files '%s' doesn't exist", $file->getClientName());
                $upload_errors = "";

                goto send_response;
            }
            else */
            $recordings = [];

            if (($note_model->numOfNotes(["user_id" => $user->id()]) + count($content->notes)) > MAX_NOTES_PER_USER)
            {
                $status_code = Response::HTTP_INSUFFICIENT_STORAGE;
                $status_reason = sprintf("You reached your limit of notes(50)");
                $upload_errors = "";

                goto send_response;
            }
            else
            {
                $content->properties->download_id = (string) $content->properties->download_id;
                $imported->src_id($content->properties->download_id);

                foreach ($content->notes as $note) 
                {
                    $imported->id(sprintf("%s%s", random_string(type: 'alpha', len: 1), random_string(len: 7)) );
                    if (mb_strtolower($file->getClientExtension()) == "json") 
                    {
                        $imported->title((string) $note->title ?? "");
                        $imported->body((string) $note->body ?? "");
                        $imported->font((string) $note->font ?? "poppins");
                        $imported->color((string) $note->color ?? "#f2f2f27a");
                    }
                    else
                    {
                        $imported->title((string) $note[0] ->note->title ?? "");
                        $imported->body((string) $note[0] ->note->body ?? "");
                        $imported->font((string) $note[0] ->note->font ?? "poppins");
                        $imported->color((string) $note[0] ->note->color ?? "#f2f2f27a");
                    }

                    // echo $note->title . PHP_EOL;
                    try 
                    {
                        $note_model->insert($imported);
                        $recordings[] = [
                            "id" => $imported->id(),
                            "body" => $imported->body(),
                            "title" => $imported->title(),
                            "font" => $imported->font(),
                            "color" => $imported->color(),
                            "src" => lang(sprintf("Label.%s", $imported->src)),
                            "created_at" => $imported->created_at()->humanize()
                        ];

                    } catch (DatabaseException $e)
                    {
                        $this->logger->error(sprintf(
                            "MESSAGE: %s, FILE: %s, LINE: %s",
                            $e->getMessage(),
                            $e->getFile(),
                            $e->getLine()
                        ));
                        continue;
                    }
                }
            }
        }

        $status_code = Response::HTTP_CREATED;
        $status_reason = "Notes has successfully uploaded";
        $upload_errors = "";

        send_response:
        return $this->response->setStatusCode($status_code, $status_reason)
        ->setJSON([
            "csrf_hash" => csrf_hash(),
            "http_code" => $status_code,
            "imported_notes" => $recordings,
            "http_reason" => $status_reason ?? null,
            "upload_errors" => $upload_errors
        ]);
    }

    public function create(): Response
    {
        $this->response->setHeader("Content-Type", "application/json");

        $status_code = Response::HTTP_METHOD_NOT_ALLOWED;
        $status_code_reason = "Method not allowed";

        if (! $this->request->is("POST")) 
        {
            goto send_response;
        }

        $note_body = $this->request->getJsonVar("note_body");
        $note_title = $this->request->getJsonVar("note_title");
        $note_color = $this->request->getJsonVar("note_color");
        $note_font = $this->request->getJsonVar("note_font");
        // Start creating note
        $user_id = session("user")["id"];

        $note_model = model(NoteModel::class);
        $note = new Note();

        $note->id(sprintf("%s%s", random_string(type: 'alpha', len: 1), random_string(len: 7)));
        $note->user_id($user_id);
        $note->body($note_body);
        $note->title($note_title);
        $note->font($note_font);
        $note->color($note_color);
        $note->created_at(Time::now()->format("Y-m-d H:i:s"));
        
        if (($note_model->numOfNotes(["user_id" => $user_id]) +1) > MAX_NOTES_PER_USER)
        {
            $status_code = Response::HTTP_INSUFFICIENT_STORAGE;
            $status_code_reason = lang("Error.body.notes-limit", ["limit" => MAX_NOTES_PER_USER]);

            goto send_response;
        }
        else if ($note_model->insert($note) === false)
        {
            $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $status_code_reason = lang("Error.title.default");

            goto send_response;
        }
        else
        {
            $status_code = Response::HTTP_CREATED;
            $status_code_reason = "The note has been successfuly created";

            goto send_response;
        }

        send_response:

        return $this->response->setStatusCode(
            $status_code,
            $status_code_reason
        )
        ->setJSON([
            "csrf_hash" => csrf_hash(),
            "http_code" => $status_code,
            "created_note" => 
            [
                "id" => $note->id(), 
                "created_at" => Time::createFromFormat("Y-m-d H:i:s", $note->created_at())
                ->humanize(),
                "src" => lang(sprintf("Label.%s", $note->src() ?? "myself"))
            ]
        ]);
    }

    public function update(): Response
    {
        $this->response->setHeader("Content-Type", $this->content_type);

        $status_code = Response::HTTP_METHOD_NOT_ALLOWED;
        $status_code_reason = "Method not allowed";
        $notes_errors = [];

        if (! ($this->request->is("PUT") || $this->request->is("POST") )) {
            goto send_response;
        }
        else if (! $this->request->isAJAX())
        {
            $status_code = Response::HTTP_BAD_REQUEST;
            $status_code_reason = "Should be an ajax request";

            goto send_response;
        }

        $user = User::setAll(session()->get("user"));
        $throttler = Services::throttler();
        $config = (object) (new Throttler())->update_note;

        if (! $throttler->check(sprintf($config->key, md5($user->id())), $config->capacity, $config->seconds))
        {
            $status_code = Response::HTTP_TOO_MANY_REQUESTS;
            $status_code_reason = lang("Error.title.429", ["sec" => $throttler->getTokenTime()]);

            goto send_response;
        }
        if ($this->validate("update_note") === false)
        {
            $notes_errors = $this->validator->getErrors();
            $status_code = Response::HTTP_BAD_REQUEST;
            $status_code_reason = "Http bad note's data";

            goto send_response;
        }
        $note_data = $this->validator->getValidated();
        $res = model(NoteModel::class)
        ->update(
            $note_data["note_id"],
            [
                "title" => $note_data["note_title"],
                "body" => $note_data["note_body"],
                "font" => $note_data["note_font"],
                "color" => $note_data["note_color"]
            ]
        );

        if ($res === false)
        {
            $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $status_code_reason = lang("Error.title.default");

            goto send_response;
        }
        else 
        {
            $status_code = Response::HTTP_OK;
            $status_code_reason = "The note has been successfuly updated";

            goto send_response;
        }

        send_response:

        return $this->response->setStatusCode(
            $status_code,
            $status_code_reason
        )
        ->setJSON([
            "csrf_hash" => csrf_hash(),
            "http_code" => $status_code,
            "http_reason" => $status_code_reason,
            "note_errors" => $notes_errors
        ]);
    }

    public function get_share_link()
    {
        $status = Response::HTTP_OK;
        $status_reason = "The share link has been successfuly genereted";

        $user = User::setAll(session()->get("user"));
        $user->id(session()->get("user")["id"]);
        $ids = $this->request->getGet("ids", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        
        $throttler = Services::throttler();
        $config = (object) (new Throttler())->share_notes_link;

        if (! $throttler->check(sprintf($config->key, md5($user->id())), $config->capacity, $config->seconds))
        {
            $status = Response::HTTP_TOO_MANY_REQUESTS;
            $status_reason = lang("Error.title.429", ["sec" => $throttler->getTokenTime()]);

            goto send_response;
        }
        $share_link_data = [];
        $now = Time::now();


        $share_link = ShareNoteLink::setAll([
            "id" => random_string(len: 16),
            "user_id" => $user->id(),
            "notes_id" => explode(",", $ids),
            "created_at" => $now->format("Y-m-d H:i:s"),
            "expired_at" => $now->addHours(24)->format("Y-m-d H:i:s")
        ]);

        try 
        {
            if (model(ShareLinksModel::class)->insert($share_link, false) == false)
            {
                $status = Response::HTTP_INTERNAL_SERVER_ERROR;
                $status_reason = lang("Error.title.default");

                goto send_response;
            }
        } catch (DatabaseException) 
        {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $status_reason = lang("Error.title.default");

            goto send_response;
        }
        $status = Response::HTTP_OK;
        $status_reason = "The share link has been successfuly genereted";

        $share_link_data = 
        [
            "link" => $share_link->getLink(),
            "created_at" => (string) $share_link->created_at(),
            "expire_at" => (string) $share_link->expired_at()
        ];
        send_response:

        return $this->response->setStatusCode($status, $status_reason)
        ->setJSON([
            "http_code" => $status,
            "http_reason" => $status_reason,
            "share_link" => $share_link_data,
            "csrf_hash" => csrf_hash()
        ]);
    }

    public function use_share_link(string $user_id, string $link_id): Response
    {
        $status = Response::HTTP_OK;
        $status_reason = "";
        $message = "";

        $user = User::setAll(session("user"));

        $head = new \stdClass();
        $head->styles = ["utilities.css", "style.css"];
        $head->description = lang("Error.title.default");
        $header = new \stdClass();

        if (! $this->request->is("GET")) 
        {
            $status = Response::HTTP_METHOD_NOT_ALLOWED;
            $status_reason = "The request should be of method GET";

            $head->title = sprintf("Error %s", Response::HTTP_METHOD_NOT_ALLOWED);
            $header->title = sprintf("Error %s. Method not allowed", Response::HTTP_METHOD_NOT_ALLOWED);

            $message = sprintf("An error occured. The request should be of method GET but %s found",
                $this->request->getMethod(true)
            );

            goto send_response;
        }

        // Check if link was created by the current link's user
        else if ($user_id == $user->id())
        {
            $status = Response::HTTP_UNAUTHORIZED;
            $status_reason = lang("Error.title.use-own-link");

            $head->title = sprintf("Error %s", Response::HTTP_UNAUTHORIZED);
            $header->title = lang("Error.title.401.use-link");

            $message = lang("Error.body.401.use-link");
            
            goto send_response;
        }
        $throttler = Services::throttler();
        $config = (object) (new Throttler())->use_share_link;

        // To limit the rate of requests
        if (! $throttler->check(sprintf($config->key, md5($user->id())), $config->capacity, $config->seconds))
        {
            $status = Response::HTTP_TOO_MANY_REQUESTS;
            $status_reason = "Too many attempts";

            $head->title = sprintf("Error %s", Response::HTTP_TOO_MANY_REQUESTS);
            $header->title = "Too many attempts";

            $message = sprintf("An error occured. There was too many attempts, try in %s second(s)", $throttler->getTokenTime());
            
            goto send_response;
        }
        $share_link_model = model(ShareLinksModel::class);
        $note_model = model(NoteModel::class);

        $share_link = ShareNoteLink::setAll(["user_id" => $user_id, "id" => $link_id]);

        // Check if the link exists
        if (! $share_link_model->exist(["user_id" => $share_link->user_id(), "id" => $share_link->id()])) 
        {
            $status = Response::HTTP_NOT_FOUND;
            $status_reason = "This link doesn't exist";

            $head->title = sprintf("Error %s", Response::HTTP_NOT_FOUND);
            $header->title = lang("Error.title.link-doesnt-exist");

            $message = lang("Error.body.link-doesnt-exist");
            
            goto send_response;
        }

        // Get data from the link
        $share_link_data = $share_link_model->where(["user_id" => $share_link->user_id(), "id" => $share_link->id()])
        ->limit(1)->find();

        // Populate the $share_link instance
        $share_link->id($share_link_data[0] ->id);
        $share_link->notes_id(explode(",", $share_link_data[0] ->notes_id));
        $share_link->user_id($share_link_data[0] ->user_id);
        $share_link->expired_at($share_link_data[0] ->expired_at);
        $share_link->created_at($share_link_data[0] ->created_at);

        // check if the share already expired...
        if ($share_link->hasExpired())
        {
            $status = Response::HTTP_GONE;
            $status_reason = "This link has expired";

            $head->title = sprintf("Error %s", Response::HTTP_GONE);
            $header->title = lang("Error.title.link-expired");

            $message = lang("Error.body.link-expired", ["period" => $share_link->expired_at()->humanize()]);
            
            goto send_response;
        }

        // Check if the link has already been used by the current user...
        else if ($note_model->exist(["src_id" => $share_link->id(), "user_id" => $user->id()])) 
        {
            $status = Response::HTTP_CONFLICT;
            $status_reason = "This link has already been used";

            $head->title = sprintf("Error %s", Response::HTTP_CONFLICT);
            $header->title = lang("Error.title.link-already-used");

            $message = lang("Error.body.link-already-used");
            
            goto send_response;
        }

        // Check if the user already reach the number notes per user
        $numOfNotes = $note_model->numOfNotes(["user_id" => $user->id()]);
        if ($numOfNotes > MAX_NOTES_PER_USER || (($numOfNotes + count($share_link->notes_id())) > MAX_NOTES_PER_USER)) 
        {
            $status = Response::HTTP_INSUFFICIENT_STORAGE;
            $status_reason = sprintf("You reached your limit of notes(%d)", MAX_NOTES_PER_USER);

            $head->title = sprintf("Error %s", Response::HTTP_INSUFFICIENT_STORAGE);
            $header->title = lang("Error.title.notes-limit");

            $message = lang("Error.body.notes-limit", ["limit" => MAX_NOTES_PER_USER]);
            
            goto send_response;
        }

        // Populate the $shared_note instance
        $shared_notes = new Note();
        $shared_notes->user_id($user->id());
        $shared_notes->src(Note::SRC_SHARE);
        $shared_notes->src_id($share_link->id());
        $shared_notes->created_at(Time::now()->format("Y-m-d H:i:s"));

        $note_info = [];
        foreach ($share_link->notes_id() as $note_id) 
        {
            $note_info = $note_model->getCreatedNote(["id" => $note_id]);

            if (! empty($note_info)) 
            {
                try 
                {
                    $shared_notes->id(sprintf("%s%s", random_string(type: 'alpha', len: 1), random_string(len: 7)) );
                    $shared_notes->body($note_info[0] ->body);
                    $shared_notes->title($note_info[0] ->title);
                    $shared_notes->font($note_info[0] ->font);
                    $shared_notes->color($note_info[0] ->color);

                    $note_model->insert($shared_notes);
                } catch (DatabaseException) 
                {
                    continue;
                }
            }
        }

        $status = Response::HTTP_OK;
        $status_reason = "Success";

        $head->title = "Success";
        $header->title = lang("Success.title.got-shared-notes");

        $message = lang("Success.body.got-shared-notes");
        
        goto send_response;

        send_response:
        $view = view("message", [
            "head" => $head,
            "header" => $header,
            "user" => $user,

            "message" => $message,
            "anchor" => null,
            "scripts" => ["responsive.js"]
        ]);

        return $this->response->setStatusCode($status, $status_reason)
        ->setBody($view);
    }

    public function delete(): Response
    {
        $this->response->setContentType($this->content_type);

        $status_code = Response::HTTP_METHOD_NOT_ALLOWED;
        $status_code_reason = "Method not allowed";

        try
        {
            $notes_id = $this->request->getJsonVar("notes_id", filter: FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $res = model(NoteModel::class)->delete($notes_id, false);
        } catch (DatabaseException) 
        {
            $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $status_code_reason = lang("Error.title.default");

            goto send_response;
        }

        if (($res == false)) {
            $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $status_code_reason = lang("Error.title.default");

            goto send_response;
        }
        else {
            $status_code = Response::HTTP_OK;
            $status_code_reason = "notes has been successfuly deleted";

            goto send_response;
        }

        send_response:

        return $this->response->setStatusCode($status_code, $status_code_reason)
        ->setJSON([
            "csrf_hash" => csrf_hash(),
            "http_code" => $status_code,
            "http_reason" => $status_code_reason
        ]);
    }

    /**
     * Validated by DownloadNotes class
     */
    public function active_download()
    {
        $this->response->setHeader("Content-Type", "text/html");

        $config = $this->request->getGet("config");
        $ids = explode(",", $this->request->getGet("ids"));

        $session = session();
        $user = User::setAll($session->get("user"));

        $head = new \stdClass();
        $head->styles = ["utilities.css", "style.css"];
        $head->description = lang("Desc.active-download");
        $head->title = lang("Header.title.download-started");

        $header = new \stdClass();
        $header->title = lang('Header.title.download-ok');

        $message = lang('Header.body.download-ok');

        $download = (new DownloadedNote([
            "id" => random_string(),
            "config" => $config,
            "notes_id" => $ids
        ]));

        $download_data = json_encode(["config" => $config, "download_id" => $download->id(), "notes_id" => $ids]);
        $session->setTempdata("download", $download_data, 2);

        return view("message", [
            "head" => $head,
            "header" => $header,
            "message" => $message,
            "anchor" => null,
            "user" => $user,
            "scripts" => ["responsive.js"]
        ])
        .view("parts/start-download", ["url" => url_to("note.download", $download->id())]);
    }

    public function download(string $download_id): Response
    {
        $status_code = Response::HTTP_OK;
        $status_code_reason = "Successfuly downloaded";

        $session = session();
        $user = User::setAll($session->get("user"));

        $head = new \stdClass();
        $head->description = "";
        $head->title = "";
        $head->styles =  ["utilities.css", "style.css"];

        $header = new \stdClass();
        $header->title = "";

        $message = "";

        if (! $session->has("download"))
        {
            $status_code = Response::HTTP_GONE;
            $status_code_reason = "This download link expired";

            $header->title = lang('Error.title.download-expired');
            $header->styles = ["utilities.css", "style.css"];

            $head->title = lang('Error.title.download-expired');
            $head->description = lang("Error.title.desc.download-expired");
            $message = lang('Error.body.download-expired');

            goto send_response;
        }
        $downloadData = json_decode(session()->get("download"), true);

        if ($download_id !== $downloadData["download_id"])
        {
            $status_code = Response::HTTP_BAD_REQUEST;
            $status_code_reason = "Wrong download id";

            $header->title = "Bad request";

            $head->title = "Bad request";
            $head->description = lang("Error.title.default");
            $message = lang("Error.title.default");

            goto send_response;
        }
        $notes = [];
        $note_model = model(NoteModel::class);
        $download_model = model(DownloadModel::class);

        $now = Time::now();
        $download = new DownloadedNote();
        $download->id(sprintf("%s%s", random_string(type: 'alpha', len: 1), random_string(len: 7)) );
        $download->notes_id();
        $download->created_at($now->format("Y-m-d H:i:s"));

        $download->user_id($user->id());
        $download->notes_id($downloadData["notes_id"]);
        $download->config($downloadData["config"]);

        $i = 0;
        foreach ($downloadData["notes_id"] as $id)
        {
            if ($i >= 10) break;
            $note = $note_model->where("id", $id)->select(["id", "title", "body", "font", "color"])
            ->limit(1)->find();

            if ($note !== null)
            {
                $notes[] = $note[0];
            }
            $i++;
        }
        $file_content = $this->set_download_content($download, $notes, $downloadData["config"]);
        $download_model->insert($download, false);

        return $this->response
        ->download(
            sprintf(
                "%s-%s.keepnote.%s",
                uniqid(), $now->format("Y-m-d-H-i-s"), $downloadData["config"]
            ),
                $file_content
        );

        send_response:

        return $this->response
        ->setStatusCode($status_code, $status_code_reason)
        ->setBody(view("message", [
            "user" => $user,
            "head" => $head,
            "header" => $header,
            "message" => $message,
            "anchor" => null,
            "scripts" => ["responsive.js"]
        ]));
    }

    protected function set_download_content(DownloadedNote $download, array $notes, string $config): string 
    {
        $res = "";

        if ($config == "json")
        {
            $notes = 
            [
                "properties" => [
                    "download_id" => $download->id(),
                    "created_at" => $download->created_at()
                ],
                "notes" => $notes
            ];
            $res = json_encode($notes);
        }
        elseif ($config == "xml")
        {
            $dom = new \DOMDocument(encoding: "utf-8");

            $dom->formatOutput = true;
            $dom->xmlVersion = "1.0";

            $keepnote = $dom->createElement("keepnote");
            $node_notes = $dom->createElement("notes");

            $properties_node = $dom->createElement("properties");
            $properties_node->appendChild($dom->createElement("download_id", $download->id()));
            $properties_node->appendChild($dom->createElement("created_at", $download->created_at()));


            foreach ($notes as $note)
            {
                $node_note = $dom->createElement("note");
                foreach ($note as $key => $value)
                {
                    $node_note->appendChild($dom->createElement($key, $value));
                }
                $node_notes->appendChild($node_note);
            }

            $keepnote->appendChild($properties_node);
            $keepnote->appendChild($node_notes);
            $dom->appendChild($keepnote);

            $res = $dom->saveXML();
        }
        return $res;
    }
}