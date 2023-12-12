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
        <div class="grid">
            <div class="container">
              <label :for="tagOptions.props.inputId">{{ tagOptions.name }}</label>
              <NcSelect :no-wrap="false"
                v-bind="tagOptions.props"
                v-model="tagOptions.props.value" />
              </div>
        <div>
          <button @click="registerBMF()">Register Ride to BesserMitFahren</button>
        </div>
        <div>
          <button @click="registerR2G()">Register Ride to Ride2Go</button>
        </div>
      </div>
      </div>
    </template>
    
    <script>
  
  import axios from 'axios';
  import NcSelectTags from '@nextcloud/vue/dist/Components/NcSelectTags.js';
  import NcSelect from '@nextcloud/vue/dist/Components/NcSelect.js';


  export default {
  name: "Rides",
  
  components: {
  NcSelect,
  NcSelectTags
  
  }, 
  
  data() {
      return {
          originInput: "",
          finalInput: "",
          dateInput: "",
          timeInput: "",
          items: [],
          errors: [],
          
          // multiple select box  for agencies
          tagOptions: {
            name: 'Agencies',
            props: {
              multiple: true,
		          closeOnSelect: false,
              options: [
                'bmf',
                'r2g'
              ],
              value: [
               'bmf',
               'r2g'
               ]
            }
          },

          selectedAgencies: []
       
      };
  },
  methods: {


    getTagOptionsJSON() {
  const tagOptionsJSON = this.tagOptions.props.options.map(option => {
    return { name: option, value: this.tagOptions.props.value.includes(option) };
  });

  // Now tagOptionsJSON holds an array of objects containing name and value pairs
  console.log(tagOptionsJSON); // You can console.log or return this JSON array
  return tagOptionsJSON;
},

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
      
      
      const tagOptionsJSON = this.getTagOptionsJSON();



      const data = {
        id: null,
        origin: this.originInput,
        final: this.finalInput,
        date: this.dateInput,
        time: this.timeInput,
        agency: tagOptionsJSON
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
        agency: this.tagOptions.props.value
        
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
  
  .grid {
	display: grid;
	grid-template-columns: repeat(1, 1fr);
	gap: 10px;
}

.container {
	max-width: 350px;
	display: flex;
	flex-direction: column;
	gap: 2px 0;
}
  </style>
  
  
  