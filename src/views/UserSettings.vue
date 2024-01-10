<template>
    <div>
   

      
      <div class="create">
        <h2>Bessermitfahren login details</h2>
        <input v-model="emailBmf" placeholder="Email address" type="email" />
        <input v-model="passwordBmf" placeholder="Password"  type="password" />
        <button @click="saveBMFSettings">Save</button>
      </div>
      
      <div class="create r2g">
        <h2>Ride2Go login details</h2>
        <input v-model="emailr2g" placeholder="Email address" type="email" />
        <input v-model="passwordr2g" placeholder="Password"  type="password" />
        <button @click="saveR2GSettings">Save</button>
      </div>

         <div>
        <p v-if="errors.length">
            <ul>
              <li v-for="error in errors">{{ error }}</li>
            </ul>
        </p>
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
        emailBmf: "",
        passwordBmf: "",
        emailr2g: "",
        passwordr2g: ""
      };
    },

    mounted() {


      axios({
          method: 'GET',
          url:'api/0.1/readsavedusersettings',
          headers: {
            'Content-Type': 'application/json',
            'Accept-Encoding': 'application/json'
          },


        }).then((response) => {
            this.jsonData = response.data

            const startingBracket = this.jsonData.indexOf("[");
            const closingBracket = this.jsonData.lastIndexOf("]");

            const respondeString = this.jsonData.substring(startingBracket + 2, closingBracket);



            const bmfStartBracket = respondeString.indexOf('[');
            const bmfCloseBracket = respondeString.indexOf(']');

            const r2gStartBracket = respondeString.indexOf('","');
            const r2gCloseBracket = respondeString.lastIndexOf(']');
            console.log(r2gStartBracket);

            const bmfDetails = respondeString.substring(bmfStartBracket + 1, bmfCloseBracket);
            const r2gDetails = respondeString.substring(r2gStartBracket + 4, r2gCloseBracket);

            const bmfJson = bmfDetails.replace(/\\/g, '');
            const r2gJson = r2gDetails.replace(/\\/g, '');


            const bmfLoginDetails = JSON.parse(bmfJson);
            const r2gLoginDetails = JSON.parse(r2gJson);

            console.log(bmfLoginDetails);
            console.log(r2gLoginDetails);

            this.emailBmf = bmfLoginDetails.email;
            this.passwordBmf = bmfLoginDetails.password;

            this.emailr2g = r2gLoginDetails.email;
            this.passwordr2g = r2gLoginDetails.password;

           // const jsonResponse = JSON.parse(`[${respondeString}]`);

          //this.jsonResponse = jsonResponse;
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
          this.errors.push('User Settings Saved');
        }) .catch(error => {
          console.log(error);
          this.errors.push('Error');

        });

        this.$router.push({ name : 'MainContent'});

      },

      saveApiConnections() {   

      const services = JSON.stringify(this.enabledServices);

      console.log(services);

      axios.post('/index.php/apps/rides/api/0.1/savesettings', services)
      .then(response => {
        console.log(response.data);
        this.errors.push('User Settings Saved');

      }).catch(error => {
        console.log(error); 
        this.errors.push('Error');

      });
},

      saveBMFSettings() {
        const bmfSettings = {
          email : this.emailBmf,
          password: this.passwordBmf
        };

        axios.post('/index.php/apps/rides/api/0.1/savebmfsettings', bmfSettings)
        .then(response => {
          console.log(response.data)
          this.errors.push('User Settings Saved');

        }).catch(error => {
          this.errors.push('Error');
          console.log(error);
        })
      },


      saveR2GSettings() {

        const r2gSettings = {
          email : this.emailr2g,
          password: this.passwordr2g
        }


        axios.post('/index.php/apps/rides/api/0.1/saver2gsettings', r2gSettings)
        .then(response => {
          console.log(response.data);
          this.errors.push('User Settings Saved');

        }). catch(error => {
          this.errors.push('Error');
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
     text-align: left;
  }
  
  .radio-checkboxes{
    display: flex;
     margin: auto;
     justify-content: center;
     align-items: center;
  }


  .create > input {
    width: 100%;
    margin-top: 20px;
  }

  .create > button {
    margin-top: 20px;
    margin-bottom: 50px;
  }

  
  </style>
  