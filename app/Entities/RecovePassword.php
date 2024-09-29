<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class RecovePassword extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function id(?string $id = null)
    {
        if (isset($id))
        {
            $this->id = $id;
            return $this;
        }
        else return $this->id;
    }

    public function user_id(?string $user_id = null)
    {
        if (isset($user_id))
        {
            $this->user_id = $user_id;
            return $this;
        }
        else return $this->user_id;
    }
    
    public function answer(?string $answer=null)
    {
        if (isset($answer))
        {
            $this->answer = $answer;
            return $this;
        }
        else return $this->answer;
    }

    public function question(?string $question=null)
    {
        if (isset($question))
        {
            $this->question = $question;
            return $this;
        }
        else return $this->question;
    }

    public function created_at(null|string|\DateTime $created_at=null)
    {
        if (isset($created_at))
        {
            $this->created_at = (is_string($created_at)) ? $created_at : $created_at->format("Y-m-d H:i:s");
            return $this;
        }
        else return $this->created_at;
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
