<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    //gen a 128 secure randomized token

    public function role()
    {
        return $this->belongsTo(Roles::class);
    }
}


