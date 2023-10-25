<template>
	<NcContent app-name="Rides">
	  	<Navigation />
	  	<NcAppContent>
			<router-view></router-view>
		</NcAppContent>
	</NcContent>
	
  </template>
  
  <script>
  import Navigation from './components/Navigation.vue';
  import MainContent from './views/MainContent.vue';
  import NcAppContent from '@nextcloud/vue/dist/Components/NcAppContent.js'
    import NcContent from '@nextcloud/vue/dist/Components/NcContent.js'
  export default {
	name: 'App',
	components: {
		NcContent,
	  	Navigation,
	  	MainContent,
	  	NcAppContent,
	},
	
  };
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
