<template>
	  <NcAppNavigation>
		<NcAppNavigationItem to="/list" name="Rides">
		</NcAppNavigationItem>
		<NcAppNavigationItem to="/view" name="Create Ride">
		</NcAppNavigationItem>
		<NcAppNavigationItem to="/settings" name="User settings">
		</NcAppNavigationItem>
	  </NcAppNavigation>
  </template>
  
<script>

import NcAppNavigation from '@nextcloud/vue/dist/Components/NcAppNavigation.js'
import NcAppNavigationItem from '@nextcloud/vue/dist/Components/NcAppNavigationItem.js'



export default {
	name: 'Navigation',

	components: {
		NcAppNavigation,
		NcAppNavigationItem
	},

	directives: {
	
	},

	props: {
		
	},

	computed: {
		
	},
	
	methods: {
		
	},
}
</script>
<style scoped lang="scss">

</style>