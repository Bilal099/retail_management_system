<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TempleteController extends Controller
{
    public function index($id)
    {
        if(view()->exists('template.'.$id)){
            return view('template.'.$id);
        }
        else
        {
            return view('template.404');
        }
    }
}
