<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">Lead List</h4>

    <b-card no-body>
      <!-- Table controls -->
      <b-card-body>
        <b-row class="d-flex justify-content-between">
          <div class="col-9">
            <b-form inline>
              <label for="genre">Status</label>
              <b-form-select
                size="sm"
                :options="leadStatusOptions"
                v-model="leadListMeta.status"
                name="genre"
                id="genre"
                class="ml-1"
              />

              <b-input-group size="sm" class="ml-2">
                <flat-pickr
                  v-model="leadListMeta.startDate"
                  :config="datePickerConfig"
                  placeholder="Start Date"
                  name="start"
                ></flat-pickr>
                <div class="input-group-btn">
                  <button class="btn btn-default btn-sm" type="button" title="Toggle" data-toggle>
                    <i class="fa fa-calendar">
                      <span aria-hidden="true" class="sr-only">Toggle</span>
                    </i>
                  </button>
                </div>
              </b-input-group>

              <b-input-group size="sm" class="ml-2">
                <flat-pickr
                  v-model="leadListMeta.endDate"
                  :config="datePickerConfig"
                  placeholder="End Date"
                  name="start"
                ></flat-pickr>
                <div class="input-group-btn">
                  <button class="btn btn-default btn-sm" type="button" title="Toggle" data-toggle>
                    <i class="fa fa-calendar">
                      <span aria-hidden="true" class="sr-only">Toggle</span>
                    </i>
                  </button>
                </div>
              </b-input-group>
            </b-form>
          </div>
          <div class="col">
            <button class="btn btn-outline-primary btn-sm ml-1 icon-btn d-inline-block float-right">
              <i class="ion ion-ios-search"></i>
            </button>
            <b-input
              size="sm"
              placeholder="Search..."
              class="d-inline-block w-auto float-right"
              v-model="leadListMeta.filter"
            />
          </div>
        </b-row>
      </b-card-body>
      <!-- / Table controls -->

      <hr class="border-light m-0" />
      <div class="table-responsive">
        <b-table
          :show-empty="true"
          :empty-text="'No Leads available'"
          :items="leadList"
          :fields="fields"
          :sort-by.sync="leadListMeta.sortBy"
          :sort-desc.sync="leadListMeta.sortDesc"
          :striped="true"
          :bordered="true"
          :current-page="leadListMeta.page"
          :per-page="0"
          :filter="leadListMeta.filter"
          ref="leadTable"
          @row-clicked="clickDetail"
          hover
          class="card-table"
        >
          <template slot="name" slot-scope="row">{{ row.item.name }}</template>

            <template v-slot:cell(supplier)="data">{{ data.item.provider.name }}</template>

          <template slot="email" slot-scope="row">{{ row.item.email }}</template>

          <template v-slot:cell(first_name)="data">
            {{ data.item.first_name }}
            {{ data.item.last_name }}
          </template>

          <template v-slot:cell(created_at)="row">
            {{ row.item.created_at | moment(momentDateTimeFormat) }}
          </template>


          <template v-slot:cell(status)="data">
              <li v-for="item in data.item.status" class="list-unstyled">
                  {{ item.buyer }} (${{ item.price_received }}) <b-badge :variant="getStatusClass(item.status)">{{ item.status==='0' || item.status===0?'Pending':item.status  }}</b-badge>
              </li>
            <div v-html="parseStatusLabel(data.item.status.status)"></div>
          </template>

          <!-- A custom formatted column -->
          <template v-slot:cell(actions)="data">
            <b-btn
              v-if="ability.can('update','Lead')"
              @click="editLead(data.item)"
              variant="outline-dark btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Edit"
            >
              <i class="fas fa-pencil-alt"></i>
            </b-btn>
            <b-btn
               v-if="ability.can('delete','Lead')"
              @click="deleteLead(data.item)"
              variant="outline-danger btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Delete"
            >
              <i class="fas fa-trash"></i>
            </b-btn>
          </template>
        </b-table>
      </div>

      <!-- Pagination -->
      <b-card-body class="pt-0 pb-3">
        <div class="row">
          <div class="col-sm text-sm-left text-center pt-3">
            <span class="text-muted" v-if="leadListMeta.totalItem">
              Page {{ leadListMeta.page }} of
              {{ leadListMeta.lastPage }}
            </span>
          </div>
          <div class="col-sm pt-3">
            <b-pagination
              class="justify-content-center justify-content-sm-end m-0"
              v-if="leadListMeta.totalItem"
              v-model="leadListMeta.page"
              :total-rows="leadListMeta.totalItem"
              :per-page="leadListMeta.perPage"
              size="sm"
            />
          </div>

          <b-form inline class="pt-3">
            <label for="perpage">Per Page:</label>
            <b-select
              size="sm"
              class="ml-1"
              v-model="leadListMeta.perPage"
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
  name: "LeadList",
  created() {
    this.loadLeads();
  },
  watch: {
    'leadListMeta.status': function(){
        this.loadLeads();
    },
    'leadListMeta.filter': function(){
        this.loadLeads();
    },
    'leadListMeta.startDate': function(){
        this.loadLeads();
    },
    'leadListMeta.endDate': function(){
        this.loadLeads();
    },
    'leadListMeta.perPage': function(){
        this.loadLeads();
    },
    'leadListMeta.page': function(){
        this.loadLeads();
    },
    'leadListMeta.sortBy': function(){
        this.loadLeads();
    },
    'leadListMeta.sortDesc': function(){
        this.loadLeads();
    }
  },
  data: () => ({
    leadStatusOptions: [
      { value: "", text: "All" },
      { value: "accepted", text: "Accepted" },
      { value: "rejected", text: "Rejected" },
      { value: "error", text: "Error" }
    ],

    //date picker settings
    datePickerConfig: {
      wrap: true, // set wrap to true only when using 'input-group'
      altFormat: window.datePickerDisplayFormat,
      altInput: true,
      altInputClass: "picker-enabled-input form-control",
      dateFormat: window.datePickerSaveFormat,
      enableTime: false,
      static: false
    },

    //table related props
    fields: [
      {
        key: "id",
        label: "Lead ID",
        sortable: true,
        tdClass: "align-middle"
      },
      {
        key: "supplier",
        label: "Supplier",
        sortable: true,
        tdClass: "align-middle"
      },
      {
        key: "email",
        label: "Email",
        sortable: true,
        tdClass: "align-middle"
      },
      {
        key: "first_name",
        label: "Full Name",
        sortable: true,
        tdClass: "align-middle"
      },
      { key: "status", label: "Status", tdClass: "align-middle" },
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
    ...mapState("leads", ["leadList", "leadListMeta"]),
    ability() {
      return window.ability;
    }
  },
  methods: {
      getStatusClass(status){
       if (status == 'accepted' || status == 'sold'){
              return 'success'
          } else if (status == '0' ){
              return 'warning'
          }

          return 'danger'
      },
    searchSubmit() {},
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
    clickDetail(data) {
      this.$router.push({ name: "lead-view", params: { id: data.id } });
    },
    editLead(data) {
      this.$router.push({ name: "lead-edit", params: { id: data.id } });
    },
    deleteLead(data) {
      let self = this;
      this.$swal({
        title: `Are you sure you want to delete Lead ${data.first_name} ${data.last_name}?`,
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Delete"
      }).then(result => {
        if (result.value) {
          self.$store.dispatch("leads/leadDelete", {
            id: data.id
          });
          self.$snotify.success('Lead Deleted','Success!');
        }
      });
    },
    viewLead() {},
    loadLeads() {
      this.$store.dispatch("leads/leadList", this.tableMeta);
    }
  }
};
</script>

