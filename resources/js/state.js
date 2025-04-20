import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import StateTable from './components/StateTable.vue';

// Define routes
const routes = [
  { path: '/states', name: 'stateTable', component: StateTable },
 
];

// Create router instance
const router = createRouter({
  history: createWebHistory(),
  routes
});

// Create Vue app
const app = createApp({});

// Register global components (optional if using routes)
app.component('state-table', StateTable);

// Use router
app.use(router);

// Mount the app
app.mount('#app');
