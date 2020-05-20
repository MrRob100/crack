<button id="playpause">Play</button>
<input class="phasecontrol" type="range" min="0" max="1" step="0.1" value="0">

<script>

const context = new (window.AudioContext || window.webkitAudioContext)()
const loopUrl = '/crack/public/storage/data/bunny.mp3'

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
    source.start(0);
}