// src/middleware/auth.js
import { useAuthStore } from '../stores/auth';

export default async function authMiddleware(to, from, next) {
  const authStore = useAuthStore();

  // Nếu có token nhưng chưa checkAuth → đồng bộ
  if (!authStore.isAuthenticated && authStore.token) {
    await authStore.checkAuth();
  }

  // Chặn nếu chưa login mà vào route cần login
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return next('/login');
  }

  // Chặn nếu đã login mà vào login/register
  if (to.meta.guestOnly && authStore.isAuthenticated) {
    return next('/calendar');
  }

  // Cho đi tiếp
  return next();
}
