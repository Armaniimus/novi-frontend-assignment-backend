<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Roles;

class AccountsController extends Controller {
    public function __construct(Message $message) {
        $this->validate = new Validate($message);
        $this->message = $message;
        $this->minPassLength = 8;
        $this->requiredUsernameLength = 3;
    }

    public function index() {
        $this->setAccountInfo( Users::all('id', 'name', 'role_id') );
    }

    public function create($accountName, $password, $roleID) {
        $accountName = trim($accountName);
        $password = trim($password);

        if ( $this->validateInput($accountName, $password, $roleID) ) {
            if (strlen($accountName) < $this->requiredUsernameLength) {
                $this->message->addError('Gebruikersnaam moet minstens ' . $this->requiredUsernameLength .' lang zijn');
                return;
            }

            $oldUser = Users::select('id', 'name', 'role_id')->where('name', $accountName)->first();
            if ( $oldUser === NULL) {
                $user = new Users();
                $user->name = $accountName;
                $user->password = password_hash($password, PASSWORD_DEFAULT);
                $user->role_id = $roleID;
                $user->save();

                $this->setAccountInfo($user);
                $this->message->addMessage('Account creation is succesfull');
            } else {
                $this->message->addError('Account with this username allready exists');
            }
        }
    }

    public function update($accountId, $accountName, $password, $roleID) {
        $accountName = trim($accountName);
        $password = trim($password);

        if ( $this->validateInput($accountName, $password, $roleID, 'update') ) {
            if (strlen($accountName) < $this->requiredUsernameLength) {
                $this->message->addError('Gebruikersnaam moet minstens ' . $this->requiredUsernameLength .' lang zijn');
                return;
            }

            $user = Users::select('id', 'name', 'role_id', 'password')->find($accountId);
            if ( $user !== NULL ) {
                $user->name = $accountName;
                if (strlen($password) < $this->minPassLength ) {
                    $this->message->addMessage('passlen' . strLen($password) );
                    $user->password = $user->password;
                } else {
                    $user->password = password_hash($password, PASSWORD_DEFAULT);
                }
                $user->role_id = $roleID;
                $user->save();

                $this->setAccountInfo($user);
                $this->message->addMessage('Account update is succesfull');
            } else {
                $this->message->addError('Account couldn\'t be found');
            }
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
        if ( !$this->validate->id($roleID, 'Rol') ) {return false;}

        $role = Roles::find($roleID);
        if ( $role === NULL) {
            $this->message->addError('Geen geldige rol');
            return false;
        }

        if ( !$this->validate->string_htmlspecialchars($accountName, 'accountName') ) {return false;}

        if ( strlen($password) < $this->minPassLength ) {
            if ( strlen($password) === 0 && $mode === 'update' ) {
                $this->message->addMessage('Password not set');
            } else {
                $this->message->addError('Wachtwoord is te kort');
                return false;
            }
        }

        return true;
    }

    private function setAccountInfo($info) {
        $this->message->addInfo('accountInfo', $info);
    }
}
