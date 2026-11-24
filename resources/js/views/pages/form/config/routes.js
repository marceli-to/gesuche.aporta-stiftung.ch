import Form from '@/views/pages/form/Form.vue';

const routes = [
  {
    name: 'form',
    path: '/formular',
    component: Form,
  },
  {
    name: 'form-edit',
    path: '/formular/bearbeiten/:token',
    component: Form,
  },

];

export default routes;