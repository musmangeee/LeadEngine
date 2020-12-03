<template>
    <div>
        <h4 class="font-weight-bold py-3 mb-4">
            Company
        </h4>

        <form @submit.prevent="handleSubmit" id="settings-company">


            <b-card sub-title="Company Info">
                <b-form-group label="Name">
                    <b-input v-model="company.name" :class="{ 'is-invalid': $v.company.name.$error }"/>
                    <div v-if="!$v.company.name.required && submitted" class="invalid-feedback">
                        Title field is required.
                    </div>
                </b-form-group>


                <b-form-row>
                    <b-col>
                        <b-form-group label="Primary Company Contact First Name">
                            <b-input v-model="company.first_name"
                                     :class="{ 'is-invalid': $v.company.first_name.$error }"/>
                            <div v-if="!$v.company.first_name.required && submitted" class="invalid-feedback">
                                Employee first name field is required.
                            </div>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Primary Company Contact Last Name">
                            <b-input v-model="company.last_name"
                                     :class="{ 'is-invalid': $v.company.last_name.$error }"/>
                            <div v-if="!$v.company.last_name.required && submitted" class="invalid-feedback">
                                Employee last name field is required.
                            </div>
                        </b-form-group>
                    </b-col>

                </b-form-row>

                <b-form-row>
                    <b-col cols="6">
                        <b-form-group label="Address">
                            <b-input v-model="company.street" :class="{ 'is-invalid': $v.company.street.$error }"/>
                            <div v-if="!$v.company.street.required && submitted" class="invalid-feedback">
                                Address field is required.
                            </div>
                        </b-form-group>
                    </b-col>

                </b-form-row>

                <b-form-row>

                    <b-col>
                        <b-form-group label="City">
                            <b-input v-model="company.city" :class="{ 'is-invalid': $v.company.city.$error }"/>
                            <div v-if="!$v.company.city.required && submitted" class="invalid-feedback">
                                City field is required.
                            </div>
                        </b-form-group>
                    </b-col>

                    <b-col>
                        <b-form-group label="State">
                            <b-form-select v-model="company.state" :options="states_options" :disabled="loading_states"
                                           :class="{ 'is-invalid': $v.company.state.$error }">
                                <template slot="first">
                                    <option :value="null" disabled v-if="company.country != null">-- Please select state --</option>
                                    <option :value="null" disabled v-if="company.country == null">-- Please Select Country --</option>
                                </template>
                                <div v-if="!$v.company.state.required && submitted" class="invalid-feedback">
                                    State field is required.
                                </div>
                            </b-form-select>
                        </b-form-group>
                    </b-col>

                </b-form-row>


                <b-form-row>


                    <b-col>
                        <b-form-group label="Zip Code">
                            <b-input v-model="company.zip" :class="{ 'is-invalid': $v.company.zip.$error }"/>
                            <div v-if="!$v.company.zip.required && submitted" class="invalid-feedback">
                                Zip code field is required.
                            </div>
                        </b-form-group>
                    </b-col>

                    <b-col>
                        <b-form-group label="Country">
                            <b-form-select v-model="company.country" :options="countries_options"
                                           :class="{ 'is-invalid': $v.company.country.$error }"></b-form-select>
                            <div v-if="!$v.company.country.required && submitted" class="invalid-feedback">
                                Country field is required.
                            </div>
                        </b-form-group>

                    </b-col>

                </b-form-row>

                <b-form-row>
                    <b-col>
                        <b-form-group label="Phone">
                            <b-input v-model="company.phone" :class="{ 'is-invalid': $v.company.phone.$error }"/>
                            <div v-if="!$v.company.phone.required && submitted" class="invalid-feedback">
                                Phone field is required.
                            </div>
                        </b-form-group>
                    </b-col>

                    <b-col>
                        <b-form-group label="Email">
                            <b-input v-model="company.email" :class="{ 'is-invalid': $v.company.email.$error }"/>
                            <div v-if="!$v.company.email.required && submitted" class="invalid-feedback">
                                Email field is required.
                            </div>
                            <div v-if="!$v.company.email.email && submitted" class="invalid-feedback">
                                Please input a valid email.
                            </div>
                        </b-form-group>
                    </b-col>

                </b-form-row>


            </b-card>

            <b-card class="mt-3" sub-title="Currency">
                <b-form-row>
                    <b-col cols="6">
                        <b-form-group label="Currency">
                            <b-form-select v-model="company.currency" :options="currencies_options"
                                           :class="{ 'is-invalid': $v.company.currency.$error }"></b-form-select>
                            <div v-if="!$v.company.currency.required && submitted" class="invalid-feedback">
                                Currency field is required.
                            </div>
                        </b-form-group>
                    </b-col>
                </b-form-row>

                <b-form-row>
                    <b-col>
                        <b-form-group label="Tax" class="col-sm-12">
                            <label class="switcher switcher-success">
                                <input type="checkbox" class="switcher-input" v-model="company.tax">
                                <span class="switcher-indicator">
                            <span class="switcher-yes text-success"></span>
                            <span class="switcher-no"></span>
                        </span>
                                <span class="switcher-label" v-if="!company.tax">Disabled</span>
                                <span class="switcher-label" v-if="company.tax">Enabled</span>
                            </label>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Tax Percentage">
                            <b-input v-model="company.tax_percentage" :class="{ 'is-invalid': $v.company.tax_percentage.$error }"/>
                            <div v-if="!$v.company.tax_percentage.required && submitted" class="invalid-feedback">
                                Tax percentage field is required.
                            </div>
                            <div v-if="!$v.company.tax_percentage.numeric && submitted" class="invalid-feedback">
                                Tax percentage field must be a valid number
                            </div>
                        </b-form-group>
                    </b-col>

                </b-form-row>


            </b-card>

            <b-card class="mt-3" sub-title="Reporting">
                    <b-col cols="6">
                        <b-form-group label="Invoice Days of Due">
                            <b-input v-model="company.days_of_due" :class="{ 'is-invalid': $v.company.days_of_due.$error }"/>
                            <div v-if="!$v.company.days_of_due.required && submitted" class="invalid-feedback">
                                Invoice Days of Due field is required.
                            </div>
                            <div v-if="!$v.company.days_of_due.numeric && submitted" class="invalid-feedback">
                                Invoice Days of Due field must be a valid number
                            </div>
                        </b-form-group>
                    </b-col>
            </b-card>


            <b-button variant="primary" type="submit" class="float-right mt-3">
                Save
            </b-button>


        </form>
    </div>

