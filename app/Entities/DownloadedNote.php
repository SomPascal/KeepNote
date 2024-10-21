<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class DownloadedNote extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at'];
    protected $casts   = ["notes_id" => "csv"];

    public function id(?string $id=null)
    {
        if ($id === null)
        {
            return $this->id;
        }
        else $this->id = $id;
    }

    public function user_id(?string $user_id=null)
    {
        if ($user_id === null)
        {
            return $this->user_id;
        }
        else $this->user_id = $user_id;
    }

    public function notes_id(?array $notes_id=null)
    {
        if ($notes_id === null) {
            return $this->notes_id;
        } else {
            $this->notes_id = $notes_id;
        }
        
    }
    
    public function created_at(\DateTime|string|null $date=null)
    {
        if ($date === null)
        {
            return $this->created_at;
        }
        else if (is_string($date)) $this->created_at = $date;
        else $this->created_at = $date->format("Y-m-d H:i:s");
    }

    public function config(?string $config=null)
    {
        if ($config === null)
        {
            return $this->config();
        }
        else $this->config = $config;
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
