import ApplicationIndex from '@/views/pages/application/List.vue';
import ApplicationArchive from '@/views/pages/application/List.vue';
import ApplicationUpdate from '@/views/pages/application/Update.vue';

const routes = [

  {
    name: 'applications-current',
    path: '/gesuche/:type',
    component: ApplicationIndex,
  },

  {
    name: 'applications-archive',
    path: '/gesuche/:type',
    component: ApplicationArchive,
  },
  
  {
    name: 'application-edit',
    path: '/gesuche/gesuch/bearbeiten/:uuid',
    component: ApplicationUpdate,
  },
];

export default routes;