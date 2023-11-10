import Vue from 'vue'
import Router from 'vue-router'
import { generateUrl } from '@nextcloud/router'
import Rides from './views/Rides.vue'
import MainContent from './views/MainContent.vue'
import UserSettings from './views/UserSettings.vue'
import RideDetails from './views/RideDetails.vue'
import FirstPage from './views/FirstPage.vue'



Vue.use(Router)

export default new Router({
	mode: 'history',
	base: generateUrl('apps/rides'),
	routes: [
		
		{
			path: '/',
			name: 'FirstPage',
			component: 	FirstPage
		},
		{
			path: '/list',
			name: 'MainContent',
			component: MainContent,
		},
		{
			path: '/view',
			name: 'Rides',
			component: Rides,
		},
		{
			path: '/settings',
			name: 'UserSettings',
			component: UserSettings,
		},
		{
			path: '/list/:id',
			name: 'RideDetails',
			component: RideDetails,
			props: true,
		},
	
	],
})




