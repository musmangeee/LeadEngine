<template>
  <div>
    <h4 class="font-weight-bold py-3">Buyer</h4>

    <b-card>
      <div>
        <b-tabs content-class="mt-3">
          <b-tab title="Buyer" active>
            <b-form-group label="Status" class="col-lg-12">
              <label class="switcher switcher-primary">
                <input
                  type="checkbox"
                  class="switcher-input"
                  v-model="buyerForm.status"
                />
                <span class="switcher-indicator">
                  <span class="switcher-yes text-primary"></span>
                  <span class="switcher-no"></span>
                </span>
                <span class="switcher-label" v-if="buyerForm.status"
                  >Active</span
                >
                <span class="switcher-label" v-if="!buyerForm.status"
                  >Inactive</span
                >
              </label>
            </b-form-group>

            <!--
            <b-form-group label="Send To Vertical" class="col-lg-12">
              <label class="switcher switcher-primary">
                <input type="checkbox" class="switcher-input" v-model="buyerForm.send_vertical" />
                <span class="switcher-indicator">
                  <span class="switcher-yes text-primary"></span>
                  <span class="switcher-no"></span>
                </span>
                <span class="switcher-label" v-if="buyerForm.send_vertical">Yes</span>
                <span class="switcher-label" v-if="!buyerForm.send_vertical">No</span>
              </label>
            </b-form-group>

            <b-form-group label="Send To Plat" class="col-lg-12">
              <label class="switcher switcher-primary">
                <input type="checkbox" class="switcher-input" v-model="buyerForm.send_plat" />
                <span class="switcher-indicator">
                  <span class="switcher-yes text-primary"></span>
                  <span class="switcher-no"></span>
                </span>
                <span class="switcher-label" v-if="buyerForm.send_plat">Yes</span>
                <span class="switcher-label" v-if="!buyerForm.send_plat">No</span>
              </label>
            </b-form-group>


              <b-form-group label="Send To Panda" class="col-lg-12">
              <label class="switcher switcher-primary">
                <input type="checkbox" class="switcher-input" v-model="buyerForm.send_panda" />
                <span class="switcher-indicator">
                  <span class="switcher-yes text-primary"></span>
                  <span class="switcher-no"></span>
                </span>
                <span class="switcher-label" v-if="buyerForm.send_panda">Yes</span>
                <span class="switcher-label" v-if="!buyerForm.send_panda">No</span>
              </label>
            </b-form-group>
-->
            <b-form-group label="Send To Plat If Declined?" class="col-lg-12">
              <label class="switcher switcher-primary">
                <input
                  type="checkbox"
                  class="switcher-input"
                  v-model="buyerForm.send_plat_declined"
                />
                <span class="switcher-indicator">
                  <span class="switcher-yes text-primary"></span>
                  <span class="switcher-no"></span>
                </span>
                <span class="switcher-label" v-if="buyerForm.send_plat_declined"
                  >Yes</span
                >
                <span
                  class="switcher-label"
                  v-if="!buyerForm.send_plat_declined"
                  >No</span
                >
              </label>
            </b-form-group>

            <b-form-group label="Buyer Name" class="col-6">
              <b-input type="text" v-model="buyerForm.name"></b-input>
              <div
                v-if="!$v.buyerForm.name.required && $v.buyerForm.name.$dirty"
                class="invalid-feedback"
                style="display: inline-block"
              >
                Name field is required.
              </div>
            </b-form-group>

            <b-form-group label="Buyer Description" class="col-6">
              <b-form-textarea
                id="textarea"
                v-model="buyerForm.description"
                placeholder="Description"
                rows="3"
                max-rows="6"
              ></b-form-textarea>
            </b-form-group>

            <!--

            <b-form-group label="Plat Channel ID" class="col-6">
              <b-input type="text" v-model="buyerForm.plat_channel_id"></b-input>
              <div
                v-if="!$v.providerForm.plat_channel_id.required && $v.providerForm.plat_channel_id.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Plat Channel ID field is required.</div>
            </b-form-group>

            <b-form-group label="Plat Channel Password" class="col-6">
              <b-input type="text" v-model="providerForm.plat_channel_password"></b-input>
              <div
                v-if="!$v.providerForm.plat_channel_password.required && $v.providerForm.plat_channel_password.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Plat Channel Password field is required.</div>
            </b-form-group>-->
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
                  <td colspan="5" class="text-center">
                    No Delivery Schedule/Caps Data Available.
                  </td>
                </tr>
              </tbody>
              <tbody v-if="deliverySchedules.length > 0">
                <tr
                  v-bind:key="schedule.id"
                  v-for="schedule in deliverySchedules"
                >
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
      <b-btn :disabled="isLoading" @click="saveBuyer" class="btn btn-primary"
        >Save</b-btn
      >
    </div>

    <div class="row mt-3" v-if="id && buyerForm.send_vertical">
      <div class="col">
        <div class="card mb-4 h-100">
          <h6 class="card-header with-elements">
            <div class="card-header-title">Vertical Channels</div>
            <div class="card-header-elements ml-md-auto">
              <button
                @click="addVerticalChannel"
                type="button"
                class="btn btn-xs btn-primary"
              >
                <span class="ion ion-md-add"></span> Add Vertical Channel
              </button>
            </div>
          </h6>

          <div class="card-body">
            <channel-buyer-list :buyerId="id" />
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-3" v-if="id && buyerForm.send_plat">
      <div class="col">
        <div class="card mb-4 h-100">
          <h6 class="card-header with-elements">
            <div class="card-header-title">Plat Channels</div>
            <div class="card-header-elements ml-md-auto">
              <button
                @click="addPlatChannel"
                type="button"
                class="btn btn-xs btn-primary"
              >
                <span class="ion ion-md-add"></span> Add Plat Channel
              </button>
            </div>
          </h6>

          <div class="card-body">
            <plat-channel-buyer-list :buyerId="id" />
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-3" v-if="id && buyerForm.send_panda">
      <div class="col">
        <div class="card mb-4 h-100">
          <h6 class="card-header with-elements">
            <div class="card-header-title">Panda Channel/Tier/Portfolio</div>
            <div class="card-header-elements ml-md-auto">
              <button
                @click="addPandaChannel"
                type="button"
                class="btn btn-xs btn-primary"
              >
                <span class="ion ion-md-add"></span> Add Panda Channel
              </button>
            </div>
          </h6>

          <div class="card-body">
            <panda-channel-buyer-list :buyerId="id" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import Vuelidate from "vuelidate";
