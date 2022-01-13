<template>
    <div>
        <p>Hello</p>
        <div >
            <p>You are not logged in</p>
            <b-card bg-variant="light">
                <b-form-group
                label-cols-lg="3"
                label="Login"
                label-size="lg"
                label-class="font-weight-bold pt-0"
                >
                    <b-form-group
                        label="Email"
                        label-for="userEmail"
                        label-cols-sm="3"
                        label-align-sm="right"
                    >
                        <b-form-input v-model="credentials.email" type='email' id="userEmail"></b-form-input>
                    </b-form-group>

                    <b-form-group
                        label="Password"
                        label-for="password"
                        label-cols-sm="3"
                        label-align-sm="right"
                    >
                        <b-form-input v-model="credentials.password" type='password' id="password"></b-form-input>
                    </b-form-group>              
                </b-form-group>
                <b-button variant='primary' @click="onLogin()">Sign In</b-button>
            </b-card>

        </div>

    </div>
</template>

<script>

export default {
  data() {
    return {
      credentials: {
        email: null,
        password: null,
      },
    };
  },

  computed: {
    
  },

  methods: {
  
    onLogin() {
      this.$store.commit('login', this.credentials);
    },
    onLogout() {
      this.logout()
        .then(response => {
            console.log(response, "--- Logged out Successfully ---")
        })
        .catch(error => {
            console.log(error)
        });
    },
  },

};
</script>

<style>

</style>