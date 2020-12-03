<template>
  <div class="authentication-wrapper authentication-2 px-4">
    <div class="authentication-inner py-5">

      <!-- Form -->
      <form class="card">
        <div class="p-4 p-sm-5">

          <!-- Logo -->
            <div class="d-flex justify-content-center align-items-center mb-5">

                <img src="images/logo.png" style="height: 60px">

            </div>
          <!-- / Logo -->

          <h5 class="text-center text-muted font-weight-normal mb-4">Reset Your Password</h5>

          <hr class="mt-0 mb-4">

          <p>
            Enter your email address and we will send you a link to reset your password.
          </p>

          <b-form-group>
            <b-input v-model="credentials.email" placeholder="Enter your email address" />
          </b-form-group>

          <b-btn variant="primary" :block="true" @click.prevent="passwordReset">Send Password Reset Email</b-btn>

        </div>
      </form>
      <!-- / Form -->

    </div>
  </div>
</template>

<!-- Page -->
<style src="@/vendor/styles/pages/authentication.scss" lang="scss"></style>


<script>

export default {
  name: 'pages-authentication-password-reset',
  metaInfo: {
    title: 'Forgot Password'
  },
  data: () => ({
    credentials: {
      email: ''
    }
  }),
  methods: {
    passwordReset() {

      axios.post('/api/password/create', {
        email: this.credentials.email,
      })
        .then(response => {
          this.$swal({
            type: 'success',
            text: 'We have e-mailed your password reset link!'
          })
        }).catch(error => {
      });
    }
  }
}
</script>
