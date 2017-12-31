<script>
import Replies from "../components/Replies.vue";
import SubscribeButton from "../components/SubscribeButton.vue";

export default {

    props: ['count', 'dataThread'],
    
    components: { Replies, SubscribeButton },

    data() {
        return {
            repliesCount: this.dataThread.replies_count,
            locked: this.dataThread.locked,
        }
    },

    methods: {
        toggleLock() {
            axios[this.locked ? 'delete' : 'post']('/locked-threads/' + this.dataThread.slug);
            this.locked = ! this.locked;
        }
    },
}
</script>
