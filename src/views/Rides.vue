<template>
    <div>

      <div>
        <p v-if="errors.length">
          <b>Correct the following errors:</b>
            <ul>
              <li v-for="error in errors">{{ error }}</li>
            </ul>
        </p>
      </div>




        <div class="create">
          <h1>Create a Ride</h1>
          <input type="text" v-model="originInput" placeholder="origin" @keyup.enter="addItem" />
          <input type="text" v-model="finalInput" placeholder="Final" @keyup.enter="addItem" />
          <input type="date" v-model="dateInput" placeholder="Date" @keyup.enter="addItem" />
          <input type="time" v-model="timeInput" placeholder="Time" @keyup.enter="addItem" />
          <button @click="addItem">Create Ride</button>
        </div>
       
        <div>
          <button @click="registerBMF()">Register Ride to BesserMitFahren</button>
        </div>
        <div>
          <button @click="registerR2G()">Register Ride to Ride2Go</button>
        </div>
      </div>
      
    </template>
    
    <script>
  
  import axios from 'axios';


  export default {
  name: "Rides",
  
  components: {
  
  
  }, 
  
  data() {
      return {
          originInput: "",
          finalInput: "",
          dateInput: "",
          timeInput: "",
          items: [],
          errors: [],
       
      };
  },
  methods: {

   // Function to validate inputs
    validateInputs() {
      const specialCharRegex = /[^\w\sÉ]/;
      this.errors = [];

      if (!this.originInput.trim()) {
        this.errors.push('origin field cannot be empty');
      }
      if (!this.finalInput.trim()) {
        this.errors.push('Final field cannot be empty');
      }
      if (!this.dateInput.trim()) {
        this.errors.push('Date field cannot be empty');
      }
      if (!this.timeInput.trim()) {
        this.errors.push('Time field cannot be empty');
      }
      if (specialCharRegex.test(this.originInput)) {
        this.errors.push('origin field contains special characters');
      }
      if (specialCharRegex.test(this.finalInput)) {
        this.errors.push('Final field contains special characters');
      }
      
      return this.errors.length === 0;
    },

    // Function to add a new item
    addItem() {
      const inputsValid = this.validateInputs();

      if (!inputsValid) {
        return;
      }
      
      const data = {
        id: null,
        origin: this.originInput,
        final: this.finalInput,
        date: this.dateInput,
        time: this.timeInput,
      };

      this.postData(data);
    },

    // Function to post data using axios
    postData(data) {
      axios.post('/index.php/apps/rides/api/0.1/rides', data)
        .then(response => {
          console.log(response.data);
          this.navigateToMainContent();

        })
        .catch(error => {
          console.error(error);
        });
    },

    // Function to navigate to MainContent
    navigateToMainContent() {
      this.$router.push({ name: 'MainContent' });
    },


    // registering rides through Bessermitfahren
    registerBMF() {

      const data = {
        id: null,
        origin: this.originInput,
        final: this.finalInput,
        date: this.dateInput,
        time: this.timeInput,
      };

      
      this.loading = true;
      axios({
        method: 'POST',
        url:'/index.php/apps/rides/registerbessermitfahren',
        headers: {
          'Accept': 'application/json',
          "Content-Encoding": "application/json"
        },
        data: data,


      }).then((response) => {
        this.loading = true;
        console.log(response.data);
        this.loading = false;
      })
        .catch((error) => {
          console.error(error);
        });
},

registerR2G() {

const data = {
  id: null,
  origin: this.originInput,
  final: this.finalInput,
  date: this.dateInput,
  time: this.timeInput,
};


this.loading = true;
axios({
  method: 'POST',
  url:'/index.php/apps/rides/registerr2g',
  headers: {
    'Accept': 'application/json',
    "Content-Encoding": "application/json"
  },
  data: data,


}).then((response) => {
  this.loading = true;
  console.log(response.data);
  this.loading = false;
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
      display: block;
      margin: auto;
      justify-content: center;
      align-items: center;
      margin-left: 100px;
      flex-direction: column;
  }
  
  
  </style>
  
  
  