import { createRouter, createWebHistory } from 'vue-router'
import Home from './Screens/Home.vue'
import Calculator from './Screens/Calculator.vue';
const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/calculator/:kind_id/:speed_id',
    name: 'Calculator',
    component: Calculator,
  },
]
const router = createRouter({
  history: createWebHistory(),
  routes
})
export default router