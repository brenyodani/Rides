<template>
    <div>
      <ul>
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
              <NcActionButton @click="deleteItem(index)">
                <template #icon>
                  <Delete :size="20" />
                </template>
              </NcActionButton>
            </NcActions>
          </template>
        </NcListItem>
      </ul>

    <div>
      <ul>
        <NcListItem
          v-for="(item, index) in externalJsonResponse"
          :key="item.id"
          :name="item.id"
          :title="'Original: ' + item.origin + ' - Final: ' + item.destination + ' - Date: ' + item.date + ' - Time: ' + item.time"
          :to="{ name: 'RideDetails', params: { id: item.id, original: item.original, final: item.final, date: item.date, time: item.time }}"

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
    <button @click="loginBesserMitFahren()">Get Logged In External Data</button>
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
  import { generateUrl } from "@nextcloud/router";
  
  export default {
    name: "FirstPage",
    components: {
      NcListItem,
      NcActionButton,
      NcActions,
      Delete,
      Pencil
    },
    data() {
      return {
        jsonData: [],
        items: [],
        jsonResponse: {},
        externalData: [],
        externalResponse: {},
        externalLoginData: [],
        data: {},
        externalJsonResponse: {}
      };
    },

    mounted() {

      var authorUrl = OC.generateUrl('/apps/rides/rides/');
  
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
          })
          .catch((error) => {
            console.error(error);
          });
    },


   

    methods: {
      
    loginBesserMitFahren() {
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
          
          this.externalJsonResponse = JSON.parse(`[${afterCleanedData}]`);
          }
          console.log(this.externalJsonResponse);

          })
          .catch((error) => {
            console.error(error);
          });
      }
 
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
  

