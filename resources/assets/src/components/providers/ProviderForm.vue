<template>
  <div>
    <h4 class="font-weight-bold py-3">Provider</h4>

    <b-card>
      <div>
        <b-tabs content-class="mt-3">
          <b-tab title="Provider" active>
            <b-form-group label="Status" class="col-lg-12">
              <label class="switcher switcher-primary">
                <input type="checkbox" class="switcher-input" v-model="providerForm.status" />
                <span class="switcher-indicator">
                  <span class="switcher-yes text-primary"></span>
                  <span class="switcher-no"></span>
                </span>
                <span class="switcher-label" v-if="providerForm.status">Active</span>
                <span class="switcher-label" v-if="!providerForm.status">Inactive</span>
              </label>
            </b-form-group>

            <b-form-group label="Provider Name" class="col-6">
              <b-input type="text" v-model="providerForm.name"></b-input>
              <div
                v-if="!$v.providerForm.name.required && $v.providerForm.name.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Name field is required.</div>
            </b-form-group>

            <b-form-group label="Provider Description" class="col-6">
              <b-form-textarea
                id="textarea"
                v-model="providerForm.description"
                placeholder="Description"
                rows="3"
                max-rows="6"
              ></b-form-textarea>
            </b-form-group>
          </b-tab>

          <b-tab title="Delivery Schedule/Caps">
            <delivery-schedule-modal
              @created="scheduleCreated"
              @updated="scheduleUpdated"
              :schedule="selectedDeliverySchedule"
              ref="deliveryScheduleForm"
            />

            <div class="text-right">
              <b-button @click="addSchedule" variant="primary">Add</b-button>
              <b-button @click="addAllTime" variant="primary">24/7</b-button>
              <b-button @click="clearAllCaps" variant="danger">Clear All Caps</b-button>
            </div>

            <table class="table mt-2">
              <thead>
                <tr>
                  <th>Day Of The Week</th>
                  <th>Start Time</th>
                  <th>End Time</th>
                  <th>Daily Cap</th>
                  <th>Hourly Cap</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody v-if="deliverySchedules.length == 0">
                <tr>
                  <td colspan="5" class="text-center">No Delivery Schedule/Caps Data Available.</td>
                </tr>
              </tbody>
              <tbody v-if="deliverySchedules.length > 0">
                <tr v-bind:key="schedule.id" v-for="schedule in deliverySchedules">
                  <td>{{ schedule.day_of_week }}</td>
                  <td>{{ schedule.start_at }}</td>
                  <td>{{ schedule.end_at }}</td>
                  <td>{{ schedule.daily_cap }}</td>
                  <td>{{ schedule.hourly_cap }}</td>
                  <td>
                    <b-btn
                      @click="editSchedule(schedule)"
                      variant="outline-dark btn-xs icon-btn md-btn-flat"
                      v-b-tooltip.hover
                      title="Edit"
                    >
                      <i class="fas fa-pencil-alt"></i>
                    </b-btn>
                    <b-btn
                      @click="deleteSchedule(schedule)"
                      variant="outline-danger btn-xs icon-btn md-btn-flat"
                      v-b-tooltip.hover
                      title="Delete"
                    >
                      <i class="fas fa-trash"></i>
                    </b-btn>
                  </td>
                </tr>
              </tbody>
            </table>
          </b-tab>
        </b-tabs>
      </div>
    </b-card>

    <div class="text-right mt-3">
      <b-btn :disabled="isLoading" @click="saveProvider" class="btn btn-primary">Save</b-btn>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import Vuelidate from "vuelidate";
import { required, requiredIf } from "vuelidate/lib/validators";
Vue.use(Vuelidate);

import moment from "moment";
import { mapState } from "vuex";

import DeliveryScheduleModal from "@/components/providers/DeliveryScheduleModal";

