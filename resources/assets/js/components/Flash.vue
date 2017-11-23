<template>
    <div class="alert alert-flash"
        :class="'alert-' + level"
        v-text="body"
        role="alert" 
        v-show="show"
        >
    </div>
</template>
<script>
export default {
    props: ["message"],

    data() {
        return {
            body: this.message,
            level: "success",
            show: false
        };
    },

    created() {
        if (this.message) {
            this.flash();
        }

        window.events.$on("flash", data => this.flash(data));
    },

    methods: {
        flash(data) {
            if (data) {
                this.body = data.message;
                this.level = data.level;
            }
            this.show = true;

            this.hide();
        },

        hide() {
            setTimeout(() => {
                this.show = false;
            }, 1500);
        }
    }
};
</script>
<style>
.alert-flash {
    position: fixed;
    right: 20px;
    bottom: 20px;
}
</style>
