<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">Daily Count by Portfolio</h4>

    <b-card no-body>
      <b-card-body>
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
                <b-btn variant="primary" size="sm" @click="loadDailyCountByPortfolio">Submit</b-btn>
            </b-form>
          </div>

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
                      <th> Buyer </th>
                      <th> # Lead </th>
                      <th> # Accepted </th>
                      <th> # Rejected </th>
                      <th> # Error </th>
                      <th> % Redirected </th>
                      <th> % Not Redirected </th>
                    </tr>
                    <tr :key="index" v-for="(buyerStat, index) in data.item.buyer_stats">
                      <td>
                        {{ buyerStat.name }}
                      </td>
                      <td>
                        {{ buyerStat.total_lead }}
                      </td>
                      <td>
                        {{ buyerStat.total_accepted }}
                      </td>
                      <td>
                        {{ buyerStat.total_rejected }}
                      </td>
                      <td>
                        {{ buyerStat.total_error }}
                      </td>
                      <td>
                        {{ buyerStat.redirected }}
                      </td>
                      <td>
                        {{ buyerStat.not_redirect }}
                      </td>
                    </tr>
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

export default {
  name: "DailyCountByPortfolio",
  metaInfo: {
    title: "Reports - Daily Count by Portfolio",
  },
  created() {
    this.loadDailyCountByPortfolio();
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
        label: "Portfolio Name",
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
    selectedDate: moment().format("Y-MM-DD"),
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
    selectedDate: function (newVal, oldVal) {
      if (newVal != oldVal) {
        this.loadDailyCountByPortfolio();
      }
    },
  },

  methods: {
    async loadDailyCountByPortfolio() {
      const statsResponse = await window.axios.get(
        `/api/portfolios/daily-stats`,{params: {
          selected_date: this.selectedDate,
          startDate: this.startDate,
          endDate: this.endDate
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


        let buyerStats = stat.buyer_stats.map((buyerStat) => {
          let buyRedirected =
            (buyerStat.total_redirect_success / buyerStat.total_accepted) * 100;
          let buyNotRedirect =
            ((buyerStat.total_accepted - buyerStat.total_redirect_success) /
              buyerStat.total_accepted) *
            100;
          buyRedirected = !isFinite(buyRedirected) ? 0 : buyRedirected;
          buyNotRedirect = !isFinite(buyNotRedirect) ? 0 : buyNotRedirect;

          return {
            name: buyerStat.name,
            total_lead: buyerStat.total_lead,
            total_accepted: buyerStat.total_accepted,
            total_rejected: buyerStat.total_rejected,
            total_error: buyerStat.total_error,
            redirected: parseFloat(buyRedirected).toFixed(2),
            not_redirect: parseFloat(buyNotRedirect).toFixed(2)
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
          buyer_stats: buyerStats
        };
      });
    },
  },
};
</script>

<style>
</style>
