<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index() {
        $path_full = 'nopath';
        
        $tunes = scandir('storage/data/');
        array_shift($tunes);
        array_shift($tunes);

        return view('dashboard', compact('path_full', 'tunes'));
    }

    public function upload(Request $request) {

        $path = $request->file('song')->store('upload');

        $file = $request->file('song');
        $song_name = $file->getClientOriginalName(); 

        dump($file);

        //change name of file
        
        // $file->store('public/data');

        dump(scandir('storage/data'));
        dump(scandir('../storage/data'));

        $file->move('storage/data/', $song_name);

        $path_bare = substr($path, 7);
        $path_full = '../storage/data/'.$path_bare; 
        // $path_full = '../public/storage/data/'.$path_bare; 

        return redirect('dashboard');

        // return view('dashboard', compact('path_full', 'song_name'));
    }

    // public function delete($file) {
    //     storage::delete($file);
    // }

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
        return $markers[$position];
    }

    public function setMarker() {
        $position = $_GET['position'];
        $which = $_GET['which'];
        $val = $_GET['value'];

        $markers = json_decode(file_get_contents('../public/data/markerData.json'), true);
        
        dump($markers);

        $markers[$position][$which] = $val;
    
        file_put_contents('../public/data/markerData.json', json_encode($markers));  

    }

    public function eff()
    {
        $path_full = 'nopath';
        
        $tunes = scandir('storage/data/');
        array_shift($tunes);
        array_shift($tunes);

        return view('eff', compact('path_full', 'tunes'));
    }  
    
    public function uploadEff(Request $request) {

        $path = $request->file('song')->store('upload');

        $file = $request->file('song');
        $song_name = $file->getClientOriginalName(); 

        //change name of file
        
        // $file->store('public/data');

        $file->move('storage/data/', $song_name);

        $path_bare = substr($path, 7);
        $path_full = '../public/storage/data/'.$path_bare; 

        return redirect('eff');

        // return view('dashboard', compact('path_full', 'song_name'));
    }

    public function loaded() {
        $path_full = 'nopath';
        
        $tunes = scandir('storage/data/');
        array_shift($tunes);
        array_shift($tunes);

        return view('loaded', compact('path_full', 'tunes'));
    }

    public function uploadLoaded(Request $request) {

        $path = $request->file('song')->store('upload');

        $file = $request->file('song');
        $song_name = $file->getClientOriginalName(); 

        $file->move('storage/data/', $song_name);

        $path_bare = substr($path, 7);
        $path_full = '../public/storage/data/'.$path_bare; 

        return redirect('loaded');
    }
}