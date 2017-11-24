<template>
    <div>
        <div v-if="signedIn">

            <div class="form-group">
                <textarea name="body" id="body" class="form-control" placeholder="say something" required
                            rows="5" v-model="body"></textarea>
            </div>

            <button type="submit" class="btn btn-default"
                @click="addReply">Post</button>
        </div>
        <p v-else class="text-center"> Please <a href="/login"> sign in</a></p>
     </div> 
</template>

<script>
import 'at.js';
import 'jquery.caret';

export default {
    
    data() {
        return {
            body: ''
        }
    },

    computed: {
        signedIn() {
            return window.App.signedIn; 
        },

        endpoint() {
            return location.pathname + '/replies';
        }
    },

    mounted() {
        $('#body').atwho({
            at: "@",
            delay: 750,
            callbacks: {
                remoteFilter: function(query, callback) {
                    $.getJSON("/api/users", {name: query}, function(names) {
                        callback(names);
                    })
                }
            }
        })
    },

    methods: {
        addReply() {
            axios.post(this.endpoint, { body: this.body })
                .then( ({data}) => {
                    this.body = '';
                    flash('Your reply has been posted!');

                    this.$emit('created', data);
                })
                .catch(error => {
                    flash(error.response.data, 'danger');
                })
        },
    },
}
</script>

<style>
@import '../../../../public/css/vendor/jquery.atwho.css';
</style>
