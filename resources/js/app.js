import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import EmployeeTable from './components/EmployeeTable.vue';
import EmployeeEdit from './components/EmployeeEdit.vue';
import RegisterForm from './components/RegisterForm.vue';
import CountryForm from './components/CountryForm.vue';
import CityForm from './components/CityForm.vue';
import DetailForm from './components/DetailForm.vue';
import StateForm from './components/StateForm.vue';

const routes = [
    {
        path: '/register',
        name: 'RegisterForm',
        component: RegisterForm,
    },
    {
        path: '/country/:user_id',
        name: 'CountryForm',
        component: CountryForm,
        props: route => ({
            userId: Number(route.params.user_id),
            countries: window.initialData.countries
        })
    },
    {
        path: '/state/:country_id/:user_id',
        name: 'stateForm',
        component: StateForm,
        props: route => ({
            countryId: Number(route.params.country_id),
            userId: Number(route.params.user_id),
            states: window.initialData.states
        })
    },
    {
        path: '/city/:state_id/:country_id/:user_id',
        name: 'CityForm',
        component: CityForm,
        props: route => ({
            stateId: Number(route.params.state_id),
            countryId: Number(route.params.country_id),
            userId: Number(route.params.user_id),
            cities: window.initialData.cities
        })
    },
    {
        path: '/detail/:city_id/:state_id/:country_id/:user_id',
        name: 'DetailForm',
        component: DetailForm,
        props: route => ({
            cityId: Number(route.params.city_id),
            stateId: Number(route.params.state_id),
            countryId: Number(route.params.country_id),
            userId: Number(route.params.user_id)
        })
    },
    {
        path: '/detail',
        name: 'admins',
        component: EmployeeTable
    },
    {
      path: '/details/:id/edit',
      name: 'employee-edit',
      component: EmployeeEdit,
      props: route => ({
      employeeId: Number(route.params.id)
      })
  }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

const app = createApp({});

// Register global components
app.component('employee-table', EmployeeTable);
app.component('employee-edit', EmployeeEdit);
app.component('register-form', RegisterForm);
app.component('country-form', CountryForm);
app.component('state-form', StateForm);
app.component('city-form', CityForm);
app.component('detail-form', DetailForm);

app.use(router);
app.mount('#app');

export default router;