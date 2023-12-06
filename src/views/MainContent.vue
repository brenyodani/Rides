<template>
    <div>
      <div class="create">
        <h1>Ride listing</h1>

        <div>
          <NcLoadingIcon  :size="64" v-if="loading"/>
        </div>

      </div>
      <div>
      <ul >
        <NcListItem
          v-for="(item, index) in jsonResponse"
          :key="item.id"
          :name="item.id"
          :title="'Original: ' + item.original + ' - Final: ' + item.final + ' - Date: ' + item.date + ' - Time: ' + item.time"
          :to="{ name: 'RideDetails', params: { id: item.id, original: item.original, final: item.final, date: item.date, time: item.time }}"
        >
          <template>
            <div>{{ 'Original: ' + item.original + ' - Final: ' + item.final + ' - Date: ' + item.date + ' - Time: ' + item.time }}</div>
          </template>
          <template #actions>
            <NcActions :inline="2">
              <NcActionButton @click="editItem(item.id)">
                <template #icon>
                  <Pencil :size="20" />
                </template>
              </NcActionButton>
              <NcActionButton @click="deleteItem(item.id)">
                <template #icon>
                  <Delete :size="20" />
                </template>
              </NcActionButton>
            </NcActions>
          </template>
        </NcListItem>
      </ul>
    </div>
      <div>
        <h2>Bessermitfahren</h2>
      <ul>
        <NcListItem
          v-for="(item, index) in externalResponse"
          :key="item.id"
          :name="item.id"
          :title="'Original: ' + item.origin + ' - Final: ' + item.destination + ' - Date: ' + item.date + ' - Time: ' + item.time"
          :to="{ name: 'RideDetails', params: {  original: item.original, final: item.final, date: item.date, time: item.time }}"

        >
          <template>
            <div>{{ 'Original: ' + item.origin + ' - Final: ' + item.destination + ' - Date: ' + item.date + ' - Time: ' + item.time }}</div>
          </template>
          <template #actions>
            <NcActions :inline="2">
              <NcActionButton @click="editItem(item.origin)">
                <template #icon>
                  <Pencil :size="20" />
                </template>
              </NcActionButton>
              <NcActionButton @click="deleteItem(item.origin)">
                <template #icon>
                  <Delete :size="20" />
                </template>
              </NcActionButton>
            </NcActions>
          </template>
        </NcListItem>
      </ul>
    <button @click="loginBesserMitFahren()">Get Logged In External Data</button>
  </div>

    <div>
      <h2>Ride2Go</h2>
      <ul>
        <NcListItem
          v-for="(item, index) in ride2GoJsonResponse"
          :key="item.origin"
          :name="item.origin"
          :title="'Original: ' + item.origin + ' - Final: ' + item.destination + ' - Date: ' + item.date + ' - Time: ' + item.time"
          :to="{ name: 'RideDetails', params: {  original: item.original, final: item.final, date: item.date, time: item.time }}"

        >
          <template>
            <div>{{ 'Original: ' + item.origin + ' - Final: ' + item.destination + ' - Date: ' + item.date + ' - Time: ' + item.time }}</div>
          </template>
          <template #actions>
            <NcActions :inline="2">
              <NcActionButton @click="editItem(item.id)">
                <template #icon>
                  <Pencil :size="20" />
                </template>
              </NcActionButton>
              <NcActionButton @click="deleteItem(index)">
                <template #icon>
                  <Delete :size="20" />
                </template>
              </NcActionButton>
            </NcActions>
          </template>
        </NcListItem>
      </ul>
      <button @click="loginR2G()">Scrape r2g</button>
    </div>
 
    </div>

   
  </template>
  

<script>

