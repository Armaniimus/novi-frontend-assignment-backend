<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth;
use App\Http\Controllers\Message;
use App\Http\Controllers\SongsController;
use App\Http\Controllers\AccountsController;
use Illuminate\Http\Request;

class Api extends Controller {
    public function __construct() {
        $this->message = new Message();
        $this->auth = new Auth($this->message);
    }

    public function login() {
        ['username' => $username, 'password' => $password] = $this->retrievePost(['username', 'password'], $_REQUEST);
        $key = $this->auth->login($username, $password);

        $this->message->retrieve();
    }

    public function logout() {
        ['token' => $token] = $this->retrievePost(['token'], $_REQUEST);
        $this->auth->logout($token);

        $this->message->retrieve();
    }

    public function overview() {
        if ($this->checkToken() !== false) {
            $lied = new SongsController($this->message);
            $lied->index();
        }      

        $this->message->retrieve();
    }

    public function overviewSpecific($id) {
        if ($this->checkToken() !== false) {
            $lied = new SongsController($this->message);
            $lied->show($id);
        }

        $this->message->retrieve();
    }

    public function liedbeheer() {
        if ( $this->checkAdmin() ) {
            $lied = new SongsController($this->message);
            $lied->index();
        }

        $this->message->retrieve();
    }

    public function liedbeheerCreate() {
        if ( $this->checkAdmin() ) {
            $lied = new SongsController($this->message);
            $lied->create();
        }

        $this->message->retrieve();
    }

    public function liedbeheerUpdate() {
        if ( $this->checkAdmin() ) {
            ['songid' => $id, 'number' => $number, 'title' => $title] = $this->retrievePost(['songid', 'number', 'title'], $_REQUEST);
            $lied = new SongsController($this->message);
            $lied->update($id, $number, $title);
        }

        $this->message->retrieve();
    }

    public function liedbeheerUpdateSongtext() {
        if ( $this->checkAdmin() ) {
            $lied = new SongsController($this->message);
            $lied->updateSongtext();
        }

        $this->message->retrieve();
    }

    public function liedbeheerDelete() {
        if ( $this->checkAdmin() ) {
            ['songid' => $id] = $this->retrievePost(['songid'], $_REQUEST);
            $lied = new SongsController($this->message);
            $lied->delete($id);
        }

        $this->message->retrieve();
    }

    public function accountbeheer() {
        if ( $this->checkAdmin() ) {
            $account = new AccountsController($this->message);
            $account->index();
        }

        $this->message->retrieve();
    }

    public function accountbeheerCreate() {
        if ( $this->checkAdmin() ) {
            ['accountname' => $accountName, 'password' => $password, 'roleid' => $roleId] = $this->retrievePost(['accountname', 'password', 'roleid'], $_REQUEST);
            $account = new AccountsController($this->message);
            $account->create($accountName, $password, $roleId);
        }

        $this->message->retrieve();
    }

    public function accountbeheerUpdate() {
        if ( $this->checkAdmin() ) {
            ['accountid' => $accountId, 'accountname' => $accountName, 'password' => $password, 'roleid' => $roleID] = $this->retrievePost(['accountid', 'accountname', 'password', 'roleid'], $_REQUEST);
            $account = new AccountsController($this->message);
            $account->update($accountId, $accountName, $password, $roleID);
        }

        $this->message->retrieve();
    }
    
    public function accountbeheerDelete() {
        if ( $this->checkAdmin() ) {
            ['accountid' => $accountid] = $this->retrievePost(['accountid'], $_REQUEST);
            $account = new AccountsController($this->message);
            $account->delete($accountid);
        }

        $this->message->retrieve();
    }

    private function checkAdmin() {
        ['token' => $token] = $this->retrievePost(['token'], $_REQUEST);
        return $this->auth->checkAdmin($token);
    }

    private function checkToken() {
        ['token' => $token] = $this->retrievePost(['token'], $_REQUEST);
        return $this->auth->checkToken($token);
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
