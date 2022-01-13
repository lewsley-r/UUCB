<template>
  <div>
    <div id="outer-div" v-if="presenceCheck > 0">
      <b-list-group id="results" >
        <b-list-group-item
          button
          id="userResult"
          v-for="user in filterUsers"
          :key="user.name"
          @click="setUser($event)"
          >{{ user.name }}  </b-list-group-item
        >
      </b-list-group>
      <b-pagination
        id="pageNum"
        v-model="currentPage"
        :total-rows="perPage"
        :per-page="perPage"
        aria-controls="my-table"
      ></b-pagination>
    </div>
    <div id="outer-div-empty" v-else>
      <h3 id="noResults">No Results Matching your search. Please try entering something else.
      </h3>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      perPage: 10,
      currentPage: 1,
    };
  },
  computed: {
    filterUsers() {
      return this.$store.state.filterUsers;
    },
    presenceCheck() {
      return this.filterUsers.length;
    },
    selectedUser() {
      return this.$store.state.selectedUser;
    },
    currentUser() {
      return this.$store.state.currentUser;
    }
    
  },
  methods: {
    setUser(event) {
      var user;
      for (user in this.filterUsers){
          if (this.filterUsers[user].name == event.target.innerText){
              this.$store.commit('setSelectedUser', this.filterUsers[user])
              this.$store.dispatch('getSelectedProfilePicture')
          }
      }
      setTimeout(() => {   this.$router.push('/selectedProfile'); }, 1000)
      var follower;
      var isFollowing;
      for (follower in this.currentUser.followings){
        if (this.selectedUser.id == this.currentUser.followings[follower].id){
          isFollowing = true;
        }
        else{
          isFollowing = false;
        }
      } 
      if (isFollowing == true){
        this.$store.commit('followSelected')
      }
    },
  },

  mounted() {},
};
</script>

<style>
#pageNum {
  margin-left: 45vw;
}

#userResult{
  margin-bottom: 1vh;
}

#results{
  width: 75vw;
  margin-left: 13vw;
  margin-top: 10vh;
}

#noResults{
  margin-top: 30vh;
}

</style>