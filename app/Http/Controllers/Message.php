<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Message extends Controller
{
    private $messages = [];
    private $errors = [];
    private $info = ['messages' => null, 'errors' => null, 'status' => ''];

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

    public function addMessage($message) {
        $this->messages[] = $message; 
    }

    public function addError($error) {
        $this->errors[] = $error;
    }

    public function addInfo($key, $value) {
        if ( isset($this->info[$key]) ) {
            $this->addMessage = 'cannot overwrite previous info on the same key';
            return false;
        }

        $this->info[$key] = $value;
    }

    public function retrieve() {
        $this->renderMessages();
        $this->renderErrors();

        echo json_encode($this->info);
    }
}