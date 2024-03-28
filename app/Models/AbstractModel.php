<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbstractModel extends Model
{
    use HasFactory;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedAt(): \Carbon\Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): \Carbon\Carbon
    {
        return $this->updated_at;
    }
}
