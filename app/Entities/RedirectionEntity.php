<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class RedirectionEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function id(?string $id=null)
    {
        if ($id == null) return $this->id;
        $this->id = $id;
    }

    public function target(?string $target=null)
    {
        if ($target == null) return $this->target;
        $this->target = $target;
    }

    public function visitor_id(?string $visitor_id)
    {
        if ($visitor_id === null) return $this->visitor_id;
        $this->visitor_id = $visitor_id;
    }

    public function ip(?string $ip=null)
    {
        if ($ip == null) return $this->ip;
        $this->ip = $ip;
    }

    public function ua(?string $ua=null)
    {
        if ($ua == null) return $this->ua;
        $this->ua = $ua;
    }

    public function created_at(null|string|\DateTime $created_at=null)
    {
        if ($created_at == null) return $this->created_at;
        else if (is_string($created_at)) $this->created_at = $created_at;
        else $this->created_at = $created_at->format("Y-m-d H:i:s");
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
