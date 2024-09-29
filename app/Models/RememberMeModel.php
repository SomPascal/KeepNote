<?php

namespace App\Models;

use App\Entities\RememberMe;
use App\Entities\User;
use CodeIgniter\Cookie\Cookie;
use CodeIgniter\I18n\Time;
use CodeIgniter\Model;
use Config\Database;
use Config\RememberMeCookie;
use Config\Services;
use Config\Throttler;

class RememberMeModel extends Model
{
    protected $table            = 'remember_me';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'user_id', 'token', 'created_at'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getUserFromToken(string $token)
    {
        $result = Database::connect()->table("remember_me")
        ->where(["token" => $token])
        ->select(select: "user_id", escape: true)
        ->get(limit: 1)
        ->getResultArray();

        if (count($result) == 0) return [];
        $user_id = $result[0];

        $user = Database::connect()->table("users")
        ->where(["id" => $user_id])
        ->select(["id", "username", "color"])
        ->get(limit: 1)
        ->getResultArray();

        return (count($user) == 0) ? [] : $user[0];
    }
    public function exist(array $where): bool
    {
        return ! empty($this->where($where)->find());
    }

    public function setRememberMe(User $user)
    {
        helper("cookie");
        $logger = Services::logger();
        $throttler = Services::throttler();

        try 
        {
            $remember_me = new RememberMe([
                "id" => random_string(),
                "user_id" => $user->id(),
                "token" => md5(random_string(len: 64)),
                "created_at" => Time::now()->format('Y-m-d H:i:s')
            ]);
            $throttler_remember_me = (object) (new Throttler())->remember_me;

            if ($throttler->check(
                key: sprintf($throttler_remember_me->key, $user->id()),
                capacity: $throttler_remember_me->capacity,
                seconds: $throttler_remember_me->seconds
            )) 
            {
                if ($this->insert($remember_me, false))
                {
                    $remember_me_config = new RememberMeCookie();

                    setcookie(
                        name: $remember_me_config->name,
                        value: $remember_me->token,
                        expires_or_options: time() + $remember_me_config->expires,
                        path: $remember_me_config->path,
                        secure: $remember_me_config->secure,
                        httponly: $remember_me_config->httponly
                    );
                }
                else 
                {
                    $logger->warning(sprintf(
                        "MESSAGE: Unable to save remember_me token in the databse FILE: %s LINE: %s",
                        __FILE__, __LINE__
                        )
                    );
                }
            }
            else 
            {
                $logger->warning(sprintf(
                    "MESSAGE: Too many remember_me token storage. Available in %s secs FILE: %s LINE: %s",
                    $throttler->getTokenTime(),
                    __FILE__, __LINE__
                )); 
            }

        } catch (\Throwable $e) 
        {
            $logger->error(sprintf(
                    "MESSAGE: %s FILE: %s LINE: %s", 
                    $e->getMessage(), 
                    $e->getFile(),
                    $e->getLine()
                )
            );
        }
    }
}
