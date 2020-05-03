@extends('layouts.app')

@section('content')

<div class="contr">
    <form class="upl" id="song-upload" action="{{ $para }}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input id="song-file-input" type="file" name="song" onchange="ch()">
    </form>
    <div class=playback>
        <p class="playback-item playback-speed">Speed: --</p><p class="playback-item playback-phase">Phaser: --</p>
    </div>

</div>
    {{-- @foreach ($tunes as $tune)
    <Tune-Stack
    para={{ $para }}
    name={{ $tune }}
    pos={{ array_search($tune, $tunes) }}
    ></Tune-Stack>
    @endforeach --}}

    <Ctx
    tunes="{{ $t_string }}"
    para="{{ $para }}"
    ></Ctx>

@endsection

<style>

.playback {
    clear: left;
}

.playback-item {
    display: inline;
    font-size: 18px;
}

.playback-phase {
    margin-left: 15px;
}

html, body {
    height: 100%;
    overflow-x: hidden;
    touch-action: none;
    background-color: rgb(50, 2, 95) !important;
    font-family: 'Courier New', Courier, monospace !important;
}
body {
    width: 100%;
    position: relative;
    color: #B27FFF !important;
}

.navbar {
    height: 20px;
}

.stack-del button {
    display: none;
}

.upl {
    float: left;
}

.dbld {
    /* background-color: aqua !important; */
}

.dl-icon {
    filter: brightness(30%);
    position: absolute;
    right: 0;
    width: 40px;
    top: 0px;
}

.dld {
    position: absolute;
}

.bld:hover {
    cursor: pointer;
    background-color: rgb(110, 78, 158) !important;
}

.strip-play {
    cursor: pointer;
    background-color: rgb(110, 78, 158) !important;
}

</style>

<script>

//upload
function ch() {
    $('#song-upload').submit();
}

setInterval(function() {
    //new
    var strp = $('.strip-play');
    var strs = $('.strip-stop');

    if (strp.length == 1) {
        strs.addClass('dbld');
        strs.removeClass('bld');
    } else {
        strs.addClass('bld');
        strs.removeClass('dbld');
    }

}, 500);

</script>