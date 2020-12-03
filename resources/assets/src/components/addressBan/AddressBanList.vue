<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">
      IP/Host Bans
      <b-btn variant="btn btn-primary" class="float-right" @click="createAddressBan">
        <i class="fas fa-plus-circle"></i> Add New
      </b-btn>
    </h4>

    <b-card>
      <hr class="border-light m-0" />

      <div class="table-responsive">
        <b-table
          :fields="addressBanFields"
          :items="addressBanList"
          :show-empty="true"
          :empty-text="'No banned ip address / hostname available.'"
          @row-clicked="clickDetail"
        >
          <template v-slot:cell(ip_address)="data">
            <span v-if="data.item.ip_address"> {{ data.item.ip_address }} </span>
            <span v-else>-</span>
            
          </template>

          <template v-slot:cell(hostname)="data">
            <span v-if="data.item.hostname"> {{ data.item.hostname }} </span>
            <span v-else>-</span>
          </template>

          <template
            v-slot:cell(created_at)="data"
          >{{ data.item.created_at | moment(momentDateTimeFormat) }}</template>

          <!-- A custom formatted column -->
          <template v-slot:cell(actions)="data">
            <b-btn
              @click="editAddressBan(data.item)"
              variant="outline-dark btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Edit"
            >
              <i class="fas fa-pencil-alt"></i>
            </b-btn>

            <b-btn
              @click="deleteAddressBan(data.item)"
              variant="outline-danger btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Delete"
            >
              <i class="fas fa-trash"></i>
            </b-btn>
          </template>
        </b-table>
      </div>
    </b-card>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "AddressBanList",
  created() {
    this.loadAddressBanList();
  },
  data: () => ({
    addressBanFields: [
      {
        key: "ip_address",
        label: "IP Address",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "hostname",
        label: "Hostname",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "created_at",
        label: "Timestamp",
        sortable: true,
        tdClass: "align-middle"
      },
      {
        key: "actions",
        label: "Actions",
        sortable: false,
        tdClass: "text-nowrap align-middle text-center"
      }
    ],
    momentDateTimeFormat: window.momentDateTimeFormat
  }),
  computed: {
    ...mapState("addressBans", ["addressBanList", "addressBanDetail"])
  },
  methods: {
    clickDetail(data) {
      this.$router.push({ name: "ip-bans-view", params: { id: data.id } });
    },
    loadAddressBanList(){
      this.$store.dispatch("addressBans/addressBanList");
    },
    createAddressBan() {
        this.$router.push({ name: "ip-bans-create"});
    },
    deleteAddressBan(data) {
      let self = this;
      this.$swal({
        title: `Are you sure you want to delete selected IP Address / Hostname?`,
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Delete"
      }).then(result => {
        if (result.value) {
          self.$store.dispatch("addressBans/addressBanDelete", {
            id: data.id
          });
          self.$snotify.success('IP Address / Hostname Deleted','Success!');
        }
      });
    },
    editAddressBan(data) {
      this.$router.push({ name: "ip-bans-edit", params: { id: data.id }});
    }
  }
};
</script>

<style>
</style>