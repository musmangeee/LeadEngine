<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">View Redirects</h4>

    <b-row class="mb-5">
            <div class="col-4">
                <b-card>
                    <div>
                        <h6>Total Leads</h6>
                    </div>
                    <div>
                        <h3>{{ totalApplication }}</h3>
                    </div>
                </b-card>
            </div>
            <div class="col-4">
                <b-card>
                    <div>
                        <h6>Total Redirected</h6>
                    </div>
                    <div>
                        <h3 class="text-success">{{ totalRedirected }}</h3>
                    </div>
                </b-card>
            </div>
            <div class="col-4">
                <b-card>
                    <div>
                        <h6>Total Not Redirected</h6>
                    </div>
                    <div>
                        <h3 class="text-danger">{{ totalNotRedirected }}</h3>
                    </div>
                </b-card>
            </div>
    </b-row>

    <!-- Table controls -->
      <!-- b-card-body>
        <b-row class="d-flex justify-content-between">
          <div class="col">
            <b-form inline>
              <label for="genre">Status</label>
              <b-form-select
                size="sm"
                :options="redirectStatusOptions"
                v-model="redirectListMeta.status"
                name="genre"
                id="genre"
                class="ml-1"
              />
              <b-input-group size="sm" class="ml-2">
                <flat-pickr
                  v-model="redirectListMeta.startDate"
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
                  v-model="redirectListMeta.endDate"
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
              v-model="redirectListMeta.filter"
            />
          </div>
        </b-row>
      </b-card-body-->
      <!-- / Table controls -->

    <b-card no-body>
      <hr class="border-light m-0" />
      <div class="table-responsive">
        <b-table
          :show-empty="true"
          :empty-text="'No Redirect available'"
          :items="redirectList"
          :fields="fields"
          :sort-by.sync="redirectListMeta.sortBy"
          :sort-desc.sync="redirectListMeta.sortDesc"
          :striped="false"
          :bordered="true"
          :current-page="redirectListMeta.page"
          :per-page="0"
          :filter="redirectListMeta.filter"
          ref="redirectTable"
          hover
          class="table"
          :tbody-tr-class="rowClass"
        >
          <template v-slot:cell(redirect_time)="row">

            {{ (row.item.redirect) ? getTimeDifferent(row.item.created_at, row.item.redirect.created_at) : 'N/A' }}
          </template>

          <template v-slot:cell(enduser_ip_address)="row">
            <span v-if="row.item.redirect">
                <span style="color: #5B0200; font-weight: bold" v-if="row.item.enduser_ip_address != row.item.redirect.redirect_user_ip">{{ row.item.enduser_ip_address }}</span>
                <span style="color: #005B04; font-weight: bold" v-if="row.item.enduser_ip_address == row.item.redirect.redirect_user_ip">{{ row.item.enduser_ip_address }}</span>
            </span>
            <span v-else>
                {{ row.item.enduser_ip_address }}
            </span>
          </template>

          <template v-slot:cell(redirect_user_ip)="row">
            <span v-if="row.item.redirect">
                <span style="color: #5B0200; font-weight: bold" v-if="row.item.enduser_ip_address != row.item.redirect.redirect_user_ip">{{ row.item.redirect.redirect_user_ip }}</span>
                <span style="color: #005B04; font-weight: bold" v-if="row.item.enduser_ip_address == row.item.redirect.redirect_user_ip">{{ row.item.redirect.redirect_user_ip }}</span>
            </span>
            <span v-else>
                N/A
            </span>
          </template>

         <template v-slot:cell(created_at)="row">
            {{ row.item.created_at | moment(momentDateTimeFormat) }}
          </template>
        </b-table>
      </div>

      <!-- Pagination -->
      <b-card-body class="pt-0 pb-3">
        <div class="row">
          <div class="col-sm text-sm-left text-center pt-3">
            <span class="text-muted" v-if="redirectListMeta.totalItems">
              Page {{ redirectListMeta.page }} of
              {{ redirectListMeta.lastPage }}
            </span>
          </div>
          <div class="col-sm pt-3">
            <b-pagination
              class="justify-content-center justify-content-sm-end m-0"
              v-if="redirectListMeta.totalItem"
              v-model="redirectListMeta.page"
              :total-rows="redirectListMeta.totalItem"
              :per-page="redirectListMeta.perPage"
              size="sm"
            />
          </div>

          <b-form inline class="pt-3">
            <label for="perpage">Per Page:</label>
            <b-select
              size="sm"
              class="ml-1"
              v-model="redirectListMeta.perPage"
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
  name: "RedirectList",
  created() {
    this.loadRedirects();
  },
  watch: {
    'redirectListMeta.status': function(){
        this.loadRedirects();
    },
    'redirectListMeta.filter': function(){
        this.loadRedirects();
    },
    'redirectListMeta.startDate': function(){
        this.loadRedirects();
    },
    'redirectListMeta.endDate': function(){
        this.loadRedirects();
    },
    'redirectListMeta.perPage': function(){
        this.loadRedirects();
    },
    'redirectListMeta.page': function(){
        this.loadRedirects();
    },
    'redirectListMeta.sortBy': function(){
        this.loadRedirects();
    },
    'redirectListMeta.sortDesc': function(){
        this.loadRedirects();
    }
  },
  data: () => ({
    redirectStatusOptions: [
      { value: "", text: "All" },
      { value: "yes", text: "Yes" },
      { value: "no", text: "No" }
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
        label: "Application ID",
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
        key: "provider.name",
        label: "Provider",
        sortable: true,
        tdClass: "align-middle"
      },
      {
        key: "affid",
        label: "AFFID",
        sortable: true,
        tdClass: "align-middle"
      },
      {
        key: "redirect_time",
        label: "Time",
        tdClass: "align-middle"
      },
      {
        key: "enduser_ip_address",
        label: "User IP",
        tdClass: "align-middle"
      },
      {
        key: "redirect_user_ip",
        label: "Redirect IP",
        tdClass: "align-middle"
      },
      {
        key: "created_at",
        label: "Time Stamp",
        tdClass: "align-middle",
        sortable: true
      }
    ],
    //end of table related props
    momentDateTimeFormat: window.momentDateTimeFormat
  }),
  computed: {
    ...mapState("redirects", ["redirectList", "redirectListMeta"]),
    totalApplication () {
        return this.$store.state.redirects.redirectWidgetData.totalApplication
    },
    totalRedirected () {
        return this.$store.state.redirects.redirectWidgetData.totalRedirected
    },
    totalNotRedirected () {
        return this.$store.state.redirects.redirectWidgetData.totalNotRedirected
    }
  },
  methods: {
      rowClass(item, type) {
      if (item && type === 'row') {
        if (!item.redirect) {
          return 'text-dark row-bg-danger'
        }else{
          return 'text-dark row-bg-success'
        }
      } else {
        return null
      }
    },
      getTimeDifferent(start, end) {
          return Math.abs(Math.round(this.$moment.duration(this.$moment(start).diff(this.$moment(end))).asSeconds())) + 's'
      },
    searchSubmit() {},
    parseStatusLabel(status) {
      if (status == "sold") {
        return '<span class="badge badge-success"> Sold </span>';
      } else if (status == "reject") {
        return '<span class="badge badge-default"> Reject </span>';
      } else if (status == "error") {
        return '<span class="badge badge-danger"> Error </span>';
      }
    },
    clickDetail(data) {
      this.$router.push({ name: "redirect-view", params: { id: data.id } });
    },
    editRedirect(data) {
      this.$router.push({ name: "redirect-edit", params: { id: data.id } });
    },
    deleteRedirect(data) {
      let self = this;
      this.$swal({
        title: `Are you sure you want to delete Redirect ${data.first_name} ${data.last_name}?`,
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Delete"
      }).then(result => {
        if (result.value) {
          self.$store.dispatch("redirects/redirectDelete", {
            id: data.id
          });
          self.$snotify.success('Redirect Deleted','Success!');
        }
      });
    },
    viewRedirect() {},
    loadRedirects() {
      this.$store.dispatch("redirects/redirectList", this.tableMeta);
      console.log(this.redirectWidgetData)
    }
  }
};
</script>
