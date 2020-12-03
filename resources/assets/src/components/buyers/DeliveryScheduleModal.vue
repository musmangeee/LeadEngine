<template>
  <b-modal
    :no-close-on-backdrop="true"
    id="delivery-schedule-form"
    size="lg"
    no-enforce-focus
    title="Delivery Schedule Form"
    v-model="showModal"
    @show="onShow()"
    ref="modal"
  >
    <b-form-group label="Day of Week" class="col">
      <b-form-select :options="daysOptions" v-model="scheduleForm.day_of_week" name="type"></b-form-select>
      <div
        v-if="!$v.scheduleForm.day_of_week.required && $v.scheduleForm.day_of_week.$dirty"
        class="invalid-feedback"
        style="display: inline-block;"
      >Day of week field is required.</div>
    </b-form-group>

    <div class="form-group col">
      <label>Start Time</label>
      <flat-pickr
        v-model="scheduleForm.start_at"
        :config="timePickerConfig"
        class="form-control"
        placeholder="Select Start Time"
        name="date"
      ></flat-pickr>
      <div
        v-if="!$v.scheduleForm.start_at.required && $v.scheduleForm.start_at.$dirty"
        class="invalid-feedback"
        style="display: inline-block;"
      >Start time field is required.</div>
    </div>

    <div class="form-group col">
      <label>End Time</label>
      <flat-pickr
        v-model="scheduleForm.end_at"
        :config="timePickerConfig"
        class="form-control"
        placeholder="Select End Time"
        name="date"
      ></flat-pickr>
      <div
        v-if="!$v.scheduleForm.end_at.required && $v.scheduleForm.end_at.$dirty"
        class="invalid-feedback"
        style="display: inline-block;"
      >End time field is required.</div>
    </div>

    <b-form-group label="Daily Cap" class="col">
      <b-input type="text" v-model="scheduleForm.daily_cap"></b-input>
      <div
        v-if="!$v.scheduleForm.daily_cap.required && $v.scheduleForm.daily_cap.$dirty"
        class="invalid-feedback"
        style="display: inline-block;"
      >Daily cap field is required.</div>
    </b-form-group>

    <b-form-group label="Hourly Cap" class="col">
      <b-input type="text" v-model="scheduleForm.hourly_cap"></b-input>
      <div
        v-if="!$v.scheduleForm.hourly_cap.required && $v.scheduleForm.hourly_cap.$dirty"
        class="invalid-feedback"
        style="display: inline-block;"
      >Hourly cap field is required.</div>
    </b-form-group>

    <template slot="modal-footer">
      <b-button variant="default" @click="cancel()">Cancel</b-button>
      <b-button variant="primary" @click="save()">Save</b-button>
    </template>
  </b-modal>
</template>

<script>
import Vue from "vue";
import Vuelidate from "vuelidate";
import { required } from "vuelidate/lib/validators";
Vue.use(Vuelidate);

export default {
  name: "DeliveryScheduleModal",
  props: ["schedule"],
  created() {},
  data: () => ({
    disabled: false,
    scheduleForm: {},
    showModal: false,
    daysOptions: [
      { value: "", text: "Select Day of Week" },
      { value: "Sunday", text: "Sunday" },
      { value: "Monday", text: "Monday" },
      { value: "Tuesday", text: "Tuesday" },
      { value: "Wednesday", text: "Wednesday" },
      { value: "Thursday", text: "Thursday" },
      { value: "Friday", text: "Friday" },
      { value: "Saturday", text: "Saturday" }
    ],
    timePickerConfig: {
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i:ss",
      minuteIncrement: 15
    }
  }),
  methods: {
    save() {
      this.$v.$touch();
      var isValid = !this.$v.$invalid;
      if (isValid) {
        if (this.schedule) {
          let data = { ...this.scheduleForm };
          this.$emit("updated", data);
          this.$v.$reset();
          this.scheduleForm = {};
          this.showModal = false;
        } else {
          let data = { ...this.scheduleForm };
          this.$emit("created", data);
          this.scheduleForm = {};
          this.showModal = false;
        }
      }
    },
    cancel() {
      this.$v.$reset();
      this.scheduleForm = {};
      this.showModal = false;
    },
    show() {
      this.showModal = true;
    },
    onShow() {
      console.log("schedule ");
      console.log(this.schedule);

      if (this.schedule != null) {
        this.scheduleForm = { ...this.schedule };
      } else {
        this.scheduleForm = {
          day_of_week: "",
          start_at: "",
          end_at: "",
          daily_cap: "",
          hourly_cap: ""
        };
      }
    }
  },
  validations: {
    scheduleForm: {
      day_of_week: {
        required
      },
      start_at: {
        required
      },
      end_at: {
        required
      },
      daily_cap: {
        required
      },
      hourly_cap: {
        required
      }
    }
  }
};
</script>
