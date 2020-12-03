<template>
  <div>
    <h4 class="font-weight-bold py-3">
      Integration
      <b-btn variant="btn btn-primary" class="float-right" @click="editProvider(id)">
        <i class="fas fa-pencil-alt"></i> Edit
      </b-btn>

    </h4>

    <div class="row mt-3">
      <div class="col">
        <div class="card mb-4 h-100">
          <h6 class="card-header with-elements">
            <div class="card-header-title">Integration Details</div>
          </h6>
          <div class="card-body">
            <b-row>
              <b-col>
                <b-form-row>
                  <b-form-group label="Status" class="text-muted">
                    <label>
                      <span
                        v-if="integrationDetail.is_enable == true"
                        class="badge badge-success"
                      >Enabled</span>
                      <span
                        v-if="integrationDetail.is_enable == false"
                        class="badge badge-secondary"
                      >Disabled</span>
                    </label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Integration Name" class="text-muted">
                    <label class="text-dark">{{ integrationDetail.name }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Integration URL" class="text-muted">
                    <label class="text-dark">{{ integrationDetail.post_url }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Integration Method" class="text-muted">
                    <label class="text-dark">{{ integrationDetail.post_method }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Content Type" class="text-muted">
                    <label class="text-dark">{{ integrationDetail.post_content_type }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Wait Timeout (in seconds)" class="text-muted">
                    <label class="text-dark">{{ integrationDetail.wait_timeout }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Content" class="text-muted col-12">
                    <div class="card">
                        <div class="card-body">
                            <pre v-if="integrationDetail.post_content_type == 'json' || integrationDetail.post_content_type == 'xml'">{{ integrationDetail.post_body }}</pre>
                            <div class="table-responsive">
                                <table class="table table-striped" v-if="integrationDetail.post_content_type == 'form-data'">
                                    <thead>
                                        <tr>
                                            <th>Key</th><th>Value</th><th>Custom Field</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in JSON.parse(integrationDetail.post_body)">
                                            <td>{{ item.key }}</td>
                                            <td>{{ item.value }}</td>
                                            <td><i class="ion ion-md-checkmark text-success" v-if="item.type == 'custom'" /></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Status Response" class="text-muted">
                    <label class="text-dark">{{ integrationDetail.status_response }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Message Response" class="text-muted">
                    <label class="text-dark">{{ integrationDetail.message_response }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Price Response" class="text-muted">
                    <label class="text-dark">{{ integrationDetail.price_response }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Redirect Response" class="text-muted">
                    <label class="text-dark">{{ integrationDetail.redirect_response }}</label>
                  </b-form-group>
                </b-form-row>
              </b-col>
            </b-row>
          </div>


        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import vkbeautify from "vkbeautify";

export default {
  name: "IntegrationView",
  props: ["id"],
  created() {
    if (this.id) {
      this.loadIntegrationDetail(this.id);
    }
  },
  methods: {
    loadIntegrationDetail(id) {
      this.$store.dispatch("integrations/integrationDetail", { id: id }).then(() => {
        if(this.integrationDetail.post_content_type == 'xml'){
            this.$set(this.integrationDetail, 'post_body', vkbeautify.xml(vkbeautify.xmlmin(this.integrationDetail.post_body, true)))
        }
        if(this.integrationDetail.post_content_type == 'json'){
            this.integrationDetail.post_body = vkbeautify.json(this.integrationDetail.post_body, 4)
        }
      });
    },
    editProvider(id){
      this.$router.push({ name: "integration-edit",  params: { id: id } });
    }
  },
  computed: {
    ...mapState("integrations", ["integrationDetail"])
  }
};
</script>

<style>
</style>
