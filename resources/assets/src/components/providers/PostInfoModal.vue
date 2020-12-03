<template>
  <div>
    <b-modal
      :no-close-on-backdrop="true"
      id="create-new-contact"
      size="lg"
      no-enforce-focus
      v-model="show_modal"
      @show="onShow()"
      ref="modal"
    >
    <template v-slot:modal-title="{ close }">
      <b-btn @click="downloadWithCSS" class="btn btn-primary btn-xs"><i class="fas fa-download" /> Download PDF</b-btn>
    </template>

    <div ref="content" id="content">
        <div class="col-12 text-center mb-5">
            <img src="/images/logo.png" class="mb-3">
            <h3>Posting Instruction for {{ provider.name }}</h3>
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
    </div>
    </b-modal>
  </div>
</template>

<script>
import Vue from "vue";
import vkbeautify from "vkbeautify";
import jsPDF from 'jspdf';
import html2canvas from 'html2canvas';

export default {
  props: {
    provider: {
      type: Object,
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
    download() {
        const doc = new jsPDF();
        const contentHtml = this.$refs.content.innerHTML;
        doc.fromHTML(contentHtml, 15, 15, {
            width: 170
        });
        doc.save("sample.pdf");
    },

    downloadWithCSS() {
        const pdf = new jsPDF();
        var element = document.getElementById('content');
        var width= element.style.width;
        var height = element.style.height;
        /** WITH CSS */
        var canvasElement = document.createElement('canvas');
        let self=this
        html2canvas(this.$refs.content, { canvas: canvasElement
        }).then(function (canvas) {
            var image = canvas.toDataURL('image/png');
            pdf.addImage(image, 'JPEG', 15, 40, width, height);
            pdf.save('Posting-Instruction-for-' + self.provider.name + '.pdf');
        });
    },
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

        this.postData = vkbeautify.xml('<?xml version="1.0"?><REQUEST><REFERRAL><STOREKEY/><ProviderUUID>'+this.provider.provider_key+'</ProviderUUID><PortfolioUUID></PortfolioUUID><REFURL>leadpier.com</REFURL><TIERKEY/><IPADDRESS>107.77.201.114</IPADDRESS><AFFID>Lead Economy</AFFID><SUBID>14488</SUBID><TEST>false</TEST></REFERRAL><CUSTOMER><PERSONAL><REQUESTEDAMOUNT>3500</REQUESTEDAMOUNT><SSN>999999999</SSN><DOB>1955-03-11</DOB><FIRSTNAME>Test</FIRSTNAME><LASTNAME>Test</LASTNAME><ADDRESS>2449 State Highway 22</ADDRESS><ADDRESS2/><CITY>Whitney</CITY><STATE>TX</STATE><ZIP>76692</ZIP><HOMEPHONE>(254)998-0269</HOMEPHONE><OTHERPHONE>(254)998-0269</OTHERPHONE><DLSTATE>TX</DLSTATE><DLNUMBER>16694315</DLNUMBER><CONTACTTIME>E</CONTACTTIME><ADDRESSMONTHS>0</ADDRESSMONTHS><ADDRESSYEARS>3</ADDRESSYEARS><RENTOROWN>R</RENTOROWN><ISMILITARY>false</ISMILITARY><ISCITIZEN>true</ISCITIZEN><OTHEROFFERS>false</OTHEROFFERS><EMAIL>bayspeggy97@gmail.com</EMAIL><LANGUAGE>en</LANGUAGE></PERSONAL><EMPLOYMENT><INCOMETYPE>E</INCOMETYPE><PAYTYPE>D</PAYTYPE><EMPMONTHS>0</EMPMONTHS><EMPYEARS>3</EMPYEARS><EMPNAME>Social security</EMPNAME><EMPPHONE>(254)583-6984</EMPPHONE><HIREDATE>2017-06-09</HIREDATE><EMPTYPE>F</EMPTYPE><JOBTITLE>Manager</JOBTITLE><PAYFREQUENCY>M</PAYFREQUENCY><NETMONTHLY>3000</NETMONTHLY><LASTPAYDATE>2020-06-08</LASTPAYDATE><NEXTPAYDATE>2020-07-08</NEXTPAYDATE><SECONDPAYDATE>2020-08-07</SECONDPAYDATE></EMPLOYMENT><BANK><BANKNAME>FIRST BANK TRUST</BANKNAME><ACCOUNTTYPE>C</ACCOUNTTYPE><ROUTINGNUMBER>111304051</ROUTINGNUMBER><ACCOUNTNUMBER>0001283866</ACCOUNTNUMBER><BANKMONTHS>0</BANKMONTHS><BANKYEARS>3</BANKYEARS></BANK><REFERENCES>PeggyBAYS</REFERENCES></CUSTOMER></REQUEST>')

    }
  }
};
</script>
