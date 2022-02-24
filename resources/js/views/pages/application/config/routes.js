import ApplicationIndex from '@/views/pages/application/List.vue';
import ApplicationArchive from '@/views/pages/application/List.vue';
import ApplicationShow from '@/views/pages/application/Show.vue';
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
    name: 'application-show',
    path: '/gesuche/:type/gesuch/anzeigen/:uuid',
    component: ApplicationShow,
  },

  {
    name: 'application-edit',
    path: '/gesuche/:type/gesuch/bearbeiten/:uuid',
    component: ApplicationUpdate,
  },
];

export default routes;