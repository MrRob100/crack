@extends('layouts.app')

@section('content')

<div class="container">
    <form class="upl" id="song-upload" action="" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input id="song-file-input" type="file" name="song" onchange="ch()">
        <div style="display:none" id="path">{{ $path_full }}</div>
    </form>
    <h2 class="tit">CRACK MANINOFF</h2>
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
    background-color: rgb(50, 2, 95) !important;
    font-family: 'Courier New', Courier, monospace !important;
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

.dbld {
    /* background-color: aqua !important; */
}

.dl-icon {
    position: absolute;
    right: 20px;
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

    //old
    // var playing = false;
    // var sp = $('.stack-play');

    // sp.each(function(k, v) {
    //     if (v.childNodes[0].innerHTML.includes('stop')) {
    //         $('.btn-crack-play').hide();
    //         playing = true;
    //     } else {
    //         if (!playing) {
    //             $('.btn-crack-play').show();
    //         }
    //     }
    // })

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