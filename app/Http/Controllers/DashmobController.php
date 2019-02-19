<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class DashmobController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashmob');
    }

    public function create()
    {
        //$dsp_mon = $_GET['dsp-mon'];
        $dsp_mon = 5;
        return view('dashmob', compact('dsp_mon'));

    }

    public function read()
    {

    }

    public function update()
    {

    }
}