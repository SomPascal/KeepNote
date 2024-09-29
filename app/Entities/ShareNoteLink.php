<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;

class ShareNoteLink extends Entity implements \Stringable
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'expired_at'];
    protected $casts   = ["notes_id" => "csv"];

    public function hasExpired(): bool
    {
        $now = Time::now();
        return $now->isAfter($this->expired_at());
    }

    public function id(?string $id=null)
    {
        if ($id == null) return $this->id;
        $this->id = $id;
    }

    public function user_id(?string $user_id=null)
    {
        if ($user_id == null) return $this->user_id;
        $this->user_id = $user_id;
    }

    public function notes_id(?array $notes_id=null)
    {
        if ($notes_id == null) return $this->notes_id;
        $this->notes_id = $notes_id;
    }

    public function created_at(\DateTime|string|null $created_at=null)
    {
        if ($created_at == null) return $this->created_at;
        $this->created_at = $created_at;
    }

    public function expired_at(\DateTime|string|null $expired_at=null)
    {
        if ($expired_at == null) return $this->expired_at;
        $this->expired_at = $expired_at;
    }

    public function getLink(): string
    {
        return url_to("note.share.use", $this->user_id(), $this->id());
    }

    public function clear(): void
    {
        $this->attributes = [];
    }

    public function __toString(): string
    {
        return $this->__toString();
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
