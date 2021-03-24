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
                                <span class="font-weight-bolder">{{ header.created_at | formatDate }}</span>
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
                    </b-tabs>
                    
	            </b-card>
            </b-col>

        </b-row>
	
    </div>
</template>
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
    BFormCheckbox
} from 'bootstrap-vue'

import axios from 'axios';
import Ripple from 'vue-ripple-directive'
import statusColors from '@/@core/app-config/status.config.json';
import moment from 'moment';
export default {
	components: {
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
        
	},
	data() {
		return {
            header : {},
            lines : [],
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
            ]
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
            const headerReq = axios.get(headerApi);
            const lineReq = axios.get(lineApi);
            const approvalReq = axios.get(approvalApi);
            var self = this;
            axios.all([headerReq, lineReq, approvalReq]).then(axios.spread((...responses) => {
                self.header = responses[0].data;
                self.lines = responses[1].data;
                self.approval = responses[2].data;
            })).catch(errors => {

            }).finally( () => {

            });
        
        }
	},
    filters : {
        formatDate: function(value) {
            if (value) {
                return moment(String(value)).format('MM/DD/YYYY')
            }
        },
    },
}
</script>

<style>

</style>
