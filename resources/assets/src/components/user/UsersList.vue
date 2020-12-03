<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">
      Users
        <router-link to="/user/create"><b-btn variant="btn btn-primary" class="float-right"><i class="ion ion-md-add-circle"></i> Create New User</b-btn></router-link>
    </h4>

    <b-card no-body>

      <!-- Table controls -->
      <b-card-body>

        <b-row class="d-flex justify-content-between">
          <div class="col-lg-6">
            <b-form inline>
              <label for="perPage">Per Page</label>
              <b-select size="sm" v-model="perPage" :options="[10, 20, 30, 40, 50]" class="ml-1" />
            </b-form>
          </div>
          <div class="col-lg-6 mt-2 mt-lg-0">
              <button class="ml-1 btn btn-outline-primary icon-btn btn-sm d-inline-block float-right" 
                  @click="searchSubmit"><i class="ion ion-ios-search"></i></button>
              <b-input size="sm" placeholder="Search..." class="d-inline-block w-auto float-right" 
                  v-model="search" @keyup.native.enter="searchSubmit" />
          </div>
        </b-row>

      </b-card-body>
      <!-- / Table controls -->

      <!-- Table -->
      <hr class="border-light m-0">
      <div class="table-responsive">

        <b-table
          :items="myProvider"
          :fields="fields"
          :sort-by.sync="sortBy"
          :sort-desc.sync="sortDesc"
          :striped="true"
          :bordered="true"
          :current-page="currentPage"
          :per-page="perPage"
          :filter="filter"
          ref="table"
          class="card-table">

            <template v-slot:cell(photo)="row">
                <img height="60" :src="`/media/${row.item.photo_path}`" v-if="row.item.photo_path" />
                <img height="60" src="/images/avatar.png" v-if="!row.item.photo_path" />
            </template>

            <template v-slot:cell(name)="row">
                {{ row.item.first_name }} {{ row.item.last_name }}
            </template>

            <template v-slot:cell(roles)="row">
                <li v-for="role in row.item.roles" class="list-unstyled">
                    <span class="badge" :class="{ 'badge-primary': role.name == 'Admin', 'badge-success': role.name == 'User'}">{{ role.name }}</span>
                </li>
            </template>
            <template v-slot:cell(status)="row">
                <span class="badge" :class="{ 'badge-success' : row.item.status == 'active', 'badge-danger': row.item.status == 'disabled' }" style="text-transform: capitalize;">{{ row.item.status }}</span>
            </template>

          <template v-slot:cell(actions)="row">
              <router-link :to="`/user/edit/${row.item.id}`"><b-btn variant="outline-warning btn-xs icon-btn md-btn-flat" v-b-tooltip.hover title="Edit"><i class="ion ion-md-create"></i></b-btn></router-link>
            <b-dropdown variant="outline-dark btn-xs icon-btn md-btn-flat hide-arrow" :right="!isRTL">
              <template slot="button-content"><i class="ion ion-ios-settings"></i></template>
              <b-dropdown-item href="javascript:void(0)">View Profile</b-dropdown-item>
              <b-dropdown-item href="javascript:void(0)" @click="row.item.status == 'active' ? disableUser(row.item.id) : activateUser(row.item.id)">{{ row.item.status == 'active' ? 'Disable User' : 'Activate User' }}</b-dropdown-item>
              <!--<b-dropdown-item href="javascript:void(0)" @click="deleteUser(row.item.id)">Delete User</b-dropdown-item>-->
            </b-dropdown>
          </template>

          <template v-slot:cell(row-details)="row">
            <b-card>
              <b-row class="mb-2">
                <b-col sm="3" class="text-sm-right"><b>Email:</b></b-col>
                <b-col>{{ row.item.email }}</b-col>
              </b-row>

              <b-row class="mb-2">
                <b-col sm="3" class="text-sm-right"><b>Role:</b></b-col>
                <b-col>
                  <li v-for="role in row.item.roles" class="list-unstyled">
                    <span class="badge" :class="{ 'badge-primary': role.name == 'Admin', 'badge-success': role.name == 'User'}">{{ role.name }}</span>
                  </li>
                </b-col>
              </b-row>

              <b-row class="mb-2">
                <b-col sm="3" class="text-sm-right"><b>Status:</b></b-col>
                <b-col>
                  <span class="badge" :class="{ 'badge-success' : row.item.status == 'active', 'badge-danger': row.item.status == 'disabled' }" style="text-transform: capitalize;">{{ row.item.status }}</span>
                </b-col>
              </b-row>

              <b-row class="mb-2">
                <b-col>
                  <router-link :to="`/user/edit/${row.item.id}`"><b-btn variant="outline-warning btn-xs icon-btn md-btn-flat" v-b-tooltip.hover title="Edit"><i class="ion ion-md-create"></i></b-btn></router-link>
                  <b-dropdown variant="outline-dark btn-xs icon-btn md-btn-flat hide-arrow" :right="!isRTL">
                    <template slot="button-content"><i class="ion ion-ios-settings"></i></template>
                    <b-dropdown-item href="javascript:void(0)">View Profile</b-dropdown-item>
                    <b-dropdown-item href="javascript:void(0)" @click="row.item.status == 'active' ? disableUser(row.item.id) : activateUser(row.item.id)">{{ row.item.status == 'active' ? 'Disable User' : 'Activate User' }}</b-dropdown-item>
                    <!--<b-dropdown-item href="javascript:void(0)" @click="deleteUser(row.item.id)">Delete User</b-dropdown-item>-->
                  </b-dropdown>
                </b-col>
              </b-row>
            </b-card>
          </template>

          <template v-slot:cell(show_details)="row">
              <button type="button" class="btn btn-danger btn-circle"
                  @click="row.toggleDetails"
              >
                  <i :class="[row.detailsShowing ? 'fa-minus' : 'fa-plus', 'fa']"></i>
              </button>
          </template>
        </b-table>

      </div>

      <!-- Pagination -->
      <b-card-body class="pt-0 pb-3">

        <div class="row">
          <div class="col-sm text-sm-left text-center pt-3">
            <span class="text-muted" v-if="totalItems">Page {{ currentPage }} of {{ totalPages }}</span>
          </div>
          <div class="col-sm pt-3">
            <b-pagination class="justify-content-center justify-content-sm-end m-0"
              v-if="totalItems"
              v-model="currentPage"
              :total-rows="totalItems"
              :per-page="perPage"
              size="sm" />
          </div>
        </div>

      </b-card-body>
      <!-- / Pagination -->

    </b-card>
  </div>
