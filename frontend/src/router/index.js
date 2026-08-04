import AuthLayout from '@/layouts/AuthLayout.vue';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import authService from '@/services/authService';
import FeedView from '@/views/FeedView.vue';
import LoginView from '@/views/LoginView.vue';
import RegisterView from '@/views/RegisterView.vue';
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    component: AuthLayout,
    children: [
      {
        path: 'login',
        name: 'login',
        component: LoginView
      },

      {
      path: 'register',
      name: 'register',
      component: RegisterView
      }
    ]
  },

  {
    path: '/feed',
    component: DefaultLayout,
    meta: {
      requiresAuth: true
    },
    children: [
      {
        path: '',
        name: 'feed',
        component: FeedView
      }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
})

router.beforeEach((to) => {
  const isAuthenticated = authService.isAuthenticated();

  if (to.meta.requiresAuth && ! isAuthenticated) {
      return { name: 'login' }
  };

  if (to.name === 'login' && isAuthenticated) {
    return { name: 'feed' }
  }
})

export default router
