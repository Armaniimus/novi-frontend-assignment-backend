<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth;
use App\Http\Controllers\Message;
use App\Http\Controllers\SongsController;
use Illuminate\Http\Request;

class Api extends Controller
{
    public function __construct() {
        $this->message = new Message();
        $this->auth = new Auth($this->message);
    }

    public function login() {
        ['username' => $username, 'password' => $password] = $this->retrievePost(['username', 'password'], $_REQUEST);

        $key = $this->auth->login($username, $password);

        $this->message->retrieve();
    }

    public function overview() {
        ['token' => $token] = $this->retrievePost(['token'], $_REQUEST);

        $role = $this->auth->checkIntern($token);
        if ( $role !== false ) { 
            $this->message->addInfo('role', $role);
            $lied = new SongsController();
            $this->message->addInfo( 'songinfo', $lied->index() );
        }      

        $this->message->retrieve();
    }

    public function overviewSpecific($id) {
        ['token' => $token] = $this->retrievePost(['token'], $_REQUEST);
        $role = $this->auth->checkIntern($token);

        if ($role !== false) {
            $this->message->addInfo('role', $role);
            $lied = new SongsController();
            $this->message->addInfo( 'songinfo', $lied->show($id) );
        }

        // $this->message->addMessage('id = ' . $id);
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
