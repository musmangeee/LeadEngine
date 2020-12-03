<template>
  <div>

    <vue-html2pdf
        :show-layout="true"
        :enable-download="false"
        :preview-modal="true"
        :paginate-elements-by-height="1400"
        filename="hee hee"
        :pdf-quality="2"
        :manual-pagination="false"
        pdf-format="a4"
        pdf-orientation="portrait"
        pdf-content-width="800px"

        @progress="onProgress($event)"
        @hasStartedGeneration="hasStartedGeneration()"
        @hasGenerated="hasGenerated($event)"
        ref="html2Pdf"
    >
        <section slot="pdf-content">
            <div class="col-12 text-center mb-5">
            <img src="/images/logo.png" class="mb-3">
            <h3>Posting Instruction for {{ providerDetail.name }}</h3>
        </div>

        <b-card title="Post URL" sub-title="This is the URL you will be use to post you XML to" class="mb-3">
            <b-card-text>
            <code><pre>https://aimleadconnect.com/intake</pre></code>
            </b-card-text>
        </b-card>

        <b-card title="Post Data" sub-title="This is the exact XML format you will be using to post to the URL above">
            <b-card-text>
            <code><pre>{{ postData }}</pre></code>
            </b-card-text>
        </b-card>

        <template slot="modal-footer">
            <b-button variant="default" @click="cancel()">Close</b-button>
        </template>
        </section>
    </vue-html2pdf>


    </div>
</template>

<script>
import Vue from "vue";
import vkbeautify from "vkbeautify";
import { mapState } from "vuex";
import VueHtml2pdf from 'vue-html2pdf'

export default {
  props: ['id'],
  components: {
      VueHtml2pdf
  },
  computed: {
      ...mapState("providers", ["providerDetail"])
  },
  watch: {

  },
  created() {
    this.$store.dispatch("providers/providerDetail", { id: this.id }).then(() => {
        this.provider = { ...this.providerDetail };
        this.$refs.html2Pdf.generatePdf()
      });


  },
  validations: {

  },
  data: () => ({
    postData: '',
    responseData: '',
    show_modal: false,
  }),
  methods: {
    async onShow() {

        //this.postData = vkbeautify.xml('<?xml version="1.0"?><REQUEST><REFERRAL><STOREKEY/><ProviderUUID>'+this.provider.provider_key+'</ProviderUUID><PortfolioUUID></PortfolioUUID><REFURL>leadpier.com</REFURL><TIERKEY/><IPADDRESS>107.77.201.114</IPADDRESS><AFFID>Lead Economy</AFFID><SUBID>14488</SUBID><TEST>false</TEST></REFERRAL><CUSTOMER><PERSONAL><REQUESTEDAMOUNT>3500</REQUESTEDAMOUNT><SSN>999999999</SSN><DOB>1955-03-11</DOB><FIRSTNAME>Test</FIRSTNAME><LASTNAME>Test</LASTNAME><ADDRESS>2449 State Highway 22</ADDRESS><ADDRESS2/><CITY>Whitney</CITY><STATE>TX</STATE><ZIP>76692</ZIP><HOMEPHONE>(254)998-0269</HOMEPHONE><OTHERPHONE>(254)998-0269</OTHERPHONE><DLSTATE>TX</DLSTATE><DLNUMBER>16694315</DLNUMBER><CONTACTTIME>E</CONTACTTIME><ADDRESSMONTHS>0</ADDRESSMONTHS><ADDRESSYEARS>3</ADDRESSYEARS><RENTOROWN>R</RENTOROWN><ISMILITARY>false</ISMILITARY><ISCITIZEN>true</ISCITIZEN><OTHEROFFERS>false</OTHEROFFERS><EMAIL>bayspeggy97@gmail.com</EMAIL><LANGUAGE>en</LANGUAGE></PERSONAL><EMPLOYMENT><INCOMETYPE>E</INCOMETYPE><PAYTYPE>D</PAYTYPE><EMPMONTHS>0</EMPMONTHS><EMPYEARS>3</EMPYEARS><EMPNAME>Social security</EMPNAME><EMPPHONE>(254)583-6984</EMPPHONE><HIREDATE>2017-06-09</HIREDATE><EMPTYPE>F</EMPTYPE><JOBTITLE>Manager</JOBTITLE><PAYFREQUENCY>M</PAYFREQUENCY><NETMONTHLY>3000</NETMONTHLY><LASTPAYDATE>2020-06-08</LASTPAYDATE><NEXTPAYDATE>2020-07-08</NEXTPAYDATE><SECONDPAYDATE>2020-08-07</SECONDPAYDATE></EMPLOYMENT><BANK><BANKNAME>FIRST BANK TRUST</BANKNAME><ACCOUNTTYPE>C</ACCOUNTTYPE><ROUTINGNUMBER>111304051</ROUTINGNUMBER><ACCOUNTNUMBER>0001283866</ACCOUNTNUMBER><BANKMONTHS>0</BANKMONTHS><BANKYEARS>3</BANKYEARS></BANK><REFERENCES>PeggyBAYS</REFERENCES></CUSTOMER></REQUEST>')

    }
  }
};
</script>
