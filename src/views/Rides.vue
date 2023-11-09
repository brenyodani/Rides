<template>
    <div>
        <div class="create">
          <h1>Create a Ride</h1>
          <input type="text" v-model="input1" placeholder="Original" @keyup.enter="addItem" />
          <input type="text" v-model="input2" placeholder="Final" @keyup.enter="addItem" />
          <input type="date" v-model="input3" placeholder="Date" @keyup.enter="addItem" />
          <input type="time" v-model="input4" placeholder="Time" @keyup.enter="addItem" />
  
          <button @click="addItem">Create Ride</button>
        </div>
      </div>
    </template>
    
    <script>
  
  
  export default {
  name: "Rides",
  
  components: {
  
  
  }, 
  
  data() {
      return {
          input1: "",
          input2: "",
          input3: "",
          input4: "",
          items: [],
      };
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


      axios.post('http://192.168.21.6/index.php/apps/rides/api/0.1/rides', data)
      .then(response => {
        console.log(response.data);
      }) .catch(error => {
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
      display: block;
      margin: auto;
      justify-content: center;
      align-items: center;
      margin-left: 100px;
      flex-direction: column;
  }
  
  
  </style>
  
  
  