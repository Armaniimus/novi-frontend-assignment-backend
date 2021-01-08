<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;

class Auth extends Controller
{
    public function __construct(Message $message) {
        $this->message = $message;
    }

    public function login($username, $pass) {
        if ($username === '') {
            $this->message->addError('no username given');
            return false;
        }

        if ($pass === '') {
            $this->message->addError('no pass given');
            return false;
        }
        
        $user = Users::where('name', $username)->first();
        if (!$user) {
            $this->message->addError('invalid user');
            return false;
        }

        if (!password_verify($pass, $user->password) ) {
            $this->message->addError('password failed');
            return false;
        }

        $key = $this->genKey();

        //prevent double tokens;
        if ( Users::where('token', $key)->first() ) {
            $this->message->addError('unlucky double token');
            return false;
        }

        $user->token = $key;
        $user->save();

        $role = $user->role->name;

        $this->message->addInfo('token', $key);
        $this->message->addInfo('role', $role);       
    }

    public function check() {
        if ( !isset( $_REQUEST['token']) ) {
            $this->message->addError('no token given');
            return false;
        } 

        return $this->checkIntern( $_REQUEST['token'] );
    }

    public function checkIntern($token) {
        if ($token === '' || $token === null) {
            $this->message->addError('token has no value');
            return false;
        }

        $user = Users::where('token', $token)->first();
        if (!$user) {
            $this->message->addError('invalid token');
            return false;
        }

        return $user->role->name;
    }

    public function genKey() {
        return bin2hex(random_bytes(64));
    }
}
