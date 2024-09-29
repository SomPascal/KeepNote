<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    // colors
    protected array $availableColors = 
    [
        "red", "brown", "chocolate", "teal", "green", "darkgreen",
        "maroon", "violet", "orange", "darkorange", "blueviolet",
        "black", "crimson", "chartreuse", "darkgoldenrod", "darkslategray",
        "pink"
    ];

    public function id(?string $id=null)
    {
        if ($id === null) return $this->id;
        $this->id = $id;
    }

    public function randColor(): void
    {
        $color = $this->availableColors[array_rand($this->availableColors)];
        $this->color = $color;
    }

    public function color(): string
    {
        return $this->color;
    }

    public function username(?string $username=null)
    {
        if ($username == null) return $this->username;
        $this->username = $username;
    }

    public function password(?string $password=null)
    {
        if ($password == null) return $this->password;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function created_at(\DateTime|string|null $created_at)
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
