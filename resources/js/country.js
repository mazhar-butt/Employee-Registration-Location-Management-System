import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import CountryTable from './components/CountryTable.vue';

// Define routes
const routes = [
  { path: '/countries', name: 'countryTable', component: CountryTable },
  
];

// Create router instance
const router = createRouter({
  history: createWebHistory(),
  routes
});

// Create Vue app
const app = createApp({});

// Register global components (optional if using routes)
app.component('country-table', CountryTable);

// Use router
app.use(router);

// Mount the app
app.mount('#app');