</template>


<script>

import { isMobile } from 'mobile-device-detect';

export default {
  name: 'user-list',
  metaInfo: {
    title: 'Users'
  },
  components: {
  },
  created () {
  },
  data: () => ({
    // Options
    //dataUrl: 'api/listuser',
    searchKeys: ['email', 'name'],
    sortBy: 'id',
    sortDesc: false,
    perPage: 10,
    totalItems: 10,
    totalPages: 0,
    isMobile: isMobile,

    fields: /*isMobile ? 
    [
      { key: 'show_details', label: ' ', tdClass: 'text-nowrap align-middle text-center'},
      { key: 'id', label: 'ID', sortable: true, tdClass: 'align-middle', thStyle:{width: '10px !important'} },
      { key: 'photo', label: 'Photo', sortable: false, tdClass: 'align-middle', thStyle:{width: '60px !important'}},
      { key: 'name', label: 'Name', sortable: true, tdClass: 'align-middle' }
    ] : */
    [
      { key: 'id', label: 'ID', sortable: true, tdClass: 'align-middle', thStyle:{width: '10px !important'} },
      { key: 'photo', label: 'Photo', sortable: false, tdClass: 'align-middle', thStyle:{width: '60px !important'}},
      { key: 'email', label: 'Email', sortable: true, tdClass: 'align-middle' },
      { key: 'name', label: 'Name', sortable: true, tdClass: 'align-middle' },
      { key: 'roles', label: 'Roles', sortable: false, tdClass: 'align-middle' },
      { key: 'status', label: 'Status', sortable: false, tdClass: 'align-middle' },
      { key: 'actions', label: ' ', tdClass: 'text-nowrap align-middle text-center' }
    ],

    // Filters
    filter: '',
    search: '',

    usersData: [],
    originalUsersData: [],

    currentPage: 1,
    isBusy: false
  }),

  methods: {
    deleteUser (id) {
      let self = this
      this.$swal({
        title: `Are you sure you wan to delete this user Id#${id}?`,
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        //confirmButtonColor: '#3085d6',
        //cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.value) {
          window.axios.delete(`/api/user/${id}`, {
            id: id
          }).then((response) =>{
            this.$refs.table.refresh();
            self.$snotify.success('User Deleted.','Success!')

          }).error((error) => {
            self.$snotify.error('There was a problem deleting user.','Error!')
          })
        }
      })

    },
    disableUser (id) {
      let self = this
        window.axios.post('/api/user/disable', {
          id: id
        }).then((response) =>{
          this.$refs.table.refresh();
            self.$snotify.success('User Disabled','Success!')
        }).error((error) => {
            self.$snotify.error('There was a problem disabling user.','Error!')
        })
    },
    activateUser (id) {
      let self = this
      window.axios.post('/api/user/activate', {
        id: id
      }).then((response) =>{
        this.$refs.table.refresh();
          self.$snotify.success('User Activated','Success!')

      }).error((error) => {
          self.$snotify.error('There was a problem activating user','Error!')


      })
    },
    searchSubmit () {
      this.filter = this.search
    },
    myProvider (ctx) {
      let filter = '';
      if(ctx.filter){
        filter = ctx.filter
      }
      let promise = axios.get(`/api/listuser?page=${ctx.currentPage}&size=${ctx.perPage}&sort=${ctx.sortBy}|${ctx.sortDesc}&filter=${filter}`)

      // Must return a promise that resolves to an array of items
      return promise.then((response) => {
        // Pluck the array of items off our axios response
        let items = response.data.data
        this.totalItems = response.data.total
        this.totalPages = response.data.last_page
        // Must return an array of items or an empty array if an error occurred
        return(items || [])
      }).catch(error => {
        // Here we could override the busy state, setting isBusy to false
        // this.isBusy = false
        // Returning an empty array, allows table to correctly handle busy state in case of error
        return []
      })
    },

  }
}
</script>
