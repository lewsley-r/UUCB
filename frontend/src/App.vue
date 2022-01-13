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
  background-color: #e8fcc2;
  height: 100vh;
}

#nav {
  display: flex;
  justify-content: center;
  padding: 30px;
  border-bottom: #829399 solid 2px;
  background-color: #d0f4ea;
}

#nav a {
  font-weight: bold;
}

#nav a.router-link-exact-active {
  color: #545f66;
}

#buttons {
  border: none;
  background-color: inherit;
  color: #829399;
  font-size: large;
  font-weight: 500;
  outline: none;
}

#searchField {
  margin-left: 2vw;
  width: 20vw;
  border: #829399 solid 2px;
  background-color: #e8fcc2;
}

#searchBtn {
  background-color: #e8fcc2;
  width: 4vw;
  border: #829399 solid 2px;
  margin-right: 1vw;
  color: #829399;
}

select:focus {
  outline: none;
}
</style>
