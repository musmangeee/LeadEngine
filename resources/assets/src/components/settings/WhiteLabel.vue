<template>
  <div>

      <h4 class="font-weight-bold py-3 mb-4">
          White label Settings
      </h4>

      <b-card no-body class="overflow-hidden">
          <div class="row no-gutters row-bordered row-border-light">

              <div class="col-md-3 pt-0">
                  <b-list-group class="account-settings-links" flush>
                      <b-list-group-item button :active="curTab === 'logo'" @click="curTab = 'logo'">Logo</b-list-group-item>
                      <!--<b-list-group-item button :active="curTab === 'password'" @click="curTab = 'password'">Change password</b-list-group-item>
                      <b-list-group-item button :active="curTab === 'info'" @click="curTab = 'info'">Info</b-list-group-item>
                      <b-list-group-item button :active="curTab === 'links'" @click="curTab = 'links'">Social links</b-list-group-item>
                      <b-list-group-item button :active="curTab === 'connections'" @click="curTab = 'connections'">Connections</b-list-group-item>
                      <b-list-group-item button :active="curTab === 'notifications'" @click="curTab = 'notifications'">Notifications</b-list-group-item>-->
                  </b-list-group>
              </div>

              <div class="col-md-9" v-if="curTab === 'logo'">
                  <b-card-body class="align-items-center">
                      <b-form-row>
                          <div class="ml-md-2">
                              <croppa v-model="croppa"
                                      :prevent-white-space="false"
                                      canvas-color="transparent"
                                      :accept="'image/jpeg,image/png'"
                                      :file-size-limit="2048000"
                                      :placeholder-font-size="12"
                                      remove-button-color="black"
                                      :show-loading="true"
                                      :reverse-scroll-to-zoom="true"
                                      :initial-image="initialImage"
                                      :width="250"
                                      :height="100"
                                      :quality="2"
                                      @file-type-mismatch="onFileTypeMismatch"
                                      @file-size-exceed="onFileSizeExceed">
                                  ></croppa>
                          </div>
                          <div class="col-auto">* Supported image format (jpeg, png) <br />
                              * Zoom with mouse scroll in Desktop, or pinch with finger in Mobile <br />
                              * drag around to reposition by holding left click in Desktop, or with finger in Mobile</div>
                          <div class="col-auto"></div>
                      </b-form-row>

                      <div class="text-right mt-3">
                          <ladda-btn :loading="loading" @click.native="saveLogo" data-style="zoom-out" class="btn btn-primary">Save</ladda-btn>
                      </div>
                  </b-card-body>
              </div>

              <!--<div class="col-md-9" v-if="curTab === 'password'">
                  <b-card-body class="pb-2">

                      <b-form-group label="Current password">
                          <b-input type="password" />
                      </b-form-group>

                      <b-form-group label="New password">
                          <b-input type="password" />
                      </b-form-group>

                      <b-form-group label="Repeat new password">
                          <b-input type="password" />
                      </b-form-group>

                  </b-card-body>
              </div>

              <div class="col-md-9" v-if="curTab === 'info'">
                  <b-card-body class="pb-2">

                      <b-form-group label="Bio">
                          <b-textarea v-model="accountData.info.bio" rows="5" />
                      </b-form-group>

                      <b-form-group label="Birthday">
                          <b-input v-model="accountData.info.birthday" />
                      </b-form-group>

                      <b-form-group label="Country">
                          <b-select v-model="accountData.info.country" :options="['USA', 'Canada', 'UK', 'Germany', 'France']" />
                      </b-form-group>

                      <b-form-group label="Languages">
                          <multiselect v-model="accountData.info.languages" :multiple="true" :options="['English', 'German', 'French']" />
                      </b-form-group>

                  </b-card-body>
                  <hr class="border-light m-0">
                  <b-card-body class="pb-2">

                      <h6 class="mb-4">Contacts</h6>

                      <b-form-group label="Phone">
                          <b-input v-model="accountData.info.phone" />
                      </b-form-group>

                      <b-form-group label="Website">
                          <b-input v-model="accountData.info.website" />
                      </b-form-group>

                  </b-card-body>
                  <hr class="border-light m-0">
                  <b-card-body class="pb-2">

                      <h6 class="mb-4">Interests</h6>

                      <b-form-group label="Favorite music">
                          <multiselect v-model="accountData.info.music" :multiple="true" :taggable="true" :options="[]" @tag="addMusicTag" placeholder="Add tag" />
                      </b-form-group>

                      <b-form-group label="Favorite movies">
                          <multiselect v-model="accountData.info.movies" :multiple="true" :taggable="true" :options="[]" @tag="addMovieTag" placeholder="Add tag" />
                      </b-form-group>

                  </b-card-body>
              </div>

              <div class="col-md-9" v-if="curTab === 'links'">
                  <b-card-body class="pb-2">

                      <b-form-group label="Twitter">
                          <b-input v-model="accountData.info.twitter" />
                      </b-form-group>

                      <b-form-group label="Facebook">
                          <b-input v-model="accountData.info.facebook" />
                      </b-form-group>

                      <b-form-group label="Google+">
                          <b-input v-model="accountData.info.google" />
                      </b-form-group>

                      <b-form-group label="LinkedIn">
                          <b-input v-model="accountData.info.linkedin" />
                      </b-form-group>

                      <b-form-group label="Instagram">
                          <b-input v-model="accountData.info.instagram" />
                      </b-form-group>

                  </b-card-body>
              </div>

              <div class="col-md-9" v-if="curTab === 'connections'">
                  <b-card-body>

                      <b-btn variant="twitter">Connect to <strong>Twitter</strong></b-btn>

                  </b-card-body>
                  <hr class="border-light m-0">
                  <b-card-body>

                      <h5 class="mb-2">
                          <a href="javascript:void(0)" class="float-right text-muted text-tiny"><i class="ion ion-md-close"></i> Remove</a>
                          <i class="ion ion-logo-google text-google"></i>
                          You are connected to Google:
                      </h5>
                      nmaxwell@mail.com

                  </b-card-body>
                  <hr class="border-light m-0">
                  <b-card-body>

                      <b-btn variant="facebook">Connect to <strong>Facebook</strong></b-btn>

                  </b-card-body>
                  <hr class="border-light m-0">
                  <b-card-body>

                      <b-btn variant="instagram">Connect to <strong>Instagram</strong></b-btn>

                  </b-card-body>
              </div>

              <div class="col-md-9" v-if="curTab === 'notifications'">
                  <b-card-body class="pb-2">

                      <h6 class="mb-4">Activity</h6>

                      <b-form-group>
                          <label class="switcher">
                              <input type="checkbox" class="switcher-input" v-model="accountData.notifications.comments">
                              <span class="switcher-indicator">
                  <span class="switcher-yes"></span>
                  <span class="switcher-no"></span>
                </span>
                              <span class="switcher-label">Email me when someone comments on my article</span>
                          </label>
                      </b-form-group>

                      <b-form-group>
                          <label class="switcher">
                              <input type="checkbox" class="switcher-input" v-model="accountData.notifications.forum">
                              <span class="switcher-indicator">
                  <span class="switcher-yes"></span>
                  <span class="switcher-no"></span>
                </span>
                              <span class="switcher-label">Email me when someone answers on my forum thread</span>
                          </label>
                      </b-form-group>

                      <b-form-group>
                          <label class="switcher">
                              <input type="checkbox" class="switcher-input" v-model="accountData.notifications.followings">
                              <span class="switcher-indicator">
                  <span class="switcher-yes"></span>
                  <span class="switcher-no"></span>
                </span>
                              <span class="switcher-label">Email me when someone follows me</span>
                          </label>
                      </b-form-group>

                  </b-card-body>
                  <hr class="border-light m-0">
                  <b-card-body class="pb-2">

                      <h6 class="mb-4">Application</h6>

                      <b-form-group>
                          <label class="switcher">
                              <input type="checkbox" class="switcher-input" v-model="accountData.notifications.news">
                              <span class="switcher-indicator">
                  <span class="switcher-yes"></span>
                  <span class="switcher-no"></span>
                </span>
                              <span class="switcher-label">News and announcements</span>
                          </label>
                      </b-form-group>

                      <b-form-group>
                          <label class="switcher">
                              <input type="checkbox" class="switcher-input" v-model="accountData.notifications.products">
                              <span class="switcher-indicator">
                  <span class="switcher-yes"></span>
                  <span class="switcher-no"></span>
                </span>
                              <span class="switcher-label">Weekly product updates</span>
                          </label>
                      </b-form-group>

                      <b-form-group>
                          <label class="switcher">
                              <input type="checkbox" class="switcher-input" v-model="accountData.notifications.blog">
                              <span class="switcher-indicator">
                  <span class="switcher-yes"></span>
                  <span class="switcher-no"></span>
                </span>
                              <span class="switcher-label">Weekly blog digest</span>
                          </label>
                      </b-form-group>

                  </b-card-body>
              </div>-->

          </div>
      </b-card>

      <!--<div class="text-right mt-3">
          <b-btn variant="primary">Save</b-btn>&nbsp;
          <b-btn variant="default">Cancel</b-btn>
      </div>-->


  </div>
