<?php

namespace App\Http\Controllers;

class ApplicationController extends Controller
{
    public function __invoke()
    {
        if (auth()->guest()) {
            return view('login');
        } else {
            return view('application');
        }
    }
}
