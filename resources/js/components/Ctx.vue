<template>
    <div>
      <div class="slither" v-for="(tune, index) in tunesFormatted" :key="tune">
        <tune
          :ctx="ctx"
          :name="tune"
          :pos="index"
        ></tune>
      </div>

    </div>

</template>

<script>

export default {

    props: ['tunes'],

    data: function() {
        return {
            ctx: {},
            tunesFormatted: {}
        }
    },

    mounted() {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        const audioCtx = new AudioContext();
        this.ctx = audioCtx;

        this.tunesFormatted = this.tunes.split(" ");

        console.log('tunes', this.tunesFormatted);

    }

}

</script>
