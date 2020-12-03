<template>
  <div>
    <h4 class="font-weight-bold py-3">Lead Detail</h4>

    <b-breadcrumb :items="breadcrumbItems" />

    <div class="row mt-3">
      <div class="col">
        <div class="card mb-4 h-100">
          <h6 class="card-header with-elements">
            <div class="card-header-title">Application Status</div>
          </h6>
          <div class="card-body">
            <b-row>
              <b-col>
                <b-form-row>
                  <b-form-group label="Status" class="text-muted">
                    <label>
                      <div v-html="parseStatusLabel(leadDetail.status.status)"></div>
                    </label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Price Received" class="text-muted">
                    <label class="text-dark">{{ leadDetail.status.price_received }}</label>
                  </b-form-group>
                </b-form-row>
              </b-col>
              <b-col></b-col>
            </b-row>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col">
        <div class="card mb-4 h-100">
          <h6 class="card-header with-elements">
            <div class="card-header-title">Personal Info</div>
          </h6>
          <div class="card-body">
            <b-row>
              <b-col>
                <b-form-row>
                  <b-form-group label="Email" class="text-muted">
                    <label class="text-dark">{{ leadDetail.email }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Last Name" class="text-muted">
                    <label class="text-dark">{{ leadDetail.first_name }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="City" class="text-muted">
                    <label class="text-dark">{{ leadDetail.city }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="ZIP" class="text-muted">
                    <label class="text-dark">{{ leadDetail.zip }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="SSN" class="text-muted">
                    <label class="text-dark">{{ leadDetail.ssn }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Address in Years" class="text-muted">
                    <label class="text-dark">{{ leadDetail.address_years }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Driver License State" class="text-muted">
                    <label class="text-dark">{{ parseState(leadDetail.dl_state) }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Is Military" class="text-muted">
                    <label class="text-dark">{{ leadDetail.is_military }}</label>
                  </b-form-group>
                </b-form-row>
              </b-col>
              <b-col>
                <b-form-row>
                  <b-form-group label="First Name" class="text-muted">
                    <label class="text-dark">{{ leadDetail.first_name }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Address" class="text-muted">
                    <label class="text-dark">{{ leadDetail.address }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="State" class="text-muted">
                    <label class="text-dark">  {{ parseState(leadDetail.state) }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Phone" class="text-muted">
                    <label class="text-dark">{{ leadDetail.phone }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Address in Months" class="text-muted">
                    <label class="text-dark">{{ leadDetail.address_months }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Driver License Number" class="text-muted">
                    <label class="text-dark">{{ leadDetail.dl_number }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Rent or Own" class="text-muted">
                    <label class="text-dark">{{ leadDetail.rent_or_own }}</label>
                  </b-form-group>
                </b-form-row>
              </b-col>
            </b-row>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col">
        <div class="card mb-4 h-100">
          <h6 class="card-header with-elements">
            <div class="card-header-title">Employment Info</div>
          </h6>
          <div class="card-body">
            <b-row>
              <b-col>
                <b-form-row>
                  <b-form-group label="Job Title" class="text-muted">
                    <label class="text-dark">{{ leadDetail.job_title }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Employer Phone" class="text-muted">
                    <label class="text-dark">{{ leadDetail.emp_phone }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Income Type" class="text-muted">
                    <label class="text-dark">{{ leadDetail.income_type }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Next Pay Date" class="text-muted">
                    <label class="text-dark">{{ leadDetail.next_pay_date }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Years of Employment" class="text-muted">
                    <label class="text-dark">{{ leadDetail.emp_years }}</label>
                  </b-form-group>
                </b-form-row>
              </b-col>
              <b-col>
                <b-form-row>
                  <b-form-group label="Employer Name" class="text-muted">
                    <label class="text-dark">{{ leadDetail.emp_name }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Pay Frequency" class="text-muted">
                    <label class="text-dark">{{ leadDetail.pay_frequency }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Net Monthly Income" class="text-muted">
                    <label class="text-dark">{{ leadDetail.net_monthly_income }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Second Pay Date" class="text-muted">
                    <label class="text-dark">{{ leadDetail.next_pay_date }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Months of Employment" class="text-muted">
                    <label class="text-dark">{{ leadDetail.emp_months }}</label>
                  </b-form-group>
                </b-form-row>
              </b-col>
            </b-row>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col">
        <div class="card mb-4 h-100">
          <h6 class="card-header with-elements">
            <div class="card-header-title">Bank Info</div>
          </h6>
          <div class="card-body">
            <b-row>
              <b-col>
                <b-form-row>
                  <b-form-group label="Bank Name" class="text-muted">
                    <label class="text-dark">{{ leadDetail.bank_name }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Account Number" class="text-muted">
                    <label class="text-dark">{{ leadDetail.account_number }}</label>
                  </b-form-group>
                </b-form-row>
              </b-col>
              <b-col>
                <b-form-row>
                  <b-form-group label="Pay Type" class="text-muted">
                    <label class="text-dark">{{ parsePayType(leadDetail.pay_type) }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Routing Number" class="text-muted">
                    <label class="text-dark">{{ leadDetail.routing_number }}</label>
                  </b-form-group>
                </b-form-row>
              </b-col>
            </b-row>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "LeadView",
  props: ["id"],
  created() {
    this.$store.dispatch("leads/leadDetail", { id: this.id });
    this.getStates();
  },
  data: () => ({
    breadcrumbItems: [
      {
        text: "Leads",
        to: { name: "lead-list" }
      },
      {
        text: "View",
        active: true
      }
    ],
    stateOptions: []
  }),
  computed: {
    ...mapGetters("leads", ["leadDetail"])
  },
  methods: {
    parseState(stateCode){
      let result = this.stateOptions.find(state => {
        return state.value == stateCode
      })
      return result.text
    },
    parsePayType(status) {
      if (status == "transfer") {
        return "direct-deposit";
      } else {
        return status;
      }
    },
    parseStatusLabel(status) {
      if (status == "sold") {
        return '<span class="badge badge-success"> Sold </span>';
      } else if (status == "reject") {
        return '<span class="badge badge-default"> Reject </span>';
      } else if (status == "error") {
        return '<span class="badge badge-danger"> Error </span>';
      }
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