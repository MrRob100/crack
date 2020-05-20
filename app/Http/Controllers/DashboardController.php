<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use App\Services\UrlService;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index($para = "") {

        $checker = App::make('App\Services\UrlService');
        $checked = $checker->checkUrl($para);

        if (!$checked) {
            return redirect('');
        }

        ini_set('max_post_size', 0);

        if (!file_exists('storage/data/'.$para)) {
            //make it self destruct
            mkdir('storage/data/'.$para);
            chmod('storage/data/'.$para, 0777);
        }

        $items = scandir('storage/data/'.$para);

        array_shift($items);
        array_shift($items);

        $tunes = [];
        foreach ($items as $item) {
            if (strpos($item, '.mp3') !== false) {
                $tunes[] = $item;
            }
        }

        $para = $para == "" ? '-' : $para;

        $t_string = implode(' ', $tunes); 

        return view('dashboard', compact('t_string', 'para'));
    }

    public function upload(Request $request, $para) {

        if ($para === '-' || $para === '') {
            $subdir = '';
        } else {
            $subdir = $para."/";
        }

        $file = $request->file('song');
        $typ = $file->getMimeType(); 
        // if ($typ === 'audio/mpeg') {

            //removes spaces in name
            $song_name = str_replace(" ", "_", $file->getClientOriginalName()); 

            $path = $request->file('song')->store('upload');

            $file->move('storage/data/'.$subdir, $song_name);

            $path_bare = substr($path, 7);
            $path_full = '../storage/data/'.$subdir.$path_bare; 

            $path = 'storage/data/';

            //compresses file
            exec('/usr/bin/ffmpeg -i '.$path.$subdir.$song_name.' -ab 64 '.$path.$subdir.'_'.$song_name, $o, $r);
            // exec('/usr/local/bin/ffmpeg -i '.$path.'mass.mp3 -ab 64 '.$path.rand().'.mp3', $o, $r);

            //deletes raw if compression worked
            if ($r === 0) {
                unlink($path.$subdir.$song_name);
            }

            // exec("/usr/local/bin/ffmpeg",$o, $r);
            // dump($r);

        // } else {
        //     return redirect($para);
        // }

        return redirect($para);
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

        $para = $_GET['para'] == '-' ? '' : $_GET['para'].'/';

        try {
            unlink('storage/data/'.$para.$_GET['song']);
        } catch (exception $e) {
            //log exep
            return "";
        }
        return 'deleted';
    }

    public function getMarker() {

        $position = $_GET['position'];
        $which = $_GET['which'];
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

    public function ctx() {

        $items = scandir('storage/data/');

        array_shift($items);
        array_shift($items);

        $tunes = [];
        foreach ($items as $item) {
            if (strpos($item, '.mp3') !== false) {
                $tunes[] = $item;
            }
        }

        $t_string = implode(' ', $tunes); 

        return view('ctx', compact('t_string'));
    }

    public function phaser() {
        return view('phaser');
    }
 
    public function allpass() {
        return view('allpass');
    }

 
}