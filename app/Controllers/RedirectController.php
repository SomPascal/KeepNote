<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\RedirectionEntity;
use App\Entities\VisitorEntity;
use App\Models\RedirectionModel;
use CodeIgniter\Config\Services;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\Response;
use CodeIgniter\I18n\Time;
use Config\Contact;
use Config\Throttler;

class RedirectController extends BaseController
{
    protected $helpers = ["auth", "text"];

    public function goto($target): RedirectResponse
    {
        $contacts = new Contact();

        if (isset($contacts->{$target}))
        {
            $visitor = VisitorEntity::setAll(session()->get('visitor'));
            $throttler = Services::throttler();
            $config = (object) (new Throttler())->goto;

            if ($throttler->check(sprintf($config->key, md5($visitor->ip())), $config->capacity, $config->seconds))
            {
                $redirection = new RedirectionEntity();
                $redirection_model = model(RedirectionModel::class);

                $redirection->id(random_string());
                $redirection->visitor_id($visitor->id());
                $redirection->target($target);
                $redirection->created_at(Time::now()->format("Y-m-d H:i:s"));

                try 
                {
                    $redirection_model->insert($redirection);
                } catch (DatabaseException $de) {
                    dd($de->getMessage());
                }
            }
            

            return redirect()->to($contacts->{$target});
        }
        else 
        {
            return redirect()->route("error")->with("error_info", [
                "error_code" => Response::HTTP_NOT_FOUND,
                "error_title" => "Erreur " . Response::HTTP_NOT_FOUND,
                "error_message" => sprintf("The contact or profil '%s' isn't available yet", $target)
            ]);
        }
    }
}
