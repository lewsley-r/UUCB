<template>
  <div v-if="rawPhotos">
    <b-pagination
      id="pageNum"
      v-model="currentPage"
      :total-rows="photosLength"
      :per-page="perPage"
      aria-controls="my-table"
    ></b-pagination>
    <b-img
      id="photoPlayer"
      :src="photo"
    ></b-img>
  </div>
  <div v-else-if="selectedPhotos">
    <b-pagination
      id="pageNum"
      v-model="currentPage"
      :total-rows="vidLength"
      :per-page="perPage"
      aria-controls="my-table"
    ></b-pagination>
    <b-img
      id="photoPlayer"
      :src="selectedPhoto"
    ></b-img>
  </div>
  <div v-else>
    <h1> No Photos to Display </h1>
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
    rawPhotos() {
      return this.$store.state.currentUser.photos;
    },
    photo() {
      var url = this.rootAzureBlob + this.rawPhotos[this.currentPage - 1]
      console.log(url)
      return url;
    },
    selectedPhotos() {
      return this.$store.state.selectedUser.photos;
    },
    selectedPhoto() {
      var url = this.rootAzureBlob + this.selectedPhotos[this.currentPage - 1]
      console.log(url)
      return url;
    },
    photosLength() {
      if (this.rawPhotos){
        return this.rawPhotos.length;
      }else if (this.selectedPhotos){
        return this.selectedPhotos.length
      }
      
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
#photoPlayer {
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