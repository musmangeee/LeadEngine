<template>
    <div>
        <h4 class="font-weight-bold py-3 mb-4">
            My Profile
        </h4>

        <b-form autocomplete="false">

            <b-tabs class="nav-tabs-top nav-responsive-sm">
                <b-tab title="Account" active>

                    <hr class="border-light m-0">
                    <b-card-body class="pb-2">

                        <b-form-group label="Email" :invalid-feedback="errors.email ? errors.email[0] : null">
                            <b-input v-model="userData.email" :state="errors.email ? 'invalid' : null" class="mb-1"/>
                        </b-form-group>


                        <b-form-row>
                            <b-form-group label="First Name"
                                :invalid-feedback="errors.first_name ? errors.first_name[0] : null"
                                class="col-sm-6">
                                <b-input v-model="userData.first_name" :state="errors.first_name ? 'invalid' : null"
                                    class="mb-1"/>
                            </b-form-group>
                            <b-form-group label="Last Name"
                                :invalid-feedback="errors.last_name ? errors.last_name[0] : null"
                                class="col-sm-6">
                                <b-input v-model="userData.last_name" :state="errors.last_name ? 'invalid' : null"
                                    class="mb-1"/>
                            </b-form-group>
                        </b-form-row>

                    </b-card-body>
                    <hr class="border-light m-0">
                    <b-card-body class="pb-2">
                        <b-form-group label="Time Zone">
                            <b-select v-model="userData.timezone" :options="timezones"/>
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
                                        >
                                    </croppa>
                                </div>
                                <div class="col-auto">* Supported image format (jpeg, png) <br/>
                                    * Zoom with mouse scroll in Desktop, or pinch with finger in Mobile <br/>
                                    * drag around to reposition by holding left click in Desktop, or with finger in Mobile
                                </div>
                                <div class="col-auto"></div>
                            </b-form-row>
                        </b-form-group>
                    </b-card-body>

                </b-tab>
                <b-tab title="Information">
                    <b-card-body class="pb-2">

                        <b-form-row>
                            <b-form-group label="Job Title" class="col-md-12">
                                <b-input v-model="userData.info.jobTitle"/>
                            </b-form-group>
                        </b-form-row>

                        <b-form-row>
                            <b-form-group label="Work Phone" class="col-md-6">
                                <b-input v-model="userData.info.workPhone"/>
                            </b-form-group>
                            <b-form-group label="Ext." class="col-md-6">
                                <b-input v-model="userData.info.ext"/>
                            </b-form-group>
                        </b-form-row>

                        <b-form-row>
                            <b-form-group label="Mobile Phone" class="col-md-12">
                                <b-input v-model="userData.info.mobilePhone"/>
                            </b-form-group>
                        </b-form-row>

                    </b-card-body>

                </b-tab>

                <b-tab title="Change Password">
                    <b-card-body class="pb-2">

                        <b-form-row>
                            <b-form-group label="Password" :invalid-feedback="errors.password ? errors.password[0] : null"
                                class="col-sm-6">
                                <vue-password v-model="userData.password">
                                    <div
                                        slot="password-input"
                                        slot-scope="props"
                                        class="control has-icons-left"
                                        >

                                        <b-input autocomplete="off" :type="'password'" v-model="userData.password"
                                            :state="errors.password ? 'invalid' : null"
                                            @input="props.updatePassword(userData.password)" class="mb-1"/>
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </vue-password>
                                <div style="color: #d9534f">
                                    {{ errors.password ? errors.password[0] : null }}
                                </div>

                            </b-form-group>
                            <b-form-group label="Confirm Password"
                                :invalid-feedback="errors.passwordConfirm ? errors.passwordConfirm[0] : null"
                                class="col-sm-6">
                                <b-input autocomplete="off" type="password" v-model="userData.passwordConfirm" required
                                    :state="errors.passwordConfirm ? 'invalid' : null"/>
                            </b-form-group>
                        </b-form-row>
                        <div class="row justify-content-start ml-2 pb-4">
                            <a href="#" @click.prevent="generateRandomPassword"><u>Generate Random Password</u></a>
                        </div>

                    </b-card-body>

                </b-tab>
                <b-tab title="Options">
                    <b-card-body class="pb-2">




                        <b-form-group label="File Review Email Notification" class="col-lg-6">
                            <label class="switcher switcher-success">
                                <input type="checkbox" class="switcher-input" v-model="userData.reviewEmailNotification">
                                <span class="switcher-indicator">
                                    <span class="switcher-yes text-success"></span>
                                    <span class="switcher-no"></span>
                                </span>
                                <span class="switcher-label" v-if="userData.reviewEmailNotification == 1">On</span>
                                <span class="switcher-label" v-if="userData.reviewEmailNotification == 0">Off</span>
                            </label>
                        </b-form-group>

                    </b-card-body>
                </b-tab>
            </b-tabs>

        </b-form>

        <div class="text-right mt-3">
            <ladda-btn :loading="loading" @click.native="submit" data-style="zoom-out" class="btn btn-primary">Save
            </ladda-btn>
        </div>
    </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.min.css"/>
