import axios from 'axios';

// actions
export const GET_CUSTOMER_TYPES = "getCustomerTypes";
export const GET_JOB_TYPES = "getJobTypes";
export const GET_DEPARTMENTS = "getDepartments";
export const GET_SECTIONS = "getSections";
export const GENERATE_PARTS = "generateParts";
export const GET_VEHICLE = "getVehicle";

// mutations
export const SET_CUSTOMER_TYPES = "setCustomerTypes";
export const SET_JOB_TYPES = "setJobTypes";
export const SET_DEPARTMENTS = "setDepartments";
export const SET_SECTIONS = "setSections";
export const ADD_JOB = "addJob";
export const DELETE_JOB = "deleteJob";
export const ADD_PART = "addPart";
export const SET_JOB_PARTS = "setJobParts";
export const UPDATE_FORM = "updateForm";
export const UPDATE_BLOCKUI = "updateBlockui";

const state = {
    blockui : {
        message : 'Please wait',
        html : '<i class="fa fa-cog fa-spin fa-3x fa-fw"></i>',
        state : false
    },
    customerTypes : [],
    jobTypes : [],
    departments : [],
    sections : [],
    jobRequestParts : [
        {
            job_request_id : 1,
            part_no : '1001',
            part_description : 'Part 1',
            quantity : 1,
            remarks : ''
        },
        {
            job_request_id : 1,
            part_no : '1002',
            part_description : 'Part 2',
            quantity : 1,
            remarks : ''
        },
        {
            job_request_id : 2,
            part_no : '1003',
            part_description : 'Part 3',
            quantity : 5,
            remarks : ''
        }
    ],
    form : {
        vin : '',
        engine : '',
        csNo : '',
        plateNo : '',
        mileage : '',
        salesModel : '',
        customerType : '',
        customerName : '',
        section : '',
        department : '',
        contactNo : '',
        dateSold : '',
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
        ]
    },
};

const getters = {

};

const actions = {
    [GET_CUSTOMER_TYPES]({commit}){
        axios.get('api/customer-types/get').then(res => {
            commit(SET_CUSTOMER_TYPES, res.data);
        }).catch(err => {
            console.log(err);
        });
    },
    [GET_JOB_TYPES]({commit}){
        axios.get('api/job-types/get').then(res => {
            commit(SET_JOB_TYPES, res.data);
        }).catch(err => {
            console.log(err);
        });
    },
    [GET_DEPARTMENTS]({commit}){
        axios.get('api/departments/get').then(res => {
            commit(SET_DEPARTMENTS, res.data);
        }).catch(err => {
            console.log(err);
        });
    },
    [GET_SECTIONS]({commit}){
        axios.get('api/sections/get').then(res => {
            commit(SET_SECTIONS, res.data);
        }).catch(err => {
            console.log(err);
        });
    },
    [GENERATE_PARTS](context, jobIndex){
        let jobId = state.form.jobRequest[jobIndex].job.id;
        let salesModel = state.form.salesModel;

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
    [GET_VEHICLE](context){
        context.commit(UPDATE_BLOCKUI, {key : 'state' , value : true});
        axios.get('api/vehicle/get-by-vin/' + state.form.vin).then(res => {
            let vehicle = res.data;
            context.commit(UPDATE_BLOCKUI, {key : 'state' , value : false});
            context.commit(UPDATE_FORM, {key : 'csNo', value : vehicle.serial_number});
            context.commit(UPDATE_FORM, {key : 'engine', value : vehicle.engine});
            context.commit(UPDATE_FORM, {key : 'mileage', value : vehicle.mileage});
            context.commit(UPDATE_FORM, {key : 'salesModel', value : vehicle.sales_model});
        }).catch(err => {
            console.log(err);
        });
    }
};

const mutations = {
    [SET_CUSTOMER_TYPES](state, customerTypes){
        state.customerTypes = customerTypes;
    },
    [SET_JOB_TYPES](state, jobTypes){
        state.jobTypes = jobTypes;  
    },
    [SET_DEPARTMENTS](state, departments){
        state.departments = departments;
    },
    [SET_SECTIONS](state, sections){
        state.sections = sections;
    },
    [ADD_JOB](state, job){
        state.form.jobRequest.push(job);
    },
    [DELETE_JOB](state, index){
        state.form.jobRequest.splice(index, 1);
    },
    [ADD_PART](state, jobIndex){
        var job_request_id = state.form.jobRequest[jobIndex].job.id;
        var newPart = {
            job_request_id : job_request_id,
            part_no : '',
            part_description : '',
            quantity : '',
            remarks : ''
        };
        state.form.jobRequest[jobIndex].parts.push(newPart);
    },
    [SET_JOB_PARTS](state, data){
        state.form.jobRequest[data.index].parts = data.parts;
    },
    //--> Dynamic Data Bindings from vue component
    [UPDATE_FORM](state, payload) {
        state.form[payload.key] = payload.value
    },
    [UPDATE_BLOCKUI](state, payload) {
        state.blockui[payload.key] = payload.value
    }
};


export default {
    state,
    actions,
    mutations,
    getters
};