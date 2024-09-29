<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Rupadana\ApiService\Contracts\HasAllowedFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consumer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}