</template>

<style src="@/vendor/libs/vue-toasted/vue-toasted.scss" lang="scss"></style>

<style>
    .v-fade-left-enter-active,
    .v-fade-left-leave-active,
    .v-fade-left-move {
        transition: all .5s;
    }
    .v-fade-left-enter,
    .v-fade-left-leave-to {
        opacity: 0;
        transform: translateX(-500px) scale(0.2);
    }
</style>

<script>
import Vue from 'vue'
import Multiselect from 'vue-multiselect'
import Croppa from 'vue-croppa'
import LaddaBtn from '@/vendor/libs/ladda/Ladda'
import Toasted from 'vue-toasted'

Vue.use(Toasted)

import 'vue-croppa/dist/vue-croppa.css'

export default {
  props: ['id'],
  name: 'pages-user-edit',
  metaInfo: {
    title: 'Settings - White Label'
  },
  components: {
    Multiselect,
    croppa: Croppa.component,
    LaddaBtn
  },
  data: () => ({
    curTab: 'logo',
    loading: false,
    initialImage: '',
    croppa: {},
  }),
  async created() {
    let self = this

    //Get timezone list
    await window.axios.get('/api/helpers/get-logo-path').then( response => {
      self.initialImage = `/media/${response.data}`
      self.croppa.refresh()
    } ).catch( error => {
    } )

  },
  methods: {
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
    saveLogo() {
      this.loading = true
      let self = this


      this.croppa.generateBlob((blob) => {
        const photo = blob
        let formData = new FormData()

        formData.append('_method', 'put')
        formData.append('logo', photo)

        const config = {
          headers: { 'content-type': 'multipart/form-data' }
        }


        window.axios.post('/api/settings/upload-logo', formData, config).then(response => {
          this.$toasted.show('Saved!', {
            icon : 'check',
            position: 'bottom-center',
            duration: 3000,
            type: 'success',
            theme: 'bubble'
          })
          this.loading = false
        }).catch(error => {
          this.$toasted.show('Fail to upload!', {
            icon : 'error',
            position: 'bottom-center',
            duration: 3000,
            type: 'error',
            theme: 'bubble'
          })
          self.loading = false
          self.errors = error.response.data.errors
        });

      }, 'image/png', 0.95)

    },
  }
}
</script>
