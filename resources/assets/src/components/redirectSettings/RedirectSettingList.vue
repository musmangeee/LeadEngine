<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">
      Redirect Settings
      <b-btn v-if="ability.can('create','Redirect-setting')" variant="btn btn-primary" class="float-right" @click="createRedirectSetting">
        <i class="fas fa-plus-circle"></i> Add New
      </b-btn>
    </h4>

    <b-card>
      <hr class="border-light m-0" />

      <div class="table-responsive">
        <b-table
          :fields="redirectSettingFields"
          :items="redirectSettingList"
          :show-empty="true"
          :empty-text="'No redirect settings available.'"
        >
          <template v-slot:cell(click_limit)="data">
            <span v-if="data.item.click_limit">{{ data.item.click_limit }}</span>
            <span v-else>-</span>
          </template>

          <template v-slot:cell(time_limit)="data">
            <span v-if="data.item.time_limit">{{ data.item.time_limit }}</span>
            <span v-else>-</span>
          </template>

          <template
            v-slot:cell(created_at)="data"
          >{{ data.item.created_at | moment(momentDateTimeFormat) }}</template>

          <!-- A custom formatted column -->
          <template v-slot:cell(actions)="data">
            <b-btn
              v-if="ability.can('update','Redirect-setting')"
              @click="editRedirectSetting(data.item)"
              variant="outline-dark btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Edit"
            >
              <i class="fas fa-pencil-alt"></i>
            </b-btn>

            <b-btn
              v-if="ability.can('delete','Redirect-setting')"
              @click="deleteRedirectSetting(data.item)"
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
  name: "RedirectSettingList",
  created() {
    this.loadRedirectSettingList();
  },
  data: () => ({
    redirectSettingFields: [
      {
        key: "click_limit",
        label: "Click Limit",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "time_limit",
        label: "Time Limit",
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
    ...mapState("redirectSettings", ["redirectSettingList"]),
    ability() {
      return window.ability;
    }
  },
  methods: {
    loadRedirectSettingList() {
      this.$store.dispatch("redirectSettings/redirectSettingList");
    },
    createRedirectSetting() {
      this.$router.push({ name: "redirect-settings-create" });
    },
    editRedirectSetting(data) {
      this.$router.push({
        name: "redirect-settings-edit",
        params: { id: data.id }
      });
    },
    deleteRedirectSetting(data) {
      let self = this;
      this.$swal({
        title: `Are you sure you want to delete selected Redirect Setting?`,
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Delete"
      }).then(result => {
        if (result.value) {
          self.$store.dispatch("redirectSettings/redirectSettingDelete", {
            id: data.id
          });
          self.$snotify.success("Redirect Setting Deleted", "Success!");
        }
      });
    }
  }
};
</script>

<style>
</style>