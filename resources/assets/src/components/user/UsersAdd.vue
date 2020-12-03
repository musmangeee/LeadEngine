<template>
  <div>

    <h4 class="text-muted font-weight-bold pt-3">
        Create New User
    </h4>
      <b-breadcrumb :items="breadcrumbItems"/>

    <b-tabs class="nav-tabs-top nav-responsive-sm mt-5">
      <b-tab title="Account" active>

        <hr class="border-light m-0">
        <b-card-body class="pb-2">

            <b-form-group label="Email" :invalid-feedback="errors.email ? errors.email[0] : null">
                <b-input :ref="'email'" v-model="userData.email" :state="errors.email ? 'invalid' : null" class="mb-1" />
            </b-form-group>


            <b-form-row>
                <b-form-group label="First Name" :invalid-feedback="errors.first_name ? errors.first_name[0] : null" class="col-sm-6">
                    <b-input v-model="userData.first_name" :state="errors.first_name ? 'invalid' : null" class="mb-1" />
                </b-form-group>
                <b-form-group label="Last Name" :invalid-feedback="errors.last_name ? errors.last_name[0] : null" class="col-sm-6">
                    <b-input v-model="userData.last_name" :state="errors.last_name ? 'invalid' : null" class="mb-1" />
                </b-form-group>
            </b-form-row>
        </b-card-body>
          <hr class="border-light m-0">
        <b-card-body class="pb-2">
            <b-form-row>
                <b-form-group label="Password" :invalid-feedback="errors.password ? errors.password[0] : null" class="col-sm-6">
                    <vue-password v-model="userData.password">
                        <div
                                slot="password-input"
                                slot-scope="props"
                                class="control has-icons-left"
                        >

                            <b-input :type="'password'" v-model="userData.password" :state="errors.password ? 'invalid' : null" @input="props.updatePassword(userData.password)" class="mb-1" />
                            <span class="icon is-small is-left">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                    </vue-password>
                    <div style="color: #d9534f">
                        {{ errors.password ? errors.password[0] : null }}
                    </div>

                </b-form-group>
                <b-form-group label="Password Confirm" :invalid-feedback="errors.passwordConfirm ? errors.passwordConfirm[0] : null" class="col-sm-6">
                    <b-input type="password" v-model="userData.passwordConfirm" :state="errors.passwordConfirm ? 'invalid' : null" />
                </b-form-group>
            </b-form-row>
            <div class="row justify-content-start ml-2 pb-4">
                <a href="#" @click.prevent="generateRandomPassword"><u>Generate Random Password</u></a>
            </div>

        </b-card-body>
        <hr class="border-light m-0">
        <b-card-body class="pb-2">

            <b-form-group label="Role">
                <b-select v-model="userData.role" :options="selectionRoles" />
            </b-form-group>

            <b-form-group label="Status">
                <b-select v-model="userData.status" :options="[{text:'Active',value:'active'}, {text:'Disabled',value:'disabled'}]" />
            </b-form-group>

            <b-form-group label="Time Zone">
                <b-select v-model="userData.timezone" :options="timezones" />
            </b-form-group>
        </b-card-body>

        <hr class="border-light m-0">
        <b-card-body class="pb-2">
            <b-form-group label="Photo">
                <b-form-row>
                    <div class="ml-md-2">
                    <croppa v-model="croppa"
                            :prevent-white-space="true"
                            :accept="'image/jpeg,image/png'"
                            :file-size-limit="2048000"
                            remove-button-color="black"
                            :show-loading="true"
                            :reverse-scroll-to-zoom="true"
                            :initial-image="initialImage"
                            :width="200"
                            :height="200"
                            :quality="1"
                            canvas-color="white"
                            @file-type-mismatch="onFileTypeMismatch"
                            @file-size-exceed="onFileSizeExceed">
                        ></croppa>
                    </div>
                    <div class="col-auto">* Supported image format (jpeg, png) <br />
                        * Zoom with mouse scroll in Desktop, or pinch with finger in Mobile <br />
                        * drag around to reposition by holding left click in Desktop, or with finger in Mobile</div>
                    <div class="col-auto"></div>
                </b-form-row>
            </b-form-group>
        </b-card-body>
        <hr class="border-light m-0">
          <b-card no-body class="mb-0">
              <b-card-header>
                  <a class="d-flex justify-content-between text-dark" href="javascript:void(0)" v-b-toggle.accordion1>
                      Customize Permissions
                      <div class="collapse-icon"></div>
                  </a>
              </b-card-header>
              <b-collapse id="accordion1" accordion="accordion1">
                  <b-card-body>

                    <div class="table-responsive">
                         <table class="table b-table card-table m-0">
                            <thead>
                              <tr>
                                <th> Module </th>
                                <th> View </th>
                                <th> Create </th>
                                <th> Edit </th>
                                <th> Delete </th>
                              </tr>
                            </thead>

                            <tbody>

                              <tr>
                                <td colspan="4"> <strong> Buyer Management </strong> </td>
                              </tr>
                              <template v-for="permission in permissions">
                                <template v-if="permission.module == 'Buyer' || permission.module == 'Buyer-panda-channel' || permission.module == 'Buyer-plat-channel' || permission.module == 'Buyer-vertical-channel'">
                                    <tr v-bind:key="permission.module">
                                      <td> {{ convertPermissionName(permission.module) }} </td>
                                      <td> <b-check v-model="permission.read.checked" :disabled="permission.read.disabled" class="px-2 m-0" /> </td>
                                      <td> <b-check v-model="permission.create.checked" :disabled="permission.create.disabled" class="px-2 m-0" /></td>
                                      <td> <b-check v-model="permission.update.checked" :disabled="permission.update.disabled" class="px-2 m-0" /></td>
                                      <td> <b-check v-model="permission.delete.checked" :disabled="permission.delete.disabled" class="px-2 m-0" /></td>
                                    </tr>
                                </template>
                              </template>

                              <tr>
                                <td colspan="4"> <strong> Lead Management </strong> </td>
                              </tr>
                              <template v-for="permission in permissions">
                                <template v-if="permission.module == 'Lead' || permission.module == 'Live-lead'">
                                    <tr v-bind:key="permission.module">
                                      <td> {{ convertPermissionName(permission.module) }} </td>
                                      <td> <b-check v-model="permission.read.checked" :disabled="permission.read.disabled" class="px-2 m-0" /> </td>
                                      <td> <b-check v-model="permission.create.checked" :disabled="permission.create.disabled" class="px-2 m-0" /></td>
                                      <td> <b-check v-model="permission.update.checked" :disabled="permission.update.disabled" class="px-2 m-0" /></td>
                                      <td> <b-check v-model="permission.delete.checked" :disabled="permission.delete.disabled" class="px-2 m-0" /></td>
                                    </tr>
                                </template>
                              </template>

                               <tr>
                                <td colspan="4"> <strong> Portfolio Management </strong> </td>
                              </tr>
                              <template v-for="permission in permissions">
                                <template v-if="permission.module == 'Portfolio'">
                                    <tr v-bind:key="permission.module">
                                      <td> {{ convertPermissionName(permission.module) }} </td>
                                      <td> <b-check v-model="permission.read.checked" :disabled="permission.read.disabled" class="px-2 m-0" /> </td>
                                      <td> <b-check v-model="permission.create.checked" :disabled="permission.create.disabled" class="px-2 m-0" /></td>
                                      <td> <b-check v-model="permission.update.checked" :disabled="permission.update.disabled" class="px-2 m-0" /></td>
                                      <td> <b-check v-model="permission.delete.checked" :disabled="permission.delete.disabled" class="px-2 m-0" /></td>
                                    </tr>
                                </template>
                              </template>


                               <tr>
                                <td colspan="4"> <strong> Provider Management </strong> </td>
                              </tr>
                              <template v-for="permission in permissions">
                                <template v-if="permission.module == 'Provider'">
                                    <tr v-bind:key="permission.module">
                                      <td> {{ convertPermissionName(permission.module) }} </td>
                                      <td> <b-check v-model="permission.read.checked" :disabled="permission.read.disabled" class="px-2 m-0" /> </td>
                                      <td> <b-check v-model="permission.create.checked" :disabled="permission.create.disabled" class="px-2 m-0" /></td>
                                      <td> <b-check v-model="permission.update.checked" :disabled="permission.update.disabled" class="px-2 m-0" /></td>
                                      <td> <b-check v-model="permission.delete.checked" :disabled="permission.delete.disabled" class="px-2 m-0" /></td>
                                    </tr>
                                </template>
                              </template>


                               <tr>
                                <td colspan="4"> <strong> Redirect Management </strong> </td>
                              </tr>
                              <template v-for="permission in permissions">
                                <template v-if="permission.module == 'Redirect'">
                                    <tr v-bind:key="permission.module">
                                      <td> {{ convertPermissionName(permission.module) }} </td>
                                      <td> <b-check v-model="permission.read.checked" :disabled="permission.read.disabled" class="px-2 m-0" /> </td>
                                      <td> <b-check v-model="permission.create.checked" :disabled="permission.create.disabled" class="px-2 m-0" /></td>
                                      <td> <b-check v-model="permission.update.checked" :disabled="permission.update.disabled" class="px-2 m-0" /></td>
                                      <td> <b-check v-model="permission.delete.checked" :disabled="permission.delete.disabled" class="px-2 m-0" /></td>
                                    </tr>
                                </template>
                              </template>




                              <tr>
                                <td colspan="4"> <strong> User Management </strong> </td>
                              </tr>
                              <template v-for="permission in permissions">
                                <template v-if="permission.module == 'User'">
                                    <tr v-bind:key="permission.module">
                                      <td> {{ convertPermissionName(permission.module) }} </td>
                                      <td> <b-check v-model="permission.read.checked" :disabled="permission.read.disabled" class="px-2 m-0" /> </td>
                                      <td> <b-check v-model="permission.create.checked" :disabled="permission.create.disabled" class="px-2 m-0" /></td>
                                      <td> <b-check v-model="permission.update.checked" :disabled="permission.update.disabled" class="px-2 m-0" /></td>
                                      <td> <b-check v-model="permission.delete.checked" :disabled="permission.delete.disabled" class="px-2 m-0" /></td>
                                    </tr>
                                </template>
                              </template>

                               <tr>
                                <td colspan="4"> <strong> Settings </strong> </td>
                              </tr>
                              <template v-for="permission in permissions">
                                <template v-if="permission.module == 'Ip-ban' || permission.module == 'Fake-lead' || permission.module == 'Redirect-setting'">
                                    <tr v-bind:key="permission.module">
                                      <td> {{ convertPermissionName(permission.module) }} </td>
                                      <td> <b-check v-model="permission.read.checked" :disabled="permission.read.disabled" class="px-2 m-0" /> </td>
                                      <td> <b-check v-model="permission.create.checked" :disabled="permission.create.disabled" class="px-2 m-0" /></td>
                                      <td> <b-check v-model="permission.update.checked" :disabled="permission.update.disabled" class="px-2 m-0" /></td>
                                      <td> <b-check v-model="permission.delete.checked" :disabled="permission.delete.disabled" class="px-2 m-0" /></td>
                                    </tr>
                                </template>
                              </template>

                            </tbody>


                         </table>
                    </div>
                  </b-card-body>
              </b-collapse>
          </b-card>

      </b-tab>
      <b-tab title="Information">
        <b-card-body class="pb-2">

            <b-form-row>
                <b-form-group label="Job Title" class="col-md-12">
                    <b-input v-model="userData.info.jobTitle" />
                </b-form-group>
            </b-form-row>

            <b-form-row>
                <b-form-group label="Work Phone" class="col-md-6">
                    <b-input v-model="userData.info.workPhone" />
                </b-form-group>
                <b-form-group label="Ext." class="col-md-6">
                    <b-input v-model="userData.info.ext" />
                </b-form-group>
            </b-form-row>

            <b-form-row>
                <b-form-group label="Mobile Phone" class="col-md-12">
                    <b-input v-model="userData.info.mobilePhone" />
                </b-form-group>
            </b-form-row>

        </b-card-body>

      </b-tab>
    </b-tabs>

    <div class="text-right mt-3">
        <ladda-btn :loading="loading" @click.native="save($event, true)" data-style="zoom-out" class="btn btn-primary">Save And Create Another</ladda-btn>
        <ladda-btn :loading="loading" @click.native="save" data-style="zoom-out" class="btn btn-primary">Save</ladda-btn>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import Multiselect from 'vue-multiselect'
