<template>
    <div>
      <!-- Dynamically render the component -->
      <component :is="componentName" v-bind="componentProps"></component>
    </div>
  </template>
  
  <script>
  import RegisterForm from './RegisterForm.vue';
  import CountryForm from './CountryForm.vue';
  import StateForm from './StateForm.vue';
  import CityForm from './CityForm.vue';
  import DetailForm from './DetailForm.vue';
  import EmployeeTable from './EmployeeTable.vue';
  import EmployeeEdit from './EmployeeEdit.vue';
  
  export default {
    name: 'MainComponent',
    components: {
      RegisterForm,
      CountryForm,
      StateForm,
      CityForm,
      DetailForm,
      EmployeeTable,
      EmployeeEdit,
    },
    data() {
      return {
        componentName: window.Laravel.componentName, // Component name from Laravel
        componentProps: window.Laravel.componentProps, // Props from Laravel
      };
    },
  };
  </script>