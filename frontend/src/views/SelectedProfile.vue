<template>
  <div id="main" class="row align-items-center profile-header">
    <div>
      <b-card
        :img-src="profilePicture"
        img-alt="Profile Picure"
        id="profileCard"
        img-left
      >
        <h2 id="username">{{ selectedUser.name }}</h2>

        <b-card-text>
          <b-button-group vertical id="btnGroup">
            <b-button
              class="profileBtns"
              @click="followUser"
              id="followBtn"
              v-if="!isFollowed"
              >Follow User</b-button
            >
            <b-button
              class="profileBtns"
              @click="unfollowUser"
              id="followBtn"
              v-if="isFollowed"
              >Unfollow User</b-button
            >
            <b-button
              class="profileBtns"
              @click="getPhotos"
              id="followBtn"
              v-if="isFollowed"
              >View Photos</b-button
            >
            <b-button
              class="profileBtns"
              @click="getVideos"
              id="followBtn"
              v-if="isFollowed"
              >View Videos</b-button
            >
          </b-button-group>
        </b-card-text>
      </b-card>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {};
  },
  methods: {
    followUser() {
      this.$store.dispatch({
        type: "followUser",
        id: this.selectedUser.id,
        currentUserId: this.currentUser.id,
      });
      this.$store.commit("followSelected");
      // setTimeout(() => { this.$router.push('SelectedProfile'); }, 3000)
    },

    unfollowUser() {
      this.$store.dispatch({
        type: "unfollowUser",
        id: this.selectedUser.id,
        currentUserId: this.currentUser.id,
      });
      this.$store.commit("unfollowSelected");
    },
    getPhotos() {
      this.$store.dispatch("getSelectedPhotos");
      setTimeout(() => {
        this.$router.push("ViewPhotos");
      }, 2000);
    },

    getVideos() {
      this.$store.dispatch("getSelectedVideos");
      setTimeout(() => {
        this.$router.push("ViewVideos");
      }, 2000);
    },
  },
  computed: {
    selectedUser() {
      return this.$store.state.selectedUser;
    },
    currentUser() {
      return this.$store.state.currentUser;
    },
    profilePicture() {
      return this.$store.state.selectedUser.ppUrl;
    },
    followings() {
      return this.$store.state.currentUser.followings;
    },
    isFollowed() {
      return this.$store.state.selectedUser.isFollowed;
    },
  },
  mounted() {},
  created() {},
};
</script>

<style scoped>
.profileBtns {
  background-color: #829399;
}

.card-img-left {
  height: 25vh;
  padding: 1vw;
  border-radius: 1em;
  margin-left: 1vw;
  margin-top: 11vh;
}

#btnGroup {
  margin-top: 5vh;
  float: right;
}

#main {
  width: 100vw;
}

#profileCard {
  background-color: #d0f4ea;
  padding: 2vw;
  width: fit-content;
  margin-top: 10vh;
}
</style>