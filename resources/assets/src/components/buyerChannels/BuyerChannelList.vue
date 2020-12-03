<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">
      Vertical Channels
      <b-btn v-if="ability.can('create','Buyer-vertical-channel')" variant="btn btn-primary" class="float-right" @click="createChannel">
        <i class="fas fa-plus-circle"></i> Add New
      </b-btn>
    </h4>

    <b-card>
      <hr class="border-light m-0" />

      <div class="table-responsive">
        <b-table
          :fields="channelFields"
          :items="buyerChannelList"
          :show-empty="true"
          :empty-text="'Channel data not available.'"
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
              v-if="ability.can('update','Buyer-vertical-channel')"
              @click="editChannel(data.item)"
              variant="outline-dark btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Edit"
            >
              <i class="fas fa-pencil-alt"></i>
            </b-btn>

            <b-btn
              v-if="ability.can('delete','Buyer-vertical-channel')"
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
    </b-card>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "ChannelList",
  created() {
    this.loadChannelList();
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
        key: "buyer_id",
        label: "Buyer Name",
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
    ...mapState("buyerChannels", ["buyerChannelList"]),
    ability() {
      return window.ability;
    }
  },
  methods: {
    loadChannelList() {
      this.$store.dispatch("buyerChannels/buyerChannelList");
    },
    createChannel() {
      this.$router.push({ name: "buyer-vertical-channel-create" });
    },
    editChannel(buyer) {
      this.$router.push({ name: "buyer-vertical-channel-edit", params: { id: buyer.id } });
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
          self.$store.dispatch("buyerChannels/buyerChannelDelete", {
            id: buyer.id
          });
          self.$snotify.success('Channel Deleted','Success!');
        }
      });
    }
  }
};
</script>
