<template>
  <div>
    <div class="table-responsive">
      <b-table
        :fields="channelFields"
        :items="buyerChannelBuyerList"
        :show-empty="true"
        :empty-text="'Channel data not available.'"
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
            @click="editChannel(data.item)"
            variant="outline-dark btn-xs icon-btn md-btn-flat"
            v-b-tooltip.hover
            title="Edit"
          >
            <i class="fas fa-pencil-alt"></i>
          </b-btn>

          <b-btn
            @click="deleteChannel(data.item)"
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
              v-model="buyerChannelBuyerListMeta.perPage"
              :options="[10, 25, 50, 100]"
            />
          </b-form>
        </div>
        <div class="col">
          <b-pagination
            align="right"
            v-model="buyerChannelBuyerListMeta.currentPage"
            :total-rows="buyerChannelBuyerListMeta.total"
            :per-page="buyerChannelBuyerListMeta.perPage"
            aria-controls="buyer-list-table"
          ></b-pagination>
        </div>
    </div>

  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "ChannelBuyerList",
  props: ["buyerId"],
  created() {
    this.loadChannelBuyerList();
  },
  data: () => ({
    channelFields: [
      {
        key: "channel_key",
        label: "Channel UUID",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "name",
        label: "Channel Name",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "channel_id",
        label: "Channel ID",
        sortable: false,
        tdClass: "align-middle"
      },
       {
        key: "waf_key",
        label: "WAF Key",
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
    ...mapState("buyerChannels", ["buyerChannelBuyerList","buyerChannelBuyerListMeta"])
  },
  watch: {
    'buyerChannelBuyerListMeta.currentPage'(){
      this.loadChannelBuyerList();
    },
    'buyerChannelBuyerListMeta.perPage'(){
      this.resetChannelBuyerListPage();
      this.loadChannelBuyerList();
    }
  },
  methods: {
    loadChannelBuyerList() {
      this.$store.dispatch("buyerChannels/buyerChannelBuyerList", {
        buyer_id: this.buyerId
      });
    },
    async resetChannelBuyerListPage() {
      await this.$store.dispatch("buyerChannels/resetbuyerChannelBuyerListPage");
    },
    createChannel() {
      this.$router.push({ name: "buyer-vertical-channel-create" });
    },
    editChannel(buyer) {      
      this.$router.push({
        name: "buyer-vertical-channel-edit",
        params: { id: buyer.id, redirectBuyerId: this.buyerId }
      });
    },
    deleteChannel(buyer) {
      let self = this;
      this.$swal({
        title: `Are you sure you want to delete selected Channel?`,
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Delete"
      }).then(result => {
        if (result.value) {
          self.$store.dispatch("buyerChannels/buyerChannelBuyerDelete", {
            id: buyer.id
          });
          self.$snotify.success("Channel Deleted", "Success!");
        }
      });
    }
  }
};
</script>
