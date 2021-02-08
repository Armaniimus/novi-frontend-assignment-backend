<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Songs;
use App\Http\Controllers\Validate;

class SongsController extends Controller {
    public function __construct(Message $message) {
        $this->validate = new Validate($message);
        $this->message = $message;
    }

    public function index() {
        $this->setLiedInfo( Songs::all('id', 'number', 'title') );
    }

    public function show(int $id) {
        $this->setLiedInfo( Songs::select(['id','number', 'title', 'songText'])->find($id) );
    }

    public function create($number, $title) {
        if ( $this->validateInput($number, $title) ) {            
            $doubleItem = Songs::select('id')->where('number', $number)->first();
            
            if ( $doubleItem !== NULL ) {
                $this->message->addError('Lied met dit nummer bestaat al');

            } else {
                $title = htmlspecialchars($title);
                $song = new Songs();
                $song->number = $number;
                $song->title = $title;
                $song->songText = '';
                $song->save();

                $this->setLiedInfo($song);
                $this->message->addMessage('Lied succesvol aangemaakt');
            }
        }
    }

    public function update($id, $number, $title) {
        if ( $this->validate->id($id, 'Liedid') && $this->validateInput($number, $title) ) {
            $song = Songs::select('id', 'title', 'number')->find($id);
            $doubleItem = Songs::select('id')->where('number', $number)->first();
            
            if ( $doubleItem !== NULL && $song->id !== $doubleItem->id ) {
                $this->message->addError('Lied met dit nummer bestaat al');

            } else if ( $song !== NULL ) {
                $title = htmlspecialchars($title);

                $song->title = $title;
                $song->number = $number;
                $song->save();

                $this->setLiedInfo($song);
                $this->message->addMessage('Lied update succesvol');
            } else {
                $this->message->addError('Lied bestaat niet');
            }
        }
    }

    public function updateSongtext($id, $songText) {
        if ( $this->validateInput($id, $songText, 'Liedid') ) {
            $song = Songs::select('id', 'title', 'number')->find($id);
            
            if ( $song !== NULL ) {
                $songText = htmlspecialchars($songText);
                $song->songText = $songText;
                $song->save();

                $this->setLiedInfo($song);
                $this->message->addMessage('Liedtekst update successvol');
            } else {
                $this->message->addError('Lied bestaat niet');
            }
        }
    }

    public function delete($id) {
        if ( $this->validate->id($id, 'Liedid') ) {
            $song = Songs::select('id')->find($id);
            if ( $song !== NULL ) {
                $song->delete();
                $this->message->addMessage('Lied succesvol verwijderd');
            } else {
                $this->message->addError('Lied bestaat niet');
            }            
        }
    }

    private function validateInput($number, $title, string $numberVarName = 'Nummer') {
        if ( !$this->validate->id($number, $numberVarName) ) { return false; }
        if ( !$this->validate->string_htmlspecialchars($title, 'Titel') ) { return false;}

        return true;
    }

    private function setLiedInfo($info) {
        $this->message->addInfo('songInfo', $info);
    }
}
