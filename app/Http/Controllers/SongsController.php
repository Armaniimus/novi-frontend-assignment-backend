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

    public function show($id) {
        $this->setLiedInfo( Songs::select(['id','number', 'title', 'songText'])->find($id) );
    }

    public function create($number, $title) {
        if ( $this->validateInput($number, $title) ) {
            $oldTitleSong = Songs::select('id')->where('title', $title)->first();
            $oldNumberSong = Songs::select('id')->where('number', $number)->first();
            
            if ( $oldTitleSong === NULL ) {
                $this->message->addError('Song this title allready exists');

            } else if ( $oldNumberSong === NULL ) {
                $this->message->addError('Song this number allready exists');

            } else {
                $title = htmlspecialchars($title);
                $song = new Songs();
                $song->number = $number;
                $song->title = $title;
                $song->songText = '';
                $song->save();

                $this->setLiedInfo($song);
                $this->message->addMessage('Song creation is succesfull');
            }
        }
    }

    public function update($id, $number, $title) {
        if ( $this->validateID($id, 'id') && $this->validateInput($number, $title) ) {
            $song = Songs::select('id', 'title', 'number')->find($id);
            $oldTitleSong = Songs::select('id')->where('title', $title)->first();
            $oldNumberSong = Songs::select('id')->where('number', $number)->first();
            
            if ( $oldTitleSong === NULL ) {
                $this->message->addError('Song this title allready exists');

            } else if ( $oldNumberSong === NULL ) {
                $this->message->addError('Song this number allready exists');

            } if ( $song !== NULL ) {
                $title = htmlspecialchars($title);

                $song->title = $title;
                $song->number = $number;
                $song->save();

                $this->setLiedInfo($song);
                $this->message->addMessage('Song update is succesfull');
            } else {
                $this->message->addError('Song couldn\'t be found');
            }
        }
    }

    public function updateSongtext($id, $songText) {
        if ( $this->validateID($id, 'id') && $this->validateInput($number, $title) ) {
            $song = Songs::select('id', 'title', 'number')->find($id);
            
            if ( $song !== NULL ) {
                $songText = htmlspecialchars($songText);
                $song->songText = $songText;
                $song->save();

                $this->setLiedInfo($song);
                $this->message->addMessage('Songtext update of the song is succesfull');
            } else {
                $this->message->addError('Song couldn\'t be found');
            }
        }
    }

    public function delete($id) {
        if ( $this->validateID($id, 'id') ) {
            $song = Songs::select('id')->find($id);
            if ( $song !== NULL ) {
                $song->delete();
                $this->message->addMessage('Song deletion is succesfull');
            } else {
                $this->message->addError('Song couldn\'t be found');
            }            
        }
    }

    private function validateInput($number, $title) {
        if ( !$this->validateID($number, 'number') ) { return false; }
        if ( !$this->validateString($title, 'title') ) { return false;}

        return true;
    }

    private function setLiedInfo($info) {
        $this->message->addInfo('songInfo', $info);
    }
}
