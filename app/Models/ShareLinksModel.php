<?php

namespace App\Models;

use CodeIgniter\Model;

class ShareLinksModel extends Model
{
    protected $table            = 'share_links';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'user_id', 'notes_id', 'created_at', 'expired_at'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'expired_at';
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

    public function getNotesId(string $id): array
    {
        $notes_id = $this->where("id", $id)->select(["notes_id"])
        ->find();

        if (empty($notes_id)) return [];
        return explode(",", $notes_id[0]->notes_id);
    }

    public function exist(array $where): bool
    {   
        return !((bool) empty($this->where($where)->find()));
    }
}