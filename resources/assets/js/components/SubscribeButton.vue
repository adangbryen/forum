<template>
    <button :class="classes" 
        v-text="info" 
        @click="subscribe"></button>
</template>

<script>
export default {
    props: ['active'],

    data() {
        return {
            sb: this.active
        }
    },

    computed: {
        classes() {
            return ['btn', ! this.sb ? 'btn-primary' : 'btn-default'];
        },

        info() {
            return ! this.sb ? '订阅' : '取消订阅';
        }
    },

    methods: {
        subscribe() {
            let requestType = this.sb ? 'delete' : 'post';

            axios[requestType](location.pathname + '/subscriptions');

            this.sb = ! this.sb;
        }
    },
}
</script>

<style>

</style>
