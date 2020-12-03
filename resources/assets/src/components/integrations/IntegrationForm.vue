<template>
  <div>
    <h4 class="font-weight-bold py-3">Integration</h4>

    <b-card>
      <div>
        <b-form-group label="Enable" class="col-lg-12">
            <label class="switcher switcher-primary">
            <input type="checkbox" class="switcher-input" v-model="integrationForm.is_enable" />
            <span class="switcher-indicator">
                <span class="switcher-yes text-primary"></span>
                <span class="switcher-no"></span>
            </span>
            <span class="switcher-label" v-if="integrationForm.is_enable">Active</span>
            <span class="switcher-label" v-if="!integrationForm.is_enable">Inactive</span>
            </label>
        </b-form-group>

        <b-form-group label="Channel" class="col-6">
            <b-select v-model="integrationForm.channel">
                <option value="vertical">Vertical</option>
                <option value="panda">Panda</option>
                <option value="plat">Plat</option>
            </b-select>
            <div
            v-if="!$v.integrationForm.channel.required && $v.integrationForm.channel.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
            >Channel is required.</div>
        </b-form-group>

        <b-form-group label="Integration Name" class="col-6">
            <b-input type="text" v-model="integrationForm.name"></b-input>
            <div
            v-if="!$v.integrationForm.name.required && $v.integrationForm.name.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
            >Name field is required.</div>
        </b-form-group>

        <b-form-group label="Integration URL" class="col-6">
            <b-input type="text" v-model="integrationForm.post_url"></b-input>
            <div
            v-if="!$v.integrationForm.post_url.required && $v.integrationForm.post_url.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
            >Integration URL field is required.</div>
            <div
            v-if="!$v.integrationForm.post_url.url && $v.integrationForm.post_url.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
            >Integration URL must be a valid URL.</div>
        </b-form-group>

        <b-form-group label="Integration Method" class="col-6">
            <b-select v-model="integrationForm.post_method">
                <option value="get">GET</option>
                <option value="post">POST</option>
            </b-select>
            <div
            v-if="!$v.integrationForm.post_method.required && $v.integrationForm.post_method.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
            >Integration Method field is required.</div>
        </b-form-group>

        <b-form-group label="Content Type" class="col-6">
            <b-select v-model="integrationForm.post_content_type">
                <option value="form-data">Form Data</option>
                <option value="json">JSON</option>
                <option value="xml">XML</option>
            </b-select>
            <div
            v-if="!$v.integrationForm.post_content_type.required && $v.integrationForm.post_content_type.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
            >Content Type field is required.</div>
        </b-form-group>

        <div v-if="integrationForm.post_content_type == 'xml' || integrationForm.post_content_type == 'json'">
            <span v-for="column in applicationColumns"
                class="badge badge-primary mr-2"
                @click="copyToClipboard(`[${column}]`)"
                v-b-tooltip.hover
                title="Click to copy"
            >[{{column}}]</span>

            <b-form-group label="Content" class="col-12">
                <b-form-textarea
                id="textarea"
                v-model="integrationForm.post_body"
                placeholder="Description"
                rows="10"
                ></b-form-textarea>
                <div
                v-if="!$v.integrationForm.post_body.required && $v.integrationForm.post_body.$dirty"
                class="invalid-feedback"
                style="display: inline-block;"
                >Content field is required.</div>
            </b-form-group>

            <div class="col-12 mb-5"><b-btn @click="beautify" class="btn btn-xs btn-success float-right">Beautify Content</b-btn></div>
        </div>

        <div class="card" v-if="integrationForm.post_content_type == 'form-data'">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="col-12 text-right">
                            <b-btn href="#" @click="addFormData" class="btn btn-primary">Add More</b-btn>
                        </div>
                        <div class="row">
                            <label class="col-4">Key</label>
                            <label class="col-4">Value</label>
                        </div>
                        <div class="row" v-for="(item, index) in integrationForm.post_form_datas">
                            <b-form-group class="col-5">
                                <b-input type="text" v-model="item.key">{{ item.key }}</b-input>
                            </b-form-group>
                            <b-form-group class="col-6">
                                <b-select v-if="item.type == 'normal'" type="text" v-model="item.value">
                                    <option v-for="option in applicationColumns" :value="option">{{ option }}</option>
                                </b-select>
                                <b-input v-if="item.type == 'custom'" type="text" v-model="item.value">{{ item.value }}</b-input>
                            </b-form-group>
                            <b-form-group class="col-1">
                                <a href="#" @click="changeFormDataType(index, $event)" class="ion ion-md-construct text-primary"></a>
                                <a href="#" @click="deleteFormData(index, $event)" class="ion ion-md-close-circle text-danger"></a>
                            </b-form-group>
                        </div>
                        <div
                        v-if="!$v.integrationForm.post_form_datas.required && $v.integrationForm.wait_timeout.$dirty"
                        class="invalid-feedback"
                        style="display: inline-block;"
                        >Form data is required if Form Data is selected in Content Type.</div>
                    </div>
                    <div class="col-4">
                        <span v-for="column in applicationColumns"
                        class="badge badge-primary mr-2"
                        @click="copyToClipboard(`[${column}]`)"
                        v-b-tooltip.hover
                        title="Click to copy"
                        >[{{column}}]</span>
                    </div>
                </div>
            </div>
        </div>

        <b-form-group label="Wait Timeout (in seconds)" class="col-6">
            <b-input type="text" v-model="integrationForm.wait_timeout"></b-input>
            <div
            v-if="!$v.integrationForm.wait_timeout.required && $v.integrationForm.wait_timeout.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
            >Wait Timeout field is required.</div>
            <div
            v-if="!$v.integrationForm.wait_timeout.numeric && $v.integrationForm.wait_timeout.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
            >Wait Timeout field must be in numeric.</div>
        </b-form-group>

        <hr class="mt-5" />

        <h1 class="mb-5">Responses </h1>