</template>

<script>

    import Vue from 'vue'
    import Vuelidate from 'vuelidate'
    import {required, email, numeric} from 'vuelidate/lib/validators'
    import LaddaBtn from '@/vendor/libs/ladda/Ladda'

    Vue.use(Vuelidate);

    const initData = {
        name: '',
        first_name: '',
        last_name: '',
        street: '',
        zip: '',
        city: '',
        state: null,
        country: null,
        phone: '',
        email: '',
        currency: 'USD',
        tax: false,
        tax_percentage: 0,
        days_of_due : 0,
    }
    export default {
        name: "CompanySetting",
        metaInfo: {
            title: 'Settings - Company'
        },
        data: () => ({
            company: {...initData},
            loading: false,
            submitted: false,
            states_options: [],
            countries_options: [],
            currencies_options: [],
            loading_states: false
        }),
        components: {
            LaddaBtn
        },
        async created() {
            let self = this




            //Get user from API
            await window.axios.get(`/api/countries/get-all-currencies`).then(response => {
                let currencies = response.data;

                let usDefault = currencies.filter(function (data) {
                    return data.currency == 'USD' && data.country_code == 'US'
                });
                console.log(usDefault[0])

                currencies.forEach(function (data) {
                    self.currencies_options.push({
                        text: `${data.country_name} (${data.currency})`,
                        value: data.currency
                    })
                })

                self.currencies_options.unshift({
                    text: `${usDefault[0].country_name} (${usDefault[0].currency})`,
                    value: usDefault[0].currency
                })


            }).catch(error => {
            })


            //Get user from API
            await window.axios.get('/api/settings').then(response => {
                console.log(response);

                let options = response.data;
                options.forEach(function (option) {
                    if (option.name === 'company_tax_status' && option.value === 'enabled') {
                        self.company.tax = true;
                    }

                    if (option.name.includes('company_')) {
                        self.company[option.name.replace('company_', '')] = option.value;
                    }
                })
            }).catch(error => {
            })

            await window.axios.get('/api/countries/get-all-name')
                .then(response => {
                    Object.keys(response.data).forEach((key) => {
                        this.countries_options.push({
                            text: response.data[key],
                            value: response.data[key]
                        })
                    })
                })


        },
        watch: {
            'company.country'(newVal, oldVal) {
                let self = this
                this.loading_states = true
                window.axios.get(`/api/countries/get-states/${newVal}`)
                    .then(response => {
                        self.states_options = []
                        Object.keys(response.data).forEach(key => {
                            self.states_options.push({
                                text: response.data[key],
                                value: response.data[key]
                            })
                        })
                        self.loading_states = false
                    })
            },
            'company.tax'(newVal, oldVal) {
                if (newVal === true) {
                    this.company.tax_status = 'enabled'
                } else {
                    this.company.tax_status = 'disabled'
                }
            }
        },
        validations: {
            company: {
                name: {required},
                first_name: {required},
                last_name: {required},
                street: {required},
                zip: {required},
                city: {required},
                state: {required},
                country: {required},
                currency: {required},
                phone: {required},
                email: {required, email},
                tax: {required},
                tax_percentage: {required, numeric},
                days_of_due : {required, numeric}
            }
        },
        methods: {
            handleSubmit() {
                this.submitted = true;

                // stop here if form is invalid
                this.$v.company.$touch();
                if (this.$v.company.$invalid) {
                    console.log(this.company)
                    return
                }

                let self = this;
                window.axios.put('/api/settings/company', this.company).then(response => {
                    console.log(response);
                    self.loading = false
                    localStorage.setItem('settings', JSON.stringify(response.data.options))
                    self.$snotify.success('Company data saved.', 'Success')

                }).catch(error => {
                    self.loading = false
                    self.$snotify.error('There was a problem saving Company.', 'Error')
                });


            }
        }
    }
</script>
