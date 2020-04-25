<template>
    <div>
      <div class="slither" v-for="(tune, index) in tunesFormatted" :key="tune">

        <tune-stack
        :ctx="ctx"
        :para="para"
        :name="tune"
        :pos="index"
        ></tune-stack>

      </div>
    </div>
</template>

<script>

export default {

    props: ['tunes', 'para'],

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
    }

}

</script>
