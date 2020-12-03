<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">Daily Lead Report</h4>


    <b-card no-body>
        <b-card-body>
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
            </b-row>
        </b-card-body>

        <div class="row mt-4">
            <div class="col">
                <leads-volume-chart
                :height="50"
                :chart-data="volumeChartData"
                :options="volumeChartOptions"
                ></leads-volume-chart>
            </div>
        </div>

        <hr class="border-light m-0 mb-5" />



    </b-card>


    <div class="table-responsive">
            <b-table
            :show-empty="true"
            :empty-text="'No Leads available'"
            :items="reportData"
            :fields="fields"
            hover
            striped
            >
            <template v-slot:cell(date)="data">
            {{ data.item.date | moment('MM/DD/Y') }}
          </template>

          <template v-slot:cell(redirected)="data">
            {{ parseFloat((data.item.total_redirect_success) / data.item.total_accepted * 100).toFixed(2)}}
          </template>

          <template v-slot:cell(not_redirect)="data">
            {{ parseFloat((data.item.total_accepted - (data.item.total_redirect_success)) / data.item.total_accepted * 100).toFixed(2)}}
          </template>
            </b-table>
        </div>

  </div>
</template>

<script>
import { mapState } from "vuex";
import LeadsVolumeChart from "@/components/dashboards/LeadsVolumeChart";
import RedirectedChart from "@/components/dashboards/RedirectedChart";
import moment from 'moment';

export default {
  name: "dashboard-1",
  components: {
    LeadsVolumeChart,
  },
  metaInfo: {
    title: "Reports - Daily Lead"
  },
  computed: {
    volumeChartOptions() {
      return {
        layout: {
          padding: {
            left: 0,
            right: 0,
            top: 0,
            bottom: 0
          }
        },
        scales: {
          yAxes: [
            {
              stacked: true,
              ticks: {
                beginAtZero: true,
                callback: function(value, index, values) {
                  return "";
                }
              },
              gridLines: {
                display: false,
                drawBorder: false
              }
            }
          ],
          xAxes: [
            {
              stacked: true,

              gridLines: {
                display: false
              }
            }
          ]
        },
        legend: {
          display: false
        },
        responsive: true,
        maintainAspectRatio: true
      };
    },
  },
  created() {
    this.loadReport();
  },
  methods: {
    loadReport() {
      window.axios.get('/api/leads/daily-lead', {params: {
            startDate: this.startDate,
            endDate: this.endDate
          }})
        .then(result => {
            this.reportData = result.data.data;
          let allLabels = Object.keys(result.data.graph_data);
          let allValues = Object.values(result.data.graph_data);

          this.volumeChartData = {
            labels: allLabels,
            datasets: [
              {
                label: "All Leads",
                borderWidth: 1,
                backgroundColor: "#26B4FF",
                data: allValues
              },
            ]
          };
        });
    }
  },
  data: () => ({
      fields: [
      {
        key: "date",
        label: "Date",
        tdClass: "align-middle"
      },
      {
        key: "total_lead",
        label: "# Lead",
        tdClass: "align-middle"
      },
      {
        key: "total_accepted",
        label: "# Accepted",
        tdClass: "align-middle"
      },
      {
        key: "total_rejected",
        label: "# Rejected",
        tdClass: "align-middle"
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
      }
    ],
      startDate: moment().subtract(1, 'month').format('Y-MM-DD'),
      endDate: moment().format('Y-MM-DD'),
    datePickerConfig: {
      wrap: true, // set wrap to true only when using 'input-group'
      altFormat: window.datePickerDisplayFormat,
      altInput: true,
      altInputClass: "picker-enabled-input form-control",
      dateFormat: window.datePickerSaveFormat,
      enableTime: false,
      static: false
    },
    reportData: [],
    volumeChartData: {
      labels: [],
      datasets: [
        {
          label: "Volume of Leads",
          borderWidth: 1,
          backgroundColor: "#8897AA",
          data: []
        }
      ]
    }
  })
};
</script>
