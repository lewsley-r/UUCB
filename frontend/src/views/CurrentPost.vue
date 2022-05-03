<template>
  <div>
    <b-card
      v-if="Post"
      id="postCard"
      :title="Post.user_name"
      :sub-title="createdAt"
    >
      <b-card-text>
        {{ Post.content }}
      </b-card-text>

      <b-form-textarea
        id="textarea"
        v-model="comment"
        placeholder="Leave a Comment ..."
        rows="3"
      ></b-form-textarea>
      <b-button id="submitComment" class="profileBtns" @click="submitComment">
        Submit Comment
      </b-button>
      <b-button
        variant="danger"
        id="deleteBtn"
        v-if="isMyPost"
        @click="deletePost"
      >
        <b-icon icon="trash-fill"></b-icon>
      </b-button>
    </b-card>
    <h3 style="margin-top: 5vh">Comments</h3>
    <div id="comments" v-if="Post.comments">
      <b-card
        id="commentCard"
        v-for="comment in comments"
        :key="comment.id"
        :title="comment.user_name"
        :sub-title="comment.created_at"
      >
        {{ comment.content }}
      </b-card>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      comment: null,
    };
  },

  computed: {
    User() {
      return this.$store.state.currentUser;
    },
    Post() {
      return this.$store.state.currentPost;
    },
    isMyPost() {
      if (this.Post.user_id == this.User.id) {
        return true;
      } else {
        return null;
      }
    },
    createdAt() {
      var temp =
        this.$store.state.currentPost.created_at.slice(11, 16) +
        ", " +
        this.$store.state.currentPost.created_at.slice(0, 10);
      return temp;
    },
    comments() {
      return this.$store.state.currentPost.comments;
    },
    // formattedComments () {  
    //   var comment;
    //   var comments;
    //   for (comment in this.comments){
    //     comments = {id: comment.id,
    //     user_name: comment.user_name,
    //     created_at}
    //     this.comments[comment].created_at.slice().slice(11, 16) +
    //     ", " +
    //     this.comments[comment].created_at.slice(0, 10);
    //   }
    //}
  },

  methods: {
    deletePost() {
      this.$store.dispatch("deletePost", this.Post.id);
    },
    submitComment() {
      var commentRecord = {
        content: this.comment,
        userName: this.User.name,
        userId: this.User.id,
        postId: this.Post.id,
      };
      this.$store.dispatch("submitComment", commentRecord);
      window.location.reload()
      
    },
  },

  mounted() {
    this.$store.dispatch('getComments', this.Post.id)
  },
};
</script>

<style scoped>
.profileBtns {
  background-color: #829399;
}

#deleteBtn {
  float: right;
  margin-top: 1vh;
}

#textarea {
  width: 100%;
}

#submitComment {
  margin-top: 1vh;
  float: left;
}

#postCard {
  background-color: #d0f4ea;
  width: 50vw;
  margin-top: 5vh;
  margin-left: auto;
  margin-right: auto;
  border: 1em;
  border: 1px #545f66 solid;
}

#commentCard {
  background-color: #d0f4ea;
  width: 50vw;
  margin-top: 2vh;
  margin-left: auto;
  margin-right: auto;
  border: 1em;
  border: 1px #545f66 solid;
}

#comments {
  margin-right: auto;
  margin-left: auto;
  margin-top: 5vh;
  height: 40vh;
  overflow-y: auto;
  scrollbar-color: red;
  scrollbar-track-color: black;
  width: 51vw;
}
</style>