<template>
    <div class="details" v-if="!editing">
        <h1>Ride Details</h1>
        <h2>origin: {{ origin }}</h2>
        <h2>Final destination: {{ final }}</h2>
        <h2>Ride date: {{ date }}</h2>
        <h2>Ride time: {{ time }}</h2>
        <button @click="editRide">Edit</button>
        <button @click="deleteRide">Delete Ride</button>
        <button @click="backToList">Back to list</button>
    </div>

    <div class="details" v-else>
        <input type="text" v-model="editorigin" placeholder="origin" @keyup.enter="addItem" />
        <input type="text" v-model="editFinal" placeholder="Final" @keyup.enter="addItem" />
        <input type="date" v-model="editDate" placeholder="Date" @keyup.enter="addItem" />
        <input type="time" v-model="editTime" placeholder="Time" @keyup.enter="addItem" />
        <button @click="saveRide">Save Ride</button>

    </div>
    </template>
    
    
    <script>
    
    import axios from 'axios';



    export default {
    name: 'RideDetails',
    props: ['id', 'origin', 'final', 'date', 'time', 'agency', 'bmf_id', 'r2g_id'],
    
    data(){
        return {
            editing: false,
            editorigin: this.origin,
            editFinal: this.final,
            editDate: this.date,
            editTime: this.time,
            items: []
        }
    },


    methods: {
        backToList() {
            this.$router.push({ name : 'MainContent'});
        },

        editRide() {
            this.editing = true;

        },   

        saveRide() {
            const baseURL = window.location.href;


            const data  = {
            id: this.$props.id,
            origin: this.editorigin,
            final: this.editFinal,
            date: this.editDate,
            time: this.editTime,
            agency: this.agency,
            bmf_id: this.bmf_id,
            r2g_id: this.r2g_id
          };

            axios.post('/index.php/apps/rides/api/0.1/edit', data)
            .then(response => {
            console.log(response.data);
        })
        .catch(error => {
            console.error(error);
        });

    this.$router.push({ name: 'MainContent' });
},

        deleteRide() {


            const baseURL = window.location.origin + "/index.php/apps/rides"; 

            const id = this.$props.id;

            const data = {
                id: this.$props.id,
                bmf_id: this.bmf_id,
                r2g_id: this.r2g_id
            }
            axios.post(`${baseURL}/api/0.1/delete`, data)
            .then(response => {
            console.log(response.data);
            this.$router.push({ name : 'MainContent'});
            }) .catch(error => {
            console.error(error);
            });


        },
        }
    }
    
    
    
    
    
    </script>
    
    <style>
    
    .details {
        display: flex;
        margin: auto;
        justify-content: center;
        flex-direction: column;
        align-items: center;
        margin-left: 100px;
      }
    </style>