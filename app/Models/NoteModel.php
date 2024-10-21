<?php

namespace App\Models;

use App\Filters\Validator\DownloadNotes;
use CodeIgniter\Model;
use Config\Database;

class NoteModel extends Model
{
    protected string $all_notes_sql = "";

    protected $table            = 'users_notes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = 
    [
        "id", "user_id", "src_id",
        "title", "body", "font",
        "color", "src", "created_at",
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


    public function exist(array $where): bool
    {
        return !((bool) empty($this->where($where)->find()));
    }

    public function numOfNotes(array $where): int
    {
        return count($this->where($where)
        ->select(["id"])->findAll(50));
    }

    public function getCreatedNote(array $where): array|object
    {
        return (array) $this->where($where)
        ->select(["body", "title", "font", "color"])
        ->find();

    }
}
