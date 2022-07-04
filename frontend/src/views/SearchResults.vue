<template>
  <div>
    <div v-if="topicPosts">
      <b-pagination
        v-model="currentPage"
        :total-rows="rows"
        :per-page="perPage"
        aria-controls="my-table"
        id="paginationBar"
      >
      </b-pagination>
      <b-list-group
        horizontal
        id="posts"
        v-for="post in topicPosts"
        :key="post.name"
        class="flex-column align-items-start"
      >
        <b-list-group-item button id="post" @click="setSelectedPost($event)">
          <p>
            Tagged: {{ post.Tag }}<br /><br />Posted By: {{ post.User }} <br /><br />
            <small id="createdAt">Created At: {{ post.Created }}</small><br>
            <small>Post ID: {{ post.ID }}</small>
          </p>
        </b-list-group-item>
      </b-list-group>
    </div>
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
    <div id="outer-div-empty" v-else-if="presenceCheck<=0 && !topicPosts">
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
    },
    topicPosts() {
      var posts = [];
      var item
      for (item in this.$store.state.topicFilterPosts) {
        var post = {
            ID: this.$store.state.topicFilterPosts[item].id,
            Tag: this.$store.state.topicFilterPosts[item].tag,
            User: this.$store.state.topicFilterPosts[item].user_name,
            Created:
                this.$store.state.topicFilterPosts[item].created_at.slice(11, 16) +
                ", " +
                this.$store.state.topicFilterPosts[item].created_at.slice(0, 10), //slicing used to format timestamp as desired
            
        };
        posts.push(post);
      }
      var filteredPosts = posts.slice(
        (this.currentPage - 1) * this.perPage,
        this.currentPage * this.perPage
      );
      if (posts.length == 0)
        return null
      else {
        return filteredPosts
      }
    },
    
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
      setTimeout(() => {this.$router.push('/selectedProfile'); }, 1000)
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
    setSelectedPost(event) {
        var post = event.target.innerText
        var index = post.search("Post ID") + 9
        var id = post.slice(index)
        var selected;
        for (post in this.$store.state.posts){
            if (this.$store.state.posts[post].id == id){
                selected = this.$store.state.posts[post]
            }
        }
        this.$store.commit('setCurrentPost', selected)
        this.$store.dispatch('getComments', id)

        this.loading = true;
        setTimeout(() => {   this.$router.push('viewPost'); }, 2000);
        this.loading = null;
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

#post{
  background-color: #D0F4EA;
  margin-bottom: 2vh;
}

#paginationBar {
  width: fit-content;
  margin-left: auto;
  margin-right: auto;
  margin-top: 2vh;
}

#posts {
  width: 60vw;
  margin-left: auto;
  margin-right: auto;
}
</style>