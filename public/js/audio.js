
// const AudioContext = window.AudioContext || window.webkitAudioContext;
// const audioCtx = new AudioContext();

// songs = document.getElementsByClassName('tunes');

// request = new XMLHttpRequest();

// var gainNode = audioCtx.createGain();

//     function songRequest(song) {

//         if (songs !== undefined) {
//             var songPath = '../public/storage/data/' + songs[song].innerHTML;
//         }

//         return songPath;
//     }

//     var playing = false;
//     document.onkeydown = function (evt) {
//         evt = evt || window.event;

//         if (evt.keyCode !== 32) {
//             if (playing) {
//                 console.log('prestop');
//                 source.stop();
//                 playing = false;
//             } else {
//                 console.log('preplay');
//                 songPath = songRequest(evt.keyCode - 49);
//                 getData(songPath);
//             }

//         } else {
//             source.stop();
//             playing = false;
//         }
//     };

// function getData(songPath) {

//     request.open('GET', songPath, true);

//     source = audioCtx.createBufferSource();
//     // source2 = audioCtx.createBufferSource();

//     playing = true;

//     request.responseType = 'arraybuffer';

//     request.onload = function () {
//         var audioData = request.response;

//         audioCtx.decodeAudioData(audioData, function (buffer) {
//                 myBuffer = buffer;
//                 source.buffer = myBuffer;

//                 // var gainNode = audioCtx.createGain();

//                 source.connect(audioCtx.destination);

//                 const delayNode = new DelayNode(audioCtx, {
//                     delayTime: 0.5,
//                     maxDelayTime: 2,
//                 });

//                 const filts = new BiquadFilterNode(audioCtx, {
//                     'type': 'lowpass'
//                 });

//                 source.connect(gainNode);

//                 gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);

//                 //fetch from html
//                 var intoffset = document.getElementById('mydiv1').style.left.replace('px', '');
//                 source.start(0, intoffset);
//             },

//             function (e) {
//                 "Error with decoding audio data" + e.err
//             });
//     }

//     request.send();
// } 
