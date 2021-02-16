<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Message extends Controller
{
    private $messages = [];
    private $errors = [];
    private $auth = null;
    private $info = ['messages' => null, 'errors' => null, 'status' => '', 'auth' => null ];

    private function renderMessages() {
        if ( count($this->messages) === 0) {
            unset( $this->info['messages'] );
        } else {
            if ( count($this->messages) == 1 ) {
                $this->info['messages'] = $this->messages[0];
            } else {
                $this->info['messages'] = $this->messages;
            }
        }
    }

    private function renderErrors() {
        if ( count($this->errors) === 0) {
            unset( $this->info['errors'] );
            $this->info['status'] = 'success';
        } else {
            if ( count($this->errors) == 1 ) {
                $this->info['errors'] = $this->errors[0];
            } else {
                $this->info['errors'] = $this->errors;
            }

            $this->info['status'] = 'failure';
        }
    }

    private function renderAuth() {        
        if ($this->auth === true) {
            $this->info['auth'] = 'success';
        } else {
            $this->info['auth'] = 'failed';
            $this->addError('authFailed');
        }
    }

    public function setAuth(bool $auth, string $message = NULL) {
        if ($auth === true && $this->auth !== false) {
            $this->auth = $auth;
        
        } else if ($auth === false) {
            $this->auth = $auth;
            if ($message !== NULL) {
                $this->addMessage($message);
            }
        } else {
            $this->addError('invalid AuthSetting');
        }
    }

    public function addMessage(string $message) {
        $this->messages[] = $message; 
    }

    public function addError(string $error) {
        $this->errors[] = $error;
    }

    public function addInfo(string $key, $value) {
        if ( isset($this->info[$key]) ) {
            $this->addMessage = 'cannot overwrite previous info on the same key';
            return false;
        }

        $this->info[$key] = $value;
    }

    public function retrieve() {
        $this->renderAuth();
        $this->renderMessages();
        $this->renderErrors();

        return response()->json($this->info);

        // echo json_encode($this->info);
    }
}
