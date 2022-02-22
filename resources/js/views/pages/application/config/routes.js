import ApplicationIndex from '@/views/pages/application/List.vue';
import ApplicationUpdate from '@/views/pages/application/Update.vue';

const routes = [

  {
    name: 'applications',
    path: '/',
    component: ApplicationIndex,
  },
  
  {
    name: 'application-edit',
    path: '/applications/application/update/:uuid',
    component: ApplicationUpdate,
  },
];

export default routes;