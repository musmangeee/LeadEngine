<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">Lead Edit</h4>
    <b-breadcrumb :items="breadcrumbItems" />

    <b-card sub-title="Personal Info">
      <b-form @submit.prevent="handleSubmit">
        <b-row>
          <b-col>
            <b-form-group label="Email" class="col">
              <b-input type="text" v-model="leadForm.email"></b-input>
              <div
                v-if="!$v.leadForm.email.required && $v.leadForm.email.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Email field is required.</div>
            </b-form-group>

            <b-form-group label="Last Name" class="col">
              <b-input type="text" v-model="leadForm.last_name"></b-input>
              <div
                v-if="!$v.leadForm.last_name.required && $v.leadForm.last_name.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Last name field is required.</div>
            </b-form-group>

            <b-form-group label="City" class="col">
              <b-input type="text" v-model="leadForm.city"></b-input>
              <div
                v-if="!$v.leadForm.city.required && $v.leadForm.city.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >City field is required.</div>
            </b-form-group>

            <b-form-group label="ZIP" class="col">
              <b-input type="text" v-model="leadForm.zip"></b-input>
              <div
                v-if="!$v.leadForm.zip.required && $v.leadForm.zip.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >ZIP field is required.</div>
            </b-form-group>

            <b-form-group label="SSN" class="col">
              <b-input type="text" v-model="leadForm.ssn"></b-input>
              <div
                v-if="!$v.leadForm.ssn.required && $v.leadForm.ssn.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >SSN field is required.</div>
            </b-form-group>

            <b-form-group label="Address in Years" class="col">
              <b-input type="text" v-model="leadForm.address_years"></b-input>
              <div
                v-if="!$v.leadForm.address_years.required && $v.leadForm.address_years.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Address in years field is required.</div>
            </b-form-group>

            <b-form-group label="Driver License State" class="col">
              <b-form-select :options="stateOptions" v-model="leadForm.dl_state" name="pay_type"></b-form-select>
              <div
                v-if="!$v.leadForm.dl_state.required && $v.leadForm.dl_state.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Driver license state field is required.</div>
            </b-form-group>

            <b-form-group label="Is Military" class="col">
              <b-form-select
                :options="isMilitaryOptions"
                v-model="leadForm.is_military"
                name="is_military"
              />
            </b-form-group>
          </b-col>
          <b-col>
            <b-form-group label="First Name" class="col">
              <b-input type="text" v-model="leadForm.first_name"></b-input>
              <div
                v-if="!$v.leadForm.first_name.required && $v.leadForm.first_name.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >First name field is required.</div>
            </b-form-group>

            <b-form-group label="Address" class="col">
              <b-input type="text" v-model="leadForm.address"></b-input>
              <div
                v-if="!$v.leadForm.address.required && $v.leadForm.address.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Address field is required.</div>
            </b-form-group>

            <b-form-group label="State" class="col">
              <b-form-select :options="stateOptions" v-model="leadForm.state" name="pay_type"></b-form-select>
              <div
                v-if="!$v.leadForm.state.required && $v.leadForm.state.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >State field is required.</div>
            </b-form-group>

            <b-form-group label="Phone" class="col">
              <b-input type="text" v-model="leadForm.phone"></b-input>
              <div
                v-if="!$v.leadForm.phone.required && $v.leadForm.phone.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Phone field is required.</div>
            </b-form-group>

            <b-form-group label="Address in Months" class="col">
              <b-input type="text" v-model="leadForm.address_months"></b-input>
              <div
                v-if="!$v.leadForm.address_months.required && $v.leadForm.address_months.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Address in months field is required.</div>
            </b-form-group>

            <b-form-group label="Driver License Number" class="col">
              <b-input type="text" v-model="leadForm.dl_number"></b-input>
              <div
                v-if="!$v.leadForm.dl_number.required && $v.leadForm.dl_number.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Driver license number field is required.</div>
            </b-form-group>

            <b-form-group label="Rent or Own" class="col">
              <b-form-select
                :options="rentOrOwnOptions"
                v-model="leadForm.rent_or_own"
                name="pay_type"
              />
            </b-form-group>
          </b-col>
        </b-row>
      </b-form>
    </b-card>

    <b-card sub-title="Employment Info" class="mt-2">
      <b-form @submit.prevent="handleSubmit">
        <b-row>
          <b-col>
            <b-form-group label="Job Title" class="col">
              <b-input type="text" v-model="leadForm.job_title"></b-input>
              <div
                v-if="!$v.leadForm.job_title.required && $v.leadForm.job_title.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Job title field is required.</div>
            </b-form-group>

            <b-form-group label="Employer Phone" class="col">
              <b-input type="text" v-model="leadForm.emp_phone"></b-input>
              <div
                v-if="!$v.leadForm.emp_phone.required && $v.leadForm.emp_phone.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Employer phone field is required.</div>
            </b-form-group>

            <b-form-group label="Income Type" class="col">
              <b-form-select
                :options="incomeTypeOptions"
                v-model="leadForm.income_type"
                name="pay_type"
              />
            </b-form-group>

            <b-form-group label="Next Pay Date" class="col">
              <b-input type="text" v-model="leadForm.next_pay_date"></b-input>
              <div
                v-if="!$v.leadForm.next_pay_date.required && $v.leadForm.next_pay_date.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Next pay date field is required.</div>
            </b-form-group>

            <b-form-group label="Years of Employment" class="col">
              <b-input type="text" v-model="leadForm.emp_years"></b-input>
              <div
                v-if="!$v.leadForm.emp_years.required && $v.leadForm.emp_years.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Employment in years field is required.</div>
            </b-form-group>
          </b-col>
          <b-col>
            <b-form-group label="Employer Name" class="col">
              <b-input type="text" v-model="leadForm.emp_name"></b-input>
              <div
                v-if="!$v.leadForm.emp_name.required && $v.leadForm.emp_name.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Employer name field is required.</div>
            </b-form-group>

            <b-form-group label="Pay Frequency" class="col">
              <b-form-select
                :options="payFrequencyOptions"
                v-model="leadForm.pay_frequency"
                name="pay_type"
              />
            </b-form-group>

            <b-form-group label="Net Monthly Income" class="col">
              <b-input type="text" v-model="leadForm.net_monthly_income"></b-input>
              <div
                v-if="!$v.leadForm.net_monthly_income.required && $v.leadForm.net_monthly_income.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Net monthly income field is required.</div>
            </b-form-group>

            <b-form-group label="Second Pay Date" class="col">
              <b-input type="text" v-model="leadForm.next_pay_date"></b-input>
            </b-form-group>

            <b-form-group label="Months of Employment" class="col">
              <b-input type="text" v-model="leadForm.emp_months"></b-input>
              <div
                v-if="!$v.leadForm.emp_months.required && $v.leadForm.emp_months.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Employment in months field is required.</div>
            </b-form-group>
          </b-col>
        </b-row>
      </b-form>
    </b-card>

    <b-card sub-title="Bank Info" class="mt-2">
      <b-form @submit.prevent="handleSubmit">
        <b-row>
          <b-col>
            <b-form-group label="Bank Name" class="col">
              <b-input type="text" v-model="leadForm.bank_name"></b-input>
              <div
                v-if="!$v.leadForm.bank_name.required && $v.leadForm.bank_name.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Bank name field is required.</div>
            </b-form-group>

             <b-form-group label="Account Number" class="col">
              <b-input type="text" v-model="leadForm.account_number"></b-input>
              <div
                v-if="!$v.leadForm.account_number.required && $v.leadForm.account_number.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Account number field is required.</div>
            </b-form-group>

          </b-col>
          <b-col>
            
            <b-form-group label="Payment Type" class="col">
              <b-form-select
                :options="paymentTypeOptions"
                v-model="leadForm.pay_type"
                name="pay_type"
              />
              <div
                v-if="!$v.leadForm.pay_type.required && $v.leadForm.pay_type.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Payment type field is required.</div>
            </b-form-group>
           
            <b-form-group label="Routing Number" class="col">
              <b-input type="text" v-model="leadForm.routing_number"></b-input>
              <div
                v-if="!$v.leadForm.routing_number.required && $v.leadForm.routing_number.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Routing number field is required.</div>
            </b-form-group>
          </b-col>
        </b-row>
      </b-form>
    </b-card>

    <b-button @click="handleSubmit" class="mt-4 float-right" variant="primary">Update Lead</b-button>
  </div>
