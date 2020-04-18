inp = document.querySelector('input');

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

        console.log('in play');
        quest();

        sourceNode.start(0, offset);

        sourceNode.playbackRate.value = 0.9;

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

    console.log('inside');

    // var play = document.querySelector('[data-js="play"]'),
    //     stop = document.querySelector('[data-js="stop"]'),
    //     info = document.querySelector('[data-js="info"]');

    var play = document.getElementsById("play");

    play.addEventListener('click', function () {
        console.log('playin');
        if (sound.getPlaying()) {
            sound.pause();
            play.innerHTML = 'play';
        } else {
            sound.play();
            play.innerHTML = 'pause';
        }
    });
    stop.addEventListener('click', function () {
        sound.stop();
        play.innerHTML = 'play';
    });

    function update() {
        window.requestAnimationFrame(update);
        info.innerHTML = sound.getCurrentTime().toFixed(1) + ' / ' + sound.getDuration().toFixed(1);
        inp.value = sound.getCurrentTime().toFixed(1);
        inp.max = sound.getDuration().toFixed(1);
    }
    update();
};

var el = document.createElement('audio'),
    url = '../public/storage/data/Bill-Vubey.mp3';

var request = new XMLHttpRequest();

function quest() {
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
}
