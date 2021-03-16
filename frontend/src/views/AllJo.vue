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

			<b-col cols="12">
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
			
				<template #cell(status)="data">
				<b-badge :variant="status[1][data.value]">
					{{ status[0][data.value] }}
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
  BCard, BCardText, BTable, BAvatar, BBadge, BRow, BCol, BFormGroup, BFormSelect, BPagination, BInputGroup, BFormInput, BInputGroupAppend, BButton,
} from 'bootstrap-vue'


export default {
  components: {
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
        {
          key: 'id', label: 'Id',
        },
        {
          key: 'avatar', label: 'Avatar',
        },
        { key: 'full_name', label: 'Full Name', sortable: true },
        { key: 'post', label: 'Post', sortable: true },
        'email',
        'city',
        'start_date',
        'salary',
        'age',
        'experience',
        { key: 'status', label: 'Status', sortable: true },
      ],
      items: [
        {
			id: 1,
			// eslint-disable-next-line global-require
			full_name: "Korrie O'Crevy",
			post: 'Nuclear Power Engineer',
			email: 'kocrevy0@thetimes.co.uk',
			city: 'Krasnosilka',
			start_date: '09/23/2016',
			salary: '$23896.35',
			age: '61',
			experience: '1 Year',
			status: 2,
        },
      ],
      status: [{
        1: 'Current', 2: 'Professional', 3: 'Rejected', 4: 'Resigned', 5: 'Applied',
      },
      {
        1: 'light-primary', 2: 'light-success', 3: 'light-danger', 4: 'light-warning', 5: 'light-info',
      }],
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
    // Set the initial number of items
    this.totalRows = this.items.length
  },
  methods: {
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
  },
}
</script>

<style>

</style>
