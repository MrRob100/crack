<template>
  <div v-if="!deleted" class="container plankhouse">

    <!-- Draggable DIV -->
    <div v-if="playing" class="wb-top" :id='"mydiv-ball-"+pos'>
    <!-- Include a header DIV with the same name as the draggable DIV, followed by "header" -->
        <div class="wb-header" :id='"mydiv-ball-"+pos+"-header"'>Click here to move</div>
        <p>Move</p>
        <p>this</p>
        <p>DIV</p>
    </div>


    <div v-if="loading" class="sec sec-play">
        <button disabled>...</button>
    </div>  
    <div v-else class="sec sec-play">
        <button
          v-if="playing"
          v-on:click="stop"
        ><img class="crack-icon" src="images/stop.png"></button>
        <button
          v-else
          v-on:click="play"
        ><img class="crack-icon" src="images/play.png"></button>
    </div>
    <div class="sec sec-name">
    {{ name }}
    </div>
    <div class="sec-crop">
        <tune-crop
        :setting='pos'
        v-bind:source='src'
        v-bind:zerox='zero'
        ></tune-crop>
        <canvas class="canv" :id='"canvas-"+pos' :width="rightWall - leftWall" height="40"></canvas>
        <!-- <canvas class="canv" :id='"canvas-"+pos' :width="end - zero" height="40"></canvas> -->
    </div>
    <div class="sec sec-del">
        <button
        v-on:click="del">
            <img class="crack-icon" src="images/waste.png">
        </button>
    </div>
  </div>
</template>
<script>

import TuneCrop from './TuneCrop';

