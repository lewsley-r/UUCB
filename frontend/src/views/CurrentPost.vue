<template>
    <div>
        <b-card v-if="Post" id="postCard" :title="Post.user_name" :sub-title="createdAt">
            <b-card-text>
                {{ Post.content }}
            </b-card-text>
            <b-button variant="danger" v-if="isMyPost" @click="deletePost">
                <b-icon icon="trash-fill"></b-icon>
            </b-button>
                    <b-form-textarea
            id="textarea"
            v-model="comment"
            placeholder="Leave a Comment ..."
            rows="3"
            ></b-form-textarea>
            <b-button id="submitComment" class="profileBtns" @click="submitComment">
                Submit Comment
            </b-button>
        </b-card>
        <div id="comments" v-if="Post.comments" >
            <h3> Comments </h3>
            <b-card id="commentCard" v-for="comment in Post.comments" :key="comment.id" :title="comment.user_name" :sub-title="comment.created_at">
                {{ comment.content }}
            </b-card>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return{
            comment: null
        }

    },

    computed: {
        User(){
            return this.$store.state.currentUser;
        },
        Post(){
            return this.$store.state.currentPost;
        },
        isMyPost(){
            if (this.Post.user_id == this.User.id){
                return true
            }
            else{
                return null
            }
        },
        createdAt(){
            var temp = this.$store.state.currentPost.created_at.slice(11, 16) +
                ", " +
                this.$store.state.currentPost.created_at.slice(0, 10)
            return temp
        }
        
    },

    methods: {
        deletePost(){
            console.log(this.Post.id)
            this.$store.dispatch('deletePost', this.Post.id)
        },
        submitComment(){
            var commentRecord = {
            content: this.comment,
            userName: this.User.name,
            userId: this.User.id,
            postId: this.Post.id
            };
            this.$store.dispatch("submitComment", commentRecord);
            setTimeout(() => {
                window.location.reload()
            }, 2000);
        }
    },

    mounted(){
        this.$store.dispatch('getComments', this.Post.id)
    }

}
</script>

<style scoped>
    .profileBtns {
        background-color: #829399;
    }

    #submitComment{
        margin-top: 1vh;
        float: left;
    }

    #postCard{
        background-color: #D0F4EA;
        width: 50vw;
        margin-top: 5vh;
        margin-left: auto;
        margin-right: auto;
        border: 1em;
        border: 2px #545F66 solid;
    }
    
    #commentCard{
        background-color: #D0F4EA;
        width: 50vw;
        margin-top: 2vh;
        margin-left: auto;
        margin-right: auto;
        border: 1em;
        border: 2px #545F66 solid;
    }

    #comments{
        margin-right: auto;
        margin-left: auto ;
        margin-top: 5vh;
        height: 25vh;
        overflow-y: auto;
        scrollbar-color: red;
        scrollbar-track-color: black;
        width: 51vw
    }
    
</style>