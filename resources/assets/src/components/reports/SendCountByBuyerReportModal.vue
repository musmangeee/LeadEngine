<template>
  <div>
    <b-modal
      :no-close-on-backdrop="true"
      id="send-count-by-provider-report-modal"
      size="lg"
      no-enforce-focus
      :title="formTitle"
      v-model="showModal"
      @shown="onShow"
      @hidden="onHidden"
      @ok="handleOk"
      ok-title="Send Report"
      ref="modal"
    >
      <b-form @submit.stop.prevent="handleSubmit">
        <b-form-row>


            <b-form-group label="Email" class="col">
              <b-input type="text" v-model="recipient.email" class=""></b-input>
              <div
                v-if="!$v.recipient.email.required && $v.recipient.email.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >Email field is required.</div>

              <div
                v-if="!$v.recipient.email.email && $v.recipient.email.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
              >A valid email is required.</div>

            </b-form-group>
        </b-form-row>


        <template slot="modal-footer">
          <b-button :disabled="isLoading" :class="{ 'disabled': isLoading }" variant="default" @click="cancel()"
            >Cancel</b-button
          >
          <b-button
            :disabled="isLoading" :class="{ 'disabled': isLoading }" 
            variant="primary"
            @click="handleSubmit()"
            >Send Report</b-button
          >
        </template>
      </b-form>
    </b-modal>
  </div>
</template>
<script>

const initRecipient = {
    email: ''
}

import Vue from "vue";
import Vuelidate from "vuelidate";
import { required, email } from "vuelidate/lib/validators";
Vue.use(Vuelidate);

export default {
    name: 'SendCountByBuyerReportModal',
    props: ['startDate','endDate'],
    data: () => ({
        recipient: {...initRecipient},
        showModal: false,
        formTitle: "Send Count by Buyer Reports",
        isLoading: false,
    }),
    methods: {
        show(){
            this.showModal = true;
        },
        handleOk(bvModalEvt) {
            // Prevent modal from closing
            bvModalEvt.preventDefault();
            // Trigger submit handler
            this.handleSubmit();
        },
        async handleSubmit(){
            this.$v.$touch();
            var isValid = !this.$v.$invalid;
            if(isValid){
                const postedData = {
                    email: this.recipient.email,
                    start_date: this.startDate,
                    end_date: this.endDate
                };
                this.isLoading = true;
                try {
                    await window.axios.post(`/api/buyers/daily-stats-send-email`, postedData);
                    this.$snotify.success("Send Count by Buyer Report Success.", "Success!");
                    this.showModal = false;
                    this.isLoading = false;
                } catch (error) {
                    this.isLoading = false;
                    this.showModal = false;
                    this.$snotify.error("Unexpected error happened. Please try again later.", "Error!");
                    console.log(error);
                }

            }
        },
        async onShow(){

        },
        async onHidden(){
            this.$v.$reset();
            this.recipient = {...initRecipient}
        }
    },
    validations: {
        recipient: {
            email: {
                required,
                email
            }
        },
    },
}
</script>

<style>

</style>