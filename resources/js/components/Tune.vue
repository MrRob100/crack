<template>
    <div>
        <button
        v-on:click="play"
        >play</button>
        <button
        v-on:click="pause"
        >pause</button>
        <small>{{ name }}</small>
    </div>
</template>

<script>

import Meths from '../meths.js';

export default {
    props: ['ctx', 'name', 'pos'],

    data: function() {
        return {
            source: {},
            started: false,
            gn: {}
        }
    },

    mounted() {
        var isso = this;

        var request = new XMLHttpRequest();

        var path = Meths.getSong('-', isso.name);

        request.open('GET', path, true);

        const source = isso.ctx.createBufferSource();

        request.responseType = 'arraybuffer';

        request.onload = function() {
            isso.loading = false; //make further down
            var audioData = request.response;

            isso.ctx.decodeAudioData(audioData, function(buffer) {

                var myBuffer = buffer;
                source.buffer = myBuffer;

                //gain
                var gainNode = isso.ctx.createGain();
                source.connect(gainNode);
                gainNode.connect(isso.ctx.destination);
                isso.gn = gainNode;

                source.connect(isso.ctx.destination);

                isso.source = source;

            },
            function (e) {
                "Error with decoding audio data"
            });
        }
        request.send();
    },

    methods: {
        play: function() {

            try {
                this.source.start();
            }
            catch(err) {
                console.log(err);
            }

            this.ctx.resume();
            this.gn.gain.value = 0;

        },

        pause: function() {
            this.ctx.suspend();
            this.gn.gain.value = -1;
        }   
    }

}

</script>