</template>

<script>
import Vue from "vue";
import Vuelidate from "vuelidate";
import { mapGetters } from "vuex";
import { required, email } from "vuelidate/lib/validators";
Vue.use(Vuelidate);

export default {
  name: "LeadEdit",
  props: ["id"],
  created() {
    this.$store.dispatch("leads/leadDetail", { id: this.id });
    this.getStates();
  },
  watch: {
    leadDetail(newVal) {
      if (newVal) {
        this.leadForm = newVal;
      }
    }
  },
  computed: {
    ...mapGetters("leads", ["leadDetail"])
  },
  data: () => ({
    leadForm: {},
    breadcrumbItems: [
      {
        text: "Leads",
        to: { name: "lead-list" }
      },
      {
        text: "Edit",
        active: true
      }
    ],
    rentOrOwnOptions: [
      { value: "rent", text: "Rent" },
      { value: "own", text: "Own" }
    ],
    incomeTypeOptions: [
      { value: "Benefits", text: "Benefits" },
      { value: "Social Security", text: "Social Security" },
      { value: "Income", text: "Income" },
      { value: "Job Income", text: "Job Income" },
      { value: "Disability", text: "Disability" },
      { value: "Military", text: "Military" },
      { value: "Self Employed", text: "Self Employment" },
      { value: "Pension", text: "Pension" }
    ],
    paymentTypeOptions: [
      { value: "cash", text: "Cash" },
      { value: "check", text: "Check" },
      { value: "transfer", text: "Transfer" }
    ],
    rentOrOwnOptions: [
      { value: "rent", text: "Rent" },
      { value: "own", text: "Own" }
    ],
    isMilitaryOptions: [
      { value: "Yes", text: "Yes" },
      { value: "No", text: "No" }
    ],
    payFrequencyOptions: [
      { value: "Weekly", text: "Weekly" },
      { value: "Monthly", text: "Monthly" },
      { value: "Twice A Month", text: "Twice A Month" },
      { value: "Every 2 Weeks", text: "Every 2 Weeks" }
    ],
    stateOptions: []
  }),
  validations: {
    leadForm: {
      first_name: {
        required
      },
      last_name: {
        required
      },
      email: {
        required,
        email
      },
      address: {
        required
      },
      city: {
        required
      },
      state: {
        required
      },
      zip: {
        required
      },
      phone: {
        required
      },
      ssn: {
        required
      },
      address_months: {
        required
      },
      address_years: {
        required
      },
      dl_state: {
        required
      },
      dl_number: {
        required
      },
      rent_or_own: {
        required
      },
      is_military: {
        required
      },
      job_title: {
        required
      },
      emp_name: {
        required
      },
      emp_phone: {
        required
      },
      pay_frequency: {
        required
      },
      income_type: {
        required
      },
      net_monthly_income: {
        required
      },
      next_pay_date: {
        required
      },
      emp_years: {
        required
      },
      emp_months: {
        required
      },
      pay_type: {
        required
      },
      bank_name: {
        required
      },
      account_number: {
        required
      },
      routing_number: {
        required
      }
    }
  },
  methods: {
    async handleSubmit() {
      let self = this;
      this.$v.$touch();
      var isValid = !this.$v.$invalid;
      if (isValid) {
        try {
          let data = { ...self.leadForm };
          let response = await this.$store.dispatch("leads/leadUpdate", {
            id: this.id,
            data: data
          });
          self.$snotify.success("Update Lead Success.", "Success!");
          self.$router.push({ name: "lead-list" });
        } catch (e) {
          self.$snotify.error("Update Lead Failed.", "Error!");
        }
      }
    },
    async getCountries() {
      let response = await axios.get(`/api/helpers/countries`);
      console.log(response.data);
    },
    async getStates() {
      let response = await axios.get(`/api/helpers/states/United States`);
      let result = response.data;
      for (let key in result) {
        this.stateOptions.push({
          value: key,
          text: result[key]
        });
      }
    }
  }
};
</script>

<style>
</style>