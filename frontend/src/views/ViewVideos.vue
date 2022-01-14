<template>
  <div v-if="videos">
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
      :src="video"
      allowfullscreen
    ></b-embed>
  </div>
  <div v-else-if="selectedVideos">
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
      :src="selectedVideo"
      allowfullscreen
    ></b-embed>
  </div>
  <div v-else>
    <h1> No Videos to Display </h1>
  </div>
</template>

<script>
export default {
  data() {
    return {
      perPage: 1,
      currentPage: 1,
      rootAzureBlob:
        "https://lewsley99.blob.core.windows.net/profile-pics-videos/",
    };
  },
  computed: {
    videos() {
      return this.$store.state.currentUser.videos;
    },
    video() {
      var url = this.rootAzureBlob + this.videos[this.currentPage - 1]
      return url
    },
    selectedVideos() {
      return this.$store.state.selectedUser.videos
    },
    selectedVideo() {
      var url = this.rootAzureBlob + this.selectedVideos[this.currentpage - 1]
      return url
    },
    vidLength() {
      return this.videos.length;
    },
    user() {
      return this.$store.state.currentUser;
    },
  },
  mounted() {
    
  },
};
</script>

<style scoped>
#videoPlayer {
  width: 60vw;
  height: 50vh;
  margin-top: 5vh;
}
#pageNum {
  margin-top: 2vh;
  margin-left: auto;
  margin-right: auto;
  width: fit-content;
}
</style>