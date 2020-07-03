@extends('layouts.app')

@section('content')

<button id="playpause">Play</button>

<div class="contr to-blur">
    <form class="upl" id="song-upload" action="{{ $para }}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input id="song-file-input" type="file" name="song" onchange="ch()">
    </form>
</div>

<Ctx
tunes="{{ $t_string }}"
para="{{ $para }}"
></Ctx>

@endsection

<script>

//upload
function ch() {
    $('#song-upload').submit();
}

const context = new (window.AudioContext || window.webkitAudioContext)()
const loopUrl = 'storage/data/_snakes.mp3'

const source = context.createBufferSource();

var request = new XMLHttpRequest();
request.open('GET', loopUrl, true);
request.responseType = 'arraybuffer';

request.onload = function() {
    var audioData = request.response;

    context.decodeAudioData(audioData, function(buffer) {
        var myBuffer = buffer;
        source.buffer = myBuffer;
        source.loop = true;
        source.connect(context.destination);
    },
    function (e) {
        "Error decoding audio data"
    });
}

request.send();

var playpause = document.getElementById('playpause');
playpause.onclick = function() {
    console.log('starting');
    source.start(0);
}

</script>