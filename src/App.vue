<template>
	<NcContent app-name="Rides">
		<Navigation />
		<NcAppContent>
			<router-view/>
		</NcAppContent>
	</NcContent>
</template>

<script>

import {
	NcContent,
	NcAppContent,
} from '@nextcloud/vue'

import Navigation from './components/Navigation.vue'
import MainContent from './views/MainContent.vue'



export default {
	name: 'App',
	components: {
		Navigation,
		NcContent,
		MainContent,
		NcAppContent,
	},

	computed: {	
	},
	methods: {
		
	}
}
</script>
<style scoped>
	#app-content > div {
		width: 100%;
		height: 100%;
		padding: 20px;
		display: flex;
		flex-direction: column;
		flex-grow: 1;
	}

	input[type='text'] {
		width: 250px;
	}

	textarea {
		flex-grow: 1;
		width: 250px;
	}	
	.wrapper {
		height: 100px;
		width: 350px;
		margin: auto;
		padding-top: 100px;
		padding-bottom: 250px;
	}

	.list-item {
		background-color: #ffffff;
	}
	
</style>
