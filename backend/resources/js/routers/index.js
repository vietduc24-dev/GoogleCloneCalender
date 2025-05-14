import { createRouter, createWebHistory } from 'vue-router';
import Login from '../pages/auth/Login.vue';
import Register from '../pages/auth/Register.vue';
import Calendar from '../pages/calendar/Calendar.vue';

const routes = [
  { path: '/', name: 'login', component: Login },
  { path: '/register', name: 'register', component: Register },
  { path: '/calendar', name: 'calendar', component: Calendar },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
