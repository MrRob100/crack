@extends('layouts.app')

@section('content')

<div class="contr to-blur">
    <form class="upl" id="song-upload" action="{{ $para }}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input id="song-file-input" type="file" name="song" onchange="ch()">
    </form>
    <div class=playback>
        <p class="playback-item playback-speed">Speed: --</p><p class="playback-item playback-phase">Phaser: --</p>
    </div>
</div>

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

</style>

<script>

//upload
function ch() {
    $('#song-upload').submit();
}

</script>