import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import AddLocation from './components/AddLocation.vue'; // Import your component


// Define routes
const routes = [
    {
        path: '/location',
        component: AddLocation,
        name: 'AddLocation'
    },
    // You can add more routes here as needed
];

const router = createRouter({
    history: createWebHistory(),
    routes
  });

  const app = createApp({});
  
app.component('add-location', AddLocation);


app.use(router);

app.mount('#app');
