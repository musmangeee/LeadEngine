<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">
      Plat Channels
      <b-btn v-if="ability.can('create','Buyer-plat-channel')" variant="btn btn-primary" class="float-right" @click="createBuyerPlatChannel">
        <i class="fas fa-plus-circle"></i> Add New
      </b-btn>
    </h4>

    <b-card>
      <hr class="border-light m-0" />

      <div class="table-responsive">
        <b-table
          :fields="platChannelFields"
          :items="buyerPlatChannelList"
          :show-empty="true"
          :empty-text="'Plat channels data not available.'"
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
              @click="editBuyerPlatChannel(data.item)"
              variant="outline-dark btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Edit"
            >
              <i class="fas fa-pencil-alt"></i>
            </b-btn>

            <b-btn
              v-if="ability.can('delete','Buyer-panda-channel')"
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
    </b-card>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "PlatChannelList",
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
        key: "buyer_id",
        label: "Buyer Name",
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
    ...mapState("buyerPlatChannels", ["buyerPlatChannelList"]),
    ability() {
      return window.ability;
    },
  },
  methods: {
    loadBuyerPlatChannelList() {
      this.$store.dispatch("buyerPlatChannels/buyerPlatChannelList");
    },
    createBuyerPlatChannel() {
      this.$router.push({ name: "buyer-plat-channel-create" });
    },
    editBuyerPlatChannel(buyer) {
      this.$router.push({ name: "buyer-plat-channel-edit", params: { id: buyer.id } });
    },
    deleteBuyerPlatChannel(buyer) {
      let self = this;
      this.$swal({
        title: `Are you sure you want to delete selected Plat Channel?`,
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Delete"
      }).then(result => {
        if (result.value) {
          self.$store.dispatch("buyerPlatChannels/buyerPlatChannelDelete", {
            id: buyer.id
          });
          self.$snotify.success('Plat Channel Deleted','Success!');
        }
      });
    }
  }
};
</script>
