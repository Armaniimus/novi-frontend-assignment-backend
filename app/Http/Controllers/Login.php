<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Http\Controllers\Auth;
// use App\Http\Controllers\Message;

// class Login extends Controller {
//     public function __construct() {
//         $this->message = new Message();
//     }

//     public function run() {
//         ['username' => $user, 'password' => $pass] = $this->retrievePost(['username', 'password'], $_REQUEST);

//         $auth = new Auth($this->message);
//         $key = $auth->login($user, $pass);

//         $this->message->retrieve();
//     }

//     private function retrievePost(array $dataIndexes, array $data) {
//         $returnData = [];
//         foreach ($dataIndexes as $index) {
//             if ( isset($data[$index]) ) {
//                 $returnData[$index] = $data[$index];
//                 continue;
//             } else {
//                 $returnData[$index] = '';
//                 continue;
//             }            
//         }

//         return $returnData;
//     }
// }
