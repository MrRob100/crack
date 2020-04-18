{{-- THE WORKING ONE, MAKE UPLOAD SYSTEM ON THIS ONE --}}
{{-- @extends('layouts.app') --}}
{{-- @section('content') --}}

{{-- <tune-crop></tune-crop> --}}
  <div class="container slider-house">
    <div class="marker" id="mydiv1">
      <div class="markerheader" id="mydiv1header">B</div>
    </div>
    <div class="marker" id="mydiv2">
      <div class="markerheader" id="mydiv2header">E</div>
    </div>
  </div>
<button data-js="play">play</button>
<button data-js="stop">stop</button>
<div data-js="info"></div>
<div class="slidecontainer">
    {{-- <input type="range" min="1" max="100" value="0" class="slider" id="myRange"> --}}
</div>
<form id="song-upload" action="eff" method="post" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <input id="song-file-input" type="file" name="song">
<div style="display:none" id="path">{{ $path_full }}</div>
<br>
<br>
@foreach ($tunes as $tune)
    <div class="tunes">{{ $tune }}</div>
@endforeach
</form>
<style>
.slider-house {
  width: 500px;
  height: 50px;
  background-color: aqua;
  margin: 20px;
}

.marker {
  position: absolute;
  z-index: 9;
  background-color: #f1f1f1;
  border: 1px solid #d3d3d3;
  text-align: center;
}

.markerheader {
  padding: 10px;
  cursor: move;
  z-index: 10;
  background-color: #2196f3;
  color: #fff;
}
</style>
{{-- @endsection --}}
<script type="text/javascript" src="{{ URL::asset('js/drag.js') }}"></script>
{{-- <script type="text/javascript" src="{{ URL::asset('js/upload.js') }}"></script> --}}
<script>
    // inp = document.querySelector('input');

    songs = document.getElementsByClassName('tunes');
    function songRequest(song) {

        var songs = document.getElementsByClassName('tunes');

        if (songs !== undefined) {
            var songPath = '../public/storage/data/' + songs[song].innerHTML;
        }

        return songPath;
    }



    playing = false;
    var AudioContext = AudioContext || webkitAudioContext,
        context = new AudioContext();

    function createSound(buffer, context) {
        var sourceNode = null,
            startedAt = 0,
            pausedAt = 11,
            playing = false;

        var play = function () {
            var offset = pausedAt;

            sourceNode = context.createBufferSource();
            sourceNode.connect(context.destination);
            sourceNode.buffer = buffer;

            var starter = document.getElementById('mydiv1');

            sourceNode.start(0, starter.offsetLeft);
            // sourceNode.start(0, offset);
            // sourceNode.start(0, 60);

            speedDemon(sourceNode);

            startedAt = context.currentTime - offset;
            pausedAt = 0;
            playing = true;

        };

        var pause = function () {
            var elapsed = context.currentTime - startedAt;
            stop();
            pausedAt = elapsed;
        };

        var stop = function () {
            if (sourceNode) {
                sourceNode.disconnect();
                sourceNode.stop(0);
                sourceNode = null;
            }
            pausedAt = 0;
            startedAt = 0;
            playing = false;
        };

        var getPlaying = function () {
            return playing;
        };

        var getCurrentTime = function () {
            if (pausedAt) {
                return pausedAt;
            }
            if (startedAt) {
                return context.currentTime - startedAt;
            }
            return 0;
        };

        var getDuration = function () {
            return buffer.duration;
        };

        return {
            getCurrentTime: getCurrentTime,
            getDuration: getDuration,
            getPlaying: getPlaying,
            play: play,
            pause: pause,
            stop: stop
        };
    }

    var init = function (buffer) {
        var sound = createSound(buffer, context);

        var play = document.querySelector('[data-js="play"]'),
            stop = document.querySelector('[data-js="stop"]'),
            info = document.querySelector('[data-js="info"]');

        play.addEventListener('click', function () {
            if (sound.getPlaying()) {
                sound.pause();
                play.innerHTML = 'play';
            } else {
                sound.play();
                playing = true;
                play.innerHTML = 'pause';
            }
        });
        stop.addEventListener('click', function () {
            sound.stop();
            play.innerHTML = 'play';
        });

            var ender = document.getElementById('mydiv2');
            ender.style.left = sound.getDuration().toFixed(1);

        function update() {
            window.requestAnimationFrame(update);
            info.innerHTML = sound.getCurrentTime().toFixed(1) + ' / ' + sound.getDuration().toFixed(1);
            // inp.value = sound.getCurrentTime().toFixed(1);
            // inp.max = sound.getDuration().toFixed(1);

        }
        update();
    };

    var el = document.createElement('audio'),
        url = '../public/storage/data/Bill-Vubey.mp3';

    var request = new XMLHttpRequest();
    request.open('GET', url, true);
    request.responseType = 'arraybuffer';

    request.addEventListener('load', function () {
        context.decodeAudioData(
            request.response,
            function (buffer) {
                init(buffer);
            },
            function (e) {
                console.error('ERROR: context.decodeAudioData:', e);
            }
        );
    });
    request.send();

    function speedDemon(sourceNode) {
        //speed
        // scroll
        var x = null;
        var y = null;

        document.addEventListener('mousemove', onMouseUpdate, false);
        document.addEventListener('mouseenter', onMouseUpdate, false);

        function onMouseUpdate(e) {
            x = (e.pageX - 100) / 100;
            y = e.pageY;

            gainVal = x < 1 ? x > 0 ? x : 0 : 1;

        //  gainNode.gain.setValueAtTime(gainVal, audioCtx.currentTime);
        
        if (playing) {
            sourceNode.playbackRate.value = (-y + 400) / 250;
        }
        
        // source2.playbackRate.value = (-y + 400) / 250;
        // source2.playbackRate.value = source.playbackRate.value + 0.0001;
        // source2.playbackRate.value = source.playbackRate.value - 0.0001;
        }

        function getMouseX() {
            return x;
        }

        function getMouseY() {
            return y;
        }
    }

document.getElementById('song-file-input').onchange = function() {
    console.log('c');
    document.getElementById('song-upload').submit();
}
</script>
