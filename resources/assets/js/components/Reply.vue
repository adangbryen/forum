<template>
    <div id="'reply-'+id" class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+reply.owner.name"
                        v-text="reply.owner.name">
                    </a> 
                    
                    said 
                    
                    <span v-text="ago"></span>
                    
                </h5>

                <favorite v-if="signedIn" :reply="reply"></favorite>

            </div>
        </div>

        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea v-model="body" rows="5" class="form-control"></textarea>
                </div>

                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="cancel">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>

        <div class="panel-footer level" v-if="canUpdate">
            <button class="btn btn-xs mr-1" @click="editing=true">Edit</button>
            <button class="btn btn-xs mr-1 btn-danger" @click="destroy">Delete</button>
        </div>
    </div>
</template>

<script>
import favorite from "./Favorite.vue";
import moment from "moment";

export default {
    props: ["reply"],

    components: { favorite },

    data() {
        return {
            editing: false,
            body: this.reply.body
        };
    },

    computed: {
        signedIn() {
            return window.App.signedIn;
        },

        canUpdate() {
            return this.$auth(user => this.reply.user_id === user.id);
        },

        ago() {
            return moment(this.reply.created_at).fromNow() + "...";
        }
    },

    methods: {
        update() {
            let vm = this;
            axios
                .patch("/replies/" + this.reply.id, {
                    body: this.body
                })
                .then(() => {
                    vm.editing = false;

                    flash("更新了！！！");
                })
                .catch(error => {
                    flash(error.response.data, "danger");
                    
                    vm.editing = false;
                    vm.body = vm.reply.body;
                });
        },

        destroy() {
            axios.delete("/replies/" + this.reply.id);

            this.$emit("deleted", this.reply.id);

            $(this.$el).fadeOut(300, () => {
                flash("删除成功...");
            });
        },

        cancel() {
            this.editing = false;
            console.log(this.reply.body);
            this.body = this.reply.body;
            console.log(this.body);
        }
    }
};
</script>