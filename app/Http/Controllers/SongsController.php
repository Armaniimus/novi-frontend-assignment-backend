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

    public function create(int $number, string $title) {
        if ( $this->validateInput($number, $title) ) {            
            $doubleItem = Songs::select('id')->where('number', $number)->first();
            
            if ( $doubleItem !== NULL ) {
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

    public function update(int $id, int $number, string $title) {
        if ( $this->validate->id($id, 'id') && $this->validateInput($number, $title) ) {
            $song = Songs::select('id', 'title', 'number')->find($id);
            $doubleItem = Songs::select('id')->where('number', $number)->first();
            
            if ( $doubleItem !== NULL && $id !== $doubleItem->id ) {
                $this->message->addInfo( 'test', [$doubleItem->id, $id] );
                $this->message->addError('Song this number allready exists');

            } else if ( $song !== NULL ) {
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

    public function updateSongtext(int $id, string $songText) {
        if ( $this->validate->id($id, 'id') && $this->validateInput($number, $title) ) {
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

    public function delete(int $id) {
        if ( $this->validate->id($id, 'songid') ) {
            $song = Songs::select('id')->find($id);
            if ( $song !== NULL ) {
                $song->delete();
                $this->message->addMessage('Song deletion is succesfull');
            } else {
                $this->message->addError('Song couldn\'t be found');
            }            
        }
    }

    private function validateInput(int $number, string $title) {
        if ( !$this->validate->id($number, 'number') ) { return false; }
        if ( !$this->validate->string_htmlspecialchars($title, 'title') ) { return false;}

        return true;
    }

    private function setLiedInfo($info) {
        $this->message->addInfo('songInfo', $info);
    }
}