<b-form-row class="ml-2">
        <b-form-group label="Response Type" class="col-6">
            <b-select v-model="integrationForm.response_type">
                <option value="json">JSON</option>
                <option value="xml">XML</option>
            </b-select>
        </b-form-group>
        <b-form-group class="col-6">
            <b-btn class="btn btn-outline-primary icon-btn borderless" @click="showResponsesHelp"><span class="ion ion-ios-help-circle" style="font-size: 18px" /></b-btn>
        </b-form-group>
</b-form-row>

        <b-form-group label="Status Response" class="col-12">
            <b-input type="text" v-model="integrationForm.status_response" :placeholder="getResponsePlaceholder('status')"></b-input>
            <div
            v-if="!$v.integrationForm.status_response.required && $v.integrationForm.status_response.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
            >Status Response field is required.</div>
        </b-form-group>

        <b-form-group label="Message Response" class="col-12">
            <b-input type="text" v-model="integrationForm.message_response" :placeholder="getResponsePlaceholder('message')"></b-input>
            <div
            v-if="!$v.integrationForm.message_response.required && $v.integrationForm.message_response.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
            >Message Response field is required.</div>
        </b-form-group>

        <b-form-group label="Price Response" class="col-12">
            <b-input type="text" v-model="integrationForm.price_response" :placeholder="getResponsePlaceholder('price')"></b-input>
            <div
            v-if="!$v.integrationForm.price_response.required && $v.integrationForm.price_response.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
            >Price Response field is required.</div>
        </b-form-group>

        <b-form-group label="Redirect Response" class="col-12">
            <b-input type="text" v-model="integrationForm.redirect_response" :placeholder="getResponsePlaceholder('redirect')"></b-input>
            <div
            v-if="!$v.integrationForm.redirect_response.required && $v.integrationForm.redirect_response.$dirty"
            class="invalid-feedback"
            style="display: inline-block;"
            >Redirect Response field is required.</div>
        </b-form-group>
      </div>
    </b-card>

    <div class="text-right mt-3">
      <b-btn :disabled="isLoading" @click="saveIntegration" class="btn btn-primary">Save</b-btn>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import Vuelidate from "vuelidate";
import { required, requiredIf, numeric, url } from "vuelidate/lib/validators";
import vkbeautify from "vkbeautify";
import _ from 'lodash';
Vue.use(Vuelidate);

import moment from "moment";
import { mapState } from "vuex";

