import { createRouter, createWebHistory } from 'vue-router'
import InvoicesView from '@/views/InvoicesView.vue'

const routes = [
  { path: '/', name: 'Invoices', component: InvoicesView }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router
