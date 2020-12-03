<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">
      Panda Channels
      <b-btn v-if="ability.can('create','Buyer-panda-channel')" variant="btn btn-primary" class="float-right" @click="createBuyerPandaChannel">
        <i class="fas fa-plus-circle"></i> Add New
      </b-btn>
    </h4>

    <b-card>
      <hr class="border-light m-0" />

      <div class="table-responsive">
        <b-table
          :fields="pandaChannelFields"
          :items="buyerPandaChannelList"
          :show-empty="true"
          :empty-text="'Panda channels data not available.'"
        >
          <template v-slot:cell(buyer_id)="data">{{ data.item.buyer.name }}</template>

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
              v-if="ability.can('update','Buyer-panda-channel')"
              @click="editBuyerPandaChannel(data.item)"
              variant="outline-dark btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Edit"
            >
              <i class="fas fa-pencil-alt"></i>
            </b-btn>

            <b-btn
              v-if="ability.can('delete','Buyer-panda-channel')"
              @click="deleteBuyerPandaChannel(data.item)"
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
  name: "BuyerPandaChannelList",
  created() {
    this.loadBuyerPandaChannelList();
  },
  data: () => ({
    pandaChannelFields: [
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
      {
        key: "buyer_id",
        label: "Buyer Name",
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
        key: "price",
        label: "Price",
        sortable: false,
        tdClass: "align-middle"
      },
      {
        key: "api_key",
        label: "Api Key",
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
    ...mapState("buyerPandaChannels", ["buyerPandaChannelList"]),
    ability() {
      return window.ability;
    },
  },
  methods: {
    loadBuyerPandaChannelList() {
      this.$store.dispatch("buyerPandaChannels/buyerPandaChannelList");
    },
    createBuyerPandaChannel() {
      this.$router.push({ name: "buyer-panda-channel-create" });
    },
    editBuyerPandaChannel(buyer) {
      this.$router.push({ name: "buyer-panda-channel-edit", params: { id: buyer.id } });
    },
    deleteBuyerPandaChannel(buyer) {
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
          self.$store.dispatch("buyerPandaChannels/buyerPandaChannelDelete", {
            id: buyer.id
          });
          self.$snotify.success('Panda Channel Deleted','Success!');
        }
      });
    }
  }
};
</script>
