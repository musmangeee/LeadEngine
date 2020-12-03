<template>
  <div>
    <h4 class="font-weight-bold py-3">Plat Channel</h4>

    <b-card>
      <div>
        <b-tabs content-class="mt-3">
          <b-tab title="Channel" active>
            <b-form-group label="Status" class="col-lg-12">
              <label class="switcher switcher-primary">
                <input type="checkbox" class="switcher-input" v-model="buyerPlatChannelForm.status" />
                <span class="switcher-indicator">
                  <span class="switcher-yes text-primary"></span>
                  <span class="switcher-no"></span>
                </span>
                <span class="switcher-label" v-if="buyerPlatChannelForm.status">Active</span>
                <span class="switcher-label" v-if="!buyerPlatChannelForm.status">Inactive</span>
              </label>
            </b-form-group>

            <b-form-group label="Channel Name" class="col-6">
              <b-input type="text" v-model="buyerPlatChannelForm.channel_name"></b-input>
              <div
                v-if="!$v.buyerPlatChannelForm.channel_name.required && $v.buyerPlatChannelForm.channel_name.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Channel name field is required.</div>
            </b-form-group>

            <b-form-group label="Buyer Name" class="col-6">
              <b-select
                v-model="buyerPlatChannelForm.buyer_id"
                :options="buyerList"
                value-field="id"
                text-field="name"
              ></b-select>
              <div
                v-if="!$v.buyerPlatChannelForm.buyer_id.required && $v.buyerPlatChannelForm.buyer_id.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Buyer name field is required.</div>
            </b-form-group>

            <b-form-group label="Plat Channel ID" class="col-6">
              <b-input type="text" v-model="buyerPlatChannelForm.plat_channel_id"></b-input>
              <div
                v-if="!$v.buyerPlatChannelForm.plat_channel_id.required && $v.buyerPlatChannelForm.plat_channel_id.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Plat Channel ID field is required.</div>
            </b-form-group>

            <b-form-group label="Plat Channel Password" class="col-6">
              <b-input type="text" v-model="buyerPlatChannelForm.plat_channel_password"></b-input>
              <div
                v-if="!$v.buyerPlatChannelForm.plat_channel_password.required && $v.buyerPlatChannelForm.plat_channel_password.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Plat Channel Password field is required.</div>
            </b-form-group>

            <b-form-group label="Price" class="col-6">
              <b-input type="text" v-model="buyerPlatChannelForm.price"></b-input>
              <div
                v-if="!$v.buyerPlatChannelForm.price.required && $v.buyerPlatChannelForm.price.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Price field is required.</div>
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
              <b-button @click="clearAllCaps" variant="danger"> Clear All Caps </b-button> 
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
      <b-btn :disabled="isLoading" @click="saveChannel" class="btn btn-primary">Save</b-btn>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import Vuelidate from "vuelidate";
import { required } from "vuelidate/lib/validators";
Vue.use(Vuelidate);

import DeliveryScheduleModal from "@/components/buyerPlatChannels/DeliveryScheduleModal";

import moment from "moment";
import { mapState } from "vuex";

export default {
  name: "PlatChannelForm",
  props: ["id", "redirectBuyerId"],
  components: {
    DeliveryScheduleModal
  },
  created() {
    if (this.id) {
      this.loadBuyerPlatChannelDetail(this.id);
    }
    this.loadActiveBuyerList();
  },
  data: () => ({
    isLoading: false,
    buyerPlatChannelForm: {
      buyer_id: null,
      channel_name: "",
      plat_channel_id: "",
      plat_channel_password: "",
      price: 0,
      status: true
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
    ]
  }),
  computed: {
    ...mapState("buyers", ["buyerList"]),
    ...mapState("buyerPlatChannels", ["buyerPlatChannelDetail"])
  },
  methods: {
    loadBuyerPlatChannelDetail(id) {
      this.$store
        .dispatch("buyerPlatChannels/buyerPlatChannelDetail", { id: id })
        .then(() => {
          this.buyerPlatChannelForm = { ...this.buyerPlatChannelDetail };
          this.buyerPlatChannelForm.status = this.buyerPlatChannelForm.status == "active" ? true : false;          
          this.deliverySchedules = this.buyerPlatChannelForm.delivery_schedules;
        });
    },
    async loadActiveBuyerList() {
      this.$store.dispatch("buyers/buyerList");
      if (this.redirectBuyerId) {
        this.buyerPlatChannelForm.buyer_id = this.redirectBuyerId;
      }
    },
    async saveChannel() {
      this.isLoading = true;
      this.$v.$touch();
      var isValid = !this.$v.$invalid;
      if (isValid) {
        if (this.id != undefined) {
          //update the provider
          try {
            let data = { ...this.buyerPlatChannelForm };
            data.deliverySchedules = [...this.deliverySchedules];
            data.status = data.status == true ? "active" : "inactive";
            let response = await this.$store.dispatch(
              "buyerPlatChannels/buyerPlatChannelUpdate",
              {
                id: this.id,
                data: data
              }
            );
            this.$snotify.success("Update Plat Channel Success.", "Success!");

            if (this.redirectBuyerId) {
              this.$router.push({
                name: "buyer-edit",
                params: { id: this.redirectBuyerId }
              });
            } else {
              this.$router.push({ name: "buyer-plat-channel-list" });
            }
          } catch (e) {
            console.log(e);
            this.isLoading = false;
            this.$snotify.error("Update Plat Channel Failed.", "Error!");
          }
        } else {
          //create new provider
          try {
            let data = { ...this.buyerPlatChannelForm };
            data.status = data.status == true ? "active" : "inactive";
            data.deliverySchedules = [...this.deliverySchedules];
            let response = await this.$store.dispatch(
              "buyerPlatChannels/buyerPlatChannelCreate",
              {
                data: data
              }
            );
            this.$snotify.success("Create Plat Channel Success.", "Success!");

            if (this.redirectBuyerId) {
              this.$router.push({
                name: "buyer-edit",
                params: { id: this.redirectBuyerId }
              });
            } else {
              this.$router.push({ name: "buyer-plat-channel-list" });
            }
          } catch (e) {
            console.log(e);
            this.$snotify.error("Create Plat Channel Failed.", "Error!");
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
            hourly_cap: 10000000,
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
    buyerPlatChannelForm: {
      buyer_id: {
        required
      },
      channel_name: {
        required
      },
      plat_channel_id: {
        required
      },
      plat_channel_password: {
        required
      },
      price: {
        required
      },
      status: {
        required
      }
    }
  }
};
</script>