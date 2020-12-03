<template>
  <div class="authentication-wrapper authentication-1 px-4">
    <div class="authentication-inner py-5">

      <!-- Logo -->
      <div class="d-flex justify-content-center align-items-center mb-5">

        <img src="images/logo.png" style="height: 60px">

      </div>
      <!-- / Logo -->

      <div class="alert alert-danger mt-5" v-if="errors">
        {{ errors.message }}
      </div>

      <!-- Form -->
      <form class="mb-5">
        <b-form-group label="Email Address">
          <b-input tabindex="1" v-model="credentials.email" />
        </b-form-group>
        <b-form-group>
          <div slot="label" class="d-flex justify-content-between align-items-end">
            <div>Password</div>
            <a href="javascript:void(0)" class="d-block small" @click.prevent="forgotPassword">Forgot password?</a>
          </div>
          <b-input tabindex="2" type="password" v-model="credentials.password" @keyup.native.enter="login" />
        </b-form-group>

        <div class="d-flex justify-content-between align-items-center m-0">
          <b-check v-model="credentials.rememberMe" class="m-0">Stay Signed In</b-check>
          <b-btn tabindex="3" variant="primary" @click.prevent="login">Sign In</b-btn>
        </div>
      </form>
      <!-- / Form -->

    </div>
  </div>
</template>

<!-- Page -->
<style src="@/vendor/styles/pages/authentication.scss" lang="scss"></style>

<script>
import { mapState } from 'vuex'
export default {
  name: 'login',
  metaInfo: {
    title: 'Login'
  },
  data: () => ({
    credentials: {
      email: '',
      password: '',
      rememberMe: false
    },
    loading: false,
  }),
  computed: mapState({
    errors: state => state.auth.errors
  }),
  methods: {
    forgotPassword() {
        this.$router.push('/password-reset')
    },

    login() {
      this.$store.dispatch('login',{
        email: this.credentials.email,
        password: this.credentials.password
      }).then(() => {
        if(this.$store.getters.loggedIn){
          console.log(this.$store.getters.roles)
          this.$router.push('/dashboard');
        }
      })
    }
  },
}
</script>
