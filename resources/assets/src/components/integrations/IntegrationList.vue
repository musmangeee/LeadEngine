<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">
      Integrations
      <b-btn v-if="ability.can('create','Integration')" variant="btn btn-primary" class="float-right" @click="createIntegration">
        <i class="fas fa-plus-circle"></i> Add New
      </b-btn>
    </h4>

    <b-card>
      <hr class="border-light m-0" />


      <div class="table-responsive">
        <b-table
          :fields="integrationListFields"
          :items="integrationList"
          :show-empty="true"
          :empty-text="'Integrations data not available.'"
        >
          <template v-slot:cell(name)="data">{{ data.item.name }}</template>
          <template v-slot:cell(description)="data">
            <span v-if="data.item.post_url"> {{ data.item.post_url }} </span>
            <span v-else> - </span>
          </template>

          <template v-slot:cell(status)="data">
            <span
              v-if="data.item.status == 'active'"
              class="badge badge-success"
            >{{ data.item.status }}</span>
            <span
              v-if="data.item.status == 'inactive'"
              class="badge badge-secondary"
            >{{ data.item.status }}</span>
          </template>

          <template v-slot:cell(waf_key)="data">
            <span v-if="data.item.waf_key">{{ data.item.waf_key }}</span>
            <span v-else>-</span>
          </template>

          <!-- A custom formatted column -->
          <template v-slot:cell(actions)="data">
             <b-btn
              @click="viewIntegration(data.item)"
              variant="outline-dark btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="View"
            >
              <i class="fas fa-eye"></i>
            </b-btn>

            <b-btn
              v-if="ability.can('update','Integration')"
              @click="editIntegration(data.item)"
              variant="outline-dark btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Edit"
            >
              <i class="fas fa-pencil-alt"></i>
            </b-btn>

            <b-btn
              v-if="ability.can('delete','Integration')"
              @click="deleteIntegration(data.item)"
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
  name: "IntegrationList",
  created() {
    this.loadIntegrationList();
  },
  data: () => ({
    perPage: 10,
    totalItems: 10,
    totalPages: 0,
    integrationListFields: [
      {
        key: "name",
        label: "Integration Name",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "post_url",
        label: "POST URL",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "post_method",
        label: "POST Method",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "actions",
        label: "Actions",
        sortable: false,
        tdClass: "text-nowrap align-middle text-center"
      }
    ]
  }),
  computed: {
    ...mapState("integrations", ["integrationList"]),
    ability() {
      return window.ability;
    }
  },
  methods: {
    loadIntegrationList() {
      this.$store.dispatch("integrations/integrationList");
    },
    createIntegration() {
      this.$router.push({ name: "integration-create" });
    },
    editIntegration(integration) {
      this.$router.push({ name: "integration-edit",  params: { id: integration.id } });
    },
    viewIntegration(integration) {
      this.$router.push({ name: "integration-view",  params: { id: integration.id } });
    },
    deleteIntegration(integration) {
      let self = this;
      this.$swal({
        title: `Are you sure you want to delete Integration ${integration.name}?`,
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Delete"
      }).then(result => {
        if (result.value) {
          self.$store.dispatch("integrations/integrationDelete", {
            id: integration.id
          });
          self.$snotify.success('Integration Deleted','Success!');
        }
      });
    }
  }
};
</script>
