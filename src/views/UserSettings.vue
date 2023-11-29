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
        <h1>User settings</h1>
        <input v-model="serciveNameInput" placeholder="Service name" />
        <input v-model="userNameInput" placeholder="Agency ID" />
        <input v-model="passwordInput" placeholder="Deeplink" />
        <input v-model="apiKeyInput" placeholder="API key" />
        <button @click="saveSettings">Save</button>
      </div>
      
      <div class="create">
        <h1>Bessermitfahren login details</h1>
        <input v-model="email" placeholder="Email address" type="email" />
        <input v-model="password" placeholder="Password"  type="password" />
        <button @click="saveBMFSettings">Save</button>
      </div>
      

      <div>
        <NcCheckboxRadioSwitch v-for="(item, index) in jsonResponse" :key="item.serviceName"  :checked.sync="enabledServices" :value="item.serviceName" name="enabledServices">
        {{ item.serviceName }}
      </NcCheckboxRadioSwitch>
      <button @click="saveApiConnections">Save Api endpoints</button>

	    </div>

      <div>
        {{ enabledServices }}
      </div>
      
    </div>
  </template>
  
  <script>
import NcCheckboxRadioSwitch from '@nextcloud/vue/dist/Components/NcCheckboxRadioSwitch.js'
import axios from 'axios';

  export default {
    name: "UserSettings",
    components: {
      NcCheckboxRadioSwitch
    },
    data() {
      return {
        serciveNameInput: "",
        userNameInput: "",
        passwordInput: "",
        apiKeyInput: "",
        settingsSaved: false,
        savedInputValues: [],
        errors: [],
        jsonData: [],
        jsonResponse: {},
        enabledServices: [],
        email: "",
        password: ""
      };
    },

    mounted() {


      axios({
          method: 'GET',
          url:'api/0.1/getusersettings',
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
      saveSettings() {

        const userSettings = {
          serviceName : this.serciveNameInput,
          userName : this.userNameInput,
          password: this.passwordInput,
          apiKey: this.apiKeyInput
        };

        this.savedInputValues.push(userSettings);


        axios.post('/index.php/apps/rides/api/0.1/settings', userSettings)
        .then(response => {
          console.log(response.data);
        }) .catch(error => {
          console.log(error);
        });

        this.$router.push({ name : 'MainContent'});

      },

      saveApiConnections() {   

      const services = JSON.stringify(this.enabledServices);

      console.log(services);

      axios.post('/index.php/apps/rides/api/0.1/savesettings', services)
      .then(response => {
        console.log(response.data);
      }).catch(error => {
        console.log(error); 
      });
},

      saveBMFSettings() {
        const bmfSettings = {
          email : this.email,
          password: this.password
        };

        axios.post('/index.php/apps/rides/api/0.1/savebmfsettings', bmfSettings)
        .then(response => {
          console.log(response.data)
        }).catch(error => {
          console.log(error);
        })




      },

    }
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
     align-items: center;
     margin-left: 100px;
     flex-direction: column;
  }
  
  .radio-checkboxes{
    display: flex;
     margin: auto;
     justify-content: center;
     align-items: center;
  }
  </style>
  