<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    use HasFactory;

    /**
     * @param string $pass
     * @param string $user
     * @return string
     */
    private function login(string $user, string $pass ):string {
        if ($login === false) {
            return false;
        }

        if ($login === true) {
            $token = $this->genKey();
            //save token in db
            return $token;
        }
    }
}
