<template>
    <div class="details">
        <h1>Ride Details</h1>
    
        <h2>Ride id: {{ id }}</h2>
        <h2>Original: {{ original }}</h2>
        <h2>Final destination: {{ final }}</h2>
        <h2>Ride date: {{ date }}</h2>
        <h2>Ride time: {{ time }}</h2>
        <button @click="editRide">Edit</button>
        <button @click="backToList">Back to list</button>
    
    
    </div>
    </template>
    
    
    <script>
    
    export default {
    name: 'RideDetails',
    props: ['id', 'original', 'final', 'date', 'time'],
    
    methods: {
        backToList() {
            this.$router.push({ name : 'MainContent'});
        },

        editRide() {
        
        }   
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