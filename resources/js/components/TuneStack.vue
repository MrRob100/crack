<template>
    <div v-if="!deleted" class="stack-house">
        <div v-if="!nonMob" class="wb-top" :id='"mydiv-ball-"+pos' style="visibility:hidden">
            <div class="wb-header" :id='"mydiv-ball-"+pos+"-header"'></div>
        </div>

        <!-- <div class="stack-slice stack-top">
            <div v-if="loading" class="stack-play">
                <button disabled>...</button>
            </div>  
            <div v-else class="stack-play">
                <button
                v-if="playing"
                v-on:click="stop"
                ><img class="crack-icon" src="images/stop.png"></button>
                <button
                class="btn-crack-play"
                v-else
                v-on:click="play"
                ><img class="crack-icon" src="images/play.png"></button>
            </div>
            <div class="stack-top-sec stack-name">
                {{ name }}
            </div>
            <div class="stack-top-sec stack-del">
                <button
                v-on:click="del">
                <img class="crack-icon" src="images/waste.png">
                </button>
            </div>
        </div> -->
        <div 
        class="stack-slice stack-bottom"
        :class="playClass()"
        v-on:click="songClick()"
        >
            <div class="inln-btn">
                <h3>{{ name }}</h3>
            </div>
            <tune-crop
            :id='"tc-"+pos'
            :setting='pos'
            v-bind:canvasWidth='canvasWidth'
            v-bind:canvasLeft='canvasLeft'
            :name='name'
            ></tune-crop>
            <canvas class="canv" :id='"canvas-"+pos' :width="canvasWidth" height="40"></canvas>
            <a :href="dlref">
                <button 
                class="dld"
                v-on:click="dl">
                <img class="crack-icon dl-icon" src="images/dld.png">
                </button>
            </a>
        </div>
    </div>
</template>
<script>

