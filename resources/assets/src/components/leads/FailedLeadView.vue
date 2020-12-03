<template>
  <div>
    <h4 class="font-weight-bold py-3">Failed Lead Detail</h4>

    <b-breadcrumb :items="breadcrumbItems" />

    <div class="row mt-3">
      <div class="col">
        <div class="card mb-4 h-100">
          <h6 class="card-header with-elements">
            <div class="card-header-title">Details</div>
          </h6>
          <div class="card-body">
            <b-row>
              <b-col>
                <b-form-row>
                  <b-form-group label="IP Address" class="text-muted">
                    <label class="text-dark">
                      {{ failedLeadDetail.ip_address }}
                    </label>
                  </b-form-group>
                </b-form-row>
              </b-col>
              <b-col></b-col>
            </b-row>
            <b-row>
              <b-col>
                <b-form-row>
                  <b-form-group label="Date Time" class="text-muted">
                    <label class="text-dark">
                      {{ failedLeadDetail.created_at }}
                    </label>
                  </b-form-group>
                </b-form-row>
              </b-col>
              <b-col></b-col>
            </b-row>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col">
        <div class="card mb-4 h-100">
          <h6 class="card-header with-elements">
            <div class="card-header-title">Reason</div>
          </h6>
          <div class="card-body">
            <pre><code>{{ formatJson(failedLeadDetail.reason) }}</code></pre>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col">
        <div class="card mb-4 h-100">
          <h6 class="card-header with-elements">
            <div class="card-header-title">Request Body</div>
          </h6>
          <div class="card-body">
            <pre><code>{{ formatXML(failedLeadDetail.request_body) }}</code></pre>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import vkbeautify from "vkbeautify";

export default {
  name: "FailedLeadView",
  props: ["id"],
  components: {
  },
  created() {
    this.$store.dispatch("leads/failedLeadDetail", { id: this.id });
  },
  data: () => ({
    postInfoId: null,
    breadcrumbItems: [
      {
        text: "Leads",
        to: { name: "lead-list" }
      },
      {
        text: "View",
        active: true
      }
    ],
    stateOptions: []
  }),
  computed: {
    ...mapGetters("leads", ["failedLeadDetail"])
  },
  methods: {
      formatJson(string){
          try{
            let result = vkbeautify.json(string, 4)
            return result
          }catch(e){
              return string
          }
      },
      formatXML(string){
          try{
            let result = vkbeautify.xml(string)
            return result
          }catch(e){
            return string
          }
      }
  }
};
</script>

<style>
</style>
