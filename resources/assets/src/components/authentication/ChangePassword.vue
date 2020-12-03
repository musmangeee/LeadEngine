<template>
  <div class="authentication-wrapper authentication-2 px-4">
    <div class="authentication-inner py-5">
      <!-- Form -->
      <form class="card">
        <div class="p-4 p-sm-5">
          <!-- Logo -->
          <div class="d-flex justify-content-center align-items-center mb-5">
            <img src="images/logo.png" style="height: 60px" />
          </div>
          <!-- / Logo -->

          <h5 class="text-center font-weight-normal mb-4">Change Your Password</h5>

          <hr class="mt-0 mb-4" />

            <b-form-group>
                <b-form-input type="email" v-model="form.email" placeholder="Enter your email" />
                <div
                        v-if="$v.form.email.$dirty && !$v.form.email.required"
                        class="invalid-feedback"
                        style="display: inline-block"
                >Email field is required.</div>
            </b-form-group>

          <b-form-group>
            <b-form-input type="password" v-model="form.password" placeholder="Enter your password" />
            <div
              v-if="$v.form.password.$dirty && !$v.form.password.required"
              class="invalid-feedback"
              style="display: inline-block"
            >Password field is required.</div>
          </b-form-group>

          <b-form-group>
            <b-form-input type="password"
              v-model="form.password_confirmation"
              placeholder="Enter your password confirmation"
            />
            <div
              v-if="$v.form.password.$dirty && !$v.form.password.required"
              class="invalid-feedback"
              style="display: inline-block"
            >Password confirmation is required.</div>

            <div
              v-if="$v.form.password_confirmation.$dirty && !$v.form.password_confirmation.sameAsPassword"
              class="invalid-feedback"
              style="display: inline-block"
            >Password confirmation must the same as password.</div>
          </b-form-group>

          <b-btn variant="primary" :disabled="loading" :block="true" @click.prevent="changePassword">Change Password</b-btn>
        </div>
      </form>
      <!-- / Form -->
    </div>
  </div>
</template>

<!-- Page -->
<style src="@/vendor/styles/pages/authentication.scss" lang="scss"></style>

<script>
import Vue from "vue";
import Vuelidate from "vuelidate";
import { required, sameAs } from "vuelidate/lib/validators";
Vue.use(Vuelidate);

export default {
  name: "ChangePassword",
  metaInfo: {
    title: "Change Password"
  },
  data: () => ({
    loading : false,
    form: {
      password: "",
      password_confirmation: "",
      token: ""
    }
  }),
  validations: {
    form: {
        email: {
          required
        },
      password: {
        required
      },
      password_confirmation: {
        required,
        sameAsPassword: sameAs("password")
      }
    }
  },
  created() {
    console.log(this.$router);

    this.form.token = this.$router.currentRoute.query.token;
  },
  methods: {
    changePassword() {

        let self = this
        this.$v.form.$touch();
        var isValid = !this.$v.form.$invalid
        if (isValid) {
            this.loading = true

            window.axios.post('/api/password/reset', this.form).then(response => {
                self.$snotify.success('Change Password Success.','Success!')
                console.log(response)
                self.loading = false
                this.$router.push("login");
            }).catch(error => {
                console.log(error)
                self.loading = false
                self.$snotify.error('There was a problem when change password.','Error!')
            });

        }

    },
    loginPage() {
      this.$router.push("login");
    }
  }
};
</script>
