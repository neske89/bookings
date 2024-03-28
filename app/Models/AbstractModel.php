<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbstractModel extends Model
{
    use HasFactory;
    protected $casts = [
        'id' => 'integer',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];
    public function getId(): int
    {
        return $this->id;
    }
}
