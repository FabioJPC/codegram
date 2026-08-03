import AuthLayout from '@/layouts/AuthLayout.vue';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import FeedView from '@/views/FeedView.vue';
import LoginView from '@/views/LoginView.vue';
import RegisterView from '@/views/RegisterView.vue';
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    component: DefaultLayout,
    children: [
      {
        path: '/',
        name: 'home',
        component: FeedView
      },
    ]
  },

  {
    path: '/',
    component: AuthLayout,
    children: [
      {
        path: '/login',
        name: 'login',
        component: LoginView
      },

      {
      path: '/register',
      name: 'register',
      component: RegisterView
      }
    ]
  }

];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
})

export default router
