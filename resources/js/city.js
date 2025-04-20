import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import CityTable from './components/CityTable.vue';

// Define routes
const routes = [
  { path: '/cities', name: 'cityTable', component: CityTable },
  
];

// Create router instance
const router = createRouter({
  history: createWebHistory(),
  routes
});

// Create Vue app
const app = createApp({});

// Register global components (optional if using routes)
app.component('city-table', CityTable);

// Use router
app.use(router);

// Mount the app
app.mount('#app');