export default {
  name: "ProviderForm",
  props: ["id"],
  components: {
    DeliveryScheduleModal
  },
  created() {
    if (this.id) {
      this.loadProviderDetail(this.id);
    }
  },
  data: () => ({
    isLoading: false,
    providerForm: {
      status: true,
      name: "",
      description: ""
    },
    selectedDeliverySchedule: {},
    deliverySchedules: [],
    daysOptions: [
      { value: "Sunday", text: "Sunday" },
      { value: "Sunday", text: "Monday" },
      { value: "Tuesday", text: "Tuesday" },
      { value: "Wednesday", text: "Wednesday" },
      { value: "Thursday", text: "Thursday" },
      { value: "Friday", text: "Friday" },
      { value: "Saturday", text: "Saturday" }
    ],
  }),
  computed: {
    ...mapState("providers", ["providerDetail"])
  },
  methods: {
    loadProviderDetail(id) {
      this.$store.dispatch("providers/providerDetail", { id: id }).then(() => {
        this.providerForm = { ...this.providerDetail };
        this.providerForm.status =
          this.providerForm.status == "active" ? true : false;
        this.deliverySchedules = this.providerForm.delivery_schedules;
        delete this.providerForm.delivery_schedules;
      });
    },
    async saveProvider() {
      this.isLoading = true;
      this.$v.$touch();
      var isValid = !this.$v.$invalid;
      if (isValid) {
        if (this.id != undefined) {
          //update the provider
          try {
            let data = { ...this.providerForm };
            data.deliverySchedules = [...this.deliverySchedules];
            data.status = data.status == true ? "active" : "inactive";
            let response = await this.$store.dispatch(
              "providers/providerUpdate",
              {
                id: this.id,
                data: data
              }
            );
            this.$snotify.success("Update Provider Success.", "Success!");
            this.$router.push({ name: "provider-list" });
          } catch (e) {
            console.log(e);
            this.isLoading = false;
            this.$snotify.error("Update Provider Failed.", "Error!");
          }
        } else {
          //create new provider
          try {
            let data = { ...this.providerForm };
            data.deliverySchedules = [...this.deliverySchedules];
            data.status = data.status == true ? "active" : "inactive";
            let response = await this.$store.dispatch(
              "providers/providerCreate",
              {
                data: data
              }
            );
            this.$snotify.success("Create Provider Success.", "Success!");
            this.$router.push({ name: "provider-list" });
          } catch (e) {
            console.log(e);
            this.$snotify.error("Create Provider Failed.", "Error!");
            this.isLoading = false;
          }
        }
      } else {
        this.isLoading = false;
      }
    },
    addAllTime() {
      this.daysOptions.forEach((day, key) => {
        let dayExists = this.deliverySchedules.find(
          schedule => schedule.day_of_week == day.text
        );
        if (dayExists == undefined) {
          this.deliverySchedules.push({
            id: "random-" + Math.random(),
            day_of_week: day.text,
            daily_cap: 100000000,
            hourly_cap: 100000000,
            start_at: moment()
              .startOf("day")
              .format("HH:mm:ss"),
            end_at: moment()
              .endOf("day")
              .format("HH:mm:ss")
          });
        }
      });
    },
    addSchedule(schedule) {
      this.selectedDeliverySchedule = null;
      this.$refs.deliveryScheduleForm.show();
    },
    editSchedule(schedule) {
      this.selectedDeliverySchedule = schedule;
      this.$refs.deliveryScheduleForm.show();
    },
    deleteSchedule(schedule) {
      let self = this;
      this.$swal({
        title: `Are you sure you want to delete selected schedule?`,
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Delete"
      }).then(result => {
        if (result.value) {
          let deletedIndex = self.deliverySchedules.indexOf(schedule);
          console.log(deletedIndex);
          if (deletedIndex > -1) {
            self.deliverySchedules.splice(deletedIndex, 1);
          }
        }
      });
    },
    scheduleUpdated(schedule) {
      this.deliverySchedules = this.deliverySchedules.map(oldSchedule =>
        oldSchedule.id === schedule.id ? schedule : oldSchedule
      );
    },
    scheduleCreated(schedule) {
      this.deliverySchedules.push(schedule);
    },
    clearAllCaps(){
      this.deliverySchedules = this.deliverySchedules.map((schedule) => {
        return {
          id: schedule.id,
          day_of_week: schedule.day_of_week,
          start_at: schedule.start_at,
          end_at: schedule.end_at,
          daily_cap: 0,
          hourly_cap: 0
        }
      });
    }
  },
  validations: {
    providerForm: {
      name: {
        required
      }
    }
  }
};
</script>