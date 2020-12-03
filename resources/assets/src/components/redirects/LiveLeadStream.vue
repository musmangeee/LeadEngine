<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">Leads Live Stream</h4>

    <div class="row">
      <div class="col-sm-6 col-xl-3">
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="lnr lnr-checkmark-circle display-4 text-success"></div>
              <div class="ml-3">
                <div class="text-muted small">Lead Sold</div>
                <div class="text-large">
                     <animated-number
                        :value="liveLeadMeta.totalSold"
                        :formatValue="formatNumber"
                        :round="0"
                        :duration="1000"
                    />
                </div>
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
                <div class="text-muted small">Lead Error</div>
                <div class="text-large">
                    <animated-number
                        :value="liveLeadMeta.totalError"
                        :formatValue="formatNumber"
                        :round="0"
                        :duration="1000"
                    />
                </div>
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
                <div class="text-muted small">Lead Reject</div>
                <div class="text-large">
                     <animated-number
                        :value="liveLeadMeta.totalReject"
                        :formatValue="formatNumber"
                        :round="0"
                        :duration="1000"
                    />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3">
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="lnr lnr-database display-4 text-info"></div>
              <div class="ml-3">
                <div class="text-muted small">Lead Total</div>
                <div class="text-large">
                     <animated-number
                        :value="liveLeadMeta.total"
                        :formatValue="formatNumber"
                        :round="0"
                        :duration="1000"
                    />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <b-card no-body>
      <hr class="border-light m-0" />
      <div class="table-responsive">
        <b-table
          :show-empty="true"
          :empty-text="'No Leads available'"
          :items="liveLeadList"
          :fields="fields"
          :striped="true"
          :sort-by.sync="liveLeadMeta.sortBy"
          :sort-desc.sync="liveLeadMeta.sortDesc"
          :bordered="true"
          :per-page="liveLeadMeta.perPage"
          ref="leadTable"
          @row-clicked="clickDetail"
          hover
          class="card-table"
        >
          <template slot="name" slot-scope="row">{{ row.item.name }}</template>

          <template v-slot:cell(supplier)="data">-</template>

          <template slot="email" slot-scope="row">{{ row.item.email }}</template>

          <template v-slot:cell(first_name)="data">
            {{ data.item.first_name }}
            {{ data.item.last_name }}
          </template>

          <template
            slot="created_at"
            slot-scope="row"
          >{{ row.item.created_at | moment(momentDateTimeFormat) }}</template>

          <template slot="requested_amount" slot-scope="row">${{ row.item.requested_amount }}</template>

          <template v-slot:cell(status)="data">
            <div v-html="parseStatusLabel(data.item.status.status)"></div>
          </template>
        </b-table>
      </div>

      <!-- Pagination -->
      <b-card-body class="pt-0 pb-3">
        <div class="float-right">
          <b-form inline class="pt-3">
            <label for="perpage">Per Page:</label>
            <b-select
              size="sm"
              class="ml-1"
              v-model="liveLeadMeta.perPage"
              :options="[10, 20, 30, 40, 50]"
              name="perpage"
              id="perpage"
            />
          </b-form>
        </div>
      </b-card-body>
      <!-- / Pagination -->

      <!-- {{ liveLeadList }}

      {{ liveLeadMeta }}-->
    </b-card>
  </div>
</template>

<script>
import { mapState } from "vuex";
import AnimatedNumber from "animated-number-vue";

export default {
  name: "LiveLeadStream",
  components: {
    AnimatedNumber
  },
  created() {
    this.loadLiveLeads();
    this.timer = setInterval(this.loadLiveLeads, 10000);
  },
  beforeDestroy() {
    this.cancelAutoRefresh();
  },
  watch: {
    "liveLeadMeta.page": function() {
      this.loadLiveLeads();
    }
  },
  computed: {
    ...mapState("liveLeads", ["liveLeadList", "liveLeadMeta"])
  },
  data: () => ({
    timer: null,
    fields: [
      {
        key: "id",
        label: "Lead ID",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "supplier",
        label: "Supplier",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "email",
        label: "Email",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "first_name",
        label: "Full Name",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "requested_amount",
        label: "Price",
        sortable: false,
        tdClass: "align-middle"
      },
      { key: "status", label: "Status", tdClass: "align-middle" },
      {
        key: "created_at",
        label: "Time Stamp",
        tdClass: "align-middle",
        sortable: false
      }
    ]
  }),
  methods: {
    clickDetail(data) {
      this.$router.push({ name: "lead-view", params: { id: data.id } });
    },
    loadLiveLeads() {
      this.$store.dispatch("liveLeads/liveLeadList", this.liveLeadMeta);
    },
    parseStatusLabel(status) {
      if (status == "sold") {
        return '<span class="badge badge-success"> Sold </span>';
      } else if (status == "reject") {
        return '<span class="badge badge-default"> Reject </span>';
      } else if (status == "error") {
        return '<span class="badge badge-danger"> Error </span>';
      }
    },
    cancelAutoRefresh() {
      clearInterval(this.timer);
    },
    formatNumber(num) {
      return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    }
  }
};
</script>
