<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $dates = ['soft_timeout', 'hard_timeout', 'created_at', 'updated_at', 'deleted_at'];
    public function role() {
        return $this->belongsTo(Roles::class);
    }
}