export default {
    data: function () {

        return {
            src: {},
            playing: false,
            waveWidth: 0,
            filter: {},
            loading: true,
            deleted: false,
            spp: 1,
            zero: 0,
            end: 0,
            left: 0,
            right: 0,
            leftWall: 0,
            rightWall: 0,
        }
    },

    methods: {

        del: function() {
            var request = new XMLHttpRequest();
            request.open('GET', '/crack/public/del?song=' + this.name, true);
            request.send();
            var isso = this;
            request.onload = function () {
                if (request.response == 'deleted') {
                    isso.deleted = true;
                }
            }
        },

        play: function() {
            var leftStart = document.getElementById("div-start-" + this.pos).offsetLeft;
            var offsetPx = (leftStart - this.zero) >0 ? leftStart - this.zero : 0;

            var leftBase = document.getElementsByClassName("sec-crop")[0].offsetLeft;
            var rightBase = document.getElementsByClassName("sec-del")[0].offsetLeft;

            this.zero = leftBase;
            this.end = rightBase;

            this.waveWidth = rightBase - leftBase;

            this.spp = this.src.buffer.duration / this.waveWidth;

            console.log('duration (s)', this.src.buffer.duration);
            console.log('seconds per pixel', this.spp);

            var rsp = offsetPx * this.spp;

            console.log('resupting start point (s)', rsp);

            console.log(this.src);

            this.src.loopStart = rsp;
            this.src.start(0, rsp);

            this.playing = true;
        },

        stop: function() {
            this.src.stop();
            this.playing = false;
            this.src = [];
            this.firstDec();
        },

        firstDec: function () {
            console.log('abbegged');
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                const audioCtx = new AudioContext();
                var request = new XMLHttpRequest();
                const ds1 = this.name;
                const set = this.pos;
                this.getData('../public/storage/data/' + ds1, request, audioCtx);

        },
        getData(songPath, request, audioCtx) {
            request.open('GET', songPath, true);
            const source = audioCtx.createBufferSource();
            request.responseType = 'arraybuffer';

            var dec = this;   
            
            request.onload = function () {

                dec.loading = false;
                var audioData = request.response;

                audioCtx.decodeAudioData(audioData, function (buffer) {

                    var canvas = document.getElementById("canvas-" + dec.pos);
                    dec.drawBuffer( canvas.width, canvas.height, canvas.getContext('2d'), buffer );

                    var myBuffer = buffer;
                    source.buffer = myBuffer;
                
                    //filter bit
                    // source.connect(audioCtx.destination);

                    var filter = audioCtx.createBiquadFilter();
                    filter.type = 'lowpass';

                    source.connect(filter);
                    filter.connect(audioCtx.destination);
                    filter.frequency.value = 24000;

                    //HACKY SET TIMEOUT TO GET SECONDS PER PIXEL
                    setTimeout(function(){

                        dec.spp = dec.src.buffer.duration / dec.waveWidth;

                    }, 3000);

                    source.loop = true;

                    dec.src = source;
                    dec.filter = filter;
                },
                function (e) {
                    "Error with decoding audio data" + e.err
                });
            }
            request.send();
        },
        drawBuffer( width, height, context, buffer ) {
            var data = buffer.getChannelData( 0 );
            var step = Math.ceil( data.length / width );
            var amp = height / 2;
            for(var i=0; i < width; i++){
                var min = 1.0;
                var max = -1.0;
                for (var j=0; j<step; j++) {
                    var datum = data[(i*step)+j]; 
                    if (datum < min)
                        min = datum;
                    if (datum > max)
                        max = datum;
                }
                context.fillRect(i,(1+min)*amp,1,Math.max(1,(max-min)*amp));
            }
        },
        scale() {
            console.log('scaling');
            var startPos = document.getElementById("div-start-" + this.pos).offsetLeft;
            var endPos = document.getElementById("div-end-" + this.pos).offsetLeft;
            var windowWidth = window.innerWidth;
        }
    },

    props: ['name', 'pos'],

    mounted() {
        var dex = this;

        

        dex.scale();

        window.onresize = function() {
            dex.scale();
        }

        //sets initial canvas constraints
        this.left = document.getElementsByClassName("sec-crop")[0].offsetLeft;
        this.leftWall = document.getElementsByClassName("sec-crop")[0].offsetLeft;

        this.right = document.getElementsByClassName("sec-del")[0].offsetLeft;
        this.rightWall = document.getElementsByClassName("sec-del")[0].offsetLeft;

        this.zero = this.left;
        this.end = this.right;

        //loading songs
        this.firstDec();

        //mousemove
        var x = null;
        var y = null;

        document.addEventListener('mousemove', onMouseUpdate, false);
        document.addEventListener('mouseenter', onMouseUpdate, false);

        function onMouseUpdate(e) {
            x = e.pageX;
            y = e.pageY;

            if (dex.playing) {
                dex.src.playbackRate.value = (-y + 400) / 250;
                dex.filter.frequency.value = (-x * 20) + 10000;
            }        
        }

        function getMouseX() {
            return x;
        }

        function getMouseY() {
            return y;
        }

        //drag ball
        dragElement(document.getElementById("mydiv-ball-" + isso.pos));

        function dragElement(elmnt) {
            var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
            if (document.getElementById(elmnt.id + "-header")) {
                // if present, the header is where you move the DIV from:
                document.getElementById(elmnt.id + "-header").onmousedown = dragMouseDown;
            } else {
                // otherwise, move the DIV from anywhere inside the DIV:
                elmnt.onmousedown = dragMouseDown;
            }

            function dragMouseDown(e) {
                e = e || window.event;
                e.preventDefault();
                // get the mouse cursor position at startup:
                pos3 = e.clientX;
                pos4 = e.clientY;
                document.onmouseup = closeDragElement;
                // call a function whenever the cursor moves:
                document.onmousemove = elementDrag;
            }

            function elementDrag(e) {
                e = e || window.event;
                e.preventDefault();
                // calculate the new cursor position:
                pos1 = pos3 - e.clientX;
                pos2 = pos4 - e.clientY;
                pos3 = e.clientX;
                pos4 = e.clientY;
                // set the element's new position:
                elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
                elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
            }

            function closeDragElement() {
                // stop moving when mouse button is released:
                document.onmouseup = null;
                document.onmousemove = null;
            }
        }
    }
};

</script>
<style>
.sec {
    display: inline;
}
.sec-play {
    background-color:darkolivegreen;
}

.sec-play button {
    height: 100%;
    width: 50px;
}

.sec-name {
    padding: 5px;
    font-size: 12px;
    line-height: 15px;
    flex:3;
    background-color: cadetblue;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sec-crop {
    flex:6.5;
    background-color: cornflowerblue;
}
.sec-del button {
    height: 100%;
}
.plankhouse {
display: -webkit-flex; /* Safari */
  display: flex;
  height: 40px;
  background-color: aqua;
  margin: 10px;
}

.crack-icon {
    height: 40px;
    top: 0;
}

.wb-top {
  position: absolute;
  z-index: 9;
  background-color: #f1f1f1;
  border: 1px solid #d3d3d3;
  text-align: center;
}

.wb-header {
  padding: 10px;
  cursor: move;
  z-index: 10;
  background-color: #2196F3;
  color: #fff;
}

</style>