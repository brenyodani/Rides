<template>
  <div>
    
    <div class="inputs">
      <h2>Create a Ride</h2>
     <input type="text" v-model="originInput" @input="fetchFromResults" placeholder="Origin" @keyup.enter="addItem" list="from_cityname"/>
      <datalist id="from_cityname">
        <div v-for="(result, index) in responsed" :key="index">
          <div v-for="(country, index) in result.features" :key="index">           
            <option :value = country.properties.name >{{ country.properties.name }}</option>
          </div>
        </div>
      </datalist>
    <input type="text" v-model="finalInput" @input="fetchToResults" placeholder="Final" @keyup.enter="addItem" list="to_cityname"/>
      <datalist id="to_cityname">
        <div v-for="(result, index) in toResponse" :key="index">
          <div v-for="(country, index) in result.features" :key="index">           
            <option :value = country.properties.name >{{ country.properties.name }}</option>
          </div>
        </div>
      </datalist>
    <input type="date" v-model="dateInput" placeholder="Date" @keyup.enter="addItem" />
    <input type="time" v-model="timeInput" placeholder="Time" @keyup.enter="addItem" />
    </div>
   

    <div class="grid">
      <div class="container">

        <label :for="tagOptions.props.inputId">{{ tagOptions.name }}</label>
        <NcSelect :no-wrap="false" v-bind="tagOptions.props" v-model="tagOptions.props.value" />
      </div>

      <button @click="addItem">Create Ride</button>
    </div>

    <div class="error-messages">
    <ul v-if="errors.length > 0">
      <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
    </ul>
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
          origin: '',
          destination: '',
          results: [],
          responsed: [],


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

          selectedAgencies: [],
          toResults:[],
          toResponse: []
       
      };
  },
  methods: {


    async fetchFromResults() {
      if(this.originInput.length > 2) {
      try {
        const response = await axios.get('/index.php/apps/rides/api/0.1/searchfromcity', {
          params: {
            origin: this.originInput
          } 
        
        });
        this.results = response.data;


          
        const startingBracket = this.results.indexOf("{");
        const closingBracket = this.results.lastIndexOf("}");

        const respondeString = this.results.substring(0, closingBracket + 1);
        const jsonResponse = JSON.parse(`[${respondeString}]`);

        this.responsed = jsonResponse

        


        console.log(this.responsed)
      } catch (error) {
        console.error('Error fetching results:', error);
      }
    }
    },

    async fetchToResults() {

      if(this.finalInput.length > 2) {
      try {
        const response = await axios.get('/index.php/apps/rides/api/0.1/searchtocity', {
          params: {
            final: this.finalInput
          } 
        
        });
        this.toResults = response.data;


          
        const startingBracket = this.toResults.indexOf("{");
        const closingBracket = this.toResults.lastIndexOf("}");

        const respondeString = this.toResults.substring(0, closingBracket + 1);
        const jsonResponse = JSON.parse(`[${respondeString}]`);

        this.toResponse = jsonResponse

        
      } catch (error) {
        console.error('Error fetching results:', error);
      }
    }
    },

        getTagOptionsJSON() {
      const tagOptionsJSON = this.tagOptions.props.options.map(option => {
        return { name: option, value: this.tagOptions.props.value.includes(option) };
      });

  
      return tagOptionsJSON;
},






   // Function to validate inputs
    validateInputs() {

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
     

      const today = new Date();
      const targetDate = new Date(this.dateInput);

      today.setHours(0, 0, 0, 0);
      targetDate.setHours(0, 0, 0, 0);

       if (targetDate < today) {
        this.errors.push('Date cannot be past');
      } 
      
      return this.errors.length === 0;
    },

    addItem() {
      const inputsValid = this.validateInputs();

      if (!inputsValid) {
        return;
      }
      
      
      const tagOptionsJSON = this.getTagOptionsJSON();

      var authorUrl = OC.generateUrl('/apps/rides/rides/');
      console.log(authorUrl);

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
      flex-direction: row;
      max-width: 75%;
  }
  
  .grid {
	display: grid;
	grid-template-columns: repeat(1, 1fr);
	gap: 10px;
  max-width: 75%;
  padding-left: 100px;
}

.container {
	max-width: 75%;
	display: flex;
	flex-direction: column;

	gap: 2px 0;
}

.inputs {
  display: block;
  align-items: center;
  justify-content: center;
  margin-left: 100px;
}

.inputs input {
  width: 80%;
}

.error-messages {
  display: flex;
  justify-content: flex-start;
  margin-left: 100px;
}
  </style>
  
  
  