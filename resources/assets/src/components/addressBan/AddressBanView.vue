<template>
  <div>
    <h4 class="font-weight-bold py-3">IP/Host Bans</h4>

    <b-breadcrumb :items="breadcrumbItems" />

    <div class="row mt-3">
      <div class="col">
        <div class="card mb-4 h-100">
          <h6 class="card-header with-elements">
            <div class="card-header-title">Blocked IP/Host Info</div>
          </h6>
          <div class="card-body">
            <b-row>
              <b-col>
                <b-form-row>
                  <b-form-group label="IP Address" class="text-muted">
                     <label class="text-dark" v-if="addressBanDetail.ip_address">
                      {{ addressBanDetail.ip_address }}
                    </label>
                    <label class="text-dark" v-else>
                      -
                    </label>
                  </b-form-group>
                </b-form-row>


                 <b-form-row>
                  <b-form-group label="Hostname" class="text-muted">
                     <label class="text-dark" v-if="addressBanDetail.hostname">
                      {{ addressBanDetail.hostname }}
                    </label>
                     <label class="text-dark" v-else>
                      -
                    </label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Timestamp" class="text-muted">
                    <label class="text-dark">{{ addressBanDetail.created_at | moment(momentDateTimeFormat) }}</label>
                  </b-form-group>
                </b-form-row>
              </b-col>
              <b-col></b-col>
            </b-row>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "AddressBanView",
  props: ["id"],
  created() {
    this.loadAddressBanDetail();
  },
  data: () => ({
     breadcrumbItems: [
      {
        text: "IP/Host Bans",
        to: { name: "ip-bans" }
      },
      {
        text: "View",
        active: true
      }
    ],
      momentDateTimeFormat: window.momentDateTimeFormat

  }),
  computed: {
    ...mapState("addressBans", ["addressBanDetail"])
  },
  methods: {
    loadAddressBanDetail() {
      this.$store.dispatch("addressBans/addressBanDetail", { id: this.id });
    }
  }
};
</script>

<style>
</style>