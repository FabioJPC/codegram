import AuthLayout from '@/layouts/AuthLayout.vue';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import authService from '@/services/authService';
import { useAuthStore } from '@/stores/authStore';
import FeedView from '@/views/FeedView.vue';
import LoginView from '@/views/LoginView.vue';
import RegisterView from '@/views/RegisterView.vue';
import { createRouter, createWebHistory } from 'vue-router'
import pinia from '@/pinia';
import ProfileView from '@/views/ProfileView.vue';

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
  },

  {
    path: '/profile',
    component: DefaultLayout,
    meta: {
      requiresAuth: true
    },
    children: [
      {
        path:'',
        component: ProfileView
      }
    ]
  },

  {
    path: '/profile/:username',
    component: DefaultLayout,
    meta: {
      requiresAuth: true
    },
    children: [
      {
        path:'',
        component: ProfileView
      }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
})

router.beforeEach( async (to) => {
  const authStore = useAuthStore(pinia);

  if (!authStore.initialized) {
    if (authService.getToken()) {
      await authStore.loadUser()
    } else {
      authStore.initialized = true;
    }
  }

  const isAuthenticated = authStore.isAuthenticated;

  if (to.meta.requiresAuth && ! isAuthenticated) {
      return { name: 'login' }
  };

  if (to.name === 'login' && isAuthenticated) {
    return { name: 'feed' }
  }
})

export default router
