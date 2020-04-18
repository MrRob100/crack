<h1>ALREADY LOADED</h1>
<form id="song-upload-loaded" action="upload-song-loaded" method="post" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <input id="song-file-input" type="file" name="song">
<div style="display:none" id="path">{{ $path_full }}</div>
<br>
<br>
@foreach ($tunes as $tune)
    <div class="tunes">{{ $tune }}</div>
@endforeach
</form>
<script>

const AudioContext = window.AudioContext || window.webkitAudioContext;
const audioCtx = new AudioContext();

//song: int
function getSongPath(song) {
    var songs = document.getElementsByClassName('tunes');
    if (songs !== undefined) {
        var songPath = '../public/storage/data/' + songs[song].innerHTML;
    }
    return songPath;
}

//audio api stuff
function makeSource(url) {
    request = new XMLHttpRequest();
    request.open('GET', url, true);
    source = audioCtx.createBufferSource();
    request.responseType = 'arraybuffer';

    request.onload = function () {
        var audioData = request.response;
        audioCtx.decodeAudioData(audioData, function (buffer) {
            myBuffer = buffer;
            source.buffer = myBuffer;

            source.connect(audioCtx.destination);
        },
        function (e) {
            "Error with decoding audio data" + e.err
        });;
    }
    request.send();
    return source;
}

// source0 = makeSource(getSongPath(0));
source1 = makeSource(getSongPath(1));

//play stop0
var playing0 = false;
document.onkeydown = function (evt) {
    evt = evt || window.event;
    if (evt.keyCode == 49) {
        console.log('fff');
        if (!playing0) {
            source0.start();
            playing0 = true;
        } else {
            source0.stop();
        }
    }
};

//play stop1
var playing1 = false;
document.onkeydown = function (evt) {
    evt = evt || window.event;
    if (evt.keyCode == 50) {
        if (!playing0) {
            // source1.start(0, 10); //defo offset
            source1.start(0, 60);
            playing1 = true;
        } else {
            source1.stop();
        }
    }
};

</script>