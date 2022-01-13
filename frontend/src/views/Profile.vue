<template>
  <div>
    <div id="mainProfileBlock" class="row align-items-center profile-header">
      <!-- Alert for upload -->
      <b-alert
        id="fileSuccess"
        :show="dismissCountDown"
        variant="primary"
        @dismissed="dismissCountDown = 0"
        @dismiss-count-down="countDownChanged"
      >
        <p>{{ alertContent }}</p>
        <b-progress
          variant="success"
          :max="dismissSecs"
          :value="dismissCountDown"
          height="4px"
        ></b-progress>
      </b-alert>
      <div>
        <!-- Profile Buttons -->
        <b-button-group id="btnGroup">
          <b-button
            class="profileBtns btnBar"
            v-b-modal.modal-1
            id="uploadPicBtn"
          >
            Change Profile Picture
          </b-button>

          <b-button
            class="profileBtns btnBar"
            v-b-modal.modal-2
            id="uploadVidBtn"
          >
            Upload a video
          </b-button>

          <b-button
            class="profileBtns btnBar"
            @click="getFollowers"
            id="viewFollowBtn"
          >
            View Followers
          </b-button>
          <b-button class="profileBtns btnBar" to="myPosts" id="viewPostsBtn">
            View Posts
          </b-button>
          <b-button class="profileBtns btnBar" @click="getPhotos">
            View Photos
          </b-button>
          <b-button class="profileBtns btnBar" @click="getVideos">
            View Videos
          </b-button>
        </b-button-group>

        <!-- Profile Block -->
        <b-card
          :img-src="user.ppUrl"
          img-alt="Profile Picure"
          id="profileCard"
          img-left
        >
          <b-card-text>
            <h5 id="postTitle">Upload a Post</h5>
            <b-form-textarea
              id="textarea"
              v-model="postContent"
              placeholder="Enter your post..."
              rows="6"
              max-rows="12"
            >
            </b-form-textarea>
            <b-form-select
              id="tagSelect"
              v-model="selectedTag"
              :options="tags"
              size="sm"
              class=""
            >
            </b-form-select>
            <b-button
              id="submitPostBtn"
              class="profileBtns"
              v-on:click="submitPost()"
              >Submit Post
            </b-button>
          </b-card-text>
        </b-card>

        <!-- Upload a video -->
        <b-modal id="modal-2" title="Video File Upload">
          <div id="uploadArea">
            <input
              type="file"
              class="custom-file-input"
              id="customFile"
              accept="video/*"
              ref="fileContainer"
              @change="onChangeFile"
            />
            <label class="custom-file-label" for="customFile" ref="fileLabel">{{
              file1
            }}</label>
            <b-button id="submitBtn" class="profileBtns" @click="submitFile()">
              Submit</b-button
            >
          </div>
        </b-modal>

        <!-- Change Profile Picture -->
        <b-modal id="modal-1" title="Change Profile Picture">
          <div id="uploadArea">
            <b-form-file
              id="fileDrop"
              accept="image/*"
              v-model="file2"
              :state="Boolean(file2)"
            ></b-form-file>
            <b-button
              id="submitBtn"
              class="profileBtns"
              v-on:click="submitFile()"
            >
              Submit</b-button
            >
          </div>
        </b-modal>

        <!-- View Followers -->
        <b-spinner v-if="loading"></b-spinner>
        <b-modal id="modal-3" title="View followers">
          <div>
            <div id="outer-div">
              <b-list-group id="results">
                <b-list-group-item
                  button
                  id="userResult"
                  v-for="follower in followers"
                  :key="follower.name"
                  @click="setSelectedUser($event)"
                  >{{ follower.name }}
                </b-list-group-item>
              </b-list-group>
              <b-pagination
                id="pageNum"
                v-model="currentPage"
                :total-rows="perPage"
                :per-page="perPage"
                aria-controls="my-table"
              ></b-pagination>
            </div>
            <!-- <div id="outer-div-empty" v-if="noFollowers">
              <h3 id="noResults">No Results Matching your search. Please try entering something else.
              </h3>
            </div> -->
          </div>
        </b-modal>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { uploadService } from "./../services";

