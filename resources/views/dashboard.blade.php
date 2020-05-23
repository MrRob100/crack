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

<script>

//upload
function ch() {
    $('#song-upload').submit();
}

</script>