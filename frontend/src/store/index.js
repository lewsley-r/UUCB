import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import createPersistedState from 'vuex-persistedstate'


Vue.use(Vuex)

/* eslint-disable no-new */
const store = new Vuex.Store({
    plugins: [createPersistedState()],
    state: {
        selectedUser: {
            isFollowed: false
        },
        users: [],
        posts: [],
        authToken: null,
        currentUser: null,
        currentPost: null,
        isAuthenticated: false,
        currentUserPosts: [],
        selectedUserPosts: [],
        followedPosts: [],
        filterUsers: [],
        nlpTopics: []
    },
    actions: {
        logout(state) {
            axios.post('logout').then(response => {
                console.log(response)
                state.isAuthenticated = false
                state.currentUser = null
                localStorage.clear();
                sessionStorage.clear();
                window.location.href = 'login'
            }).catch(error => console.log(error));
        },

        getAllUsers({ commit }) {
            axios.get("/api/getUsers").then((response => {
                commit('setUsers', response.data)
            })).catch((error) => {
                console.log(error)
            })
        },

        getUser({ commit, dispatch }) {
            axios.get('/user').then((response => {
                commit('setCurrentUser', response.data)
                dispatch('getProfilePicture')
                dispatch('getFollowers', response.data.id)
                dispatch('getFollowings', response.data.id)
            })).catch((error) => {
                console.log(error)
            })
        },

        getProfilePicture({ commit, state }) {
            axios
                .get("/api/getProfilePicture", { params: { userId: state.currentUser.id } })
                .then((response => {
                    commit('setProfilePicture', response.data)
                })).catch((error) => {
                    console.log(error)
                })
        },

        getSelectedProfilePicture({ commit, state }) {
            axios
                .get("/api/getProfilePicture", { params: { userId: state.selectedUser.id } })
                .then((response => {
                    commit('setSelectedProfilePicture', response.data)
                })).catch((error) => {
                    console.log(error)
                })
        },

        getComments({ commit }, postId) {
            axios.get('/api/getComments', { params: { postId: postId } }).then((response => {
                commit('setComments', response.data)
            })).catch((error) => {
                console.log(error)
            })
        },

        getPosts({ commit }) {
            axios.get('/api/getPosts').then((response => {
                commit('setPosts', response.data)
            })).catch((error) => {
                console.log(error)
            })
        },

        getPhotos({ commit, state }) {
            axios.get('api/getPhotos', { params: { userId: state.currentUser.id } }).then((response => {
                commit('setUserPhotosUrls', response.data)
            }))
                .catch((error) => {
                    console.log(error)
                })
        },

        getSelectedPhotos({ commit, state }) {
            axios.get('api/getPhotos', { params: { userId: state.selectedUser.id } }).then((response => {
                commit('setSelectedUserPhotosUrls', response.data)
            }))
                .catch((error) => {
                    console.log(error)
                })
        },


        getVideos({ commit, state }) {
            axios.get('api/getVideos', { params: { userId: state.currentUser.id } }).then((response => {
                commit('setUserVideosUrls', response.data)
            }))
                .catch((error) => {
                    console.log(error)
                })
        },

        getSelectedVideos({ commit, state }) {
            axios.get('api/getVideos', { params: { userId: state.selectedUser.id } }).then((response => {
                commit('setSelectedUserVideosUrls', response.data)
            }))
                .catch((error) => {
                    console.log(error)
                })
        },


        getFollowedPosts({ state, commit }, posts) {
            var followers = state.currentUser.followers
            var post;
            var follower;
            var followerPosts = [];
            for (post in posts) {
                for (follower in followers) {
                    if (posts[post].user_id == followers[follower].id) {
                        followerPosts.push(posts[post])
                    }
                }
            }
            commit('setFollowedPosts', followerPosts)
        },

        getFollowers({ commit }, id) {
            axios
                .get("/api/getFollowers", { params: { profileId: id } })
                .then((response => {
                    commit('setFollowers', response.data)
                })).catch((error) => {
                    console.log(error)
                })
        },

        getFollowings({ commit }, id) {
            axios
                .get("/api/getFollowings", { params: { profileId: id } })
                .then((response => {
                    commit('setFollowings', response.data)
                })).catch((error) => {
                    console.log(error)
                })
        },

        nlpAnalysis({ commit }, post) {
            // const headers = {"Access-Control-Allow-Origin": "*",
            //                 "Content-Type": "application/json",
            //                 "Accept": "*"}
            axios.post("/api/nlpAnalyse", {"message": post}
            )
                .then((response => {
                    var record;
                    var temp = response['data'][0]['category'].split('/').concat(response['data'][1]['category'].split('/'))
                    for (record in temp){
                        if (temp[record] == ""){
                            temp.splice(record, 1)
                        }
                    }
                    for (record in temp){
                        temp[record] = {'text': temp[record], 'value': temp[record]} 
                    }
                    for (record in response['data']) {
                        if (response['data'][record]['name']) {
                            temp.push({'text': response['data'][record]['name'], 'value': response['data'][record]['name']})
                        }
                    }
                    console.log(temp)
                    var finalArray = temp.map((x) => x);
                    console.log(finalArray)
                    commit('setTopics', temp)
                }))
        },

        submitPost(state, payload) {
            axios.post("/api/uploadPost", payload)
                .then((response => {
                    alert("Posted Successfully!", response)
                    window.location.replace('/myPosts')
                }))
                .catch(error =>
                    console.log(error));
        },

        submitComment({ commit }, payload) {
            console.log(payload)
            commit('addComment', payload)
            axios.post("/api/postComment", payload)
                .catch(error =>
                    console.log(error));
        },

        deletePost(state, id) {
            axios.post("/api/deletePost", { id: id })
                .then((response => {
                    alert("Delete Successfully!", response)
                    window.location.replace('/myPosts')
                }))
                .catch(error =>
                    console.log(error));
        },

        followUser(state, payload) {
            console.log(payload)
            let formData = new FormData();
            formData.append("profileId", payload.id);
            formData.append("userId", payload.currentUserId);
            // console.log(formData)
            axios.post("/api/follow", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            })
                .catch((error) => {
                    console.log(error)
                })
        },

        unfollowUser(state, payload) {
            let formData = new FormData();
            formData.append("profileId", payload.id);
            formData.append("userId", payload.currentUserId);
            axios.post("/api/unfollow", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            })
                .catch((error) => {
                    console.log(error)
                })
        },
    },


    mutations: {
        addComment(state, comment) {
            console.log(state.currentPost)
            if (state.currentPost.comments) {
                state.currentPost.comments.push(comment)
            }
            else {
                state.currentPost.comments = [comment]
            }
        },

        setTopics(state, topics){
            state.nlpTopics = topics
        },

        setUsers(state, users) {
            state.users = users
        },

        setCurrentUser(state, user) {
            state.currentUser = user
            state.isAuthenticated = true

        },

        setCurrentPost(state, post) {
            state.currentPost = post
        },

        setProfilePicture(state, url) {
            state.currentUser.ppUrl = url
        },

        setSelectedProfilePicture(state, url) {
            state.selectedUser.ppUrl = url
        },

        setSelectedUser(state, user) {
            state.selectedUser = Object.assign(state.selectedUser, user)
        },

        setComments(state, comments) {
            state.currentPost.comments = comments
        },

        setPosts(state, posts) {
            state.posts = posts
        },

        setFollowedPosts(state, posts) {
            state.followedPosts = posts
        },

        setFilterUsers(state, users) {
            state.filterUsers = users
        },

        setFollowers(state, followers) {
            state.currentUser.followers = followers
        },

        setFollowings(state, followings) {
            state.currentUser.followings = followings
        },

        setUserVideosUrls(state, videos) {
            state.currentUser.videos = videos
        },

        setUserPhotosUrls(state, photos) {
            state.currentUser.photos = photos
        },

        setSelectedUserVideosUrls(state, videos) {
            state.selectedUser.videos = videos
        },

        setSelectedUserPhotosUrls(state, photos) {
            state.selectedUser.photos = photos
        },

        followSelected(state) {
            state.selectedUser.isFollowed = true
        },

        unfollowSelected(state) {
            state.selectedUser.isFollowed = false
        }
    }
})

export default store