export default {
  name: "IntegrationForm",
  props: ["id"],
  components: {
  },
  created() {
      this.loadApplicationColumns()
    if (this.id) {
      this.loadIntegrationDetail(this.id);
    }
  },
  data: () => ({
    isLoading: false,
    integrationForm: {
      is_enable: 1,
      channel: "vertical",
      name: "",
      post_url: "",
      post_method: "post",
      post_content_type: "xml",
      post_body: "",
      wait_timeout: 30,
      response_type: "xml",
      status_response: "",
      message_response: "",
      price_response: "",
      redirect_response: "",
      post_form_datas: [
        {type:'normal', key:'', value:''},
        {type:'normal', key:'', value:''},
        {type:'normal', key:'', value:''},
        {type:'normal', key:'', value:''},
        {type:'normal', key:'', value:''},
      ]
    },
  }),
  computed: {
    ...mapState("integrations", ["integrationDetail"]),
    ...mapState("integrations", ["applicationColumns"])
  },
  methods: {
    getResponsePlaceholder(response){
        if(this.integrationForm.response_type == 'xml'){
            if(response == 'status'){
                return '/response/status'
            }

            if(response == 'message'){
                return '/response/message'
            }

            if(response == 'price'){
                return '/response/price'
            }

            if(response == 'redirect'){
                return '/response/redirect'
            }
        }

        if(this.integrationForm.response_type == 'json'){
            if(response == 'status'){
                return '$.response.status'
            }

            if(response == 'message'){
                return '$.response.message'
            }

            if(response == 'price'){
                return '$.response.price'
            }

            if(response == 'redirect'){
                return '$.response.redirect'
            }
        }
    },
    showResponsesHelp(){
        let sampleXml = '<?xml version = "1.0" encoding = "utf-8" ?><response><id>0001</id><status>sold</status><message></message><price>0.5</price><redirect><![CDATA[https://aimleadintake.com/lead/go/0001]]></redirect></response>';
        let sampleJson = '{"id": "0001","status": "sold","message": [],"price": "0.5","redirect": "https://aimleadintake.com/lead/go/0001"}'
        let html = ''
        if(this.integrationForm.response_type == 'xml'){
            html = 'The system uses XPath (XML Path Language) to extract a value from a XML document, below are an example on how to extract a value from a XML Document<br /><br />' +
            '<textarea class="col-12" rows=12 disabled style="font-size: 14px; resize: none">//Sample response\n\n'+vkbeautify.xml(sampleXml)+'</textarea>' +
            '<textarea class="col-12 mt-3" rows=3 disabled style="font-size: 14px; resize: none">//XPath expression to get status\n\n/response/status</textarea>' +
            '<textarea class="col-12 mt-3 mb-5" rows=3 disabled style="font-size: 14px; resize: none">//result\n\nsold</textarea>' +
            '<a href="https://www.w3schools.com/xml/xpath_syntax.asp" target="_blank">XPath Expressions</a><br />' +
            '<a href="http://xpather.com" target="_blank">XPath online testing tools</a>'
        }

        if(this.integrationForm.response_type == 'json'){
            html = 'The system uses JsonPath (JSON Path Language) to extract a value from a JSON structure, below are an example on how to extract a value from a JSON Structure<br /><br />' +
            '<textarea class="col-12" rows=12 disabled style="font-size: 14px; resize: none">//Sample response\n\n'+vkbeautify.json(sampleJson)+'</textarea>' +
            '<textarea class="col-12 mt-3" rows=3 disabled style="font-size: 14px; resize: none">//JsonPath expression to get status\n\n$.response.status</textarea>' +
            '<textarea class="col-12 mt-3 mb-5" rows=3 disabled style="font-size: 14px; resize: none">//result\n\nsold</textarea>' +
            '<a href="https://support.smartbear.com/alertsite/docs/monitors/api/endpoint/jsonpath.html" target="_blank">JsonPath Expressions</a><br />' +
            '<a href="https://jsonpath.com" target="_blank">JsonPath online testing tools</a>';
        }
        this.$swal({
            type: 'Responses Help',
            html: html
        })
    },
    changeFormDataType(index, e){
        e.preventDefault()
        console.log(this.integrationForm.post_form_datas[index].type)
        if (this.integrationForm.post_form_datas[index].type == 'normal'){
            this.$set(this.integrationForm.post_form_datas[index], 'type', 'custom');
            this.$set(this.integrationForm.post_form_datas[index], 'value', '');
            return
        }

        if (this.integrationForm.post_form_datas[index].type == 'custom'){
            this.$set(this.integrationForm.post_form_datas[index], 'type', 'normal');
            this.$set(this.integrationForm.post_form_datas[index], 'value', '');
            return
        }
    },
    addFormData(e){
        e.preventDefault();
        this.integrationForm.post_form_datas.push({ type: 'normal', key:'', value:''})
    },
    deleteFormData(index, e){
        e.preventDefault();
        this.integrationForm.post_form_datas.splice(index, 1)
    },
    beautify(){
        if(this.integrationForm.post_content_type == 'xml'){
            this.integrationForm.post_body = vkbeautify.xml(this.integrationForm.post_body)
        }
        if(this.integrationForm.post_content_type == 'json'){
            this.integrationForm.post_body = vkbeautify.json(this.integrationForm.post_body,4)
        }
    },
    loadApplicationColumns(){
        this.$store.dispatch("integrations/getApplicationColumns")
    },
    loadIntegrationDetail(id) {
      this.$store.dispatch("integrations/integrationDetail", { id: id }).then(() => {
        this.integrationForm = { ...this.integrationDetail };

        //If content type is form data, do json parse post_body to post_form_datas and set post_body to blank
        if(this.integrationForm.post_content_type == 'form-data'){
            this.$set(this.integrationForm, 'post_form_datas', JSON.parse(_.cloneDeep(this.integrationForm.post_body)))
            this.integrationForm.post_body = ''
        }
      });
    },
    copyToClipboard(message) {
      let self = this;
      this.$copyText(message).then(
        function(e) {
          self.$toasted.show("Copied to clipboard!", {
            position: "bottom-center",
            duration: 3000,
            type: "success",
            theme: "bubble"
          });
        },
        function(e) {
          self.$toasted.show("Failed copy to clipboard!", {
            position: "bottom-center",
            duration: 3000,
            type: "error",
            theme: "bubble"
          });
        }
      );
    },
    async saveIntegration() {
      this.isLoading = true;
      this.$v.$touch();
      var isValid = !this.$v.$invalid;
      if (isValid) {
        if (this.id != undefined) {
          //update the provider
          try {
            let data = { ...this.integrationForm };
            data.status = data.status == true ? "active" : "inactive";
            let response = await this.$store.dispatch(
              "integrations/integrationUpdate",
              {
                id: this.id,
                data: data
              }
            );
            this.$snotify.success("Update Integration Success.", "Success!");
            this.$router.push({ name: "integration-list" });
          } catch (e) {
            console.log(e);
            this.isLoading = false;
            this.$snotify.error(e.response.data.errors, "Error!");
          }
        } else {
          //create new provider
          try {
            let data = { ...this.integrationForm };
            let response = await this.$store.dispatch(
              "integrations/integrationCreate",
              {
                data: data
              }
            );
            this.$snotify.success("Create Integration Success.", "Success!");
            this.$router.push({ name: "integration-list" });
          } catch (e) {
            console.log(e.response.data.errors);
            if(e.response.status == 460){
                this.$snotify.error(e.response.data, "Error!",{
                    timeout: -1,
                    //position: 'centerCenter'
                });
                 this.isLoading = false;
                 return
            }
            this.$snotify.error(e.response.data.errors, "Error!");
            this.isLoading = false;
          }
        }
      } else {
        this.isLoading = false;
      }
    },
  },
  validations: {
    integrationForm: {
      channel: {
        required
      },
      name: {
        required
      },
      post_url: {
          required,
          url
      },
      post_method: {
          required
      },
      post_content_type: {
          required
      },
      post_body: {
          required: requiredIf(function(integrationForm){
              return this.integrationForm.post_content_type != 'form-data'
          }),
      },
      post_form_datas: {
          required: requiredIf(function(integrationForm){
              return this.integrationForm.post_content_type == 'form-data'
          }),
      },
      wait_timeout: {
          required,
          numeric
      },
      status_response: {
          required,
      },
      message_response: {
          required,
      },
      price_response: {
          required,
      },
      redirect_response: {
          required,
      }
    }
  }
};
</script>
