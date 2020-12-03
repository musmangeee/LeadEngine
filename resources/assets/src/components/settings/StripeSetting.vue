<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">Stripe Integration</h4>

    <form @submit.prevent="handleSubmit" id="settings-stripe">
      <b-card sub-title="Stripe Settings">
          <b-form-group label="Stripe Integration">
            <label class="switcher switcher-success">
              <input type="checkbox" class="switcher-input" v-model="stripe.stripe_enable" />
              <span class="switcher-indicator">
                <span class="switcher-yes text-success"></span>
                <span class="switcher-no"></span>
              </span>
              <span class="switcher-label" v-if="!stripe.stripe_enable">Disabled</span>
              <span class="switcher-label" v-if="stripe.stripe_enable">Enabled</span>
            </label>
          </b-form-group>

          <b-form-group label="Publishable key">
              <b-input
                      v-model="stripe.stripe_key"
                      :class="{ 'is-invalid': $v.stripe.stripe_key.$error }"
              />
              <div
                      v-if="!$v.stripe.stripe_key.required && submitted"
                      class="invalid-feedback"
              >Stripe key field is required.</div>
          </b-form-group>

        <b-form-group label="Secret key">
          <b-input
            v-model="stripe.stripe_secret"
            :class="{ 'is-invalid': $v.stripe.stripe_secret.$error }"
          />
          <div
            v-if="!$v.stripe.stripe_secret.required && submitted"
            class="invalid-feedback"
          >Secret secret field is required.</div>
        </b-form-group>
      </b-card>

      <b-button variant="primary" type="submit" class="float-right mt-3">
          Save
      </b-button>
    </form>
  </div>
</template>

<script>
import Vue from "vue";
import Vuelidate from "vuelidate";
import { requiredIf, required } from "vuelidate/lib/validators";
Vue.use(Vuelidate);

const initStripe = {
  stripe_enable: false,
  stripe_secret: "",
  stripe_key: ""
};
export default {
  name: "StripeSetting",
    metaInfo: {
        title: 'Settings - Stripe'
    },
  created() {
    let self = this
    window.axios.get('/api/settings').then(response => {

        let options = response.data;
        options.forEach(function (option) {

            if (option.name.includes('stripe_')) {
                if (option.name == 'stripe_enable'){

                    if (option.value == 1) {
                        self.stripe.stripe_enable = true
                    }
                    else {
                        self.stripe.stripe_enable = false

                    }
                }else{
                    self.stripe[option.name] = option.value
                }

            }
        })

        console.log(this.stripe)
    }).catch(error => {
    })
  },
  data: () => ({
    submitted: false,
    stripe: { ...initStripe }
  }),
  validations: {
    stripe: {
      stripe_enable: {
        required
      },
      stripe_secret: {
        required: requiredIf(function(stripe) {
          return stripe.stripe_enable;
        })
      },
      stripe_key: {
        required: requiredIf(function(stripe) {
          return stripe.stripe_enable;
        })
      }
    }
  },
  methods: {
    handleSubmit() {
      this.submitted = true;

      // stop here if form is invalid
      this.$v.stripe.$touch();
      if (this.$v.stripe.$invalid) {
        console.log(this.stripe);
        return;
      }

      let self = this;
      window.axios
        .put("/api/settings/stripe", this.stripe)
        .then(response => {
          console.log(response);
          self.submitted = false;
          localStorage.setItem(
            "settings",
            JSON.stringify(response.data.options)
          );
          self.$snotify.success("Stripe settings saved.", "Success");
        })
        .catch(error => {
          self.loading = false;
          self.$snotify.error(
            "There was a problem saving Stripe Settings.",
            "Error"
          );
        });
    }
  }
};
</script>

<style>
</style>
