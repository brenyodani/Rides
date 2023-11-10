<template>
    <div class="details" v-if="!editing">
        <h1>Ride Details</h1>
    
        <h2>Ride id: {{ id }}</h2>
        <h2>Original: {{ original }}</h2>
        <h2>Final destination: {{ final }}</h2>
        <h2>Ride date: {{ date }}</h2>
        <h2>Ride time: {{ time }}</h2>
        <button @click="editRide">Edit</button>
        <button @click="deleteRide">Delete Ride</button>
        <button @click="backToList">Back to list</button>
    </div>

    <div class="details" v-else>
        <input type="text" v-model="editOriginal" placeholder="Original" @keyup.enter="addItem" />
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
    props: ['id', 'original', 'final', 'date', 'time'],
    
    data(){
        return {
            editing: false,
            editOriginal: this.original,
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
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 48e4ffaea83ce9aa1ef50d4e50cd0703de988d1e
            
            const baseURL = window.location.href;


<<<<<<< HEAD
=======
=======
>>>>>>> 6ab165ccb6a63309458b38d69b614e4223a2592d
>>>>>>> 48e4ffaea83ce9aa1ef50d4e50cd0703de988d1e
            const data  = {
            id: this.$props.id,
            original: this.editOriginal,
            final: this.editFinal,
            date: this.editDate,
            time: this.editTime,
          };

<<<<<<< HEAD
            axios.post(baseURL + '/api/0.1/edit', data)
=======
<<<<<<< HEAD
            axios.post(baseURL + '/api/0.1/edit', data)
=======
            axios.post('http://192.168.21.6/index.php/apps/rides/api/0.1/edit', data)
>>>>>>> 6ab165ccb6a63309458b38d69b614e4223a2592d
>>>>>>> 48e4ffaea83ce9aa1ef50d4e50cd0703de988d1e
            .then(response => {
            console.log(response.data);
            }) .catch(error => {
            console.error(error);
            });

            this.$router.push({ name : 'MainContent'});

        },

        deleteRide() {
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 48e4ffaea83ce9aa1ef50d4e50cd0703de988d1e

            const baseURL = window.location.href;


            const id = this.$props.id;

           
            axios.post(baseURL + '/api/0.1/delete', id)
<<<<<<< HEAD
=======
=======
            const id = this.$props.id;

           
            axios.post('http://192.168.21.6/index.php/apps/rides/api/0.1/delete', id)
>>>>>>> 6ab165ccb6a63309458b38d69b614e4223a2592d
>>>>>>> 48e4ffaea83ce9aa1ef50d4e50cd0703de988d1e
            .then(response => {
            console.log(response.data);
            }) .catch(error => {
            console.error(error);
            });

            this.$router.push({ name : 'MainContent'});

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