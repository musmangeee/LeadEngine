<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">Count by Buyer</h4>


    <b-card no-body>
      <b-card-body>
        <send-count-by-buyer-report-modal ref="sendCountByBuyerReportModal" :startDate="startDate" :endDate="endDate" />


        <b-row class="d-flex justify-content-between">
      <div class="col">
            <b-form inline>
              <span class="mr-2"> Start Date </span>
              <b-input-group size="sm" class="ml-2">
                <flat-pickr
                  v-model="startDate"
                  :config="datePickerConfig"
                  placeholder="Start Date"
                  name="startDate"
                ></flat-pickr>
                <div class="input-group-btn">
                  <button
                    class="btn btn-default btn-sm"
                    type="button"
                    title="Toggle"
                    data-toggle
                  >
                    <i class="fa fa-calendar">
                      <span aria-hidden="true" class="sr-only">Toggle</span>
                    </i>
                  </button>
                </div>
              </b-input-group>

              <span class="ml-4 mr-2"> End Date </span>
              <b-input-group size="sm" class="ml-2">
                <flat-pickr
                  v-model="endDate"
                  :config="datePickerConfig"
                  placeholder="End Date"
                  name="endDate"
                ></flat-pickr>
                <div class="input-group-btn">
                  <button
                    class="btn btn-default btn-sm"
                    type="button"
                    title="Toggle"
                    data-toggle
                  >
                    <i class="fa fa-calendar">
                      <span aria-hidden="true" class="sr-only">Toggle</span>
                    </i>
                  </button>
                </div>
              </b-input-group>
                <b-btn variant="primary" size="sm" @click="loadReport">Submit</b-btn>
            </b-form>
          </div>
          <div class="col text-right">
            <a @click="exportPdf" class="btn btn-primary btn-sm text-white"  v-b-tooltip.hover title="Export Count by Buyer to PDF">
              <i class="fa fa-download"></i>
            </a>

            <a @click="sendReportByEmail" class="btn btn-primary btn-sm text-white"  v-b-tooltip.hover title="Send Count by Buyer Report to Email">
              <i class="fa fa-envelope"></i>
            </a>
          </div>
        </b-row>

        <div class="table-responsive mt-4">
          <b-table
            :show-empty="true"
            :empty-text="'No Leads available'"
            :items="statsData"
            :fields="fields"
            hover
            striped
          >

            <template v-slot:cell(showDetails)="data">
               <a class="btn btn-primary btn-sm icon-btn rounded-pill text-white" @click="data.toggleDetails">
                    <i :class="[data.detailsShowing ? 'fa-minus' : 'fa-plus', 'fa']"></i>
                </a>
            </template>

            <template slot="row-details" slot-scope="data">
              <b-card>
                <div class="table-responsive">
                   <table class="table">
                    <tr>
                      <th width="14.28%"> Panda Channel </th>
                      <th width="14.28%"> # Lead </th>
                      <th width="14.28%"> # Accepted </th>
                      <th width="14.28%"> # Rejected </th>
                      <th width="14.28%"> # Error </th>
                      <th width="14.28%"> % Redirected </th>
                      <th width="14.28%"> % Not Redirected </th>
                    </tr>

                    <tbody v-if="data.item.panda_stats.length > 0">
                       <tr :key="index" v-for="(pandaStat, index) in data.item.panda_stats">
                        <td>
                          {{ pandaStat.name }}
                        </td>
                        <td>
                          {{ pandaStat.total_lead }}
                        </td>
                        <td>
                          {{ pandaStat.total_accepted }}
                        </td>
                        <td>
                          {{ pandaStat.total_rejected }}
                        </td>
                        <td>
                          {{ pandaStat.total_error }}
                        </td>
                        <td>
                          {{ pandaStat.redirected }}
                        </td>
                        <td>
                          {{ pandaStat.not_redirect }}
                        </td>
                      </tr>
                    </tbody>


                    <tbody v-else>
                      <tr>
                        <td colspan="7" class="text-center">
                          No Leads data available
                        </td>
                      </tr>
                    </tbody>
                   
                  </table>

                  <table class="table">
                    <tr>
                      <th width="14.28%"> Vertical Channel </th>
                      <th width="14.28%"> # Lead </th>
                      <th width="14.28%"> # Accepted </th>
                      <th width="14.28%"> # Rejected </th>
                      <th width="14.28%"> # Error </th>
                      <th width="14.28%"> % Redirected </th>
                      <th width="14.28%"> % Not Redirected </th>
                    </tr>

                    <tbody v-if="data.item.vertical_stats.length > 0">
                       <tr :key="index" v-for="(verticalStat, index) in data.item.vertical_stats">
                          <td>
                            {{ verticalStat.name }}
                          </td>
                          <td>
                            {{ verticalStat.total_lead }}
                          </td>
                          <td>
                            {{ verticalStat.total_accepted }}
                          </td>
                          <td>
                            {{ verticalStat.total_rejected }}
                          </td>
                          <td>
                            {{ verticalStat.total_error }}
                          </td>
                          <td>
                            {{ verticalStat.redirected }}
                          </td>
                          <td>
                            {{ verticalStat.not_redirect }}
                          </td>
                        </tr>
                    </tbody>


                    <tbody v-else>
                      <tr>
                        <td colspan="7" class="text-center">
                          No Leads data available
                        </td>
                      </tr>
                    </tbody>

                    
                  </table>

                  <table class="table">
                    <tr>
                      <th width="14.28%"> Plat Channel </th>
                      <th width="14.28%"> # Lead </th>
                      <th width="14.28%"> # Accepted </th>
                      <th width="14.28%"> # Rejected </th>
                      <th width="14.28%"> # Error </th>
                      <th width="14.28%"> % Redirected </th>
                      <th width="14.28%"> % Not Redirected </th>
                    </tr>

                    <tbody v-if="data.item.plat_stats.length > 0">
                       <tr :key="index" v-for="(platStat, index) in data.item.plat_stats">
                          <td>
                            {{ platStat.name }}
                          </td>
                          <td>
                            {{ platStat.total_lead }}
                          </td>
                          <td>
                            {{ platStat.total_accepted }}
                          </td>
                          <td>
                            {{ platStat.total_rejected }}
                          </td>
                          <td>
                            {{ platStat.total_error }}
                          </td>
                          <td>
                            {{ platStat.redirected }}
                          </td>
                          <td>
                            {{ platStat.not_redirect }}
                          </td>
                        </tr>
                    </tbody>


                    <tbody v-else>
                      <tr>
                        <td colspan="7" class="text-center">
                          No Leads data available
                        </td>
                      </tr>
                    </tbody>

                    
                  </table>
                </div>

              </b-card>
            </template>

            <template v-slot:cell(name)="data">
              {{ data.item.name }}
            </template>

            <template v-slot:cell(redirected)="data">
              {{ data.item.redirected }}
            </template>

            <template v-slot:cell(not_redirect)="data">
              {{ data.item.not_redirect }}
            </template>
          </b-table>
        </div>
      </b-card-body>
    </b-card>
  </div>
