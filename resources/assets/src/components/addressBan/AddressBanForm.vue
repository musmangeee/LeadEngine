<template>
  <div>
    <h4 class="font-weight-bold py-3">IP/Host Bans</h4>
    <b-breadcrumb :items="breadcrumbItems" />

    <b-card>
      <b-form-row>
        <b-form-group label="Address Type" class="col">
          <b-form-select :options="typeOptions" v-model="addressBanForm.type" name="type"></b-form-select>
          <div
            v-if="!$v.addressBanForm.type.required && $v.addressBanForm.type.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
          >Address type field is required.</div>
        </b-form-group>

        <b-form-group :label="typeText" class="col">
          <b-input type="text" v-model="addressBanForm.address"></b-input>
          <div
            v-if="!$v.addressBanForm.address.required && $v.addressBanForm.address.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
          >{{ typeText }} field is required.</div>

          <div
            v-if="!$v.addressBanForm.address.ipAddress && $v.addressBanForm.address.$dirty && addressBanForm.type == 'ip-address'"
            class="invalid-feedback"
            style="display: inline-block;"
          >IP Address field must be a valid IP.</div>

          <div
            v-if="!$v.addressBanForm.address.isUniqueAddress && $v.addressBanForm.address.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
          >{{ typeText }} field already exists.</div>
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
import { required, ipAddress } from "vuelidate/lib/validators";
Vue.use(Vuelidate);

export default {
  name: "AddressBanForm",
  props: ["id"],
  computed: {
    ...mapState("addressBans", ["addressBanDetail"]),

    breadcrumbItems: function() {
      if (this.id) {
        return [
          {
            text: "IP/Host Bans",
            to: { name: "ip-bans" }
          },
          {
            text: "Edit",
            active: true
          }
        ];
      } else {
        return [
          {
            text: "IP/Host Bans",
            to: { name: "ip-bans" }
          },
          {
            text: "Create",
            active: true
          }
        ];
      }
    },

    typeText() {
      if (this.addressBanForm.type == "ip-address") {
        return "IP Address";
      } else if (this.addressBanForm.type == "hostname") {
        return "Hostname";
      } else {
        return "IP Address/Hostname";
      }
    }
  },
  data: () => ({
    typeOptions: [
      { value: "", text: "Select Type" },
      { value: "ip-address", text: "IP Address" },
      { value: "hostname", text: "Hostname" }
    ],
    addressBanForm: {
      type: "",
      address: ""
    }
  }),

  created() {
    if (this.id) {
      this.loadAddressBanDetail();
    }
  },

  validations() {
    if (this.addressBanForm.type == "ip-address") {
      return {
        addressBanForm: {
          type: {
            required
          },
          address: {
            required,
            ipAddress,
            async isUniqueAddress(value) {
              // standalone validator ideally should not assume a field is required
              if (value === "") return true;
              try {
                let result = await axios.post(
                  `/api/address-ban/check-unique-address`,
                  {
                    type: this.addressBanForm.type,
                    address: value,
                    address_ban_id: this.id
                  }
                );
                return true;
              } catch (error) {
                return false;
              }
            }
          }
        }
      };
    } else {
      return {
        addressBanForm: {
          type: {
            required
          },
          address: {
            required,
            async isUniqueAddress(value) {
              // standalone validator ideally should not assume a field is required
              if (value === "") return true;
              try {
                let result = await axios.post(
                  `/api/address-ban/check-unique-address`,
                  {
                    type: this.addressBanForm.type,
                    address: value,
                    address_ban_id: this.id
                  }
                );
                return true;
              } catch (error) {
                return false;
              }
            }
          }
        }
      };
    }
  },

  watch: {
    addressBanDetail(newVal) {
      if (newVal) {
        this.addressBanForm.type =
          newVal.hostname != null ? "hostname" : "ip-address";
        this.addressBanForm.address =
          newVal.hostname != null ? newVal.hostname : newVal.ip_address;
      }
    }
  },

  methods: {
    loadAddressBanDetail() {
      this.$store.dispatch("addressBans/addressBanDetail", { id: this.id });
    },
    async handleSubmit() {
      let self = this;
      this.$v.$touch();
      var isValid = !this.$v.$invalid;
      if (isValid) {
        if (this.id == undefined) {
          try {
            let data = { ...self.addressBanForm };
            let response = await this.$store.dispatch(
              "addressBans/addressBanCreate",
              {
                data: data
              }
            );
            self.$snotify.success("Create IP/Host Ban Success.", "Success!");
            self.$router.push({ name: "ip-bans" });
          } catch (e) {
            self.$snotify.error("Create IP/Host Ban Failed.", "Error!");
          }
        } else {
          try {
            let data = { ...self.addressBanForm };
            let response = await this.$store.dispatch(
              "addressBans/addressBanUpdate",
              {
                id: this.id,
                data: data
              }
            );
            self.$snotify.success("Update IP/Host Ban Success.", "Success!");
            self.$router.push({ name: "ip-bans" });
          } catch (e) {
            self.$snotify.error("Update IP/Host Ban Failed.", "Error!");
          }
        }
      }
    }
  }
};
</script>