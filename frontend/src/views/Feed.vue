<template>
  <div class="overflow-auto">
    <div>
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
        v-for="post in posts"
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
      <h3 v-if="!posts">No Posts Found</h3>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      perPage: 3,
      currentPage: 1,
      loading: false
      // fields: ['User Name', 'Content', 'Tag']
    };
  },
  computed: {
    posts() {
      var posts = [];
      var item;
      //   if (this.currentPage == 1){
      //         var item;
      //   }
      //   else{
      //       var item = currentPage * this.perPage - 1
      //   }
      for (item in this.$store.state.followedPosts) {
        var post = {
            ID: this.$store.state.followedPosts[item].id,
            Tag: this.$store.state.followedPosts[item].tag,
            User: this.$store.state.followedPosts[item].user_name,
            Created:
                this.$store.state.followedPosts[item].created_at.slice(11, 16) +
                ", " +
                this.$store.state.followedPosts[item].created_at.slice(0, 10), //slicing used to format timestamp as desired
            
        };
        posts.push(post);
      }
      var filteredPosts = posts.slice(
        (this.currentPage - 1) * this.perPage,
        this.currentPage * this.perPage
      );
      return filteredPosts;
    },
    rows() {
      return this.$store.state.followedPosts.length;
    },
    user() {
      return this.$store.state.currentUser;
    },
    allPosts(){
        return this.$store.state.posts;
    }
  },

  mounted() {
      this.$store.dispatch('getFollowedPosts', this.allPosts)
  },

  methods: {
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
};
</script>

<style scoped>
    #posts {
        width: 60vw;
        margin-left: auto;
        margin-right: auto;
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
    #createdAt{
        pointer-events: none;
    }
</style>