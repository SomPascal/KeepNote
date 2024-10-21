<?php

namespace App\Models;

use CodeIgniter\Model;
use ReflectionException;

class AccountModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = 
    [
        "id", "username",
        "password", "color",
        "created_at", "ip_adress",
        "ua"
    ];

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

    public function get(string $column, int $limit=1, array $where = [])
    {
        if ($where == []) {
            $user_id = session()->get("user")["id"];
            $where = ["key" => "id", "value" => $user_id];
        }

        return $this->where($where["key"], $where["value"])
        ->select($column)
        ->limit($limit)
        ->first()[$column];
    }

    public function create_account(
        string $user_id,
        string $username,
        string $hashed_password,
        string $color,
        string $ua,
        string $ip_adress,
        string $created_at
    )
    {
        try {
            return $this->insert([
                "id" => $user_id,
                "username" => $username,
                "password" => $hashed_password,
                "ua" => $ua,
                "color" => $color,
                "created_at" => $created_at,
                "ip_adress" => $ip_adress
            ], false);
        } catch (ReflectionException) {
            return false;
        }
    }
}
