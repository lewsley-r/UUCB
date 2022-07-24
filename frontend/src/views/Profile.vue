<template>
  <div>
    <div
      id="mainProfileBlock"
      class="row align-items-center profile-header"
      v-if="!loading"
    >
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
            v-b-modal.modal-4
            id="uploadPicBtn"
          >
            Upload a picture
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
              placeholder="Enter a minimum of 10 words for additional NLP generated categories."
              rows="6"
              max-rows="12"
            >
            </b-form-textarea>
            <b-button
              id="submitPostBtn"
              class="profileBtns"
              v-on:click="analysePost()"
              >Submit Post for NLP Analysis
            </b-button>
          </b-card-text>
        </b-card>

        <!-- Select NLP Assigned Topics -->
        <b-modal id="modal-6" hide-footer title="NLP Generated Topics">
          <div>
            <b-form-group label="Select Topics you want to assign:">
              <b-form-checkbox-group
                id="checkbox-group-1"
                v-model="selectedTags"
                :options="options"
                name="flavour-1"
              ></b-form-checkbox-group>
            </b-form-group>
            <b-button
              id="submitPostBtn"
              class="profileBtns"
              v-on:click="submitPost()"
              >Submit Post with selected topics
            </b-button>
          </div>
        </b-modal>

        <!-- Upload a video -->
        <b-modal id="modal-2" title="Video File Upload">
          <template #modal-footer>
            <div class="w-100"></div>
          </template>

          <div id="uploadArea">
            <b-row>
              <input
                type="file"
                class="custom-file-input"
                id="customFile"
                accept="video/*"
                ref="fileContainer"
                @change="onChangeFile"
              />
              <label
                class="custom-file-label"
                for="customFile"
                ref="fileLabel"
                >{{ file1 }}</label
              >
            </b-row>
            <b-row>
              <b-button
                id="submitVidBtn"
                class="profileBtns"
                @click="submitFile()"
              >
                Submit
              </b-button>
            </b-row>
          </div>
        </b-modal>

        <!-- Change Profile Picture -->
        <b-modal id="modal-1" title="Change Profile Picture">
          <template #modal-footer>
            <div class="w-100"></div>
          </template>
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

        <!-- Upload a Photo -->
        <b-modal id="modal-4" title="Upload a photo">
          <template #modal-footer>
            <div class="w-100"></div>
          </template>
          <div id="uploadArea">
            <b-form-file
              id="fileDrop"
              accept="image/*"
              v-model="file3"
              :state="Boolean(file3)"
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
    <div v-else>
      <b-spinner id="loadSpin" variant="primary"></b-spinner>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { uploadService } from "./../services";

export default {
  data() {
    return {
      loading: false,
      progress: 0,
      result: null,
      perPage: 10,
      label: "Choose File",
      currentPage: 1,
      isLoading: false,
      file1: null,
      file2: null,
      file3: null,
      dismissSecs: 10,
      dismissCountDown: 0,
      alertContent: null,
      alertVariant: "primary",
      pictureUrl: null,
      postContent: null,
      selectedTags: [],
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
    analysePost() {
      this.loading = !this.loading;
      setTimeout(() => {
        this.$store.dispatch("nlpAnalysis", this.postContent);
        this.loading = !this.loading;
      }, 2000);
      setTimeout(() => {
        this.$bvModal.show("modal-6");
      }, 2500);
    },

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
            alert("An error has occurred!", err);
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
      } else if (this.file3 != null) {
        let formData = new FormData();
        formData.append("file", this.file3);
        formData.append("filetype", "photo");
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
        user_id: this.user.id,
        user_name: this.user.name,
        content: this.postContent,
        tag: this.selectedTags.toString(),
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
    getPhotos() {
      this.$store.dispatch("getPhotos");
      setTimeout(() => {
        this.$router.push("ViewPhotos");
      }, 2000);
    },
    getVideos() {
      this.$store.dispatch("getVideos");
      setTimeout(() => {
        this.$router.push("ViewVideos");
      }, 2000);
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
    options() {
      return this.$store.state.nlpTopics;
    },
  },

  mounted() {},
};
</script>

<style>
.custom-control-label {
  padding: 1vh;
}

.custom-file-label {
  display: none;
}

.profileBtns {
  background-color: #0003076c;
}

.profileBtns.btnBar {
  border: 1px black solid;
  margin-bottom: 2vh;
  margin-top: -5vh;
}

.card-img-left {
  height: 25vh;
  padding: 1vw;
  border-radius: 3vh;
  margin-top: 5vh;
  margin-left: 2vw;
  margin-right: 5vw;
}

.modal-content {
  /* margin-left: -15vw; */
  margin-top: 20vh;
  width: 30vw;
  height: 40vh;
  overflow: scroll;
}

.close {
  background-color: inherit;
  color: red;
  font-size: 3vh;
  border: none;
}

#customFile {
  float: left;
}

#loadSpin {
  margin-top: 25vh;
}

#submitBtn {
  width: 8vw;
  float: bottom;
  margin-top: 4vh;
}

#submitVidBtn {
  width: 8vw;
  margin-left: 1vh;
  margin-top: 4vh;
}

#submitPostBtn {
  background-color: green;
  width: 60%;
  margin-top: 6vh;
  display: block;
}

#submitPicBtn {
  width: 8vw;
  margin-top: 2vh;
  float: right;
}
/* 
#uploadArea {
  border-radius: 1em;
  padding: 2vh;
  
} */

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
  background-color: #0003076c;
  border: 1px black solid;
  border-radius: 1vh;
  margin-right: auto;
  margin-left: auto;
  width: 60vw;
  height: fit-content;
}

#postTitle {
  margin-top: 1vh;
  margin-right: 27vw;
  color: white;
  width: 10vw;
}

#model-6___BV_modal_content_ {
  height: fit-content;
}
</style>