import axios from 'axios';
import NcListItem from '@nextcloud/vue/dist/Components/NcListItem.js';
import NcActionButton from '@nextcloud/vue/dist/Components/NcActionButton.js';
import NcActions from '@nextcloud/vue/dist/Components/NcActions.js';
import Delete from 'vue-material-design-icons/Delete';
import Pencil from 'vue-material-design-icons/Pencil';
import NcLoadingIcon from '@nextcloud/vue/dist/Components/NcLoadingIcon.js';







  export default {
    name: "MainContent",
  
    components: {
      NcListItem,
      NcActionButton,
      NcActions,
      Delete,
      Pencil,
      NcLoadingIcon
    },
  
    data() {
      return {
        input1: "",
        input2: "",
        input3: "",
        input4: "",
        items: [],
        jsonResponse: {},
        jsonData: [],
        componentKey: 0,
        externalData: [],
        externalResponse: {},
        externalLoginData: [],
        data: {},
        externalJsonResponse: {},
        ride2GoData: {},
        ride2GoResponse: {},
        ride2GoJsonResponse: {},
        loading: 'false',
     
      };
    },
  
    created() {
      this.items = this.$route.params.items || [];

      this.$watch( 
        () => this.$route.params,
        () => {
          this.fetchData();
        },
        { immediate : true }
      )
    },
    
    mounted() {
    
    },

    methods: {

      forceRerender() {
      this.componentKey += 1;
    },

    fetchData() {


      this.loading = true;


      axios({
          method: 'GET',
          url:'api/0.1/get',
          headers: {
            'Content-Type': 'application/json',
            'Accept-Encoding': 'application/json'
          },


        }).then((response) => {
            this.jsonData = response.data
           
            const startingBracket = this.jsonData.indexOf("[");
            const closingBracket = this.jsonData.indexOf("]");

            const respondeString = this.jsonData.substring(startingBracket + 1, closingBracket);
            const jsonResponse = JSON.parse(`[${respondeString}]`);
            console.log(jsonResponse);
            
            this.jsonResponse = jsonResponse

            this.loading = false;
          })
          .catch((error) => {
            console.error(error);
          });

          
    },
  
      editItem(id) {
        const item = this.items.find(item => item.id === id);      
      
        if (item) {
      this.$router.push({ 
        name: 'RideDetails', 
        params: { 
          id, 
          original: item.original, 
          final: item.final, 
          date: item.date, 
          time: item.time 
        } 
      });
    }
      },

    
      loginBesserMitFahren() {
        this.loading = true;
        axios({
          method: 'GET',
          url:'/index.php/apps/rides/loginbessermitfahren',
          headers: {
            'Accept': 'application/json',
            "Content-Encoding": "application/json"
          },


        }).then((response) => {

          this.externalLoginData = response.data;
          let lastBraceIndex = this.externalLoginData.lastIndexOf('}');
          if (lastBraceIndex !== -1) {
          const cleanedData = this.externalLoginData.substring(0, lastBraceIndex + 1);
          const afterCleanedData = cleanedData.replace(/\}(?=[^\}]*\})/g, '},');
          
          this.externalResponse = JSON.parse(`[${afterCleanedData}]`);
          }
          console.log(this.externalResponse);
          this.loading = false;
          })
          .catch((error) => {
            console.error(error);
          });
      },

      loginR2G() {
         this.loading = true;
        axios({
          method: 'GET',
          url:'/index.php/apps/rides/loginride2go',
          headers: {
            'Accept': 'application/json',
            "Content-Encoding": "application/json"
          },


        }).then((response) => {

          this.ride2GoData = response.data;
          let lastBraceIndex = this.ride2GoData.lastIndexOf('}');
          if (lastBraceIndex !== -1) {
          const cleanedData = this.ride2GoData.substring(0, lastBraceIndex + 1);
          const afterCleanedData = cleanedData.replace(/\}(?=[^\}]*\})/g, '},');
          
          this.ride2GoJsonResponse = JSON.parse(`[${afterCleanedData}]`);
          this.loading = false;
          }

          })
          .catch((error) => {
            console.error(error);
          });


      },


     


    },
  };
  </script>
  
  <style>
  .listitem {
    color: #000000;
  }
  
  .create {
    display: flex;
    margin: auto;
    justify-content: center;
    flex-direction: column;
    align-items: center;
    margin-left: 100px;
  }
  </style>
  

