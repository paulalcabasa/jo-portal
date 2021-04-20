<template>
    <div>
        <b-row>
            <b-col sm="4">
                <b-card>
                    <b-tabs content-class="mt-1">
                        <b-tab title="Request details">
                            <div class="mb-1">
                                <span class="d-block">Job Order No.</span>
                                <span class="font-weight-bolder">{{ header.id }}</span>
                            </div>
                            <div class="mb-1">
                                <span class="d-block">Customer Type</span>
                                <span class="font-weight-bolder">{{ header.customer_type }}</span>
                            </div>
                            <div class="mb-1">
                                <span class="d-block">Customer Name</span>
                                <span class="font-weight-bolder">{{ header.customer_name }}</span>
                            </div>
                            <div class="mb-1">
                                <span class="d-block">Contact Number</span>
                                <span class="font-weight-bolder">{{ header.contact_number }}</span>
                            </div>
                            <div class="mb-1">
                                <span class="d-block">Department</span>
                                <span class="font-weight-bolder">{{ header.department }}</span>
                            </div>
                            <div class="mb-1">
                                <span class="d-block">Section</span>
                                <span class="font-weight-bolder">{{ header.section }}</span>
                            </div>
                            <div class="mb-1">
                                <span class="d-block">Date created</span>
                                <span class="font-weight-bolder">{{ header.created_at | formatDateTime }}</span>
                            </div>
                            <div class="mb-1">
                                <span class="d-block">Status</span>
                                <span class="font-weight-bolder"><b-badge variant="light-primary">{{ header.status }}</b-badge></span>
                            </div>
                        </b-tab>

                        <b-tab
                            title="Vehicle"
                            lazy
                        >
                            <div class="mb-1">
                                <span class="d-block">VIN</span>
                                <span class="font-weight-bolder">{{ header.vin }}</span>
                            </div>
                            <div class="mb-1">
                                <span class="d-block">CS No.</span>
                                <span class="font-weight-bolder">{{ header.cs_no }}</span>
                            </div>
                            <div class="mb-1">
                                <span class="d-block">Plate No.</span>
                                <span class="font-weight-bolder">{{ header.plate_no }}</span>
                            </div>
                            <div class="mb-1">
                                <span class="d-block">Model</span>
                                <span class="font-weight-bolder">{{ header.sales_model }}</span>
                            </div>
                            <div class="mb-1">
                                <span class="d-block">Date sold</span>
                                <span class="font-weight-bolder"><b-badge variant="light-primary">{{ header.date_sold | formatDate }}</b-badge></span>
                            </div>
                        </b-tab>
                    </b-tabs>
                </b-card>
            </b-col>

            <b-col sm="8">
                <b-card>
                    <b-tabs content-class="mt-1">
                        <b-tab title="Job Request">
                            <b-table
                                :items="lines"
                                :fields="fields"
                                striped
                                responsive
                                small
                            >
                                <template #cell(parts_info)="row">
                                    <b-form-checkbox
                                        v-model="row.detailsShowing"
                                        plain
                                        class="vs-checkbox-con"
                                        @change="row.toggleDetails"
                                    >
                                
                                    <span class="vs-label">{{ row.detailsShowing ? 'Hide' : 'Show' }}</span>
                                    </b-form-checkbox>
                                </template>
                                <template #row-details="row">
                                    <b-card>
                                        <b-table :fields="parts_fields" :items="row.item.parts"></b-table>
                                    </b-card>
                                </template>        
                            </b-table>
                        </b-tab>
                        <b-tab title="Approval">
                            <b-table
                                :items="approval"
                                :fields="approvalFields"
                                striped
                                responsive
                                small
                            >
                                <template #cell(date_sent)="data">
                                    {{ data.value | formatDate }}
                                </template> 
                                <template #cell(date_approved)="data">
                                    {{ data.value | formatDate }}
                                </template> 
                            </b-table>
                        </b-tab>
                        <b-tab title="Schedule">
                            <b-row>
                                <b-col>
                                    <b-row>
                                        <b-col>Technician</b-col>
                                        <b-col>
                                            <b-link v-b-toggle.sidebar-right v-if="user.user_type == 'Administrator'">
                                                {{ header.technician_id != null ? header.technician_name : 'Set technician'}}
                                            </b-link>
                                            <span v-if="user.user_type == 'Regular'">{{ header.technician_name }}</span>
                                        </b-col>
                                    </b-row>
                                    <b-row>
                                        <b-col>Schedule start date</b-col>
                                        <b-col>
                                            <span v-if="header.start_date != null && user.user_type == 'Regular'">{{ header.start_date | formatDateTime }}</span>
                                            <b-link v-b-toggle.sidebar-right v-if="user.user_type == 'Administrator'">
                                                <span v-if="header.start_date != null">{{ header.start_date | formatDateTime }}</span>
                                                <span v-if="header.start_date == null">Set start date</span>
                                            </b-link>
                                        </b-col>
                                    </b-row>
                                    <b-row>
                                        <b-col>Completion date</b-col>
                                        <b-col>
                                            <span v-if="user.user_type == 'Regular' && header.completion_date != ''">{{ header.completion_date | formatDateTime }}</span>
                                            <b-link v-if="user.user_type == 'Administrator' && header.completion_date === null"  v-b-toggle.completion-details>Set as completed</b-link>
                                            <b-link v-if="user.user_type == 'Administrator' && header.completion_date != ''"  v-b-toggle.completion-details>{{ header.completion_date | formatDateTime }}</b-link>
                                        </b-col>
                                    </b-row>
                                </b-col>
                                
                                <b-col></b-col>
                            </b-row>
                        </b-tab>
                    </b-tabs>
	            </b-card>
           
            </b-col>

        </b-row>

        <!-- Sidebar Overlay -->
        <b-sidebar
            id="sidebar-right"
            ref="formSideBar"
            bg-variant="white"
            right
            backdrop
            shadow
            no-header
        >
            <b-overlay
                :show="form.busy"
                rounded="sm"
            >
            <div class="d-flex justify-content-between align-items-center content-sidebar-header px-2 py-1">
                <h5 class="mb-0">
                    Schedule
                </h5>
                <div>
                    <feather-icon
                        icon="SaveIcon"
                        class="cursor-pointer"
                        @click="saveSchedule"
                    />
                </div>
            </div>
            <!-- Body -->
            <!-- Form -->
            <b-form
                class="p-2"  
            >
                <b-form-group
                    label="Technician"
                    label-for="Technician"
                >
                    <v-select
                        size="sm"
                        v-model="form.assigned_technician_id"
                        :options="technicians"
                        label="first_name"
                    >
                        <template v-slot:option="option">
                            {{ option.first_name + ' ' + option.last_name }}
                        </template>
                    </v-select>
                </b-form-group>
                <!-- Start Date -->
                <b-form-group
                    label="Start Date"
                    label-for="start-date"
                >
                    <flat-pickr
                        v-model="form.start_date"
                        class="form-control"
                        :config="{ enableTime: true, dateFormat: 'Y-m-d H:i'}"
                    />
                    
                </b-form-group>

            
                <!-- <div class="d-flex mt-2">
                    <b-button
                        v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                        variant="primary"
                        class="mr-2"
                        type="submit"
                    >
                        Save
                    </b-button>
                </div> -->
            </b-form>

            </b-overlay>
        </b-sidebar>

        <b-sidebar
            id="completion-details"
            bg-variant="white"
            right
            backdrop
            shadow
            no-header
            ref="completionDetails"
            width="800px"
        >
            <div class="d-flex justify-content-between align-items-center content-sidebar-header px-2 py-1">
                <h5 class="mb-0">
                    Complete Job Order
                </h5>
                <div>
                    <feather-icon
                        icon="SaveIcon"
                        class="cursor-pointer"
                        @click="saveCompletion"
                    />
                </div>
            </div>

             <b-form
                    class="p-2"  
                >
                    <b-overlay
                        :show="blockCompletion"
                        rounded="sm"
                    >
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Job</th>
                                    <th>Job Done</th>
                                    <th>OP Code</th>
                                    <th>Labor charge</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(line, index) in lines" :key="index">
                                    <td>{{ line.job_type }}</td>
                                    <td><b-form-input v-model="line.job_done" /></td>
                                    <td><b-form-input v-model="line.op_code" /></td>
                                    <td><b-form-input v-model="line.labor_charge" /></td>
                                </tr>
                            </tbody>
                        </table>
                    </b-overlay>
                </b-form>

        </b-sidebar>
    </div>
