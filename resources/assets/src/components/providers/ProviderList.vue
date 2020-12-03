<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">
      Providers
      <b-btn v-if="ability.can('create','Provider')" variant="btn btn-primary" class="float-right" @click="createProvider">
        <i class="fas fa-plus-circle"></i> Add New
      </b-btn>
    </h4>

    <b-card>
      <hr class="border-light m-0" />

      <div class="table-responsive">
        <b-table
          id="provider-list-table"
          :fields="providerListFields"
          :items="providerList"
          :show-empty="true"
          :empty-text="'Providers data not available.'"
        >
          <template v-slot:cell(name)="data">{{ data.item.name }}</template>
          <template v-slot:cell(description)="data">
            <span v-if="data.item.description"> {{ data.item.description }} </span>
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
              @click="viewProvider(data.item)"
              variant="outline-dark btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="View"
            >
              <i class="fas fa-eye"></i>
            </b-btn>

            <b-btn
              v-if="ability.can('update','Provider')"
              @click="editProvider(data.item)"
              variant="outline-dark btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Edit"
            >
              <i class="fas fa-pencil-alt"></i>
            </b-btn>

            <b-btn
              v-if="ability.can('read','Provider')"
              @click="showPostInfo(data.item)"
              variant="outline-dark btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Post Instruction"
            >
              <i class="far fa-paper-plane"></i>
            </b-btn>

            <b-btn
              v-if="ability.can('delete','Provider')"
              @click="deleteProvider(data.item)"
              variant="outline-danger btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Delete"
            >
              <i class="fas fa-trash"></i>
            </b-btn>
          </template>
        </b-table>
      </div>


       <div class="row">
        <div class="col">
          <b-form inline class="pt-1">
            <label>Per Page:</label>
            <b-select
              class="ml-1"
              v-model="providerListMeta.perPage"
              :options="[10, 25, 50, 100]"
            />
          </b-form>
        </div>
        <div class="col">
          <b-pagination
            align="right"
            v-model="providerListMeta.currentPage"
            :total-rows="providerListMeta.total"
            :per-page="providerListMeta.perPage"
            aria-controls="provider-list-table"
          ></b-pagination>
        </div>
      </div>

     
    </b-card>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "ProviderList",
  components: {
  },
  created() {
    this.loadProviderList();
  },
  data: () => ({
    postInfoProvider: {},
    providerListFields: [
      {
        key: "provider_key",
        label: "Provider UUID",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "name",
        label: "Provider Name",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "description",
        label: "Provider Description",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "status",
        label: "Status",
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
    ...mapState("providers", ["providerList","providerListMeta"]),
    ability() {
      return window.ability;
    }
  },
  watch: {
    'providerListMeta.currentPage'(){
      this.loadProviderList();
    },
    'providerListMeta.perPage'(){
      this.resetProviderListPage();
      this.loadProviderList();
    }
  },
  methods: {
    showPostInfo(provider){
        window.open("providers/"+provider.id+"/post-instruction", "_blank");
    },
    
    loadProviderList() {
      this.$store.dispatch("providers/providerList");
    },
    async resetProviderListPage() {
      await this.$store.dispatch("providers/resetProviderListPage");
    },
    createProvider() {
      this.$router.push({ name: "provider-create" });
    },
    editProvider(provider) {
      this.$router.push({ name: "provider-edit",  params: { id: provider.id } });
    },
    viewProvider(provider) {
      this.$router.push({ name: "provider-view",  params: { id: provider.id } });
    },
    deleteProvider(provider) {
      let self = this;
      this.$swal({
        title: `Are you sure you want to delete Provider ${provider.name}?`,
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Delete"
      }).then(result => {
        if (result.value) {
          self.$store.dispatch("providers/providerDelete", {
            id: provider.id
          });
          self.$snotify.success('Provider Deleted','Success!');
        }
      });
    }
  }
};
</script>
