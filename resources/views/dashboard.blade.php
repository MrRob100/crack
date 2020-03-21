@extends('layouts.app')

@section('content')

<div class="container">
    <form class="upl" id="song-upload" action="dashboard" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input id="song-file-input" type="file" name="song" onchange="ch()">
        <div style="display:none" id="path">{{ $path_full }}</div>
    </form>
    <h1 class="tit">CRACK MANINOFF</h1>
    {{-- @foreach ($tunes as $tune)
    <Tune-Strip
    name={{ $tune }}
    pos={{ array_search($tune, $tunes) }}
    ></Tune-Strip>
    @endforeach --}}
</div>
@foreach ($tunes as $tune)
<Tune-Stack
name={{ $tune }}
pos={{ array_search($tune, $tunes) }}
></Tune-Stack>
@endforeach
@endsection

<style>

html, body {
    overflow-x: hidden;
    touch-action: none;
    background-color: rgb(70, 2, 95) !important;
}
body {
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

</style>

<script>

//upload
function ch() {
    $('#song-upload').submit();
}

setInterval(function() {
    var playing = false;
    var sp = $('.stack-play');

    sp.each(function(k, v) {
        if (v.childNodes[0].innerHTML.includes('stop')) {
            $('.btn-crack-play').hide();
            playing = true;
        } else {
            if (!playing) {
                $('.btn-crack-play').show();
            }
        }
    })
}, 500);

</script>