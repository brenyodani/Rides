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
        jsonResponse: {}
      };
    },

    mounted() {
        axios({
          method: 'GET',
          url:'http://192.168.21.6/index.php/apps/rides/api/0.1/get',
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
      fetchData() {
      },

      
      editItem() {
      },
      deleteItem() {
     },

     cleanResponse() {

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
  