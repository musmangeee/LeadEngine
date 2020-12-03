<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">Dashboard</h4>

    <div class="row">
      <div class="col-sm-6 col-xl-3">
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="lnr lnr-database display-4 text-primary"></div>
              <div class="ml-3">
                <div class="text-muted">Leads</div>
                <div class="text-large">{{ leadListStat.total }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3">
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="lnr lnr-checkmark-circle display-4 text-success"></div>
              <div class="ml-3">
                <div class="text-muted">Accepted</div>
                <div class="text-large">{{ leadListStat.total_sold }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3">
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="lnr lnr-cross-circle display-4 text-danger"></div>
              <div class="ml-3">
                <div class="text-muted">Rejected</div>
                <div class="text-large">{{ leadListStat.total_reject }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3">
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="lnr lnr-bug display-4 text-warning"></div>
              <div class="ml-3">
                <div class="text-muted">Error</div>
                <div class="text-large">{{ leadListStat.total_error }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col">
        <leads-volume-chart
          :height="50"
          :chart-data="volumeChartData"
          :options="volumeChartOptions"
        ></leads-volume-chart>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-xs-12 col-sm-4">
        <redirected-chart
          :height="150"
          :chart-data="redirectChartData"
          :options="redirectChartOptions"
        ></redirected-chart>
      </div>
      <div class="col-xs-12 col-sm-8">
        <div class="row mt-4">
          <div class="col-xs-12 col-sm-4">
            <div class="card mb-4">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="lnr lnr-clock display-4 text-primary"></div>
                  <div class="ml-3">
                    <div class="text-muted">Avg Time of Redirect</div>
                    <div> <span class="text-large"> {{ leadListStat.avg_time_redirected }}  </span> <span class="text-muted"> seconds </span> </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xs-12 col-sm-4">
            <div class="card mb-4">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="lnr lnr-history display-4 text-primary"></div>
                  <div class="ml-3">
                    <div class="text-muted">Avg Redirect / Min </div>
                    <div class="text-large">{{ leadListStat.avg_redirected_minute }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

           <div class="col-xs-12 col-sm-4">
            <div class="card mb-4">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="lnr lnr-history display-4 text-primary"></div>
                  <div class="ml-3">
                    <div class="text-muted">Avg Redirect / Hour </div>
                    <div class="text-large">{{ leadListStat.avg_redirected_hour }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import LeadsVolumeChart from "@/components/dashboards/LeadsVolumeChart";
import RedirectedChart from "@/components/dashboards/RedirectedChart";

export default {
  name: "dashboard-1",
  components: {
    LeadsVolumeChart,
    RedirectedChart
  },
  metaInfo: {
    title: "Dashboard 1 - Dashboards"
  },
  computed: {
    ...mapState("leads", ["leadListStat"]),
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
    redirectChartOptions() {
      return {
        borderWidth: "10px",
        hoverBackgroundColor: "red",
        hoverBorderWidth: "10px",
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          fullWidth: true,
          position: "left",
          labels: {
            fontSize: 10
          }
        },
        title: {
          display: true,
          text: "Redirect Data"
        }
      };
    }
  },
  created() {
    this.loadLeads();
  },
  methods: {
    loadLeads() {
      this.$store
        .dispatch("leads/leadListStat", {
          selectedDate: this.$moment().format("DD-MM-Y")
        })
        .then(result => {
          let allLabels = Object.keys(this.leadListStat.all_range);
          let allValues = Object.values(this.leadListStat.all_range);
          let acceptedValues = Object.values(this.leadListStat.accepted_range);
          let rejectedValues = Object.values(this.leadListStat.rejected_range);
          let errorValues = Object.values(this.leadListStat.error_range);

          this.volumeChartData = {
            labels: allLabels,
            datasets: [
              {
                label: "All Leads",
                borderWidth: 1,
                backgroundColor: "#26B4FF",
                data: allValues
              },
              {
                label: "Accepted Leads",
                borderWidth: 1,
                backgroundColor: "#02BC77",
                data: acceptedValues
              },
              {
                label: "Rejected Leads",
                borderWidth: 1,
                backgroundColor: "#d9534f ",
                data: rejectedValues
              },
              {
                label: "Error Leads",
                borderWidth: 1,
                backgroundColor: "#FFD950",
                data: errorValues
              }
            ]
          };

          let successRedirected = this.leadListStat.success_redirected;
          let notRedirected = this.leadListStat.not_redirected;
          let errorRedirected = this.leadListStat.error_redirected;

          this.redirectChartData = {
            hoverBackgroundColor: "red",
            hoverBorderWidth: 10,
            labels: ["Redirected", "Didn't Redirect", "Redirect Error"],
            datasets: [
              {
                text: "Redirect Data",
                backgroundColor: ["#02BC77", "#FFD950", "#d9534f"],
                data: [successRedirected, notRedirected, errorRedirected]
              }
            ]
          };
        });
    }
  },
  data: () => ({
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
    },
    redirectChartData: {
      hoverBackgroundColor: "red",
      hoverBorderWidth: 10,
      labels: ["Redirected", "Didn't Redirect", "Redirect Error"],
      datasets: [
        {
          text: "Redirect Data",
          backgroundColor: ["#02BC77", "#FFD950", "#d9534f"],
          data: [0, 0, 0]
        }
      ]
    }
  })
};
</script>
