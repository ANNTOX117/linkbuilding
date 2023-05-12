<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AjaxController extends Controller {

    public function cookies(Request $request){
        $response = new Response('Cookies');
        $response->withCookie(cookie()->forever('cookies', true));
        return $response;
    }

}
