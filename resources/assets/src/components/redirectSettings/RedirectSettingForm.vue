<template>
  <div>
    <h4 class="font-weight-bold py-3">Redirect Settings</h4>
    <b-breadcrumb :items="breadcrumbItems" />

    <b-card>
      <b-form-row>
        <b-form-group label="Click Limit" class="col">
          <b-input type="number" v-model="redirectSettingForm.click_limit"></b-input>
          <div
            v-if="!$v.redirectSettingForm.click_limit.required && $v.redirectSettingForm.click_limit.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
          >Click limit field is required.</div>
        </b-form-group>

        <b-form-group label="Time Limit (seconds)" class="col">
          <b-input type="text" v-model="redirectSettingForm.time_limit"></b-input>
          <div
            v-if="!$v.redirectSettingForm.time_limit.required && $v.redirectSettingForm.time_limit.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
          >Time limit field is required.</div>
        </b-form-group>
      </b-form-row>
    </b-card>

    <b-button @click="handleSubmit" class="mt-4 float-right" variant="primary">
      <span v-if="id">Update</span>
      <span v-else>Save</span>
    </b-button>
  </div>
</template>

<script>
import Vue from "vue";
import Vuelidate from "vuelidate";
import { mapState } from "vuex";
import { required } from "vuelidate/lib/validators";
Vue.use(Vuelidate);

export default {
  name: "RedirectSettingForm",
  props: ["id"],
  computed: {
    ...mapState("redirectSettings", ["redirectSettingDetail"]),

    breadcrumbItems: function() {
      if (this.id) {
        return [
          {
            text: "Redirect Settings",
            to: { name: "redirect-settings" }
          },
          {
            text: "Edit",
            active: true
          }
        ];
      } else {
        return [
          {
            text: "Redirect Settings",
            to: { name: "redirect-settings" }
          },
          {
            text: "Create",
            active: true
          }
        ];
      }
    },

  },
  data: () => ({
    redirectSettingForm: {
      click_limit: "",
      time_limit: ""
    }
  }),

  created() {
    if (this.id) {
      this.loadRedirectSettingDetail();
    }
  },

  validations() {
      return {
          redirectSettingForm : {
              click_limit : {
                  required
              },
              time_limit : {
                  required
              }
          }
      }
  },

  watch : {
      redirectSettingDetail(newVal, oldVal){
          if(newVal){
              this.redirectSettingForm = {...this.redirectSettingDetail}
          }
      }
  },



  methods: {
    loadRedirectSettingDetail() {
      this.$store.dispatch("redirectSettings/redirectSettingDetail", { id: this.id });
    },
    async handleSubmit() {
      let self = this;
      this.$v.$touch();
      var isValid = !this.$v.$invalid;
      if (isValid) {
        if (this.id == undefined) {
          try {
            let data = { ...self.redirectSettingForm };
            let response = await this.$store.dispatch(
              "redirectSettings/redirectSettingCreate",
              {
                data: data
              }
            );
            self.$snotify.success("Create Redirect Setting Success.", "Success!");
            self.$router.push({ name: "redirect-settings" });
          } catch (e) {
            self.$snotify.error("Create Redirect Setting Failed.", "Error!");
          }
        } else {
          try {
            let data = { ...self.redirectSettingForm };
            let response = await this.$store.dispatch(
              "redirectSettings/redirectSettingUpdate",
              {
                id: this.id,
                data: data
              }
            );
            self.$snotify.success("Update Redirect Setting Success.", "Success!");
            self.$router.push({ name: "redirect-settings" });
          } catch (e) {
            self.$snotify.error("Update Redirect Setting Failed.", "Error!");
          }
        }
      }
    }
  }
};
</script>