<template>
    <div>
      <div class="create">
        <h1>User settings</h1>
        <input v-model="input1" placeholder="Service name" />
        <input v-model="input2" placeholder="Username" />
        <input v-model="input3" placeholder="Password" />
        <input v-model="input4" placeholder="API key" />
        <button @click="saveSettings">Save</button>
      </div>
  
      <div class="radio-checkboxes" v-if="settingsSaved">
        <label v-for="(checkbox, index) in checkboxes" :key="index">
          <input type="checkbox" v-model="selectedCheckboxes" :value="checkbox.value" @change="checkAll(checkbox.value)" />
          {{ checkbox.label }}
        </label>
  
        <label v-for="(inputValue, index) in savedInputValues" :key="index">
          <input type="checkbox" v-model="selectedCheckboxes" :value="inputValue" />
          {{ inputValue }}
        </label>
      </div>
    </div>
  </template>
  
  <script>
import NcCheckboxRadioSwitch from '@nextcloud/vue/dist/Components/NcCheckboxRadioSwitch.js'

  export default {
    name: "UserSettings",
    components: {
      NcCheckboxRadioSwitch
    },
    data() {
      return {
        input1: "",
        input2: "",
        selectedCheckboxes: [],
        checkboxes: [
          { label: "All", value: "all" },
        ],
        settingsSaved: false,
        savedInputValues: [] 
      };
    },
    methods: {
      saveSettings() {
        console.log("User settings saved.");
        this.settingsSaved = true;
  
        if (this.input1) {
          this.savedInputValues.push(this.input1);
        }
      },
      checkAll(checkboxValue) {
        if (checkboxValue === 'all') {
          this.selectedCheckboxes = [...this.checkboxes.map(item => item.value), ...this.savedInputValues];
        }
      }
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
  