import { required, requiredIf } from "vuelidate/lib/validators";
Vue.use(Vuelidate);

import DeliveryScheduleModal from "@/components/buyers/DeliveryScheduleModal";
import ChannelBuyerList from "@/components/buyers/ChannelBuyerList";
import PandaChannelBuyerList from "@/components/buyers/PandaChannelBuyerList";
import PlatChannelBuyerList from "@/components/buyers/PlatChannelBuyerList";

import moment from "moment";
import { mapState } from "vuex";

export default {
  name: "BuyerForm",
  props: ["id"],
  components: {
    DeliveryScheduleModal,
    ChannelBuyerList,
    PandaChannelBuyerList,
    PlatChannelBuyerList,
  },
  created() {
    if (this.id) {
      this.loadBuyerDetail(this.id);
    }
  },
  data: () => ({
    isLoading: false,
    buyerForm: {
      status: true,
      name: "",
      description: "",
      send_vertical: true,
      send_plat: true,
      send_panda: true,
      send_panda_declined: false,
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
      { value: "Saturday", text: "Saturday" },
    ],
  }),
  computed: {
    ...mapState("buyers", ["buyerDetail"]),
  },
  methods: {
    addPlatChannel() {
      this.$router.push({
        name: "buyer-plat-channel-create",
        params: { redirectBuyerId: this.id },
      });
    },

    addPandaChannel() {
      this.$router.push({
        name: "buyer-panda-channel-create",
        params: { redirectBuyerId: this.id },
      });
    },

    addVerticalChannel() {
      this.$router.push({
        name: "buyer-vertical-channel-create",
        params: { redirectBuyerId: this.id },
      });
    },
    loadBuyerDetail(id) {
      this.$store.dispatch("buyers/buyerDetail", { id: id }).then(() => {
        this.buyerForm = { ...this.buyerDetail };
        this.buyerForm.status =
          this.buyerForm.status == "active" ? true : false;

        this.deliverySchedules = this.buyerForm.delivery_schedules;
        delete this.buyerForm.delivery_schedules;
      });
    },
    async saveBuyer() {
      this.isLoading = true;
      this.$v.$touch();
      var isValid = !this.$v.$invalid;
      if (isValid) {
        if (this.id != undefined) {
          //update the provider
          try {
            let data = { ...this.buyerForm };
            data.status = data.status == true ? "active" : "inactive";
            data.deliverySchedules = [...this.deliverySchedules];
            let response = await this.$store.dispatch("buyers/buyerUpdate", {
              id: this.id,
              data: data,
            });
            this.$snotify.success("Update Buyer Success.", "Success!");
            this.$router.push({ name: "buyer-list" });
          } catch (e) {
            console.log(e);
            this.isLoading = false;
            this.$snotify.error("Update Buyer Failed.", "Error!");
          }
        } else {
          //create new provider
          try {
            let data = { ...this.buyerForm };
            data.status = data.status == true ? "active" : "inactive";
            data.deliverySchedules = [...this.deliverySchedules];
            let response = await this.$store.dispatch("buyers/buyerCreate", {
              data: data,
            });
            this.$snotify.success("Create Buyer Success.", "Success!");
            this.$router.push({ name: "buyer-list" });
          } catch (e) {
            console.log(e);
            this.$snotify.error("Create Buyer Failed.", "Error!");
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
          (schedule) => schedule.day_of_week == day.text
        );
        if (dayExists == undefined) {
          this.deliverySchedules.push({
            id: "random-" + Math.random(),
            day_of_week: day.text,
            daily_cap: 100000000,
            hourly_cap: 100000000,
            start_at: moment().startOf("day").format("HH:mm:ss"),
            end_at: moment().endOf("day").format("HH:mm:ss"),
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
        confirmButtonText: "Delete",
      }).then((result) => {
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
      this.deliverySchedules = this.deliverySchedules.map((oldSchedule) =>
        oldSchedule.id === schedule.id ? schedule : oldSchedule
      );
    },
    scheduleCreated(schedule) {
      this.deliverySchedules.push(schedule);
    },
    clearAllCaps() {
      this.deliverySchedules = this.deliverySchedules.map((schedule) => {
        return {
          id: schedule.id,
          day_of_week: schedule.day_of_week,
          start_at: schedule.start_at,
          end_at: schedule.end_at,
          daily_cap: 0,
          hourly_cap: 0,
        };
      });
    },
  },
  validations: {
    buyerForm: {
      name: {
        required,
      },
    },
  },
};
</script>
