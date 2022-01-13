<template>
    <div>
        <b-pagination
            id="pageNum"
            v-model="currentPage"
            :total-rows="vidLength"
            :per-page="perPage"
            aria-controls="my-table"
        ></b-pagination>
        <b-embed
            id="videoPlayer"
            controls
            type="video"
            :src="videos[currentPage-1]"
            allowfullscreen
        ></b-embed>
    </div>
</template>

<script>
export default {
    data() {
        return{
            perPage: 1,
            currentPage: 1,
        }
    },
    computed: {
        videos(){
            var videos = ['https://lewsley99.blob.core.windows.net/profile-pics-videos/user_id_1/videos/sample_5153ea7e9fc20cf5acfe7e145ae6a055.mp4']
            return videos
        },
        vidLength(){
            return this.videos.length;
        }
    }

}
</script>

<style scoped>
    #videoPlayer{
        width: 60vw;
        height: 50vh;
        margin-top: 5vh;
    }
    #pageNum{
        margin-top: 2vh;
        margin-left: auto;
        margin-right: auto;
        width: fit-content;
    }
</style>