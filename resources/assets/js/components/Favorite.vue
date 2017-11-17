<template>
    <div>
        <button class="btn btn-default" :class="classes" @click="toggle">
            <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
            <span v-text="count"></span>
        </button>
    </div>
</template>

<script>
    export default {
        props: ['reply'],

        data() {
            return {
                data: this.reply,
                isFavorited: this.reply.isFavorited,
                count: this.reply.favoritesCount
            }
        },

        computed: {
            classes() {
                return ['btn', this.isFavorited ? 'btn-default' : 'btn-primary'];
            },
            endpoint() {
                return '/replies/' + this.reply.id + '/favorites';
            }
        },

        methods: {
            toggle() {
                this.isFavorited ? this.unFavorite() : this.favorite();
            },

            unFavorite() {
                axios.delete(this.endpoint);

                this.isFavorited = ! this.isFavorited;
                this.count--;
            },

            favorite() {
                axios.post(this.endpoint);

                this.isFavorited = ! this.isFavorited;
                this.count++;
            }
        }
    }
</script>