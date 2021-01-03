<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth;
use App\Http\Controllers\Message;
use Illuminate\Http\Request;

class Api extends Controller
{
    public function __construct() {
        $this->message = new Message();
    }

    public function login() {
        ['username' => $username, 'password' => $password] = $this->retrievePost(['username', 'password'], $_REQUEST);

        $auth = new Auth($this->message);
        $key = $auth->login($username, $password);

        $this->message->retrieve();
    }

    private function retrievePost(array $dataIndexes, array $data) {
        $returnData = [];
        foreach ($dataIndexes as $index) {
            if ( isset($data[$index]) ) {
                $returnData[$index] = $data[$index];
                continue;
            } else {
                $returnData[$index] = '';
                continue;
            }            
        }

        return $returnData;
    }
}