export default {
  data() {
    return {
      progress: 0,
      result: null,
      perPage: 10,
      label: "Choose File",
      currentPage: 1,
      isLoading: false,
      loading: false,
      file1: null,
      file2: null,
      dismissSecs: 10,
      dismissCountDown: 0,
      alertContent: null,
      alertVariant: "primary",
      pictureUrl: null,
      postContent: null,
      selectedTag: null,
      tags: [
        { value: null, text: "Select Most Appropriate Topic" },
        { value: "Other", text: "Other" },
        { value: "Web", text: "Web" },
        { value: "Machine Learning", text: "Machine Learning" },
        { value: "IoT", text: "IoT" },
        { value: "AI", text: "Artificial Intelligence" },
        { value: "DevOps", text: "DevOps" },
      ],
    };
  },

  methods: {
    submitFile() {
      if (this.file1 != null && this.isLoading == false) {
        //Hits this if its a video
        this.loading = true;
        this.progress = 0;
        this.result = null;
        uploadService.chunk(
          "/api/uploadVideoFile",
          this.file1,
          this.user.id,
          // onProgress
          (percent) => {
            this.progress = percent;
          },
          // onError
          (err) => {
            alert("An error has occurred!");
            console.log(err);
          },
          // onSuccess
          (res) => {
            const { data } = res;
            this.result = data.path;
            this.loading = null;
          }
        );
      } else if (this.file2 != null) {
        // or this if its a photo, allows for use of one endpoint and function on backend side.
        let formData = new FormData();
        formData.append("file", this.file2);
        formData.append("filetype", "profilePhoto");
        formData.append("userId", this.user.id);
        axios
          .post("/api/uploadFile", formData, {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          })
          .then(this.showAlert.bind(this))
          .catch(this.showFailure.bind(this));
      }
    },
    onChangeFile() {
      const file = this.$refs.fileContainer.files;
      this.file1 = file.length > 0 ? file[0] : null;
      if (null !== this.file1) {
        this.label = `${this.file1.name}`;
      } else {
        this.label = "Choose File";
      }
    },
    submitPost() {
      var postRecord = {
        userID: this.user.id,
        userName: this.user.name,
        content: this.postContent,
        tag: this.selectedTag,
      };
      this.$store.dispatch("submitPost", postRecord);
    },
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown;
    },
    showAlert() {
      this.alertContent = "File uploaded successfully.";
      this.file1 = null;
      this.file2 = null;
      this.dismissCountDown = this.dismissSecs;
    },
    showPostAlert() {
      this.alertContent = "Post uploaded successfully.";
      this.postContent = null;
      this.dismissCountDown = this.dismissSecs;
    },
    showFailure() {
      this.alertContent = "Something went wrong, upload failed.";
      this.alertVariant = "danger";
      this.dismissCountDown = this.dismissSecs;
    },
    getFollowers() {
      this.$store.dispatch("getFollowers", this.user.id);
      // this.loading = true;
      // setTimeout(() => {  this.$bvModal.show('modal-3');
      //                     this.loading = false;  }, 2000)
      this.$bvModal.show("modal-3");
    },
    setSelectedUser(event) {
      var user;
      for (user in this.followers) {
        if (this.followers[user].name == event.target.innerText) {
          this.$store.commit("setSelectedUser", this.followers[user]);
          this.$store.dispatch("getSelectedProfilePicture");
        }
      }
      setTimeout(() => {
        this.$router.push("/selectedProfile");
      }, 1000);
      var follower;
      var isFollowing;
      for (follower in this.user.followings) {
        if (this.selectedUser.id == this.user.followings[follower].id) {
          isFollowing = true;
        } else {
          isFollowing = false;
        }
      }
      if (isFollowing == true) {
        this.$store.commit("followSelected");
      }
    },
    getPhotos() {},
    getVideos() {
      this.$router.push("ViewVideos");
    },
  },

  computed: {
    user() {
      return this.$store.state.currentUser;
    },
    selectedUser() {
      return this.$store.state.selectedUser;
    },
    followers() {
      return this.$store.state.currentUser.followers;
    },
    presenceCheck() {
      if (this.followers) {
        return this.followers.length;
      }
      return 0;
    },
  },

  mounted() {},
};
</script>

<style>
.profileBtns {
  background-color: #829399;
}

.profileBtns.btnBar {
  margin-bottom: 2vh;
  margin-top: -5vh;
}

.card-img-left {
  height: 25vh;
  padding: 1vw;
  border-radius: 1em;
  margin-top: 5vh;
  margin-left: 1vw;
  margin-right: 5vw;
}

#submitBtn {
  width: 8vw;
  float: right;
  margin-top: -4vh;
}

#submitPostBtn {
  width: 60%;
  margin-top: 9vh;
  display: block;
}

#submitPicBtn {
  width: 8vw;
  margin-top: 2vh;
  float: right;
}

#uploadArea {
  border-radius: 1em;
  padding: 2vh;
  width: 33vw;
}

#fileDrop {
  background-color: #e8fcc2;
}

#fileSuccess {
  width: 12vw;
  height: 12vh;
  position: absolute;
  right: 2vw;
  top: 1vh;
}

#mainProfileBlock {
  height: 70vh;
  width: 85vw;
  margin-left: 12.5vw;
  border-radius: 1em;
  margin-top: 10vh;
}

#modal-2 {
  width: 50vw;
}

#textarea {
  width: 60%;
  border: #829399 solid 2px;
  background-color: #d0f4ea;
}
#tagSelect {
  display: block;
  margin-bottom: 2vh;
  margin-top: 1vh;
}

#profileCard {
  background-color: #d0f4ea;
  margin-right: auto;
  margin-left: auto;
  width: 60vw;
}

#postTitle {
  margin-top: 1vh;
  margin-right: 27vw;
}

label {
  display: none;
}
</style>