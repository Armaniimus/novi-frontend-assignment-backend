<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Users;

class Auth extends Controller {
    public function __construct(Message $message) {
        $this->message = $message;
    
        $this->hardLockoutTime = 180 * 60;
        $this->softLockoutTime = 15 * 60;
    }

    public function logout(string $token) {
        if ( $this->checkToken($token) === false) {
            $this->message->addMessage('logout failed');
            return false;
        }

        $user = Users::where('token', $token)->first();
        $this->closeSession($user);

        $this->message->addMessage('logout successfull');
        return true;
    }

    public function login(string $username, string $pass) {
        if ($username === '') {
            $this->message->setAuth(false, 'no username given');
            return false;
        }

        if ($pass === '') {
            $this->message->setAuth(false, 'no pass given');
            return false;
        }
        
        $user = Users::where('name', $username)->first();
        if (!$user) {
            $this->message->setAuth(false, 'user doesn\'t exist');
            return false;
        }

        if (!password_verify($pass, $user->password) ) {
            $this->message->setAuth(false, 'password failed');
            return false;
        }

        $token = $this->genToken();

        //prevent double tokens;
        if ( Users::where('token', $token)->first() ) {
            $this->message->setAuth(false, 'unlucky double token');
            return false;
        }
        $user->soft_timeout = Carbon::now();
        $user->hard_timeout = Carbon::now();
        $user->token = $token;
        $user->save();

        $role = $user->role->name;

        $this->message->addInfo('token', $token);
        $this->message->addInfo('role', $role);       
        $this->message->setAuth(true);
    }

    public function checkToken(string $token) {
        if ($token === '' || $token === null) {
            $this->message->setAuth(false, 'token has no value');
            return false;
        }

        if (strLen($token) !== 128) {
            if (strLen($token) < 32 || strLen($token) > 256 ) {
                $this->message->setAuth(false, 'token has incorrect length');
            } else {
                $this->message->setAuth(false, 'invalid token');
            }
            return false;
        }

        $user = Users::where('token', $token)->first();
        if (!$user) {
            $this->message->setAuth(false, 'invalid token');
            return false;
        }

        if ( !$this->checkLockOuts($token) ) {
            return false;
        }

        $this->message->setAuth(true);
        return $user->role->name;
    }

    public function checkAdmin(string $token) {
        $role = $this->checkToken($token);
        if ( $role == false ) { 
            return false; 
        
        } else if ( $role !== 'admin') { 
            $this->message->setAuth(false, 'Account has no permission for this endpoint');
            return false;
        }

        $this->message->setAuth(true);
        return true;
    }

    private function checkLockOuts(string $token) {
        $user = Users::select('id','soft_timeout', 'hard_timeout')->where('token', $token)->first();
        $timeNow = time();

        $softDifference = $timeNow - $user->soft_timeout->getTimestamp();
        if ($softDifference > $this->softLockoutTime) {
            $this->message->setAuth(false, 'SoftTimeout has been hit');
            $this->closeSession($user);
            $user->save();
            return false;
        }
        
        $hardDifference = $timeNow - $user->hard_timeout->getTimestamp();
        if ($hardDifference > $this->hardLockoutTime) {
            $this->message->setAuth(false, 'hardTimeout has been hit');
            $this->closeSession($user);
            return false;
        }

        $user->soft_timeout = Carbon::now();
        $user->save();

        return true;
    }

    private function closeSession(Users $user) {
        $user->soft_timeout = null;
        $user->hard_timeout = null;
        $user->token = null;
        $user->save();
    }

    private function genToken() {
        return bin2hex(random_bytes(64));
    }
}
