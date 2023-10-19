<template>
    <div>
      <div class="create">
        <h1>Ride listing</h1>
        <input type="text" v-model="input1" placeholder="Original" @keyup.enter="addItem" />
        <input type="text" v-model="input2" placeholder="Final" @keyup.enter="addItem" />
        <input type="date" v-model="input3" placeholder="Date" @keyup.enter="addItem" />
        <input type="time" v-model="input4" placeholder="Time" @keyup.enter="addItem" />
        <button @click="addItem">Create Ride</button>
      </div>
      <ul>
        <NcListItem
          v-for="(item, index) in items"
          :key="item.id"
          :name="item"
          :title="'Original' + item.original + ' - Final:'  + item.final + ' - Date:' + item.date + ' - Time:' + item.time"
          :to="{ name: 'RideDetails', params: { id: item.id, original: item.original, final: item.final, date: item.date, time: item.time }}"
        >
          <template>
            <div>{{ item.original + ' - ' + item.final + ' - ' + item.date + ' - ' + item.time }}</div>
          </template>
          <template #actions>
            <NcActions :inline="2">
              <NcActionButton @click="editItem(item.id)">
                <template #icon>
                    <Pencil :size="20" />
                </template>
              </NcActionButton>
              <NcActionButton @click="deleteItem(index)">
                <template #icon>
                  <Delete :size="20" />
                </template>
              </NcActionButton>
            </NcActions>
          </template>
        </NcListItem>
      </ul>
    </div>
  </template>
  
  <script>
  import NcListItem from '@nextcloud/vue/dist/Components/NcListItem.js'
  import NcActionButton from '@nextcloud/vue/dist/Components/NcActionButton.js'
  import NcActions from '@nextcloud/vue/dist/Components/NcActions.js'
  import Delete from 'vue-material-design-icons/Delete'
  import Pencil from 'vue-material-design-icons/Pencil'

  
  export default {
    name: "MainContent",
  
    components: {
      NcListItem,
      NcActionButton,
      NcActions,
      Delete,
      Pencil
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
  
    created() {
      this.items = this.$route.params.items || [];
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
          this.input1 = "";
          this.input2 = "";
          this.input3 = "";
          this.input4 = "";
        }
      },
  
      deleteItem(index) {
        this.items.splice(index, 1);
      },
  
      editItem(id) {
        const item = this.items.find(item => item.id === id);      
      
        if (item) {
      this.$router.push({ 
        name: 'RideDetails', 
        params: { 
          id, 
          original: item.original, 
          final: item.final, 
          date: item.date, 
          time: item.time 
        } 
      });
    }
      
      
      },
    },
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
    flex-direction: column;
    align-items: center;
    margin-left: 100px;
  }
  </style>
  