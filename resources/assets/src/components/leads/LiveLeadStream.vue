<template>
  <div>
    <div class="row">
        <h4 class="font-weight-bold py-3 mb-4 col-6">
            Leads Live Stream
        </h4>
        <div class="col-6">
            <b-form inline class="py-3 mb-4 float-right">
                <label for="inline-form-input-name">Refresh Interval</label>
                <b-form-select v-model="live_stream_refresh_interval" :options="interval_options" class="ml-1" />
            </b-form>
        </div>
    </div>

    <div class="row">
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

          
          

          <template slot="email" slot-scope="row">{{ row.item.email }}</template>
          <template v-slot:cell(supplier)="data">{{ data.item.provider.name }}</template>
          <template v-slot:cell(first_name)="data">
            {{ data.item.first_name }}
            {{ data.item.last_name }}
          </template>

          <template v-slot:cell(created_at)="row">
            {{ row.item.created_at | moment(momentDateTimeFormat) }}
          </template>
      
          <template slot="price_received" slot-scope="row">${{ row.item.status.price_received }}</template>

          <template v-slot:cell(status)="data">
              <li v-for="item in data.item.status" class="list-unstyled">
                  {{ item.buyer }} (${{ item.price_received }}) <b-badge :variant="getStatusClass(item.status)">{{ item.status==='0' || item.status===0?'Pending':item.status  }}</b-badge>
              </li>
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
    this.timer = setInterval(this.loadLiveLeads, this.live_stream_refresh_interval*1000);
    
  },
  beforeDestroy() {
    this.cancelAutoRefresh();
  },
  watch: {
    "liveLeadMeta.page": function() {
      this.loadLiveLeads();
    },
    "live_stream_refresh_interval": function() {
        this.cancelAutoRefresh();
        this.timer = setInterval(this.loadLiveLeads, this.live_stream_refresh_interval*1000);
        console.log(this.live_stream_refresh_interval)
    }
  },
  computed: {
    ...mapState("liveLeads", ["liveLeadList", "liveLeadMeta"])
  },
  data: () => ({
    momentDateTimeFormat: window.momentDateTimeFormat,
    live_stream_refresh_interval: 10,
    interval_options: [
          { value: 5, text: '5s' },
          { value: 10, text: '10s' },
          { value: 20, text: '20s' },
          { value: 30, text: '30s' },
          { value: 40, text: '40s' },
          { value: 50, text: '50s' },
          { value: 60, text: '60s' }
        ],
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
      { key: "status", label: "Status", tdClass: "align-middle" },
      {
        key: "created_at",
        label: "Time Stamp",
        tdClass: "align-middle",
        sortable: false,
        
      }
    ]
  }),
  methods: {
      getStatusClass(status){
          if (status == 'accepted' || status == 'sold'){
              return 'success'
          } else if (status == '0' ){
              return 'warning'
          }

          return 'danger'
      },
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
      }  else if (status == "0") {
        return '<span class="badge badge-warning"> Pending </span>';
      }  
      else if (status == "error") {
        return '<span class="badge badge-danger"> Error </span>';
      }
      
    },
    cancelAutoRefresh() {
      clearInterval(this.timer);
    },
    formatNumber(num) {
      return num.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    }
  }
};
</script>
