<template>
    <div 
        v-if="!deleted" 
        class="stack-house"
        >
        <div v-if="!nonMob" class="wb-top" :id='"mydiv-ball-"+pos' style="visibility:hidden">
            <div class="wb-header" :id='"mydiv-ball-"+pos+"-header"'></div>
        </div>
        <div 
        class="delhide"
        v-on:click="del">
            <h1>DEL</h1>
        </div>
        <div 
        class="stack-slice stack-bottom"
        :class="playClass()"
        v-on:click="songClick()"
        >
            <div class="inln-btn">
                <h3>{{ name }}</h3>
            </div>
            <tune-crop
            @clicked="cropClick"
            :id='"tc-"+pos'
            :setting='pos'
            v-bind:canvasWidth='canvasWidth'
            v-bind:canvasLeft='canvasLeft'
            :name='name'
            ></tune-crop>
            <canvas class="canv" :id='"canvas-"+pos' :width="canvasWidth" height="40"></canvas>
            <a :href="dlref"
                v-if="nonMob"
            >
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

import Meths from '../meths.js';

export default {

    props: ['para', 'name', 'pos'],

    data: function () {
        return {
            gn: {},
            gn2: {},
            dlref: "",
            spp: 1,
            ctx: {},
            src: {},
            src2: {},
            loading: true,
            started: false,
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
            cropping: false,
        }
    },

    methods: {

        cropClick: function() {
            var isso = this;
            isso.cropping = true;

            setTimeout(function() {
                isso.cropping = false;
            }, 200)
        },

        dl: function() {
            var isso = this;

            if (window.location.hostname == 'localhost') { 
                //deleting if local
                // isso.del();
            } else {
                isso.dlding = true;
                setTimeout(function() {
                    isso.dlding = false;
                }, 500);
            }
        },

        songClick: function() {
            if (!this.dlding && !this.cropping) {
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
            if (window.location.hostname == 'localhost') {
                slug = '/crack/public';
            }
            return slug;
        },

        del: function() {
            var request = new XMLHttpRequest();

            // if (window.location.hostname == 'localhost') {
            //     request.open('GET', '/crack/public/del?song=' + this.name, true);
            // } else {
            //     request.open('GET', '/del?song=' + this.name, true);
            // }

            var delPath = Meths.deleteSongPath(this.para, this.name);

            request.open('GET', delPath, true);

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

            var speedPlayback = document.getElementsByClassName("playback-speed")[0];
            var phasePlayback = document.getElementsByClassName("playback-phase")[0];

            speedPlayback.innerHTML = "Speed: 100%";
            phasePlayback.innerHTML = "Phaser: 0%";

            var resultantStartingTime = this.loopUpdate();

            if (!this.started) {
                this.src.start(0, resultantStartingTime);
                this.src2.start(0, resultantStartingTime);
            } else {
                this.ctx.resume();
            }

            //set 

            this.setBody('play');

            this.playing = true;
            this.started = true;

            if (!this.nonMob) {

                document.getElementById("mydiv-ball-"+this.pos).style.visibility = 'visible';
                this.wreckBallMeth(document.getElementById("mydiv-ball-"+this.pos));
            }
        },
        stop: function() {
            var speedPlayback = document.getElementsByClassName("playback-speed")[0];
            var phasePlayback = document.getElementsByClassName("playback-phase")[0];
            
            speedPlayback.innerHTML = "Speed: --";
            phasePlayback.innerHTML = "Phaser: --";

            this.setBody('stop');

            this.ctx.suspend();
            

            this.playing = false;

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

            var path = Meths.getSong(isso.para, isso.name);

            request.open('GET', path, true);

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

                    //gainz
                    var gainNode = audioCtx.createGain();
                    var gainNode2 = audioCtx.createGain();

                    source.connect(gainNode);
                    source2.connect(gainNode2);

                    gainNode.connect(audioCtx.destination);
                    gainNode2.connect(audioCtx.destination);

                    //normal
                    source2.connect(audioCtx.destination);
                    source.connect(audioCtx.destination);

                    gainNode2.gain.value = -1;
                    gainNode.gain.value = 0;

                    isso.gn = gainNode;
                    isso.gn2 = gainNode2;
                    

                    //filter bit
                    var filter = audioCtx.createBiquadFilter();
                    filter.type = 'highpass';
                    // filter.type = 'lowpass';
                    // filter.type = 'bandpass';

                    // source2.connect(filter);
                    // filter.connect(audioCtx.destination);
                    // filter.frequency.value = 0; 
                    filter.frequency.value = 20000;

                    source.loop = true;
                    source2.loop = true;
                    isso.src = source;
                    isso.src2 = source2;
                    isso.ctx = audioCtx; //new
                    isso.filter = filter;

                    // var analyser = audioCtx.createAnalyser();

                    isso.loopUpdate();
                    isso.phaser();
                },
                function (e) {
                    "Error with decoding audio data"
                });
            }
            request.send();
        },

        panToggle() {
            if (!this.pan) {
                this.pan = true;
            } else {
                this.pan = false;
            }
        },

        loopUpdate() {

            var leftStart = document.getElementById("div-start-" + this.pos).offsetLeft;
            var leftEnd = document.getElementById("div-end-" + this.pos).offsetLeft + 20;
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

            return resultantStartingTime;
        },  

        phaser() {
            var isso = this;

            setInterval(function() {
                //osc
                if (isso.playing) {
                    
                    setTimeout(function() {
                            isso.src2.playbackRate.value = isso.src.playbackRate.value * 1.01;
                        setTimeout(function() {
                            isso.src2.playbackRate.value = isso.src.playbackRate.value;
                        }, 1000);
                    }, 1);

                    setTimeout(function() {
                        isso.src2.playbackRate.value = isso.src.playbackRate.value * 0.99;
                        setTimeout(function() {
                            isso.src2.playbackRate.value = isso.src.playbackRate.value;
                        }, 1000);
                    }, 1100);
                }
            }, 2500);
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
            var bp = 300;

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

                var speedPlayback = document.getElementsByClassName("playback-speed")[0];
                var phasePlayback = document.getElementsByClassName("playback-phase")[0];

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
                
                var freqFormula = (-elmnt.offsetLeft * 50) + 18000; //hipass                
                // isso.filter.frequency.value = freqFormula;

                // isso.filter.frequency.value = (elmnt.offsetLeft);
                // isso.filter.frequency.value = ((elmnt.offsetLeft) * 25) + 10000;

                if (elmnt.offsetLeft < bp) {
                    //0.2 is gain range (0 to -0.2)
                    isso.gn.gain.value = -elmnt.offsetLeft * (0.2 / bp);

                    //0.8 is gain range (-1 to -0.2)
                    isso.gn2.gain.value = (elmnt.offsetLeft * (0.8 / bp)) - 1;
                    phasePlayback.innerHTML = "Phaser: " + Math.floor((elmnt.offsetLeft / bp) * 100) + "%";
                } else {
                    isso.gn.gain.value = - 0.2;
                    isso.gn2.gain.value = - 0.2;
                    phasePlayback.innerHTML = "Phaser: 100%";

                }

                speedPlayback.innerHTML = "Speed: " + Math.floor(isso.src.playbackRate.value * 100) + "%";

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

            if (window.location.hostname == 'localhost') {
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

        // Meths.Check();

        var isso = this;

        setInterval(function() {
            if (isso.playing) {
                isso.loopUpdate();
            }
        }, 1000);


        var tc = document.getElementById('tc-'+this.pos);
        tc.onclick = function() {
            isso.dl();
        }

        //download link
        if (window.location.hostname == 'localhost') {
            //local
            isso.dlref = "#";
            // isso.dlref = window.location.origin + "/crack/public/dl?song=" + isso.name;
        } else {
            //prod
            isso.dlref = window.location.origin + "/dl?song=" + isso.name;
        }

        if (!this.nonMob) {
            this.wreckBallMeth(document.getElementById("mydiv-ball-"+this.pos));
        }

        this.setRange();

        this.checkMob();

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
                var bp = 500;

                if (isso.playing) {
                    var speedPlayback = document.getElementsByClassName("playback-speed")[0];
                    var phasePlayback = document.getElementsByClassName("playback-phase")[0];

                    // var formula = (-y + 6600 + window.scrollY) / 6500;
                    var formula = (-y + 900 + window.scrollY) / 650;

                    var freqFormula = (-x * 30) + 15000; //hipass
                    // var freqFormula = (-x * 20) + 10000; //hipass
                    // var freqFormula = (x * 1.2 - 100); //lopass
                    isso.src.playbackRate.value = formula;
                    isso.src2.playbackRate.value = formula;

                    speedPlayback.innerHTML = "Speed: " + Math.floor(formula * 100) + "%";

                    // isso.filter.frequency.value = freqFormula;

                    // isso.filter.frequency.value = (-x * 20) + 10000;
                    
                    if (x < bp) {
                        phasePlayback.innerHTML = "Phaser: " + Math.floor((x / bp) * 100) + "%";
                    
                        //0.2 is gain range (0 to -0.2)
                        isso.gn.gain.value = -x * (0.2 / bp);

                        //0.8 is gain range (-1 to -0.2)
                        isso.gn2.gain.value = (x * (0.8 / bp)) - 1;
                    } else {
                        isso.gn.gain.value = - 0.2;
                        isso.gn2.gain.value = - 0.2;

                        phasePlayback.innerHTML = "Phaser: 100%";

                    }


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

    .delhide {
        position: absolute;
    }

    .delhide h1 {
        color: rgba(50, 2, 95, 0) !important;
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
        /* margin-left: 200px; */
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