<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Validate extends Controller
{
    public function __construct(Message $message) {
        $this->message = $message;
    }

    public function id( $id, $inputname = 'unknown input') {
        if ($id === null || $id === '') {
            $this->message->addError($inputname . ' heeft geen waarde');
            return false;
        } if ( !is_int((int) $id) ) {
            $this->message->addError($inputname . ' is geen integer getal');
            return false;
        }
        return true;
    }

    public function string_stripTags(string $string, string $inputname) {
        if ( $string !== strip_tags($string) ) {
            $this->message->addError($inputname . ' heeft niet toegestane characters');
            return false;
        }
    }

    public function string_htmlspecialchars(string $string, string $inputname) {
        if ( $string !== htmlspecialchars($string) ) {
            $this->message->addError($inputname . ' heeft niet toegestane characters');
            return false;
        }
        return true;
    }
}
