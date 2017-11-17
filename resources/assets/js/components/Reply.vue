<script>
    import favorite from './Favorite.vue'

    export default {
        props: ['attributes'],

        components: { favorite },

        data() {
            return {
                editing: false,
                body: this.attributes.body
            }
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.attributes.id, {
                    body: this.body
                });

                this.editing = false;

                flash('更新了！！！');
            },

            destroy() {
                axios.delete('/replies/' + this.attributes.id);

                $(this.$el).fadeOut(300, ()=>{
                    flash('删除成功...');
                });
            },

            cancel() {
                this.editing = false;
                this.body = this.attributes.body;
            }
        }
    }
</script>