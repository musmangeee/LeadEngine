<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">
      Buyers
      <b-btn v-if="ability.can('create','Buyer')" variant="btn btn-primary" class="float-right" @click="createBuyer">
        <i class="fas fa-plus-circle"></i> Add New
      </b-btn>
    </h4>

    <b-card>
      <hr class="border-light m-0" />

      <div class="table-responsive">
        <b-table
          id="buyer-list-table"
          :fields="buyerListFields"
          :items="buyerList"
          :show-empty="true"
          :empty-text="'Buyers data not available.'"
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
              v-if="ability.can('update','Buyer')"
              @click="editBuyer(data.item)"
              variant="outline-dark btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Edit"
            >
              <i class="fas fa-pencil-alt"></i>
            </b-btn>

            <b-btn
             v-if="ability.can('delete','Buyer')"
              @click="deleteBuyer(data.item)"
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
              v-model="buyerListMeta.perPage"
              :options="[10, 25, 50, 100]"
            />
          </b-form>
        </div>
        <div class="col">
          <b-pagination
            align="right"
            v-model="buyerListMeta.currentPage"
            :total-rows="buyerListMeta.total"
            :per-page="buyerListMeta.perPage"
            aria-controls="buyer-list-table"
          ></b-pagination>
        </div>
      </div>
      
    </b-card>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "BuyerList",
  created() {
    this.loadBuyerList();
  },
  data: () => ({
    buyerListFields: [
      {
        key: "buyer_key",
        label: "Buyer UUID",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "name",
        label: "Buyer Name",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "description",
        label: "Buyer Description",
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
    ...mapState("buyers", ["buyerList","buyerListMeta"]),
    ability() {
      return window.ability;
    },
  },
  watch: {
    'buyerListMeta.currentPage'(){
      this.loadBuyerList();
    },
    'buyerListMeta.perPage'(){
      this.resetBuyerListPage();
      this.loadBuyerList();
    }
  },
  methods: {
    loadBuyerList() {
      this.$store.dispatch("buyers/buyerList");
    },
    async resetBuyerListPage() {
      await this.$store.dispatch("buyers/resetBuyerListPage");
    },
    createBuyer() {
      this.$router.push({ name: "buyer-create" });
    },
    editBuyer(buyer) {
      this.$router.push({ name: "buyer-edit",  params: { id: buyer.id } });
    },
    deleteBuyer(buyer) {
      let self = this;
      this.$swal({
        title: `Are you sure you want to delete Buyer ${buyer.name}?`,
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Delete"
      }).then(result => {
        if (result.value) {
          self.$store.dispatch("buyers/buyerDelete", {
            id: buyer.id
          });
          self.$snotify.success('Buyer Deleted','Success!');
        }
      });
    }
  }
};
</script>
