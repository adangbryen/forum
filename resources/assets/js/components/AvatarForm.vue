<template>
    <div>
        <div class="level">
            <img :src="avatar" alt="" width="50" height="50" class="mr-1">
            <h1 v-text="user.name"></h1>
        </div>

        <form action="" v-if="canUpdate" enctype="multipart/form-data">
            <image-upload name="avatar" @loaded="onChange"></image-upload>
        </form>

    </div>
</template>

<script>
import ImageUpload from "./ImageUpload.vue";
export default {
    props: ['user'],

    components: { ImageUpload },

    data() {
        return {
            avatar: this.user.avatar_path
        }
    },
    computed: {
        canUpdate() {
            return this.$auth(user => user.id === this.user.id) 
        }
    },

    methods: {
        onChange(avatar) {
            this.avatar = avatar.src;
            this.persist(avatar.file);
        },

        persist(avatar) {
            let data = new FormData();

            data.append('avatar', avatar);

            axios.post(`/api/users/${this.user.name}/avatar`, data)
                .then(() => flash('头像上传成功了！'));
        }
    },
};
</script>

<style>

</style>
