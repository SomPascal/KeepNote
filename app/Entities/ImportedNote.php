<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ImportedNote extends Entity
{
    protected $datamap = [];
    protected $dates   = [];
    protected $casts   = ["downloads_id" => "csv"];

    public function id(?string $id=null)
    {
        if ($id === null) return $this->id;
        $this->id = $id;
    }

    public function user_id(?string $user_id=null)
    {
        if ($user_id == null) return $this->user_id;
        $this->user_id = $user_id;
    }

    public function downloads_id(?array $downloads_id=null)
    {
        if ($downloads_id === null) return $this->downloads_id;
        $this->downloads_id = $downloads_id;
    }

    public function created_at(\DateTime|string|null $created_at=null)
    {
        if ($created_at === null) return $this->created_at;
        $this->created_at = $created_at;
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