</template>
<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
  @import '@core/scss/vue/libs/vue-flatpicker.scss';
</style>
<script>

import {
	BCard, 
	BCardText, 
	BBadge, 
	BRow, 
	BCol, 
	BButton,
    BTabs, 
    BTab,
    BTable,
    BFormCheckbox,
    BLink,
    BSidebar,
    VBToggle,
    BFormInvalidFeedback,
    BForm,
    BFormInput,
    BFormGroup,
    BFormDatepicker,
    BOverlay
} from 'bootstrap-vue'
import flatPickr from 'vue-flatpickr-component'
import axios from 'axios';
import Ripple from 'vue-ripple-directive'
import statusColors from '@core/app-config/status.config.json';
import moment from 'moment';
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required, email, url } from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation'
import vSelect from 'vue-select'
import useJwt from '@/auth/jwt/useJwt'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
	components: {
		BCard, 
        vSelect,
        BCardText, 
        BBadge, 
        BRow, 
        BCol, 
        BButton,
        BTabs, 
        BTab,
        BTable,
        BFormCheckbox,
        BLink,
        BSidebar,
        ValidationProvider,
        BFormInvalidFeedback,
        ValidationObserver,
        BForm,
        BFormInput,
        BFormGroup,
        flatPickr,
        BFormDatepicker,
        ToastificationContent,
        BOverlay
	},
    directives: {
        'b-toggle': VBToggle,
        Ripple
    },
	data() {
		return {
            dateNtim: null,
            header : {},
            lines : [],
            user : useJwt.getUser(),
            parts_fields : [
                { key : 'part_no' , label : 'Part No.'},
                { key : 'part_description' , label : 'Part Description.'},
                { key : 'quantity' , label : 'Quantity'},
            ],
            fields : [
                {
                    key : 'job_type',
                    label : 'Job'
                },
                {
                    key : 'job_done',
                    label : 'Job Done'
                },
                {
                    key : 'op_code',
                    label : 'OP Code'
                },
                {
                    key : 'labor_charge',
                    label : 'Labor charge'
                },
                {
                    key : 'parts_info',
                    label : 'Parts'
                }
            ],
            approval : [],
            approvalFields : [
                {
                    key : 'sequence_no',
                    label : 'Sequence'
                },
                {
                    key : 'approver_name',
                    label : 'Approver Name'
                },
                {
                    key : 'email_address',
                    label : 'Email'
                },
                {
                    key : 'status',
                    label : 'Status'
                },
                {
                    key : 'date_sent',
                    label : 'Date sent'
                },
                {
                    key : 'date_approved',
                    label : 'Date approved'
                },
                {
                    key : 'remarks',
                    label : 'Remarks'
                }
            ],
            technicians : [],
            isEventHandlerSidebarActive : false,
            form : {
                job_order_id : '',
                start_date : '',
                completion_date : '',
                assigned_technician_id : '',
                busy : false
            },
            blockCompletion : false
            
    	}
	},
	mounted() {
		this.loadData();
	},
	methods: {
		loadData(){
            let headerApi = 'api/job-order/header/get/' + this.$route.params.jobOrderId;
            let lineApi = 'api/job-order/line/get/' + this.$route.params.jobOrderId;
            let approvalApi = 'api/job-order/approval/' + this.$route.params.jobOrderId;
            this.form.job_order_id  = this.$route.params.jobOrderId;
            let technicianApi = 'api/technician/get';
            const headerReq = axios.get(headerApi);
            const lineReq = axios.get(lineApi);
            const approvalReq = axios.get(approvalApi);
            const technicianReq = axios.get(technicianApi);
            var self = this;
            axios.all([headerReq, lineReq, approvalReq, technicianReq]).then(axios.spread((...responses) => {
                self.header = responses[0].data;
                self.lines = responses[1].data;
                self.approval = responses[2].data;
                self.technicians = responses[3].data;
            })).catch(errors => {

            }).finally( () => {

            });
        },
        saveSchedule(){
            var self = this;
            self.form.busy = true;
            axios.patch('api/job-order/schedule/update', self.form).then( ({res}) => {
                self.header.technician_name = self.form.assigned_technician_id.first_name + ' ' + self.form.assigned_technician_id.last_name; 
                self.header.start_date = this.form.start_date;
                self.header.technician_id = self.form.assigned_technician_id;
                self.showToast('success', 'Successfully saved changes.');
                self.$refs.formSideBar.hide();
            }).catch(({err}) => {
                console.log(err);
                self.showToast('error', 'Failed saving changes, try again.');
            }).finally( () => {
                self.form.busy = false;
            });
        },
        showToast(variant, message) {
            this.$toast({
                component: ToastificationContent,
                props: {
                title: 'Notification',
                icon: 'BellIcon',
                text: message,
                variant,
                },
            })
        },
        saveCompletion(){ 
            this.blockCompletion = true;  
            axios.post('api/job-order/complete', {
                job_order_id : this.header.id,
                jobs : this.lines
            }).then(response => {
                this.header.completion_date = response.data.completion_date;
                this.showToast('success', response.data.message);
                this.$refs.completionDetails.hide();
            }).catch( (err) => {
                this.showToast('error', 'Failed completion, please report to Sys Admin');
                console.log(err);
            }).finally(() => {
                this.blockCompletion = false;
            });
        }
	},
    filters : {
        formatDate: function(value) {
            if (value) {
                return moment(String(value)).format('MM/DD/YYYY')
            }
        },
        formatDateTime: function(value) {
            if (value) {
                return moment(String(value)).format('MM/DD/YYYY hh:mm A')
            }
        },
    },
}
</script>

<style>

</style>
