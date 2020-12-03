<template>
  <div>
    <div class="table-responsive">
      <b-table
        :fields="platChannelFields"
        :items="buyerPlatChannelBuyerList"
        :show-empty="true"
        :empty-text="'Plat channels data not available.'"
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

        <template v-slot:cell(price)="data">${{ data.item.price }}</template>

        <!-- A custom formatted column -->
        <template v-slot:cell(actions)="data">
          <b-btn
            @click="editBuyerPlatChannel(data.item)"
            variant="outline-dark btn-xs icon-btn md-btn-flat"
            v-b-tooltip.hover
            title="Edit"
          >
            <i class="fas fa-pencil-alt"></i>
          </b-btn>

          <b-btn
            @click="deleteBuyerPlatChannel(data.item)"
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
            v-model="buyerPlatChannelBuyerListMeta.perPage"
            :options="[10, 25, 50, 100]"
          />
        </b-form>
      </div>
      <div class="col">
        <b-pagination
          align="right"
          v-model="buyerPlatChannelBuyerListMeta.currentPage"
          :total-rows="buyerPlatChannelBuyerListMeta.total"
          :per-page="buyerPlatChannelBuyerListMeta.perPage"
          aria-controls="buyer-list-table"
        ></b-pagination>
      </div>
    </div>

  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "PlatChannelBuyerList",
  props: ["buyerId"],
  created() {
    this.loadBuyerPlatChannelList();
  },
  data: () => ({
    platChannelFields: [
      {
        key: "channel_key",
        label: "Channel UUID",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "channel_name",
        label: "Channel Name",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "plat_channel_id",
        label: "Plat Channel ID",
        sortable: true,
        tdClass: "align-middle"
      },
      {
        key: "plat_channel_password",
        label: "Plat Channel Password",
        sortable: true,
        tdClass: "align-middle"
      },
      {
        key: "price",
        label: "Price",
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
    ...mapState("buyerPlatChannels", ["buyerPlatChannelBuyerList","buyerPlatChannelBuyerListMeta"])
  },
  watch: {
    'buyerPlatChannelBuyerListMeta.currentPage'(){
      this.loadBuyerPlatChannelList();
    },
    'buyerPlatChannelBuyerListMeta.perPage'(){
      this.resetBuyerPlatChannelBuyerListPage();
      this.loadBuyerPlatChannelList();
    }
  },
  methods: {
    loadBuyerPlatChannelList() {
      this.$store.dispatch("buyerPlatChannels/buyerPlatChannelBuyerList", {
        buyer_id: this.buyerId
      });
    },
    async resetBuyerPlatChannelBuyerListPage(){
      await this.$store.dispatch("buyerPlatChannels/resetBuyerPlatChannelBuyerListPage");
    },
    createBuyerPlatChannel() {
      this.$router.push({
        name: "buyer-plat-channel-create",
        params: { redirectBuyerId: this.buyerId }
      });
    },
    editBuyerPlatChannel(buyer) {
      this.$router.push({
        name: "buyer-plat-channel-edit",
        params: { id: buyer.id, redirectBuyerId: this.buyerId }
      });
    },
    deleteBuyerPlatChannel(buyer) {
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
          self.$store.dispatch(
            "buyerPlatChannels/buyerPlatChannelBuyerDelete",
            {
              id: buyer.id
            }
          );
          self.$snotify.success("Plat Channel Deleted", "Success!");
        }
      });
    }
  }
};
</script>
