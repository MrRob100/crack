<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an example component.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['prop1', 'prop2'],

        data: function() {
            return {
                data1: {},
                data2: {}
            }
        },

        mounted() {
            console.log('Component mounted.')
        },

        computed: {
            //
        },

        watch: {
            // whenever question changes, this function will run
            playing: function (newState, oldState) {
            // console.log('was', oldState, 'now', newState);
            }
        },

        methods: {
            //       
        }
    }
</script>

