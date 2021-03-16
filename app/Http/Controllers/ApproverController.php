<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApproverController extends Controller
{
    public function get(){
        $user = auth()->user();

        return $user;
    }
}
