<template>
    <div>
        <input id="body_input" type="text" name="body" v-model="form.body">
        <button id="send" @click="send">Send</button>
    </div>
</template>

<style>
</style>

<script>
    import axios from 'axios';
    export default {

        data() {
            return {
                form:{
                   title: 'Dylan',
                   body: ''     
                }
            }
        },

        methods: {

            send() {
                axios.post('/contact/update', this.form).then(response => {
                    console.log(response);
                });
            }

            

        }
    }

</script>

