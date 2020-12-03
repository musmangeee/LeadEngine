<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">Failed Lead List</h4>

    <b-card no-body>

      <hr class="border-light m-0" />
      <div class="table-responsive">
        <b-table
          :show-empty="true"
          :empty-text="'No Failed Leads available'"
          :items="failedLeadList"
          :fields="fields"
          :striped="true"
          :bordered="true"
          :current-page="failedLeadListMeta.page"
          :per-page="0"
          :filter="failedLeadListMeta.filter"
          ref="leadTable"
          @row-clicked="clickDetail"
          hover
          class="card-table"
        >

          <template v-slot:cell(created_at)="row">
            {{ row.item.created_at | moment(momentDateTimeFormat) }}
          </template>
          <!-- A custom formatted column -->
          <template v-slot:cell(actions)="data">
            <b-btn
               v-if="ability.can('read','Lead')"
              @click="clickDetail(data.item)"
              variant="outline-primary btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Delete"
            >
              <i class="far fa-eye"></i>
            </b-btn>
          </template>
        </b-table>
      </div>

      <!-- Pagination -->
      <b-card-body class="pt-0 pb-3">
        <div class="row">
          <div class="col-sm text-sm-left text-center pt-3">
            <span class="text-muted" v-if="failedLeadListMeta.totalItem">
              Page {{ failedLeadListMeta.page }} of
              {{ failedLeadListMeta.lastPage }}
            </span>
          </div>
          <div class="col-sm pt-3">
            <b-pagination
              class="justify-content-center justify-content-sm-end m-0"
              v-if="failedLeadListMeta.totalItem"
              v-model="failedLeadListMeta.page"
              :total-rows="failedLeadListMeta.totalItem"
              :per-page="failedLeadListMeta.perPage"
              size="sm"
            />
          </div>

          <b-form inline class="pt-3">
            <label for="perpage">Per Page:</label>
            <b-select
              size="sm"
              class="ml-1"
              v-model="failedLeadListMeta.perPage"
              :options="[10, 20, 30, 40, 50]"
              name="perpage"
              id="perpage"
            />
          </b-form>
        </div>
      </b-card-body>
      <!-- / Pagination -->
    </b-card>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  name: "FailedLeadList",
  created() {
    this.loadLeads();
  },
  watch: {
    'failedLeadListMeta.status': function(){
        this.loadLeads();
    },
    'failedLeadListMeta.filter': function(){
        this.loadLeads();
    },
    'failedLeadListMeta.startDate': function(){
        this.loadLeads();
    },
    'failedLeadListMeta.endDate': function(){
        this.loadLeads();
    },
    'failedLeadListMeta.perPage': function(){
        this.loadLeads();
    },
    'failedLeadListMeta.page': function(){
        this.loadLeads();
    },
    'failedLeadListMeta.sortBy': function(){
        this.loadLeads();
    },
    'failedLeadListMeta.sortDesc': function(){
        this.loadLeads();
    }
  },
  data: () => ({
    //table related props
    fields: [
        {
        key: "id",
        label: "ID",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "ip_address",
        label: "IP Address",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "reason",
        label: "Reason",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "created_at",
        label: "Time Stamp",
        tdClass: "align-middle",
        sortable: true
      },
      {
        key: "actions",
        label: "Actions",
        tdClass: "text-nowrap align-middle text-center"
      }
    ],
    //end of table related props
    momentDateTimeFormat: window.momentDateTimeFormat
  }),
  computed: {
    ...mapState("leads", ["failedLeadList", "failedLeadListMeta"]),
    ability() {
      return window.ability;
    }
  },
  methods: {
    searchSubmit() {},
    clickDetail(data) {
      this.$router.push({ name: "failed-lead-view", params: { id: data.id } });
    },
    loadLeads() {
      this.$store.dispatch("leads/failedLeadList", this.tableMeta);
    }
  }
};
</script>