export default {

    props: ['name', 'pos'],

    data: function () {
        return {
            dlref: "",
            spp: 1,
            src: {},
            src2: {},
            loading: true,
            playing: false,
            deleted: false,
            leftWall: 0,
            rightWall: 0,
            leftMarker: {},
            rightMarker: {},
            leftMarkerScale: 0,
            rightMarkerScale: 0,
            canvasLeft: 0,
            canvasWidth: 0,
            nonMob: true,
            wreckBall: {},
            deleted: false,
            dlding: false,
        }
    },

    methods: {

        dl: function() {
            var isso = this;
            isso.dlding = true;
            setTimeout(function() {
                isso.dlding = false;
            }, 500);
        },

        songClick: function() {
            if (!this.dlding) {
                var slc = document.getElementsByClassName('stack-slice');
                var abled = document.getElementsByClassName('dbld');

                if (slc.length == 1 && this.playing) {
                    this.stop();
                }
                else if (abled.length == 0) {
                    this.play();
                }
                else if (this.playing) {
                    this.stop();
                }
            }
        },

        playClass: function() {
            return this.playing ? 'strip-play' : 'strip-stop';
        },

        path: function() {
            var slug = '';
            if (window.location.pathname == '/crack/public/dashboard') {
                slug = '/crack/public';
            }
            return slug;
        },

        del: function() {
            var request = new XMLHttpRequest();

            if (window.location.pathname == '/crack/public/dashboard') {
                request.open('GET', '/crack/public/del?song=' + this.name, true);
            } else {
               request.open('GET', '/del?song=' + this.name, true);
            }
            request.send();
            var isso = this;
            request.onload = function () {
                if (request.response == 'deleted') {
                    isso.deleted = true;
                }
            }
        },
        setBody: function(state) {
            var ht = document.querySelector('html');
            var bod = document.querySelector('body');

            if (state === 'stop') {
                var off = ht.offsetTop;
                ht.style.top = 'initial';
                bod.style.position = 'relative';
                ht.style.position = 'relative';
                window.scrollTo(0, off - (off * 2));                
            } else {
                ht.style.top = - window.scrollY + 'px';
                bod.style.position = 'fixed';
                ht.style.position = 'fixed';
            }
        },
        play: function() {

            var leftStart = document.getElementById("div-start-" + this.pos).offsetLeft;
            var leftEnd = document.getElementById("div-end-" + this.pos).offsetLeft;
            var offsetPx = leftStart >0 ? leftStart : 0;
            var offsetPxEnd = leftEnd >0 ? leftEnd : 0;

            var waveWidth = document.getElementsByClassName('canv')[0].offsetWidth;
            var spp = this.src.buffer.duration / waveWidth;

            var resultantStartingTime = offsetPx * spp;
            var resultantLoopEnd = offsetPxEnd * spp;
            this.src.loopStart = resultantStartingTime;
            this.src2.loopStart = resultantStartingTime;
            this.src.loopEnd = resultantLoopEnd;
            this.src2.loopEnd = resultantLoopEnd;

            this.src.start(0, resultantStartingTime);
            this.src2.start(0, resultantStartingTime);

            this.setBody('play');

            this.playing = true;

            if (!this.nonMob) {

                document.getElementById("mydiv-ball-"+this.pos).style.visibility = 'visible';
                this.wreckBallMeth(document.getElementById("mydiv-ball-"+this.pos));
            }
        },
        stop: function() {

            this.setBody('stop');
            this.src.stop();
            this.src2.stop();
            this.playing = false;
            this.src = {};
            this.src2 = {};
            this.load();

            if (!this.nonMob) {
                document.getElementById("mydiv-ball-"+this.pos).style.visibility = 'hidden';
            }
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
        setCanvasWidth: function() {
            this.canvasWidth = window.innerWidth;
        },
        load: function() {

            var isso = this;
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            const audioCtx = new AudioContext();
            var request = new XMLHttpRequest();
            // const set = this.pos;

            if (window.location.pathname == '/crack/public/dashboard') {
                //local
                request.open('GET', '../public/storage/data/' + isso.name, true);
            } else {
                //prod
                request.open('GET', '../storage/data/' + isso.name, true);
            }

            const source = audioCtx.createBufferSource();
    
            const source2 = audioCtx.createBufferSource();
            request.responseType = 'arraybuffer';

            request.onload = function() {
                isso.loading = false; //make further down
                var audioData = request.response;

                audioCtx.decodeAudioData(audioData, function(buffer) {
                    //canvas bit
                    var canvas = document.getElementById("canvas-" + isso.pos);
                    isso.drawBuffer( canvas.width, canvas.height, canvas.getContext('2d'), buffer );

                    var myBuffer = buffer;
                    source.buffer = myBuffer;
                    source2.buffer = myBuffer;

                    //without filter
                    source.connect(audioCtx.destination)
                    // source2.connect(audioCtx.destination)

                    //filter bit
                    var filter = audioCtx.createBiquadFilter();
                    filter.type = 'highpass';
                    // filter.type = 'lowpass';
                    // filter.type = 'bandpass';

                    source2.connect(filter);
                    filter.connect(audioCtx.destination);
                    // filter.frequency.value = 0; 
                    filter.frequency.value = 20000;

                    source.loop = true;
                    source2.loop = true;
                    isso.src = source;
                    isso.src2 = source2;

                    isso.filter = filter;
                },
                function (e) {
                    "Error with decoding audio data"
                });
            }
            request.send();
        },
        osc: function() {
            var isso = this;
                var boundary = 80
                var v = -boundary;
                var up = true;
                setInterval(function () {
                    if (isso.playing) {

                        if (v == boundary) {
                            up = false;
                            isso.src2.playbackRate.value = isso.src.playbackRate.value;
                        }

                        if (v == -boundary) {
                            up = true;
                        }

                        if (up) {
                            v++;
                        } else {
                            v--;
                        }

                        //snaking pan
                        isso.src2.playbackRate.value += v / 1000000;
                    }
                }, 2);
            // }
        },
        checkMob: function() {
            if (window.innerWidth < 800) {
                this.nonMob = false;
            } else {
                this.nonMob = true;
            }
        },
        wreckBallMeth: function(elmnt) {

            var isso = this;

            var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
            if (document.getElementById(elmnt.id + "-header")) {
                // if present, the header is where you move the DIV from:
                var ballHead = document.getElementById(elmnt.id + "-header");
                ballHead.onmousedown = dragMouseDown;
                ballHead.ontouchstart = dragMouseDown;

            } else {
                // otherwise, move the DIV from anywhere inside the DIV:
                elmnt.onmousedown = dragMouseDown;
            }
            
            //first touch
            function dragMouseDown(e) {
                e = e || window.event;
                // get the mouse cursor position at startup:
                if (e.touches) {
                    pos3 = e.touches[0].clientX;
                    pos4 = e.touches[0].clientY;
                    document.ontouchend = closeDragElement;
                    document.ontouchmove = elementDrag;
                } else {
                    e.preventDefault();
                    pos3 = e.clientX;
                    pos4 = e.clientY;
                    document.onmouseup = closeDragElement;
                    document.onmousemove = elementDrag;
                }
            }

            //while moving
            function elementDrag(e, touch) {
                e = e || window.event;
                if (e.touches) {
                    pos1 = pos3 - e.touches[0].clientX;
                    pos2 = pos4 - e.touches[0].clientY;
                    pos3 = e.touches[0].clientX;
                    pos4 = e.touches[0].clientY;
                } else {
                    e.preventDefault();
                    // calculate the new cursor position:
                    pos1 = pos3 - e.clientX;
                    pos2 = pos4 - e.clientY;
                    pos3 = e.clientX;
                    pos4 = e.clientY;
                }

                // set the element's new position:
                elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
                elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
                isso.src.playbackRate.value = (-(elmnt.offsetTop - pos2) + 600 + window.scrollY) /450;
                isso.src2.playbackRate.value = (-(elmnt.offsetTop - pos2) + 600 + window.scrollY) /450;
                // isso.filter.frequency.value = ((elmnt.offsetLeft) * 25) + 10000;
                // isso.filter.frequency.value = (elmnt.offsetLeft);
                // isso.filter.frequency.value = ((elmnt.offsetLeft) * 25) + 10000;

            }

            //finished moving
            function closeDragElement() {
                // stop moving when mouse button is released:
                // document.onmouseup = null;
                document.ontouchend = null;
                // document.onmousemove = null;
                document.ontouchmove = null;
            }
        },
            setRange: function() {
                var isso = this;
                var request = new XMLHttpRequest();

                if (window.location.pathname == '/crack/public/dashboard') {
                    request.open('GET', '/crack/public/get?position=' + this.name);
                } else {
                    request.open('GET', '/get?position=' + this.name);
                }

                request.send();
                request.onload = function() {
                    if (request.response) {
                        var jsonResp = JSON.parse(request.response);

                        if (jsonResp.startScale) {
                            isso.leftMarker.style.left = jsonResp.startScale * 100 + '%';
                        }

                        if (jsonResp.endScale) {
                            isso.rightMarker.style.left = jsonResp.endScale * 100 + '%';
                        }
                    }
                };
            }
    },

    mounted() {
        var isso = this;

        var tc = document.getElementById('tc-'+this.pos);
        tc.onclick = function() {
            isso.dl();
        }


        //download link
        if (window.location.pathname == '/crack/public/dashboard') {
            //local
            isso.dlref = window.location.origin + "/crack/public/dl?song=" + isso.name;
        } else {
            //prod
            isso.dlref = window.location.origin + "/dl?song=" + isso.name;
        }

        if (!this.nonMob) {
            this.wreckBallMeth(document.getElementById("mydiv-ball-"+this.pos));
        }

        this.setRange();

        this.checkMob();

        this.osc();

        this.load();

        isso.leftMarker = document.getElementById("div-start-" + this.pos);
        isso.rightMarker = document.getElementById("div-end-" + this.pos);

        window.addEventListener('resize', function() {
            isso.checkMob();
        });    

        var canvas = document.getElementById("canvas-" + this.pos);
        this.canvasLeft = canvas.offsetLeft;
        this.setCanvasWidth();

        //mousemove
        var x = null;
        var y = null;

        document.addEventListener('mousemove', onMouseUpdate, false);
        document.addEventListener('mouseenter', onMouseUpdate, false);

        function onMouseUpdate(e) {

            if (isso.nonMob) {
                x = e.pageX;
                y = e.pageY;

                if (isso.playing) {

                    var formula = (-y + 900 + window.scrollY) / 650;

                    var freqFormula = (-x * 30) + 15000; //hipass
                    // var freqFormula = (-x * 20) + 10000; //hipass
                    // var freqFormula = (x * 1.2 - 100); //lopass
                    isso.src.playbackRate.value = formula;
                    isso.src2.playbackRate.value = formula;

                    isso.filter.frequency.value = freqFormula;

                    // isso.filter.frequency.value = (-x * 20) + 10000;
                }      
            }  
        }

        function getMouseX() {
            return x;
        }

        function getMouseY() {
            return y;
        }
        //drag ball
    }
};

</script>
<style>
    .inln-btn {
        position: absolute;
    }

    .container {
        padding: 0!important;
    }
    button {
        -webkit-appearance: none;
        height: 40px;
    }
    .stack-play {
        flex: 1;
    }
    .stack-name {
        flex: 4;
    }
    .stack-del {
        flex: 1;
    }
    .stack-del button {
        float: right;
    }
    .stack-top-sec {
        display: inline;
    }
    .stack-house {
        margin-bottom: 10px;
        width: 100%;
    }
    .stack-slice {
        display: block;
    }
    .stack-top {
        display: flex;
        height: 40px;
        background-color: rgb(62, 59, 105);
    }
    .stack-bottom {
        height: 40px;
        background-color: rgb(79, 56, 114);
    }
    .canv {
        width: 100%;
        height: 40px !important;
    }
    .wb-top {
        position: absolute;
        z-index: 20;
        text-align: center;
        border-radius: 50%;
    }
    .wb-header {
        -webkit-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
        -moz-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
        box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
        border-width: 10px;
        border-style: double;
        border-radius: 50%;
        width: 70px;
        height: 70px;
        cursor: move;
        z-index: 50;
        background-color: #2196F3;
        color: #fff;
    }
</style>