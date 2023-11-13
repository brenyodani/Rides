<template>
    <div>
      <div class="create">
        <h1>Ride listing</h1>

      </div>
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
  </template>
  

<script>

import axios from 'axios';
import NcListItem from '@nextcloud/vue/dist/Components/NcListItem.js'
import NcActionButton from '@nextcloud/vue/dist/Components/NcActionButton.js'
import NcActions from '@nextcloud/vue/dist/Components/NcActions.js'
import Delete from 'vue-material-design-icons/Delete'
import Pencil from 'vue-material-design-icons/Pencil'
import { generateUrl } from "@nextcloud/router"







  export default {
    name: "MainContent",
  
    components: {
      NcListItem,
      NcActionButton,
      NcActions,
      Delete,
      Pencil
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

     
     
      };
    },
  
    created() {
      this.items = this.$route.params.items || [];
    },
    
    mounted() {
      axios({
          method: 'GET',
          url:'/apps/rides/api/0.1/get',
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
      addItem() {


        if (this.input1 && this.input2 && this.input3 && this.input4) {
          const id = Date.now();
          const newItem = {
            id: id,
            original: this.input1,
            final: this.input2,
            date: this.input3,
            time: this.input4,
          };

          this.items.push(newItem);
         
        }
      
        const data  = {
            id: 1,
            original: this.input1,
            final: this.input2,
            date: this.input3,
            time: this.input4,
          };


        axios.post(baseURL + '/api/0.1/rides', data)
        .then(response => {
          console.log(response.data);
        }) .catch(error => {
          console.error(error);
        });

        this.input1 = "";
        this.input2 = "";
        this.input3 = "";
        this.input4 = "";
      },
  
      deleteItem(id) {

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
  

