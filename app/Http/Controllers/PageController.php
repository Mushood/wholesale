<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function welcome(Request $request)
    {

        return view('welcome');
    }

    public function category(Request $request)
    {

        return view('category.index');
    }

    public function contact(Request $request)
    {

        return view('contact');
    }
}
