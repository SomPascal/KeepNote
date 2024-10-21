<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class RememberMe extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at'];
    protected $casts   = [];

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
