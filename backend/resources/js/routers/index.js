
import { createRouter, createWebHistory } from 'vue-router';
import Login from '../pages/auth/Login.vue';
import Register from '../pages/auth/Register.vue';
import Calendar from '../pages/calendar/Calendar.vue';
import authMiddleware from '../middleware/auth';

const routes = [
  { path: '/login', name: 'login', component: Login, meta: { guestOnly: true } },
  { path: '/register', name: 'register', component: Register, meta: { guestOnly: true } },
  { path: '/calendar', name: 'calendar', component: Calendar, meta: { requiresAuth: true } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  authMiddleware(to, from, next); // sử dụng middleware
});

export default router;
