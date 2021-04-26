<template>
    <div>
        
        <b-row>
            <b-col cols="12">
                <b-overlay
                    :show="loading"
                    no-wrap
                />
                <form-wizard
                    color="#ec5959"
                    :title="null"
                    :subtitle="null"
                    shape="square"
                   
                    finish-button-text="Submit"
                    back-button-text="Previous"
                    class="mb-3"
                    @on-complete="formSubmitted"
                >

                    <!-- vehicle and customer details -->
                    <tab-content
                        title="Vehicle"
                        :before-change="validationForm"
                    >
                        
                        <validation-observer
                            ref="accountRules"
                            tag="form"
                        >
                        <b-row>
                           
                            <b-col>
                                <b-form-group
                                    label="VIN"
                                    label-for="vin"
                                >
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="VIN"
                                    >
                                        <b-input-group>
                                            <b-form-input 
                                                name="VIN" 
                                                placeholder="VIN" 
                                                v-model="form.vin" 
                                                :state="errors.length > 0 ? false:null"
                                            />
                                            <b-input-group-append>
                                            <b-button variant="danger" @click="searchVehicle">
                                                Find
                                            </b-button>
                                            </b-input-group-append>
                                        </b-input-group>

                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>

                                <b-form-group
                                    label="Engine"
                                    label-for="engine"
                                >
                                    <validation-provider
                                        #default="{ errors }"
                                        name="Engine"
                                        rules="required"
                                    >
                                        <b-form-input
                                            v-model="form.engine"
                                            type="text"
                                            name="name"
                                            :state="errors.length > 0 ? false:null"
                                            placeholder="Engine"
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>

                                <b-form-group
                                    label="CS No."
                                    label-for="cs_no"
                                >
                                    <validation-provider
                                        #default="{ errors }"
                                        name="CS No"
                                        rules="required"
                                    >
                                        <b-form-input
                                            v-model="form.cs_no"
                                            type="text"
                                            :state="errors.length > 0 ? false:null"
                                            placeholder="CS No."
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>
                                <b-form-group
                                    label="Plate No"
                                    label-for="plate no"
                                >
                                    <validation-provider
                                        #default="{ errors }"
                                        name="Plate No"
                                    >
                                        <b-form-input
                                            v-model="form.plate_no"
                                            type="text"
                                            :state="errors.length > 0 ? false:null"
                                            placeholder="Plate No"
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>
                                <b-form-group
                                    label="Model"
                                    label-for="model"
                                >
                                    <validation-provider
                                        #default="{ errors }"
                                        name="Model"
                                        rules="required"
                                    >
                                        <b-form-input
                                            v-model="form.model"
                                            type="text"
                                            :state="errors.length > 0 ? false:null"
                                            placeholder="Model"
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>
                                <b-form-group
                                    label="Mileage"
                                    label-for="Mileage"
                                >
                                    <validation-provider
                                        #default="{ errors }"
                                        name="Mileage"
                                        rules="required"
                                    >
                                        <b-form-input
                                            v-model="form.mileage"
                                            type="email"
                                            :state="errors.length > 0 ? false:null"
                                            placeholder="Mileage"
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>
                                
                            </b-col>
                            <b-col>
                            
                                <b-form-group
                                    label="Customer Type"
                                    label-for="customer_type"
                                >
                                    <v-select
                                        size="sm"
                                        v-model="form.customer_type"
                                        label="type"
                                        :options="customerTypes"
                                    />
                                </b-form-group>

                                <b-form-group
                                    label="Customer Name"
                                    label-for="customer_name"
                                >
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="Customer Name"
                                    >
                                        <b-form-input
                                            v-model="form.customer_name"
                                            type="text"
                                            name="customer_name"
                                            :state="errors.length > 0 ? false:null"
                                            placeholder="Customer name"
                                        />

                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>

                                <b-form-group
                                    label="Department"
                                    label-for="department"
                                >
                                    <v-select
                                        v-model="form.department"
                                        label="department"
                                        value="department"
                                        :options="departments"
                                    />
                                </b-form-group>

                                <b-form-group
                                    label="Section"
                                    label-for="section"
                                >
                                    <v-select
                                        v-model="form.section"
                                        label="section"
                                        :options="sections"
                                    />
                                </b-form-group>

                                <b-form-group
                                    label="Contact Number"
                                    label-for="contact_number"
                                >
                                    <b-form-input
                                        v-model="form.contact_number"
                                        type="text"
                                        placeholder="Contact Number"
                                    /> 
                                </b-form-group>

                                <b-form-group
                                    label="Date sold"
                                    label-for="date_sold"
                                >
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="Date sold"
                                    >
                                        <b-form-datepicker
                                            v-model="form.date_sold"
                                            type="text"
                                            :state="errors.length > 0 ? false:null"
                                            placeholder="Date sold"
                                        />

                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>
                                
                            </b-col>
                            <b-col>
                               
                            </b-col>
                          
                        </b-row>
                        </validation-observer>
                    </tab-content>

                  
                   

                    <!-- job request  -->
                    <tab-content
                        title="Job Request"
                    >

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Job Request</th>
                                    <th v-if="user.user_type != 'Administrator' ? false : true" >Job Done</th>
                                    <th v-if="user.user_type != 'Administrator' ? false : true" >OP Code</th>
                                    <th v-if="user.user_type != 'Administrator' ? false : true" >Labor Charge</th>
                                    <th>Parts</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody v-for="(row, index) in form.jobRequest" :key="index">
                                <tr>
                                    <td>
                                        <v-select
                                            v-model="row.job"
                                            label="job_name"
                                            :options="jobTypes"
                                            v-if="row.job != 'OTHERS'"
                                        />
                                        <!-- <select name="" class="form-control form-control-sm"  @change="generateParts(index)" v-if="row.job.job_name != 'OTHERS'" v-model="row.job">
                                            <option value="">Select job</option>
                                            <option :value="job" v-for="(job, index) in jobTypes" :key="index">{{ job.job_name }}</option>
                                        </select> -->
                                        <div class="input-group" v-if="row.job == 'OTHERS'">
                                            <input type="text" class="form-control" v-model="row.other_job" placeholder="Enter job request..."/>
                                            <div class="input-group-append">
                                                <button class="btn btn-danger" type="button" @click="row.job = ''">X</button>
                                            </div>
                                        </div>
                                    </td>
                                    <td v-if="user.user_type != 'Administrator' ? false : true" ><b-form-input v-model="row.job_done"/></td>
                                    <td v-if="user.user_type != 'Administrator' ? false : true" ><b-form-input v-model="row.op_code" /></td>
                                    <td v-if="user.user_type != 'Administrator' ? false : true" ><b-form-input v-model="row.labor_charge" /></td>
                                    <td>
                                        <b-button 
                                            variant="danger" 
                                            size="sm" 
                                            @click.prevent="row.partsToggle = !row.partsToggle"
                                        >{{ row.partsToggle ? 'Hide' : 'Show' }}
                                        </b-button>
                                    </td>
                                    <td>
                                        <b-button
                                            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                            variant="flat-danger"
                                            class="btn-icon"
                                            @click.prevent="deleteJobRequest(index)"
                                        >
                                            <feather-icon icon="DeleteIcon" />
                                        </b-button>
                                    </td>
                                </tr>
                                <tr v-show="row.partsToggle">
                                    <td colspan="6">
                                        <table clas="table">
                                            <thead>
                                                <tr>
                                                    <th>Part No</th>
                                                    <th>Part Description</th>
                                                    <th>Quantity</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(parts, partIndex) in row.parts" :key="partIndex">
                                                    <td><input type='text' class="form-control form-control-sm" v-model="parts.part_no"></td>
                                                    <td><input type='text' class="form-control form-control-sm" v-model="parts.part_description"></td>
                                                    <td><input type='text' class="form-control form-control-sm" v-model="parts.quantity"></td>
                                                    <td>
                                                        <b-button
                                                            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                                            variant="flat-danger"
                                                            class="btn-icon"
                                                            @click.prevent="deletePart(index, partIndex)"
                                                        >
                                                            <feather-icon icon="DeleteIcon" />
                                                        </b-button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <b-link @click.prevent="addPart(index)">Add part</b-link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                          <b-link size="sm" variant="danger" @click="addJob">Add Job</b-link>

                    </tab-content>

                </form-wizard>
            </b-col>
        </b-row>
    </div>
