<template>
  <div>
        <div class="table-responsive">
        <b-table
          :fields="pandaChannelBuyerFields"
          :items="buyerPandaChannelBuyerList"
          :show-empty="true"
          :empty-text="'Panda channels data not available.'"
        >
          <template v-slot:cell(provider_id)="data">{{ data.item.provider.name }}</template>

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

          <template v-slot:cell(price)="data">
            ${{ data.item.price }}
          </template>

          <!-- A custom formatted column -->
          <template v-slot:cell(actions)="data">
            <b-btn
              @click="editPandaChannel(data.item)"
              variant="outline-dark btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Edit"
            >
              <i class="fas fa-pencil-alt"></i>
            </b-btn>

            <b-btn
              @click="deletePandaChannel(data.item)"
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
              v-model="buyerPandaChannelBuyerListMeta.perPage"
              :options="[10, 25, 50, 100]"
            />
          </b-form>
        </div>
        <div class="col">
          <b-pagination
            align="right"
            v-model="buyerPandaChannelBuyerListMeta.currentPage"
            :total-rows="buyerPandaChannelBuyerListMeta.total"
            :per-page="buyerPandaChannelBuyerListMeta.perPage"
            aria-controls="buyer-list-table"
          ></b-pagination>
        </div>
      </div>

  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "PandaChannelBuyerList",
props: ["buyerId"],
  created() {
    this.loadBuyerPandaChannelBuyerList();
  },
  data: () => ({
    pandaChannelBuyerFields: [
     {
        key: "channel_key",
        label: "Channel UUID",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "channels",
        label: "Channel Name",
        sortable: false,
        tdClass: "align-middle"
      },
      /*{
        key: "tier",
        label: "Tier",
        sortable: true,
        tdClass: "align-middle"
      },
      {
        key: "portfolio",
        label: "Portfolio",
        sortable: false,
        tdClass: "align-middle"
      },*/
      {
        key: "api_key",
        label: "Api Key",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "price",
        label: "Price",
        sortable: true,
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
    ...mapState("buyerPandaChannels", ["buyerPandaChannelBuyerList","buyerPandaChannelBuyerListMeta"])
  },
  watch: {
    'buyerPandaChannelBuyerListMeta.currentPage'(){
      this.loadBuyerPandaChannelBuyerList();
    },
    'buyerPandaChannelBuyerListMeta.perPage'(){
      this.resetBuyerPandaChannelBuyerListPage();
      this.loadBuyerPandaChannelBuyerList();
    }
  },
  methods: {
    loadBuyerPandaChannelBuyerList() {
      this.$store.dispatch("buyerPandaChannels/buyerPandaChannelBuyerList", { buyer_id: this.buyerId });
    },
    async resetBuyerPandaChannelBuyerListPage() {
      await this.$store.dispatch("buyerPandaChannels/resetBuyerPandaChannelBuyerListPage");
    },
    createPandaChannel() {
      this.$router.push({ name: "buyer-panda-channel-create" });
    },
    editPandaChannel(buyer) {
      this.$router.push({ name: "buyer-panda-channel-edit", params: { id: buyer.id, redirectBuyerId: this.buyerId } });
    },
    deletePandaChannel(buyer) {
      let self = this;
      this.$swal({
        title: `Are you sure you want to delete selected Panda Channel?`,
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Delete"
      }).then(result => {
        if (result.value) {
          self.$store.dispatch("buyerPandaChannels/buyerPandaChannelBuyerDelete", {
            id: buyer.id
          });
          self.$snotify.success('Panda Channel Deleted','Success!');
        }
      });
    }
  }
};
</script>
