@extends('layouts.app')

@section('content')

{{-- <button id="playpause">Play Outside Vue</button> --}}

<br>
<br>

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

// setTimeout(function() {

//     var playpause = document.getElementById('playpause');


//     playpause.onclick = function() {
//         source.start(0);
//     }


//     const context = new (window.AudioContext || window.webkitAudioContext)()
//     const loopUrl = 'storage/data/_snakes.mp3'

//     const source = context.createBufferSource();

//     var request = new XMLHttpRequest();
//     request.open('GET', loopUrl, true);
//     request.responseType = 'arraybuffer';

//     request.onload = function() {
//         console.log('onload');
//         var audioData = request.response;

//         context.decodeAudioData(audioData, function(buffer) {
//             var myBuffer = buffer;
//             source.buffer = myBuffer;
//             // source.loop = true;
//             source.connect(context.destination);
//         },
//         function (e) {
//             "Error decoding audio data"
//         });
//     }

//     request.send();

// }, 2000);

</script>