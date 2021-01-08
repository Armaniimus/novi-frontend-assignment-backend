<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Songs;

class SongsController extends Controller {
    public function __construct() {
        $this->songs = new Songs();
    }

    public function index() {
        $songs = Songs::all('id', 'number', 'title');
        return $songs;
    }
}
