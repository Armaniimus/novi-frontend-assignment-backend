<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Songs;

class SongsController extends Controller {
    public function index() {
        return Songs::all('id', 'number', 'title');
    }

    public function show($id) {
        return Songs::select(['id','number', 'title', 'songText'])->find(2);
    }
}