</template>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-wizard.scss';
  @import '@core/scss/vue/libs/vue-select.scss';
</style>

<script>

import { FormWizard, TabContent } from 'vue-form-wizard'
import vSelect from 'vue-select'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import Ripple from 'vue-ripple-directive'
import {
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BInputGroup,
    BFormInvalidFeedback,
    BInputGroupAppend, 
    BInputGroupPrepend,
    BButton,
    BOverlay,
    BFormDatepicker,
    BLink,
    BBadge
} from 'bootstrap-vue'
import { required, email } from '@validations'
import { GET_VEHICLE } from '@/store/job-order/index.js';
import axios from 'axios';
import useJwt from '@/auth/jwt/useJwt';
export default {
    components: {
        ValidationProvider,
        ValidationObserver,
        FormWizard,
        TabContent,
        BRow,
        BCol,
        BFormGroup,
        BFormInput,
        vSelect,
        BFormInvalidFeedback,
        ToastificationContent,
        BInputGroup,
        BInputGroupAppend, 
        BInputGroupPrepend,
        BButton,
        BOverlay,
        BFormDatepicker,
        BLink,
        BBadge
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            form : {
                job_order_id : '',
                vin : '',
                cs_no : '',
                engine : '',
                plate_no : '',
                model : '',
                mileage :'',
                customer_type : '',
                customer_name: '',
                department : '',
                section : '',
                contact_number : '',
                date_sold : '',
                jobRequest: [
                    {
                        job : '',
                        other_job : '',
                        job_done : '',
                        op_code : '',
                        labor_charge : '',
                        partsToggle : false,
                        parts : []
                    }
                ],
            },
            loading : false,
            customerTypes : [],
            jobTypes : [],
            sections : [],
            departments : [],
            action : 'create',
            user : useJwt.getUser()
        }
    },
    mounted() {
        this.loadOptions();
        if(this.$route.params.jobOrderId){
            this.action = "update";
            this.form.job_order_id = this.$route.params.jobOrderId;
            this.loadData();
        }
    },
    methods: {
        formSubmitted() {
            var self = this;
            var apiUrl = "";
            if(this.action == "create"){
                apiUrl = "api/job-order/submit";
            }
            else if(this.action == "update"){
                apiUrl = "api/job-order/update";
            }
            this.loading = true;
            axios.post(apiUrl, this.form).then(response => {
                var response = response.data;
                self.$toast({
                    component: ToastificationContent,
                    props: {
                        title: response.message,
                        icon: 'EditIcon',
                        variant: 'success',
                    },  
                })
                self.$router.push({
                    name : 'jo-details',
                    params : {
                        jobOrderId : response.job_order_id
                    }
                });
            }).catch(err => {
                self.$toast({
                    component: ToastificationContent,
                    props: {
                        title: 'Error submitting request.',
                        icon: 'AlertTriangleIcon',
                        variant: 'danger',
                    },  
                })
                console.log(err);
            }).finally( () => {
                self.loading = false;
            });
            
        },
        validationForm() {
            return new Promise((resolve, reject) => {
                this.$refs.accountRules.validate().then(success => {
                if (success) {
                    resolve(true)
                } else {
                    reject()
                }
                })
            })
        },  
        searchVehicle(){
            var self = this;
            this.loading = true;
            axios.get('api/vehicle/get-by-vin/' + this.form.vin).then(res => {
                let vehicle = res.data;
                self.form.engine = vehicle.engine;
                self.form.cs_no = vehicle.serial_number;
                self.form.plate_no = vehicle.plate_no;
                self.form.model = vehicle.sales_model;
                self.form.mileage = vehicle.mileage;
                // context.commit(UPDATE_BLOCKUI, {key : 'state' , value : false});
                // context.commit(UPDATE_FORM, {key : 'csNo', value : vehicle.serial_number});
                // context.commit(UPDATE_FORM, {key : 'engine', value : vehicle.engine});
                // context.commit(UPDATE_FORM, {key : 'mileage', value : vehicle.mileage});
                // context.commit(UPDATE_FORM, {key : 'salesModel', value : vehicle.sales_model});
            }).catch(err => {
                console.log(err);
            }).finally(() => {
                this.loading = false;
            });
        },
        loadOptions(){
            let custTypesApi = 'api/customer-types/get';
            let departmentsApi = 'api/departments/get';
            let sectionsApi = 'api/sections/get';
            let jobTypesApi = 'api/job-types/get';
           
            const custTypesReq = axios.get(custTypesApi);
            const departmentsReq = axios.get(departmentsApi);
            const sectionsReq = axios.get(sectionsApi);
            const jobTypesReq = axios.get(jobTypesApi);
          
            var self = this;
            axios.all([custTypesReq, departmentsReq, sectionsReq, jobTypesReq]).then(axios.spread((...responses) => {
                self.customerTypes = responses[0].data;
                self.departments = this.objectToArray(responses[1].data, 'department');
                self.sections = this.objectToArray(responses[2].data, 'section');
                self.jobTypes = this.objectToArray(responses[3].data, 'job_name');
            })).catch(errors => {

            }).finally( () => {

            });
        
        },
        addJob(){
            this.form.jobRequest.push({
                job : '',
                job_done : '',
                op_code : '',
                labor_charge: '',
                parts : [],
                partsToggle : false
            });
		},
		generateParts(jobIndex){
            let jobId = this.form.jobRequest[jobIndex].job.id;
            let salesModel = this.form.salesModel;
            axios.get('api/job-parts/get' , {
                params : {
                    sales_model : salesModel,
                    job_type_id : jobId
                }
            }).then(res => {
                context.commit(SET_JOB_PARTS, { index : jobIndex, parts : res.data });
            }).catch(err => {
                console.log(err);
            });
        },
		deleteJobRequest(index){
            this.form.jobRequest.splice(index, 1);
		},
		deletePart(jobIndex, partIndex){
			this.form.jobRequest[jobIndex].parts.splice(partIndex, 1);
		},
		addPart(jobIndex){
            //var job_request_id = this.form.jobRequest[jobIndex].job.id;
            var newPart = {
                //job_request_id : job_request_id,
                part_no : '',
                part_description : '',
                quantity : '',
                remarks : ''
            };
            this.form.jobRequest[jobIndex].parts.push(newPart);
        },
        loadData(){
            let headerApi = 'api/job-order/header/get/' + this.$route.params.jobOrderId;
            let lineApi = 'api/job-order/line/get/' + this.$route.params.jobOrderId;
            const headerReq = axios.get(headerApi);
            const lineReq = axios.get(lineApi);
            var self = this;
            axios.all([headerReq, lineReq]).then(axios.spread((...responses) => {
                let header = responses[0].data;
                this.form.vin = header.vin;
                this.form.engine = header.engine;
                this.form.cs_no = header.cs_no;
                this.form.plate_no = header.plate_no;
                this.form.model = header.sales_model;
                this.form.mileage = header.mileage;
                this.form.contact_number = header.contact_number;
                this.form.customer_name = header.customer_name;
                this.form.date_sold = header.date_sold;
                this.form.department = header.department;
                this.form.section = header.section;
                this.form.customer_type = {
                    'id' : header.customer_type_id,
                    'type' : header.customer_type
                };
                let lines = responses[1].data;

                this.form.jobRequest = [];
                for(let i = 0; i < lines.length; i++) {
                    let line = lines[i];
                    let parts = lines[i].parts;        
                    this.form.jobRequest.push({
                        job : line.job_type,
                        job_done : line.job_done,
                        labor_charge : line.labor_charge,
                        op_code : line.op_code,
                        partsToggle : false,
                        parts : parts
                    });
                }
            })).catch(errors => {

            }).finally( () => {

            });
        },
        objectToArray(arrObj, label){
            return arrObj.map(value => value[label]);
        }
    },
}
</script>