import Croppa from 'vue-croppa'
import LaddaBtn from '@/vendor/libs/ladda/Ladda'
import Toasted from 'vue-toasted'
import VuePassword from 'vue-password'
import _ from 'lodash'

import 'vue-croppa/dist/vue-croppa.css'

Vue.use(VuePassword)
Vue.use(Toasted)


export default {
  name: 'pages-user-edit',
  metaInfo: {
    title: 'Add User'
  },
  components: {
    Multiselect,
    croppa: Croppa.component,
    LaddaBtn,
    VuePassword
  },
  data: () => ({
    breadcrumbItems: [
      {
        text: 'Users',
        to: { name: 'user-list'}
      },
      {
        text: 'Create New',
        active: true,
      }
    ],
    loading: false,
    errors: [],
    permissions: _.cloneDeep(window.permissions),
    selectionRoles: [],
    roles: [],
    timezones: [],
    croppa: {},
    initialImage: '',
    userData: {
      email: '',
      first_name: '',
      last_name: '',
      role: '',
      status: 'active',
      selectedPermission: [],
      timezone: 'UTC',
      password: '',
      passwordConfirm: '',
      photo: null,
      info: {
        jobTitle: '',
        workPhone: '',
        ext: '',
        mobilePhone: ''
      }
    }
  }),
  watch: {
    'userData.role' (val, oldVal) {
      self = this
      if(val == 'Admin') {
          this.resetPermissions()
          this.permissions.forEach(key => {
              if (typeof this.roles[val][key.module] != 'undefined') {
                  key.create.checked = this.roles[val][key.module].includes('create')
              }

              if (typeof this.roles[val][key.module] != 'undefined') {
                  key.read.checked = this.roles[val][key.module].includes('read')
              }

              if (typeof this.roles[val][key.module] != 'undefined') {
                  key.update.checked = this.roles[val][key.module].includes('update')
              }

              if (typeof this.roles[val][key.module] != 'undefined') {
                  key.delete.checked = this.roles[val][key.module].includes('delete')
              }

          })
      }
      else if(val == 'Super Admin') {
          this.resetPermissions()
          this.permissions.forEach(key => {
              if (typeof this.roles[val][key.module] != 'undefined') {
                  key.create.checked = true
              }

              if (typeof this.roles[val][key.module] != 'undefined') {
                  key.read.checked = true
              }

              if (typeof this.roles[val][key.module] != 'undefined') {
                  key.update.checked = true
              }

              if (typeof this.roles[val][key.module] != 'undefined') {
                  key.delete.checked = true
              }

          })
      }
      else if(val == 'user'){
        this.resetPermissions()
        this.permissions.forEach(key => {
              if (typeof this.roles[val][key.module] != 'undefined') {
                  key.create.checked = this.roles[val][key.module].includes('create')
              }

              if (typeof this.roles[val][key.module] != 'undefined') {
                  key.read.checked = this.roles[val][key.module].includes('read')
              }

              if (typeof this.roles[val][key.module] != 'undefined') {
                  key.update.checked = this.roles[val][key.module].includes('update')
              }

              if (typeof this.roles[val][key.module] != 'undefined') {
                  key.delete.checked = this.roles[val][key.module].includes('delete')
              }

              if (key.module == "User") {
                key.read.checked = true;
                key.create.checked = false;
                key.update.checked = false;
                key.update.checked = false;
              }

              else if (key.module == "Buyer") {
                key.read.checked = true;
                key.create.checked = false;
                key.update.checked = false;
                key.update.checked = false;
              }

              else if (key.module == "Buyer-vertical-channel") {
                key.read.checked = true;
                key.create.checked = false;
                key.update.checked = false;
                key.update.checked = false;
              }

              else if (key.module == "Buyer-panda-channel") {
                key.read.checked = true;
                key.create.checked = false;
                key.update.checked = false;
                key.update.checked = false;
              }

              else if (key.module == "Buyer-plat-channel") {
                key.read.checked = true;
                key.create.checked = false;
                key.update.checked = false;
                key.update.checked = false;
              }

              else if (key.module == "Portfolio") {
                key.read.checked = true;
                key.create.checked = false;
                key.update.checked = false;
                key.update.checked = false;
              }

              else if (key.module == "Provider") {
                key.read.checked = true;
                key.create.checked = false;
                key.update.checked = false;
                key.update.checked = false;
              }

              else if (key.module == "Redirect") {
                key.read.checked = true;
                key.create.checked = false;
                key.update.checked = false;
                key.update.checked = false;
              }

          })
      }
    }
  },
  async created() {
    let self = this

    //Get roles and permissions from API
    await window.axios.get('/api/permission/get-all-roles-with-permission-in-group').then( response => {
        self.roles = response.data
        Object.keys(response.data).forEach( role => {
          self.selectionRoles.push({
            value: role,
            text: role.charAt(0).toUpperCase() + role.slice(1)
          })
        })
        self.userData.role = self.selectionRoles[0].value
    } ).catch( error => {
      console.log(error)
    } )

    //Get timezone list
    self.timezones = this.$_timezones
  },
  methods: {
    convertPermissionName(permission){
      const permissionNames = {
        'Buyer' : 'Buyer',
        'Buyer-panda-channel' : 'Buyer Panda Channel',
        'Buyer-plat-channel' : 'Buyer Plat Channel',
        'Buyer-vertical-channel' : 'Buyer Vertical Channel',
        'Fake-lead' : 'Fake Lead',
        'Ip-ban' : 'IP Ban',
        'Lead' : 'Lead',
        'Live-lead' : 'Live Lead',
        'Portfolio' : 'Portfolio',
        'Provider' : 'Provider',
        'Redirect-setting' : 'Redirect Setting',
        'Redirect' : 'Redirect',
        'User': 'User',
      };
      return permissionNames[permission] || '';
    },
    generateRandomPassword(){
      let length = 12
      let self = this

      var password = '', character;
      while (length > password.length) {
        if (password.indexOf(character = String.fromCharCode(Math.floor(Math.random() * 94) + 33), Math.floor(password.length / 94) * 94) < 0) {
          password += character;
        }
      }
      this.userData.password = password
      this.userData.passwordConfirm = password

      this.$copyText(this.userData.password).then(function (e) {
          self.$snotify.success('Copied Password To Clipboard, Please Continue Adding The User.','Success!')
      }, function (e) {
          self.$snotify.error('Error copying password to clipboard. Please click on the eye in password textbox to view the password.','Success!')
      })
    },
    onFileTypeMismatch (file) {
      this.$swal({
        type: 'error',
        text: 'Invalid file type. Please choose a jpeg or png file!'
      })
    },
    onFileSizeExceed (file) {
      this.$swal({
        type: 'error',
        text: 'File size exceeds. Please choose a file smaller than 100kb!'
      })
    },
    save(event, createAnother = false) {
      this.loading = true
      let self = this
      this.userData.selectedPermission = []
      this.permissions.forEach( permission => {
        if(permission.read.checked){
          self.userData.selectedPermission.push(`${permission.module}.read`)
        }
        if(permission.create.checked){
          self.userData.selectedPermission.push(`${permission.module}.create`)
        }
        if(permission.update.checked){
          self.userData.selectedPermission.push(`${permission.module}.update`)
        }
        if(permission.delete.checked){
          self.userData.selectedPermission.push(`${permission.module}.delete`)
        }
      })

      this.croppa.generateBlob((blob) => {
        const photo = blob
        let formData = new FormData()
        self.userData.photo = photo

        //formData.append('_method', 'put')

        for ( var key in self.userData ) {
          formData.append(key, self.userData[key]);
        }
        formData.append('photo', photo)
        formData.append('info', JSON.stringify(self.userData.info))

        const config = {
          headers: { 'content-type': 'multipart/form-data' }
        }

        window.axios.post('/api/user', formData, config).then(response => {
          self.clearForm()
            self.$snotify.success('New User Created','Success!')
            self.loading = false
          self.$refs.email.focus();
          if(!createAnother){
            self.$router.push('/user/list')
          }
        }).catch(error => {
          self.loading = false
          self.errors = error.response.data.errors
            self.$snotify.error('There was a problem creating user.','Error!')
            console.log(error)
        });

      })


    },
    resetPermissions() {
        this.permissions = _.cloneDeep(window.permissions)
      /*this.permissions.forEach(permission => {
        permission.read.checked = false
        permission.create.checked = false
        permission.update.checked = false
        permission.delete.checked = false
      })*/
    },
    clearForm() {
      this.userData.first_name = ''
      this.userData.last_name = ''
      this.userData.email = ''
      this.userData.photo = ''
      this.userData.status = 'active'
      this.userData.password = ''
      this.userData.passwordConfirm = ''
      this.croppa.remove()
      this.errors = []
    }
  }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css" />
<style src="@/vendor/libs/vue-multiselect/vue-multiselect.scss" lang="scss"></style>

<!-- Page -->
<style src="@/vendor/styles/pages/users.scss" lang="scss"></style>
