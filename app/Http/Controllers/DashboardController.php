<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index() {

        ini_set('max_post_size', 0);

        $path_full = 'nopath';
        
        $tunes = scandir('storage/data/');
        array_shift($tunes);
        array_shift($tunes);

        return view('dashboard', compact('path_full', 'tunes'));
    }

    public function upload(Request $request) {

        $file = $request->file('song');
        $typ = $file->getMimeType(); 
        if ($typ === 'audio/mpeg') {

            //removes spaces in name
            $song_name = str_replace(" ", "_", $file->getClientOriginalName()); 

            $path = $request->file('song')->store('upload');

            $file->move('storage/data/', $song_name);

            $path_bare = substr($path, 7);
            $path_full = '../storage/data/'.$path_bare; 
            // $path_full = '../public/storage/data/'.$path_bare; 

        } else {
            return redirect('');
        }

        return redirect('');
    }

    public function dl() {
        try {
            // return Storage::download(app_path('../public/storage/data/'.$_GET['song']));
            return Response()->download('storage/data/'.$_GET['song']);
        } catch (exception $e) {
            dump($e);
        }
    }

    public function delete() {
        try {
            unlink('storage/data/'.$_GET['song']);
        } catch (exception $e) {
            //log exep
            return "";
        }
        return 'deleted';
    }

    public function getMarker() {

        $position = $_GET['position'];
        $markers = json_decode(file_get_contents('../public/data/markerData.json'), true);
        return array_key_exists($position, $markers) ? $markers[$position] : null;
    }

    public function setMarker() {
        $position = $_GET['position'];
        $which = $_GET['which'];
        $val = $_GET['value'];
        $markers = json_decode(file_get_contents('../public/data/markerData.json'), true);
        $markers[$position][$which] = $val;
        file_put_contents('../public/data/markerData.json', json_encode($markers));  

    }
 
}