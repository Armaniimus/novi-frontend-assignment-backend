<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Roles;

class AccountsController extends Controller {
    public function __construct($message) {
        $this->minPassLength = 3;
        $this->message = $message;
    }

    public function index() {
        $this->setAccountInfo( Users::all('id', 'name', 'role_id') );
    }

    public function create($accountName, $password, $roleID) {      
        if ( $this->validateInput($accountName, $password, $roleID) ) {
            $oldUser = Users::select('id', 'name', 'role_id')->where('name', $accountName)->first();
            if ( $oldUser === NULL) {
                $user = new Users();
                $user->name = $accountName;
                $user->password = password_hash($password, PASSWORD_DEFAULT);
                $user->role_id = $roleID;
                $user->token = '';
                $user->save();

                $this->setAccountInfo($user);
                $this->message->addMessage('Account creation is succesfull');
            } else {
                $this->message->addError('Account with this username allready exists');
            }            
        } else {
            $this->message->addError('invalidValidation failed');
        }
    }

    public function update($accountId, $accountName, $password, $roleID) {
        if ( $this->validateInput($accountName, $password, $roleID, 'update') ) {
            $user = Users::select('id', 'name', 'role_id')->find($accountId);
            if ( $user !== NULL ) {
                $user->name = $accountName;
                if (strLen($password) >= $this->minPassLength ) {
                    $user->password = password_hash($password, PASSWORD_DEFAULT);
                }
                $user->role_id = $roleID;
                $user->save();

                $this->setAccountInfo($user);
                $this->message->addMessage('Account update is succesfull');
            } else {
                $this->message->addError('Account couldn\'t be found');
            }
        } else {
            $this->message->addError('invalidValidation failed');
        }
    }

    public function delete($accountId) {
        $user = Users::select('id', 'name', 'role_id')->find($accountId);
        if ( $user !== NULL ) {
            $user->delete();
            $this->message->addMessage('Account deletion is succesfull');
        } else {
            $this->message->addError('Account couldn\'t be found');
        }
    }

    private function validateInput($accountName, $password, $roleID, $mode = 'create') {
        if ( !is_int($roleID * 1) ) {
            $this->message->addError('roleID not an integer');
            return false;
        }
        
        $role = Roles::find($roleID);
        if ( $role === NULL) {
            $this->message->addError('invalid roleID');
            return false;
        }

        if ( strlen($accountName) > 0 && $accountName !== htmlspecialchars($accountName) ) {
            $this->message->addError('invalid accountName');
            return false;
        }

        if ( strlen($password) < $this->minPassLength ) {
            if ( strlen($password) === 0 && $mode === 'update' ) {
                $this->message->addMessage('Password not set');
            } else {
                $this->message->addError('Password too short');
                return false;
            }
        }

        return true;
    }

    private function setAccountInfo($info) {
        $this->message->addInfo('accountInfo', $info);
    }
}