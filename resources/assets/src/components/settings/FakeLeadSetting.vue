<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">Fake/Test Lead Settings</h4>

    <form @submit.prevent="handleSubmit" id="settings-stripe">
      <b-card sub-title="set 0 to disable the fake lead and maximum number is 500">
          <b-form-group label="Inline radios (default)">
            <b-form-radio-group
                v-model="data.test_type"
                :options="typeOptions"
                name="radio-inline"
            ></b-form-radio-group>
          </b-form-group>

          <b-form-group label="Number of fake/test lead per minute">
              <b-input
                      v-model="data.number_of_fake_lead_per_minute"
                      :class="{ 'is-invalid': $v.data.number_of_fake_lead_per_minute.$error }"
              />
              <div
                      v-if="!$v.data.number_of_fake_lead_per_minute.required && submitted"
                      class="invalid-feedback"
              >Number of fake lead per minute is required.</div>
              <div
                      v-if="!$v.data.number_of_fake_lead_per_minute.numeric && submitted"
                      class="invalid-feedback"
              >Number of fake lead per minute must be a numeric.</div>
          </b-form-group>

          <b-btn class="btn btn-danger" @click="deleteAllFakeLead">Delete All Fake/Test Lead</b-btn>

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
import { requiredIf, required, numeric } from "vuelidate/lib/validators";
Vue.use(Vuelidate);

const initData = {
  number_of_fake_lead_per_minute: 0,
  test_type: 'off',
};
export default {
  name: "FakeLeadSetting",
    metaInfo: {
        title: 'Settings - Fake Lead'
    },
  mounted() {
    let self = this
    window.axios.get('/api/settings').then(response => {

        let options = response.data;
        options.forEach(function (option) {
            if (option.name == 'number_of_fake_lead_per_minute'){
                self.data.number_of_fake_lead_per_minute = option.value
            }

            if (option.name == 'test_type'){
                self.data.test_type = option.value
            }

        })
    }).catch(error => {
    })
  },
  data: () => ({
    submitted: false,
    typeOptions: [
        { text: 'Off', value: 'off' },
        { text: 'Faker', value: 'faker' },
        { text: 'Test', value: 'test' }
    ],
    data: { ...initData }
  }),
  validations: {
    data: {
      number_of_fake_lead_per_minute: {
        required,
        numeric
      },
    }
  },
  methods: {
      deleteAllFakeLead(){
          let self=this
          window.axios
            .delete("/api/settings/delete-all-fake-lead")
            .then(response => {
                console.log(response)
                self.$snotify.success("All Fake Leads deleted.", "Success");
            })
            .catch(error => {
                self.loading = false;
                self.$snotify.error(
                    "There was a problem deleting fake leads.",
                    "Error"
                );
            });
      },
    handleSubmit() {
      this.submitted = true;

      // stop here if form is invalid
      this.$v.data.$touch();
      if (this.$v.data.$invalid) {
        console.log(this.data);
        return;
      }

      let self = this;
      window.axios
        .put("/api/settings/fake-lead", this.data)
        .then(response => {
          console.log(response);
          self.submitted = false;
          self.$snotify.success("Fake Lead settings saved.", "Success");
        })
        .catch(error => {
          self.loading = false;
          self.$snotify.error(
            "There was a problem saving Fake Lead Settings.",
            "Error"
          );
        });
    }
  }
};
</script>

<style>
</style>
