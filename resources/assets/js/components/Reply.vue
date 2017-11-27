<template>
    <div :id="'reply-'+id" class="panel" :class="isBest ? 'panel-success' : 'panel-default'">
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
            <form @submit="update">
                <div v-if="editing">
                    <div class="form-group">
                        <textarea v-model="body" rows="5" class="form-control" required></textarea>
                    </div>

                    <button class="btn btn-xs btn-primary">Update</button>
                    <button class="btn btn-xs btn-link" @click="cancel" type="button">Cancel</button>
                </div>
                <div v-else v-html="body"></div>
            </form>
        </div>

        <div class="panel-footer level">
            <div v-if="authorize('updateReply', reply)">
                <button class="btn btn-xs mr-1" @click="editing=true">Edit</button>
                <button class="btn btn-xs mr-1 btn-danger" @click="destroy">Delete</button>
            </div>

            <button class="btn btn-xs ml-a btn-default" @click="markAsBestReply" v-show="!isBest">Best Reply?</button>
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
            id: this.reply.id,
            editing: false,
            body: this.reply.body,
            isBest: this.reply.isBest,
        };
    },

    created() {
        window.events.$on('best-reply-selected', id=> {
            this.isBest = (id === this.id);
        });
    },

    computed: {
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
            this.body = this.reply.body;
        },

        markAsBestReply() {
            axios.post('/replies/' + this.reply.id + '/best');

            window.events.$emit('best-reply-selected', this.reply.id);
        }
    }
};
</script>