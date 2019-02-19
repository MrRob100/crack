<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GettestController extends Controller
{
    public function index()
    {
        return view('dashnarrowclient');
    }

    public function update()
    {
        dd($_GET);
        return view('dashnarrowclient');
    }
}
