<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class VisitorEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function id(?string $id=null)
    {
        if ($id == null) return $this->id;
        $this->id = $id;
    }

    public function ua(?string $ua=null)
    {
        if ($ua === null) return $this->ua;
        $this->ua = $ua;
    }

    public function ip(?string $ip=null)
    {
        if ($ip == null) return $this->ip;
        return $this->ip;
    }

    /**
     * @author Ruben
     * setAll && getAll have been added by me
     */
    public static function setAll(array|object $data): self
    {
        return new static($data);
    }

    public function getAll(): array
    {
        return $this->attributes;
    }
}
