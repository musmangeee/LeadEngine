<template>
  <div>
    <h4 class="font-weight-bold py-3">
      Provider
      <b-btn variant="btn btn-primary" class="float-right" @click="editProvider(id)">
        <i class="fas fa-pencil-alt"></i> Edit
      </b-btn>

    </h4>

    <div class="row mt-3">
      <div class="col">
        <div class="card mb-4 h-100">
          <h6 class="card-header with-elements">
            <div class="card-header-title">Provider Info</div>
          </h6>
          <div class="card-body">
            <b-row>
              <b-col>
                <b-form-row>
                  <b-form-group label="Status" class="text-muted">
                    <label>
                      <span
                        v-if="providerDetail.status == 'active'"
                        class="badge badge-success"
                      >{{ providerDetail.status }}</span>
                      <span
                        v-if="providerDetail.status == 'inactive'"
                        class="badge badge-secondary"
                      >{{ providerDetail.status }}</span>
                    </label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Provider Name" class="text-muted">
                    <label class="text-dark">{{ providerDetail.name }}</label>
                  </b-form-group>
                </b-form-row>

                <b-form-row>
                  <b-form-group label="Provider Description" class="text-muted">
                    <label class="text-dark">{{ providerDetail.description }}</label>
                  </b-form-group>
                </b-form-row>
              </b-col>
            </b-row>
          </div>
        </div>
      </div>
    </div>


    <div class="row mt-3">
      <div class="col">
        <div class="card mb-4 h-100">
          <h6 class="card-header with-elements">
            <div class="card-header-title">Delivery Schedule/Caps Info</div>
          </h6>
          <div class="card-body">

             <table class="table">
              <thead>
                <tr>
                  <th>Day Of The Week</th>
                  <th>Start Time</th>
                  <th>End Time</th>
                  <th>Daily Cap</th>
                  <th>Hourly Cap</th>
                </tr>
              </thead>

              <tbody v-if="providerDetail.delivery_schedules.length == 0">
                <tr>
                  <td colspan="5" class="text-center">No Delivery Schedule/Caps Data Available.</td>
                </tr>
              </tbody>
              <tbody v-if="providerDetail.delivery_schedules.length > 0">
                <tr v-bind:key="schedule.id" v-for="schedule in providerDetail.delivery_schedules">
                  <td>{{ schedule.day_of_week }}</td>
                  <td>{{ schedule.start_at }}</td>
                  <td>{{ schedule.end_at }}</td>
                  <td>{{ schedule.daily_cap }}</td>
                  <td>{{ schedule.hourly_cap }}</td>
                </tr>
              </tbody>

             </table>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "ProviderView",
  props: ["id"],
  created() {
    if (this.id) {
      this.loadProviderDetail(this.id);
    }
  },
  methods: {
    loadProviderDetail(id) {
      this.$store.dispatch("providers/providerDetail", { id: id }).then(() => {
        this.providerForm = { ...this.providerDetail };
      });
    },
    editProvider(id){
      this.$router.push({ name: "provider-edit",  params: { id: id } });
    }
  },
  computed: {
    ...mapState("providers", ["providerDetail"])
  }
};
</script>

<style>
</style>
