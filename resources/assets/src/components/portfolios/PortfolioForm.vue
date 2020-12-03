<template>
  <div>
    <h4 class="font-weight-bold py-3">Portfolio</h4>
    <b-breadcrumb :items="breadcrumbItems" />

    <b-card>
      <b-form-row>
        <b-form-group label="Status" class="col-lg-12">
          <label class="switcher switcher-primary">
            <input type="checkbox" class="switcher-input" v-model="portfolioForm.status" />
            <span class="switcher-indicator">
              <span class="switcher-yes text-primary"></span>
              <span class="switcher-no"></span>
            </span>
            <span class="switcher-label" v-if="portfolioForm.status">Active</span>
            <span class="switcher-label" v-if="!portfolioForm.status">Inactive</span>
          </label>
        </b-form-group>
      </b-form-row>

      <b-form-row>
        <b-form-group label="Portfolio Type" class="col-6">
          <b-form-select v-model="portfolioForm.route_type" :options="route_types"></b-form-select>
          <div
            v-if="!$v.portfolioForm.route_type.required && $v.portfolioForm.route_type.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
          >Route Type is required.</div>
        </b-form-group>
      </b-form-row>

      <b-form-row>
        <b-form-group label="Portfolio Name" class="col-6">
          <b-input type="text" v-model="portfolioForm.name"></b-input>
          <div
            v-if="!$v.portfolioForm.name.required && $v.portfolioForm.name.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
          >Name field is required.</div>
        </b-form-group>
      </b-form-row>

      <b-form-row>
        <b-form-group label="Portfolio Description" class="col-6">
          <b-form-textarea
            id="textarea"
            v-model="portfolioForm.description"
            placeholder="Description"
            rows="3"
            max-rows="6"
          ></b-form-textarea>
        </b-form-group>
      </b-form-row>
    </b-card>

    <div class="card mt-4" v-if="id">
      <h6 class="card-header with-elements">
        <div class="card-header-title">Portfolio Routing</div>
      </h6>



      <div class="card-body">

        <div class="row">
          <div class="alert alert-danger col" v-if="sumTrafficPercentage != 100 && portfolioForm.route_type == 'percentage'">
            The Total of Portfolio Routing Traffic Percentage must be 100%.
          </div>
        </div>

        <div class="row">
          <div class="col" style="border: 2px dashed #c5c5c5; padding: 10px; max-height: 600px; min-height: 600px; overflow: auto;">


            <div class="mb-3" v-for="buy in availableChannels" v-bind:key="buy.buyer_name">
              <h5> {{ buy.buyer_name }} </h5>

              <div v-if="buy.vertical.length > 0">
                  <h6>Vertical Channel</h6>
                  <draggable class="list-group" :list="buy.vertical" group="people">
                    <div
                      class="list-group-item mt-1"
                      v-for="(element) in buy.vertical"
                    :key="buy.buyer_name+'-vertical-'+element.id"
                    > <i class="fas fa-arrows-alt text-primary mr-1"></i> Vertical - {{ element.name }}  </div>
                  </draggable>
              </div>

              <div v-if="buy.plat.length > 0">
                <h6 class="mt-3">Plat</h6>
                <draggable class="list-group" :list="buy.plat" group="people">
                  <div
                    class="list-group-item mt-1"
                    v-for="(element) in buy.plat"
                    :key="buy.buyer_name+'-plat-'+element.id"
                  > <i class="fas fa-arrows-alt text-primary mr-1"></i> Plat - {{ element.channel_name }}  </div>
                </draggable>
              </div>

              <div v-if="buy.panda.length > 0">
                <h6 class="mt-3">Panda</h6>
                <draggable class="list-group" :list="buy.panda" group="people">
                  <div
                    class="list-group-item mt-1"
                    v-for="(element) in buy.panda"
                    :key="buy.buyer_name+'-panda-'+element.id"
                  > <i class="fas fa-arrows-alt text-primary mr-1"></i> Panda - {{ element.channels }}  </div>
                </draggable>
              </div>



            </div>

          </div>

          <div class="col">


            <draggable class="list-group" style="border: 2px dashed #c5c5c5; padding: 10px; max-height: 600px; min-height: 600px; overflow: auto;" :list="selectedChannels" group="people">
                <div
                  class="list-group-item mt-1"
                  v-for="(element) in selectedChannels"
                    :key="element.buyer_id+'-selected-'+element.id"
                >
                  <div class="row">
                    <div class="col-10">
                       <i class="fas fa-arrows-alt text-primary mr-1"></i>
                      <span v-if="element.channel_type == 'panda'">{{ capitalizeFirstLetter(element.channel_type) }} - {{ element.channels }}</span>
                      <span v-if="element.channel_type == 'vertical'">{{ capitalizeFirstLetter(element.channel_type) }} - {{ element.name }}</span>
                      <span v-if="element.channel_type == 'plat'">{{ capitalizeFirstLetter(element.channel_type) }} - {{ element.channel_name }}</span>
                    </div>
                    <div class="col-2 text-right">
                      <i class="fas fa-expand text-primary" @click="element.minimized = !element.minimized;" v-if="element.minimized && portfolioForm.route_type == 'percentage'"></i>
                      <i class="fas fa-compress text-primary" @click="element.minimized = !element.minimized;" v-if="!element.minimized && portfolioForm.route_type == 'percentage'"></i>
                    </div>

                  </div>

                  <b-input-group class="mt-1" prepend="% of Traffic" v-if="!element.minimized && portfolioForm.route_type == 'percentage'">
                    <b-form-input type="number" v-model="element.traffic_percentage"></b-form-input>
                  </b-input-group>
                </div>
            </draggable>
          </div>
        </div>
      </div>
    </div>

    <b-button :disabled="id && sumTrafficPercentage != 100 && portfolioForm.route_type == 'percentage'" @click="handleSubmit" class="mt-4 float-right" variant="primary">
      <span v-if="id">Update</span>
      <span v-else>Save</span>
    </b-button>
  </div>
