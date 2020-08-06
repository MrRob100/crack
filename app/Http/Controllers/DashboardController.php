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

        $para_a = $para == "" ? "" : $para."/";

        $tunes_ordered = glob('storage/data/'.$para_a.'*.mp3');
        usort($tunes_ordered, function($a, $b) {
            return filemtime($a) < filemtime($b);
        });

        $tunes = [];
        foreach ($tunes_ordered as $tune_ordered) {
            $tunes[] = str_replace('storage/data/'.$para_a, '', $tune_ordered);
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

        /*
            .wav = 'audio/x-wav'
            .mp3 = 'audio/mpeg'
        */
        if ($typ === 'audio/mpeg' || $typ === 'audio/x-wav') {

            //removes spaces in name
            $song_name = str_replace(" ", "_", $file->getClientOriginalName()); 

            $path = $request->file('song')->store('upload');

            $file->move('storage/data/'.$subdir, $song_name);

            $path_bare = substr($path, 7);
            $path_full = '../storage/data/'.$subdir.$path_bare; 

            $path = 'storage/data/';

            if ($typ === 'audio/mpeg') {
                //only works on server
                exec('/usr/bin/ffmpeg -i '.$path.$subdir.$song_name.' -ab 110k '.$path.$subdir.'_'.$song_name, $o, $r);
                // exec('/usr/local/bin/ffmpeg -i '.$path.'mass.mp3 -ab 64 '.$path.rand().'.mp3', $o, $r);
                // dump('1st (compress)');
                // dump($o);
                // dd($r);
            }

            if ($typ === 'audio/x-wav') {
                // ffmpeg -i tenniscourt.wav -f mp2 tenniscourt.mp3

                
                
                //only works on server
                exec('/usr/bin/ffmpeg -i '.$path.$subdir.$song_name.' -f mp2 '.$path.$subdir.'_'.str_replace($song_name, '.wav', '').'.mp3', $o, $r);
            }

                // dump('2nd (convert');
                // dump($o);
                // dd($r);

            //deletes raw if compression worked
            if ($r === 0) {
                unlink($path.$subdir.$song_name);
            }

        } else {

            return redirect($para);
        }

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

        $markers = json_decode(file_get_contents('../public/data/markerData.json'), true);
        
        unset($markers[$_GET['song']]);
        
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