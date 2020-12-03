<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class CsrfController extends Controller
{
    public function getCsrf()
    {
        return response(csrf_token());
    }
}
