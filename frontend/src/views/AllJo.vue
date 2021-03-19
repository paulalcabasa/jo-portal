<template>
	<b-card>
		<b-row>
			
			<b-col
				md="4"
				sm="4"
				align="left"
				class="my-1"
			>
			<b-form-group
				label-align-sm="left"
				label-size="sm"
				label-for="sortBySelect"
				class="mb-0"
			>
				<b-input-group size="sm">
				<b-form-select
					id="sortBySelect"
					v-model="sortBy"
					:options="sortOptions"
					class="w-75"
				>
					<template v-slot:first>
					<option value="">
						-- none --
					</option>
					</template>
				</b-form-select>
				<b-form-select
					v-model="sortDesc"
					size="sm"
					:disabled="!sortBy"
					class="w-25"
				>
					<option :value="false">
					Asc
					</option>
					<option :value="true">
					Desc
					</option>
				</b-form-select>
				</b-input-group>
			</b-form-group>
			</b-col>
			<b-col
				md="8"
				sm="8"
				class="my-1"
				align="right"
			>
			<b-form-group
				label=""
				label-cols-sm="4"
				label-align-sm="right"
				label-size="sm"
				label-for="filterInput"
				class="mb-0"
			>
				<b-input-group size="sm">
				<b-form-input
					id="filterInput"
					v-model="filter"
					type="search"
					placeholder="Type to Search"
				/>
				<b-input-group-append>
					<b-button
					:disabled="!filter"
					@click="filter = ''"
					>
					Clear
					</b-button>
				</b-input-group-append>
				</b-input-group>
			</b-form-group>
			</b-col>

			<b-col>
				<b-table
					striped
					hover
					responsive
					:per-page="perPage"
					:current-page="currentPage"
					:items="items"
					:fields="fields"
					:sort-by.sync="sortBy"
					:sort-desc.sync="sortDesc"
					:sort-direction="sortDirection"
					:filter="filter"
					:filter-included-fields="filterOn"
					@filtered="onFiltered"
				>

				<template #cell(action)="row">
			
					<b-dropdown
						variant="link"
						toggle-class="text-decoration-none"
						no-caret
					>
						<template v-slot:button-content>
							<feather-icon
							icon="MoreVerticalIcon"
							size="16"
							class="text-body align-middle mr-25"
							/>
						</template>
							<b-dropdown-item @click="viewDetails(row.item.id)">
								<feather-icon
								icon="InfoIcon"
								class="mr-50"
								/>
								<span>View</span>
							</b-dropdown-item>
							<b-dropdown-item @click="edit(row.item.id)">
								<feather-icon
									icon="Edit2Icon"
									class="mr-50"
								/>
								<span>Edit</span>
							</b-dropdown-item>
							<b-dropdown-item>
								<feather-icon
								icon="TrashIcon"
								class="mr-50"
								/>
								<span>Cancel</span>
							</b-dropdown-item>
					</b-dropdown>

				</template>
				<template #cell(created_at)="data">
					{{ data.value | formatDate }}
				</template>
				<template #cell(status)="data">
					<b-badge :variant="status[data.value]">
						{{ data.value }} 
					</b-badge>
				</template>
				
			</b-table>
			</b-col>
			
			<b-col
				md="4"
				sm="4"
				align="left"
			>
			<b-form-group
				class="mb-0"
			>
				<b-form-select
					id="perPageSelect"
					v-model="perPage"
					size="sm"
					:options="pageOptions"
					class="w-50"
				/>
			</b-form-group>
			</b-col>

			<b-col
				cols="8"
			>
			<b-pagination
				v-model="currentPage"
				:total-rows="totalRows"
				:per-page="perPage"
				align="right"
				size="sm"
				class="my-0"
			/>
			</b-col>
		</b-row>
	</b-card>
</template>
<script>

import {
	BCard, 
	BCardText, 
	BTable, 
	BAvatar, 
	BBadge, 
	BRow, 
	BCol, 
	BFormGroup, 
	BFormSelect, 
	BPagination, 
	BInputGroup, 
	BFormInput, 
	BInputGroupAppend, 
	BButton,
	BDropdown,
    BDropdownItem,
	BDropdownDivider 
} from 'bootstrap-vue'

import axios from 'axios';
import Ripple from 'vue-ripple-directive'
import statusColors from '@/@core/app-config/status.config.json';
import moment from 'moment';
export default {
	components: {
		BBadge,
		BCard,
		BCardText,
		BTable,
		BAvatar,
		BRow,
		BCol,
		BFormGroup,
		BFormSelect,
		BPagination,
		BInputGroup,
		BFormInput,
		BInputGroupAppend,
		BButton,
		BDropdown,
    	BDropdownItem,
		BDropdownDivider
	},
	data() {
		return {
			perPage: 5,
			pageOptions: [3, 5, 10],
			totalRows: 1,
			currentPage: 1,
			sortBy: '',
			sortDesc: false,
			sortDirection: 'asc',
			filter: null,
			filterOn: [],
			infoModal: {
				id: 'info-modal',
				title: '',
				content: '',
			},
			fields: [
				{ key: 'action', label: 'Action' },
				{ key: 'id', label: 'JO #',},
				{ key: 'vin', label: 'VIN', sortable: true },
				{ key: 'sales_model', label: 'Model', sortable: true },
				{ key: 'customer_name', label: 'Customer', sortable: true },
				{ key: 'created_by', label: 'Created by', sortable: true },
				{ key: 'created_at', label: 'Date created', sortable: true },
				{ key: 'status', label: 'Status', sortable: true },
			],
			items: [],
			status: statusColors.statusColors,
    	}
	},
	computed: {
		sortOptions() {
			// Create an options list from our fields
			return this.fields
			.filter(f => f.sortable)
			.map(f => ({ text: f.label, value: f.key }))
		},
	},
	mounted() {
		this.loadData();
	},
	methods: {
		loadData(){
			axios.get('api/admin/job-order/list').then(res => {
				this.items = res.data;
				this.totalRows = res.data.length
			}).catch(err => {

			}).finally(() => {

			});
		},
		info(item, index, button) {
			this.infoModal.title = `Row index: ${index}`
			this.infoModal.content = JSON.stringify(item, null, 2)
			this.$root.$emit('bv::show::modal', this.infoModal.id, button)
		},
		resetInfoModal() {
			this.infoModal.title = ''
			this.infoModal.content = ''
		},
		onFiltered(filteredItems) {
			// Trigger pagination to update the number of buttons/pages due to filtering
			this.totalRows = filteredItems.length
			this.currentPage = 1
		},
		viewDetails(job_order_id){
			this.$router.push({
				name : 'jo-details',
				params : {
					jobOrderId : job_order_id
				}
			});
		},
		edit(job_order_id){
			this.$router.push({
				name : 'edit-jo',
				params : {
					jobOrderId : job_order_id
				}
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
