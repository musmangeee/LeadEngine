<template>
  <div>
    <b-modal
      :no-close-on-backdrop="true"
      id="create-new-contact"
      size="lg"
      no-enforce-focus
      v-model="show_modal"
      title="Posting Details"
      @show="onShow()"
      ref="modal"
    >
      <b-card title="Post Data">
        <b-card-text>
           <code><pre>{{ postData }}</pre></code>
        </b-card-text>
      </b-card>

      <b-card title="Response Data" class="mt-3">
        <b-card-text>
           <code><pre>{{ responseData }}</pre></code>
        </b-card-text>
      </b-card>
      <template slot="modal-footer">
        <b-button variant="default" @click="cancel()">Close</b-button>
      </template>
    </b-modal>
  </div>
</template>

<script>
import Vue from "vue";
import vkbeautify from "vkbeautify";

export default {
  props: {
    postInfoId: {
      type: Number,
      default: null
    }
  },
  components: {
  },
  computed: {
  },
  watch: {

  },
  created() {

  },
  validations: {

  },
  data: () => ({
    postData: '',
    responseData: '',
    show_modal: false,
  }),
  methods: {
    show() {
      this.show_modal = true;
    },
    hide() {
      this.show_modal = false;
    },
    cancel() {
      this.hide();
    },
    async onShow() {

        window.axios.get('/api/leads/status/' + this.postInfoId).then(response => {
          this.postData = vkbeautify.xml(response.data.xml_post_record)
          this.responseData = vkbeautify.xml(response.data.xml_response_record)
        })

        /* if(this.postInfoId == 1){
            this.postData = vkbeautify.xml('<?xml version="1.0"?><REQUEST><REFERRAL><STOREKEY/><REFURL>leadpier.com</REFURL><TIERKEY/><IPADDRESS>107.77.201.114</IPADDRESS><AFFID>Lead Economy</AFFID><SUBID>14488</SUBID><TEST>false</TEST></REFERRAL><CUSTOMER><PERSONAL><REQUESTEDAMOUNT>3500</REQUESTEDAMOUNT><SSN>999999999</SSN><DOB>1955-03-11</DOB><FIRSTNAME>Test</FIRSTNAME><LASTNAME>Test</LASTNAME><ADDRESS>2449 State Highway 22</ADDRESS><ADDRESS2/><CITY>Whitney</CITY><STATE>TX</STATE><ZIP>76692</ZIP><HOMEPHONE>(254)998-0269</HOMEPHONE><OTHERPHONE>(254)998-0269</OTHERPHONE><DLSTATE>TX</DLSTATE><DLNUMBER>16694315</DLNUMBER><CONTACTTIME>E</CONTACTTIME><ADDRESSMONTHS>0</ADDRESSMONTHS><ADDRESSYEARS>3</ADDRESSYEARS><RENTOROWN>R</RENTOROWN><ISMILITARY>false</ISMILITARY><ISCITIZEN>true</ISCITIZEN><OTHEROFFERS>false</OTHEROFFERS><EMAIL>bayspeggy97@gmail.com</EMAIL><LANGUAGE>en</LANGUAGE></PERSONAL><EMPLOYMENT><INCOMETYPE>E</INCOMETYPE><PAYTYPE>D</PAYTYPE><EMPMONTHS>0</EMPMONTHS><EMPYEARS>3</EMPYEARS><EMPNAME>Social security</EMPNAME><EMPPHONE>(254)583-6984</EMPPHONE><HIREDATE>2017-06-09</HIREDATE><EMPTYPE>F</EMPTYPE><JOBTITLE>Manager</JOBTITLE><PAYFREQUENCY>M</PAYFREQUENCY><NETMONTHLY>3000</NETMONTHLY><LASTPAYDATE>2020-06-08</LASTPAYDATE><NEXTPAYDATE>2020-07-08</NEXTPAYDATE><SECONDPAYDATE>2020-08-07</SECONDPAYDATE></EMPLOYMENT><BANK><BANKNAME>FIRST BANK TRUST</BANKNAME><ACCOUNTTYPE>C</ACCOUNTTYPE><ROUTINGNUMBER>111304051</ROUTINGNUMBER><ACCOUNTNUMBER>0001283866</ACCOUNTNUMBER><BANKMONTHS>0</BANKMONTHS><BANKYEARS>3</BANKYEARS></BANK><REFERENCES>PeggyBAYS</REFERENCES></CUSTOMER></REQUEST>')
            this.responseData = vkbeautify.xml('<?xml version = "1.0" encoding = "utf-8" ?><response><id>0001</id><status>sold</status><message></message><price>0.5</price><redirect><![CDATA[https://aimleadintake.com/lead/go/0001]]></redirect></response>')
        } */
    }
  }
};
</script>
