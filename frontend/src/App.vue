<template>
  <div id="app">
    <div id="nav">
      <div>
        <b-button-group id="btnGroup">
          <b-button id="buttons" to="/frontendHome">
            <b-icon icon="house-door-fill"> </b-icon>
            Home
          </b-button>
          <b-button id="buttons" to="/about">
            <b-icon icon="book-half"> </b-icon>
            About
          </b-button>
          <b-button id="buttons" to="/profile" v-if="user">
            <b-icon icon="person-circle"> </b-icon>
            {{ user.name }}
          </b-button>
          <b-button id="buttons" to="/Feed" v-if="user">
            <b-icon icon="rss"> </b-icon>
            Feed
          </b-button>
          <b-button id="buttons" href="/login" v-if="!isAuthenticated">
            Login
          </b-button>
          <b-button id="buttons" @click="logout()" v-if="isAuthenticated">
            <b-icon icon="box-arrow-left"> </b-icon>
            Logout
          </b-button>
          <b-form-input
            id="searchField"
            v-model="searchContent"
            placeholder="Search users ..."
          ></b-form-input>
          <b-button id="searchBtn" @click="searchUsers">
            <b-icon icon="search" scale="1"></b-icon>
          </b-button>
        </b-button-group>
      </div>
    </div>
    <router-view />
  </div>
</template>
<script>
export default {
  data() {
    return {
      searchContent: null,
    };
  },
  methods: {
    searchUsers() {
      var user;
      var filterUsers = [];
      for (user in this.users) {
        if (this.users[user].name.includes(this.searchContent) == true) {
          filterUsers.push(this.users[user]);
        }
      }
      this.$store.commit("setFilterUsers", filterUsers);
      this.$router.push("/searchResults");
    },
    logout() {
      this.$store.dispatch("logout");
    },
  },
  computed: {
    isAuthenticated() {
      return this.$store.state.isAuthenticated;
    },
    user() {
      return this.$store.state.currentUser;
    },
    users() {
      return this.$store.state.users;
    },
    selectedUser() {
      return this.$store.state.selectedUser;
    },
  },

  async created() {
    await this.$store.dispatch("getUser");
    await this.$store.dispatch("getAllUsers");
    // await this.$store.dispatch('getVideos', this.user.id)
    await this.$store.dispatch("getPosts");
  },

  mounted() {
    // this.$store.dispatch('getFollowers', this.user.id)
    // this.$store.dispatch('getFollowings', this.user.id)
  },
};
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Comfortaa&family=Ubuntu:wght@500&display=swap");

#app {
  font-family: "Comfortaa", cursive;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #545f66;
  background-color: #43bae9;
  min-height: 100vh;
  height: fit-content;
}

#nav {
  display: flex;
  justify-content: center;
  padding: 30px;
  border-bottom: #1e8cb4 solid 2px;
  background-color: #0003076c;
}

#nav a {
  font-weight: bold;
}

#nav a.router-link-exact-active {
  color: white;
}

#buttons {
  border: none;
  background-color: inherit;
  color: #43bae9;
  font-size: large;
  font-weight: 500;
  outline: none;
}

#searchField {
  margin-left: 2vw;
  width: 20vw;
  background-color: white;
}

#searchBtn {
  background-color: #43bae9;
  width: 4vw;
  margin-right: 1vw;
  color: white;
}

select:focus {
  outline: none;
}
</style>
