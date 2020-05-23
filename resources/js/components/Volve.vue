<template>
  <div>
    <div class="stack-house to-blur">
      <div 
      :id='"stack-" + pos'
      class="stack-slice stack-bottom"
      :class="playClass()"
      >
        <div class="inln-btn">
            <h3>{{ nameTrimmed }}</h3>
        </div>
        <tune-crop
        @value="cropVal"
        @setStart="playSelection"
        @setEnd="playSelection"
        :id='"tc-"+pos'
        :setting='pos'
        :name='name'
        ></tune-crop>
        <canvas class="canv" :id='"canvas-"+pos' :width="canvasWidth()" height="40"></canvas>
        <a :href="dlref"
        >
        <button 
        class="dld"
        v-on:click="dl">
        <img class="crack-icon dl-icon" src="images/dld.png">
        </button>
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["playable", "ctx", "para", "name", "pos"],

  data: function() {
    return {
      nameTrimmed: "",
      playFrom: "",
      playTo: "",
      ableToPlay: true,
      first: true,
      dlref: "",
      dlding: false,
      playing: false,
      loading: false,
      loaded: false,
      src: {},
      gain: {},
      filter: {},
      notch: {},
      amt: 0
    };
  },

  mounted() {
    var isso = this;
    var body = document.querySelector("body");
    var toBlur = document.getElementsByClassName("to-blur");

    isso.dlref = window.location.origin + "/dl?song=" + isso.name;

    var source;
    var request;
    var myBuffer;
    var myImpulseBuffer;
    var impulseRequest;
    var impulseConvolver = isso.ctx.createConvolver();

    var convolver = isso.ctx.createConvolver();
    var convolverGain = isso.ctx.createGain();
    var masterGain = isso.ctx.createGain();
    var filter = isso.ctx.createBiquadFilter();
    var notch = isso.ctx.createBiquadFilter();

    filter.type = "lowpass";
    filter.frequency.value = 20000;

    notch.type = 'notch';
    notch.frequency.value = 100;
    filter.Q.value = 1.5;

    var masterCompression = isso.ctx.createDynamicsCompressor();
    masterCompression.threshold.value = -10;

    //source and impulse
    var subdir = isso.para !== "-" ? isso.para + "/" + isso.name : "" + isso.name;
    var sourceUrl = "storage/data/" + subdir;

    var impulseUrl = "storage/data/tenniscourt.wav";

    var box = document.getElementsByClassName("control-box")[0];
    var play = document.getElementById("stack-" + isso.pos);
    var stop = document.getElementById("stbutton-" + isso.pos); 

    function getSource() {
      
      request = new XMLHttpRequest();
      request.open("GET", sourceUrl, true);
      request.responseType = "arraybuffer";

      request.onload = function() {
        var audioData = request.response;

        isso.ctx.decodeAudioData(
          audioData,
          function(buffer) {

            //canvas
            var canvas = document.getElementById("canvas-" + isso.pos);
            isso.drawBuffer( canvas.width, canvas.height, canvas.getContext('2d'), buffer );

            //audio
            myBuffer = buffer;

            isso.myBuffer = myBuffer;

            connectandplay();

          },

          function(e) {
            "Error with decoding audio data" + e.err;
          }
        );
      };

      request.send();

      if (isso.first) {
        getImpulse();
        isso.first = false;
      }

    }

    //connect and start
    function connectandplay() {

      source = isso.ctx.createBufferSource();

      source.buffer = isso.myBuffer;
      source.loop = true;

      masterGain.gain.value = 0.5;

      source.connect(convolverGain);
      source.connect(masterGain);
      masterGain.connect(filter).connect(notch).connect(masterCompression);
      masterCompression.connect(isso.ctx.destination);
      
      isso.src = source;
      isso.gain = masterGain;
      isso.filter = filter; 
      isso.notch = notch;

      //start from
      var duration = source.buffer.duration;
      var offset = duration * isso.playFrom;
      var endset = duration * isso.playTo;

      try {
        source.start(0, offset);
        isso.loading = false;

        for (let item of toBlur) {
          item.style.filter = "blur(5px)";
        }

        body.style.position = "fixed";
        body.style.overflowY = "hidden";

        isso.playing = true;
        isso.loaded = true;
        stop.style.display = "block";
        box.style.display = "block";

        source.loopStart = offset;
        source.loopEnd = endset;

      } catch(err) {
        isso.$emit('able', true);
        console.log(err);
      }

    }

    function getImpulse() {

      impulseConvolver = isso.ctx.createConvolver();
      impulseRequest = new XMLHttpRequest();
      impulseRequest.open("GET", impulseUrl, true);
      impulseRequest.responseType = "arraybuffer";

      impulseRequest.onload = function() {
        isso.loaded = true;
        var impulseData = impulseRequest.response;

        isso.ctx.decodeAudioData(
          impulseData,
          function(buffer) {
            myImpulseBuffer = buffer;
            impulseConvolver.buffer = myImpulseBuffer;
            impulseConvolver.loop = true;
            impulseConvolver.normalize = true;
            convolverGain.gain.value = 0;
            convolverGain.connect(impulseConvolver);
            impulseConvolver.connect(masterGain);
          },

          function(e) {
            "Error with decoding audio data" + e.err;
          }
        );
      };

      impulseRequest.send();
    }

    //can remove abletoplay
    play.onclick = function() {

      isso.$emit('able', false);

      var prevent = document.getElementById('prevent-' + isso.pos);
      if (!isso.playing && isso.ableToPlay && !prevent && isso.playable) {
        isso.ableToPlay = false;
        convolver.disconnect();

        //if not cached

        if (!source) {
          getSource();
        } else {
          connectandplay();
        }

      }
    };

    stop.onclick = function() {
      isso.$emit('able', true);
      isso.ableToPlay = true;
      body.style.position = "relative";
      body.style.overflowY = "scroll";

      for (let item of toBlur) {
        item.style.filter = "none";
      }

      source.stop(0);
      convolver.disconnect();
      isso.playing = false;
      isso.loaded = false;
      stop.style.display = "none";
      box.style.display = "none";
    };

    //get effect readings
    var speedControl = document.getElementsByClassName("speed-control")[0];
    var reverbControl = document.getElementsByClassName("reverb-control")[0];
    var filterControl = document.getElementsByClassName("filter-control")[0];
    var phaserControl = document.getElementsByClassName("phaser-control")[0];

    var speedValue = document.getElementsByClassName("speed-value")[0];
    var reverbValue = document.getElementsByClassName("reverb-value")[0];
    var filterValue = document.getElementsByClassName("filter-value")[0];
    var phaserValue = document.getElementsByClassName("phaser-value")[0];

    //if playing
    setInterval(function() {
      if (isso.playing) {
          //try catcher
          try {
            isso.src.playbackRate.value = speedControl.value;
            speedValue.innerHTML = Math.floor(speedControl.value * 100) + "%";

            convolverGain.gain.value = reverbControl.value;
            reverbValue.innerHTML = Math.floor(reverbControl.value * 100) + "%";

            isso.filter.frequency.value = filterControl.value < 20000 ? filterControl.value / 4 : filterControl.value;
                        
            filterValue.innerHTML = Math.floor(isso.filter.frequency.value) + " Hz";

            isso.amt = phaserControl.value;
            phaserValue.innerHTML = Math.floor(phaserControl.value * 100) + "%";

          } catch {
            //
          }
      }
    }, 10);

    var i = 2;
    var up = true;
    isso.amt = 0;

    setInterval(function() {
      if (i === 120) {
        up = false;
      }
      if (i === 2) {
        up = true;
      }

      if (up) {
          i++;
      } else {
          i--;
      }

        try {
          if ((i * 100) - isso.amt > 50) {

              var calc = (-isso.amt * 20000) + 20000;
              var calcfull = (i * 100) + calc;

              isso.notch.frequency.value = calcfull < 24000 ? calcfull : 24000;

          } else {
              isso.notch.frequency.value = 50;
          }
        } catch {
          //
        }

    }, 2);

    this.nameTrim();
    window.addEventListener("resize", this.nameTrim);

  },

  methods: {

    canvasWidth() {
      return window.innerWidth;
    },

    playClass: function() {
        return this.playing ? 'strip-play' : 'strip-stop';
    },

    cropVal: function(which, value) {
      var isso = this;
      isso.ableToPlay = false;
      setTimeout(function() {
          isso.ableToPlay = true;
      }, 500);
      this.playSelection(which, value);
    },

    playSelection: function(which, value) {
      
      if (which === "startScale") {
        this.playFrom = value;
      }
      if (which === "endScale") {
        this.playTo = value;
      }
    },

    nameTrim: function() {
      var scale = window.innerWidth / 15.16;
      this.nameTrimmed = this.name.length > scale - 5 ? this.name.substr(0, scale - 5) + "..." : this.name;
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

    drawBuffer: function( width, height, context, buffer ) {

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

  }
};
</script>