<style src="@/vendor/libs/vue-multiselect/vue-multiselect.scss" lang="scss"></style>
<style src="@/vendor/libs/vue-toasted/vue-toasted.scss" lang="scss"></style>

<!-- Page -->
<style src="@/vendor/styles/pages/users.scss" lang="scss"></style>

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
import Croppa from 'vue-croppa'
import LaddaBtn from '@/vendor/libs/ladda/Ladda'
import Toasted from 'vue-toasted'
import VuePassword from 'vue-password'

Vue.use(Toasted)
Vue.use(VuePassword)

import 'vue-croppa/dist/vue-croppa.css'

export default {
    props: ['id'],
    name: 'pages-user-edit',
    metaInfo: {
        title: 'My Profile'
    },
    components: {
        croppa: Croppa.component,
        LaddaBtn,
        VuePassword
    },
    data: () => ({
        loading: false,
        errors: [],
        initialImage: '',
        timezones: [],
        croppa: {},
        userData: {
            email: '',
            first_name: '',
            last_name: '',
            timezone: 'UTC',
            password: '',
            passwordConfirm: '',
            //photo: null,
            info: {
                jobTitle: '',
                workPhone: '',
                ext: '',
                mobilePhone: ''
            },
            soundReminder : 0,
            reviewEmailNotification : 0
        }
    }),
    watch: {
        'userData.role'(val, oldVal) {
            //this.setPermissions(this.roles[val])
        }
    },
    async created() {
        let self = this


        //Get timezone list
        this.timezones = this.$_timezones
        //Get user from API
        await window.axios.get(`/api/me`).then(response => {


            self.userData.email = response.data.user.email
            self.userData.first_name = response.data.user.first_name
            self.userData.last_name = response.data.user.last_name
            self.userData.timezone = response.data.user.timezone
            self.initialImage = `/media/${response.data.user.photo_path}`
            self.userData.info.jobTitle = response.data.user.information.job_title
            self.userData.info.workPhone = response.data.user.information.work_phone
            self.userData.info.ext = response.data.user.information.ext
            self.userData.info.mobilePhone = response.data.user.information.mobile_phone

            let data = response.data;
            let soundReminder = data.user.options.find(function (opt) {
                return opt.option_name == 'sound_reminder'
            })
            if(soundReminder && soundReminder.option_value == 'on'){
                self.userData.soundReminder = 1;
            }
            else {
                self.userData.soundReminder = 0;
            }


            let reviewEmailNotification = data.user.options.find(function (opt) {
                return opt.option_name == 'review_email_notification'
            })
            if(reviewEmailNotification && reviewEmailNotification.option_value == 'on'){
                self.userData.reviewEmailNotification = 1;
            }
            else {
                self.userData.reviewEmailNotification = 0;
            }


            self.croppa.refresh()

        })
    },
    methods: {
        generateRandomPassword() {
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

            this.$copyText(this.userData.password).then(function () {
                self.$toasted.show('Copied Password To Clipboard, Please Continue Adding The User', {
                    position: 'bottom-center',
                    duration: 3000,
                    type: 'success',
                    theme: 'bubble'
                })
            }, function () {
                self.$toasted.show('Error copying password to clipboard. Please click on the eye in password textbox to view the password', {
                    position: 'bottom-center',
                    duration: 3000,
                    type: 'error',
                    theme: 'bubble'
                })
            })
        },
        onFileTypeMismatch(file) {
            this.$swal({
                type: 'error',
                text: 'Invalid file type. Please choose a jpeg or png file!'
            })
        },
        onFileSizeExceed(file) {
            this.$swal({
                type: 'error',
                text: 'File size exceeds. Please choose a file smaller than 100kb!'
            })
        },
        submit() {
            this.loading = true
            let self = this


            this.croppa.generateBlob((blob) => {
                const photo = blob
                let formData = new FormData()
                self.userData.photo = photo

                for (var key in self.userData) {
                    console.log(self.userData[key])
                    formData.append(key, self.userData[key]);
                }
                formData.append('photo', photo)
                formData.append('info', JSON.stringify(self.userData.info))

                const config = {
                    headers: {'content-type': 'multipart/form-data'}
                }


                window.axios.post('/api/users/profile', formData, config).then(response => {
                    this.$toasted.show('Saved!', {
                        icon: 'check',
                        position: 'bottom-center',
                        duration: 3000,
                        type: 'success',
                        theme: 'bubble'
                    })
                    this.loading = false

                    let data = response.data
                    localStorage.setItem('options',JSON.stringify(data.options))
                    localStorage.setItem('timezone',data.timezone)


                }).catch(error => {
                    this.$toasted.show('Fail to save', {
                        icon: 'error',
                        position: 'bottom-center',
                        duration: 3000,
                        type: 'error',
                        theme: 'bubble'
                    })
                    self.loading = false
                    self.errors = error.response.data.errors
                });

            }, 'image/jpeg', 0.8)


        },
        clearForm() {
            this.userData.first_name = ''
            this.userData.last_name = ''
            this.userData.email = ''
            this.userData.photo = ''
            this.errors = []
        }
    }
}
</script>
