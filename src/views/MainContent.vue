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
          :name="item.origin"
          :bold="true"
          :title="'origin: ' + item.origin + ' - Final: ' + item.final + ' - Date: ' + item.date + ' - Time: ' + item.time"
          :to="{ name: 'RideDetails', params: { id: item.id, origin: item.origin, final: item.final, date: item.date, time: item.time, agency: item.agency, bmf_id : item.bmf_id, r2g_id: item.r2g_id}}"
        >
          <template #subname>
            <div v-if="!editing">{{ 'origin: ' }}</div>
            <div v-else><h2>Editing</h2></div>

          </template>

          <template #actions>
            <NcActions :inline="2"  >
              <NcActionButton aria-label="Edit" @click="editItem()">
                <template #icon>
                  <Pencil :size="20" />
                  Edit
                </template>
              </NcActionButton>
              <NcActionButton @click="editItem()" ariaLabel="Delete">
                <template #icon>
                  <Delete :size="20" />
                </template>
              </NcActionButton>
            </NcActions>
          </template>
        </NcListItem>
      </ul>
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
import NcActionTextEditable from '@nextcloud/vue/dist/Components/NcActionTextEditable.js'






  export default {
    name: "MainContent",
  
    components: {
      NcListItem,
      NcActionButton,
      NcActions,
      Delete,
      Pencil,
      NcLoadingIcon,
      NcActionTextEditable
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
        editing: false,
        editorigin: this.origin,
        editFinal: this.final,
        editDate: this.date,
        editTime: this.time,
     
      };
    },
  
  
    mounted() {
      this.fetchData();

    },

    methods: {

    

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
            const closingBracket = this.jsonData.lastIndexOf("]");

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
  
      editItem() {

        this.editing = true;
     
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
  