</template>

<script>
import Vue from "vue";
import Vuelidate from "vuelidate";
import { mapState } from "vuex";
import { required, ipAddress } from "vuelidate/lib/validators";
Vue.use(Vuelidate);

import draggable from "vuedraggable";

export default {
  name: "PorfolioForm",
  props: ["id"],
  components: {
    draggable
  },
  // watch: {
  //   selectedChannels(newVal, oldVal){
  //     if(newVal.length > 0){
  //       newVal.forEach(channel => {
  //          this.availableChannels.forEach((buy, index) => {


  //            if(channel.buyer_name == buy.buyer_name){
  //              if(channel.routeable_type == 'App\\Models\\BuyerChannel'){
  //               let vertical = buy.vertical;
  //               console.log('new vertical');
  //               console.log(vertical);
  //             }

  //             if(channel.routeable_type == 'App\\Models\\BuyerPlatChannel'){
  //                 alert('plat')
  //                 buy.plat = buy.plat.filter(plat => {
  //                   return plat.routeable_id != channel.routeable_id && plat.routeable_type != channel.routeable_type
  //                 });
  //             }

  //             if(channel.routeable_type == 'App\\Models\\BuyerPandaChannel'){
  //               alert('panda')
  //               buy.panda = buy.panda.filter(panda => {
  //                 return panda.routeable_id != channel.routeable_id && panda.routeable_type != channel.routeable_type
  //               });
  //             }
  //            }





  //           console.log('new buy');
  //           console.log(buy);


  //         })


  //       });


  //     }
  //   }
  // },
  computed: {
    ...mapState("portfolios", ["portfolioDetail", "portfolioRouting"]),

    sumTrafficPercentage: function(){
      let total = this.selectedChannels.reduce((total, channel) => {
        return total + parseFloat(channel.traffic_percentage);
      },0);
      return total;
    },

    breadcrumbItems: function() {
      if (this.id) {
        return [
          {
            text: "Portfolio",
            to: { name: "portfolio-list" }
          },
          {
            text: "Edit",
            active: true
          }
        ];
      } else {
        return [
          {
            text: "Portfolio",
            to: { name: "portfolio-list" }
          },
          {
            text: "Create",
            active: true
          }
        ];
      }
    }
  },
  data: () => ({
    route_types: [
      { value: null, text: 'Select Your Portfolio Type' },
      {value: 'percentage', text: '% Percentage'},
      {value: 'round_robin', text: 'Round Robin'}
    ],
    portfolioForm: {
      status: true,
      route_type: null,
      name: "",
      description: ""
    },
    availableChannels: [],
    selectedChannels: [],
  }),

  created() {
    if (this.id) {
      this.loadPortfolioRouting();
      this.loadPortfolioDetail();
    }
  },

  validations() {
    return {
      portfolioForm: {
        name: {
          required
        },
        status: {
          required
        },
        route_type: {
          required
        }
      }
    };
  },

  methods: {
    capitalizeFirstLetter(string){
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    loadPortfolioDetail() {
      this.$store
        .dispatch("portfolios/portfolioDetail", { id: this.id })
        .then(() => {
          this.portfolioForm = this.portfolioDetail;
          this.portfolioForm.status =
            this.portfolioForm.status == "active" ? true : false;
          this.selectedChannels = this.portfolioDetail.routings;
        });
    },
    loadPortfolioRouting() {
      let self = this;
      this.$store.dispatch("portfolios/portfolioRouting").then(() => {
        this.availableChannels = [...this.portfolioRouting]
      })
    },
    async handleSubmit() {
      let self = this;
      this.$v.$touch();
      var isValid = !this.$v.$invalid;
      if (isValid) {
        if (this.id == undefined) {
          try {
            let data = { ...self.portfolioForm };
            data.status = data.status == true ? "active" : "inactive";

            let response = await this.$store.dispatch(
              "portfolios/portfolioCreate",
              {
                data: data
              }
            );
            self.$snotify.success("Create Portfolio Success.", "Success!");
            self.$router.push({ name: "portfolio-list" });
          } catch (e) {
            self.$snotify.error("Create Portfolio Failed.", "Error!");
          }
        } else {
          try {
            let data = { ...self.portfolioForm };
            data.status = data.status == true ? "active" : "inactive";

            let routings = [...self.selectedChannels]
            data.routings = routings;

            let response = await this.$store.dispatch(
              "portfolios/portfolioUpdate",
              {
                id: this.id,
                data: data
              }
            );
            self.$snotify.success("Update Portfolio Success.", "Success!");
            self.$router.push({ name: "portfolio-list" });
          } catch (e) {
            self.$snotify.error("Update Portfolio Failed.", "Error!");
          }
        }
      }
    }
  }
};
</script>
