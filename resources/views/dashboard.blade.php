@extends('layouts.app')

@section('content')

<div class="container">
    <form id="song-upload" action="dashboard" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input id="song-file-input" type="file" name="song" onchange="ch()">
        <div style="display:none" id="path">{{ $path_full }}</div>
    </form>
    <br>
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
}
body {
    position: relative;
}

.navbar {
    height: 20px;
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