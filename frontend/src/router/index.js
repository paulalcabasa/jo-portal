import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const router = new VueRouter({
	mode: 'history',
	base: 'jo-portal',
	scrollBehavior() {
		return { x: 0, y: 0 }
	},
	routes: [
		{
			path: "/",
      		redirect: "/all-jo",
      		component: () => import('@/views/AllJo.vue'),
		},
		{
			path: '/create-jo',
			name: 'create-jo',
			component: () => import('@/views/JobOrderForm.vue'),
			meta: {
			pageTitle: 'Job Order',
				breadcrumb: [
					{
					text: 'Create',
					active: true,
					},
				],
			},
		},
		{
			path: '/all-jo',
			name: 'all-jo',
			component: () => import('@/views/AllJo.vue'),
			meta: {
			pageTitle: 'Job Order',
				breadcrumb: [
					{
						text: 'List',
						active: true,
					},
				],
			},
		},
		{
			path: '/jo-details/:jobOrderId',
			name: 'jo-details',
			component: () => import('@/views/ViewJo.vue'),
			meta: {
				pageTitle: 'Job Order',
				breadcrumb: [
					{
						text: 'View',
						active: true,
					},
				],
			},
		},
		{
			path: '/edit-jo/:jobOrderId',
			name: 'edit-jo',
			component: () => import('@/views/JobOrderForm.vue'),
			meta: {
			pageTitle: 'Job Order',
				breadcrumb: [
					{
					text: 'Edit',
					active: true,
					},
				],
			},
		},
		{
			path: '/calendar',
			name: 'calendar',
			component: () => import('@/views/calendar/Calendar.vue'),
			meta: {
			pageTitle: 'Calendar',
			breadcrumb: [
				{
				text: 'View',
				active: true,
				},
			],
			},
		},
	// {
	//   path: '/login',
	//   name: 'login',
	//   component: () => import('@/views/Login.vue'),
	//   meta: {
	//     layout: 'full',
	//   },
	// },
		{
			path: '/login',
			name: 'login',
			component: () => import('@/views/LoginJWT.vue'),
			meta: {
				layout: 'full',
			},
		},
		{
			path: '/error-404',
			name: 'error-404',
			component: () => import('@/views/error/Error404.vue'),
			meta: {
			layout: 'full',
			},
		},
		{
			path: '*',
			redirect: 'error-404',
		},
	],
})

export default router