</template>

<script>
import moment from "moment";
import SendCountByBuyerReportModal from "@/components/reports/SendCountByBuyerReportModal";

export default {
  name: "CountByBuyer",
  metaInfo: {
    title: "Reports - Count by Buyer",
  },
  components: {
    SendCountByBuyerReportModal
  },
  created() {
    this.loadDailyCountByBuyer();
    SendCountByBuyerReportModal
  },
  data: () => ({
    fields: [
      {
        key: "showDetails",
        label: " ",
        tdClass: "text-nowrap align-middle text-center",
      },
      {
        key: "name",
        label: "Buyer Name",
        tdClass: "align-middle",
      },
      {
        key: "total_lead",
        label: "# Lead",
        tdClass: "align-middle",
      },
      {
        key: "total_accepted",
        label: "# Accepted",
        tdClass: "align-middle",
      },
      {
        key: "total_rejected",
        label: "# Rejected",
        tdClass: "align-middle",
      },
      {
        key: "total_error",
        label: "# Error",
        tdClass: "align-middle",
      },
      {
        key: "redirected",
        label: "% Redirected",
        tdClass: "align-middle",
      },
      {
        key: "not_redirect",
        label: "% Not Redirected",
        tdClass: "align-middle",
      },
    ],
    startDate: moment().subtract(1,'days').format("Y-MM-DD"),
    endDate: moment().format("Y-MM-DD"),
    datePickerConfig: {
      wrap: true, // set wrap to true only when using 'input-group'
      altFormat: window.datePickerDisplayFormat,
      altInput: true,
      altInputClass: "picker-enabled-input form-control",
      dateFormat: window.datePickerSaveFormat,
      enableTime: false,
      static: false,
    },
    statsData: [],
  }),

  watch: {
    startDate: function (newVal, oldVal) {
      if (newVal != oldVal) {
        this.loadDailyCountByBuyer();
      }
    },
    endDate: function (newVal, oldVal) {
      if (newVal != oldVal) {
        this.loadDailyCountByBuyer();
      }
    },
  },

  methods: {
    sendReportByEmail(){
      this.$refs.sendCountByBuyerReportModal.show();
    },
    async exportPdf(){
      const postedData = {
        start_date: this.startDate,
        end_date: this.endDate
      };

      try {
        const response = await window.axios.post('/api/buyers/daily-stats-export',postedData,  { responseType: "arraybuffer" });
        this.downloadFile(response, 'Count by Provider Report','pdf');

      } catch (error) {
        console.log(error);
      }
    },
    downloadFile(response, filename, fileType) {
      // It is necessary to create a new blob object with mime-type explicitly set
      // otherwise only Chrome works like it should
      let exportType = null;
      if (fileType == "pdf") {
        exportType = "application/pdf";
      } else if (fileType == "csv") {
        exportType = "text/csv";
      }
      var newBlob = new Blob([response.data], { type: exportType });
      // IE doesn't allow using a blob object directly as link href
      // instead it is necessary to use msSaveOrOpenBlob
      if (window.navigator && window.navigator.msSaveOrOpenBlob) {
        window.navigator.msSaveOrOpenBlob(newBlob);
        return;
      }
      // For other browsers:
      // Create a link pointing to the ObjectURL containing the blob.
      const data = window.URL.createObjectURL(newBlob);
      var link = document.createElement("a");
      link.href = data;
      link.download = filename + `.${fileType}`;
      link.click();
      setTimeout(function() {
        // For Firefox it is necessary to delay revoking the ObjectURL
        window.URL.revokeObjectURL(data);
      }, 100);
    },
    async loadDailyCountByBuyer() {
      const statsResponse = await window.axios.post(
        `/api/buyers/daily-stats`,{params: 
        {
          start_date: this.startDate,
          end_date: this.endDate
        }}
      );


      this.statsData = statsResponse.data.data.map((stat) => {
        let redirected =
          (stat.total_redirect_success / stat.total_accepted) * 100;
        let not_redirect =
          ((stat.total_accepted - stat.total_redirect_success) /
            stat.total_accepted) *
          100;
        redirected = !isFinite(redirected) ? 0 : redirected;
        not_redirect = !isFinite(not_redirect) ? 0 : not_redirect;

        //panda
        let pandaStats = stat.panda_stats.map((pandaStat) => {
          let pandaRedirected =
            (pandaStat.total_redirect_success / pandaStat.total_accepted) * 100;
          let pandaNotRedirect =
            ((pandaStat.total_accepted - pandaStat.total_redirect_success) /
              pandaStat.total_accepted) *
            100;
          pandaRedirected = !isFinite(pandaRedirected) ? 0 : pandaRedirected;
          pandaNotRedirect = !isFinite(pandaNotRedirect) ? 0 : pandaNotRedirect;

          return {
            name: pandaStat.name,
            total_lead: pandaStat.total_lead,
            total_accepted: pandaStat.total_accepted,
            total_rejected: pandaStat.total_rejected,
            total_error: pandaStat.total_error,
            redirected: parseFloat(pandaRedirected).toFixed(2),
            not_redirect: parseFloat(pandaNotRedirect).toFixed(2)
          }
        });

        //vertical
        let verticalStats = stat.vertical_stats.map((verticalStat) => {
          let verticalRedirected =
            (verticalStat.total_redirect_success / verticalStat.total_accepted) * 100;
          let verticalNotRedirect =
            ((verticalStat.total_accepted - verticalStat.total_redirect_success) /
              verticalStat.total_accepted) *
            100;
          verticalRedirected = !isFinite(verticalRedirected) ? 0 : verticalRedirected;
          verticalNotRedirect = !isFinite(verticalNotRedirect) ? 0 : verticalNotRedirect;

          return {
            name: verticalStat.name,
            total_lead: verticalStat.total_lead,
            total_accepted: verticalStat.total_accepted,
            total_rejected: verticalStat.total_rejected,
            total_error: verticalStat.total_error,
            redirected: parseFloat(verticalRedirected).toFixed(2),
            not_redirect: parseFloat(verticalNotRedirect).toFixed(2)
          }
        });

        //plat
        let platStats = stat.plat_stats.map((platStat) => {
          let platRedirected =
            (platStat.total_redirect_success / platStat.total_accepted) * 100;
          let platNotRedirect =
            ((platStat.total_accepted - platStat.total_redirect_success) /
              platStat.total_accepted) *
            100;
          platRedirected = !isFinite(platRedirected) ? 0 : platRedirected;
          platNotRedirect = !isFinite(platNotRedirect) ? 0 : platNotRedirect;

          return {
            name: platStat.name,
            total_lead: platStat.total_lead,
            total_accepted: platStat.total_accepted,
            total_rejected: platStat.total_rejected,
            total_error: platStat.total_error,
            redirected: parseFloat(platRedirected).toFixed(2),
            not_redirect: parseFloat(platNotRedirect).toFixed(2)
          }
        });

        return {
          name: stat.name,
          total_lead: stat.total_lead,
          total_accepted: stat.total_accepted,
          total_rejected: stat.total_rejected,
          total_error: stat.total_error,
          redirected: parseFloat(redirected).toFixed(2),
          not_redirect: parseFloat(not_redirect).toFixed(2),
          panda_stats: pandaStats,
          vertical_stats: verticalStats,
          plat_stats: platStats
        };
      });
    },
  },
};
</script>

<style>
</style>
