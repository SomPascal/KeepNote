<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Note extends Entity
{
    public const SRC_MYSELF = "myself";
    public const SRC_SHARE = "share";
    public const SRC_IMPORT = "import";

    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function id(?string $id=null)
    {
        if ($id == null) return $this->id;
        $this->id = $id;
    }

    public function src(?string $src=null)
    {
        if ($src == null) return $this->src;
        $this->src = $src;
    }

    public function user_id(?string $user_id=null)
    {
        if ($user_id == null) return $this->user_id;
        $this->user_id = $user_id;
    }

    public function src_id(?string $src_id=null)
    {
        if ($src_id == null) return $this->src_id;
        $this->src_id = $src_id;
    }

    public function body(?string $body=null)
    {
        if ($body == null) return $this->body;
        $this->body = $body;
    }

    public function title(?string $title=null)
    {
        if ($title === null) return $this->title;
        $this->title = $title;
    }

    public function font(?string $font=null)
    {
        if ($font == null) return $this->font;
        $this->font = $font;
    }

    public function color(?string $color=null)
    {
        if ($color == null) return $this->color;
        $this->color = $color;
    }

    public function created_at(\DateTime|string|null $created_at=null)
    {
        if ($created_at == null) return $this->created_at;
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
