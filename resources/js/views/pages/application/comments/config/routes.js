import ApplicationCommentIndex from '@/views/pages/application/comments/List.vue';
import ApplicationCommentCreate from '@/views/pages/application/comments/Create.vue';

const routes = [

  {
    name: 'application-comments',
    path: '/gesuche/:type/gesuch/:uuid/kommentare',
    component: ApplicationCommentIndex,
  },
  {
    name: 'application-create',
    path: '/gesuche/:type/gesuch/:uuid/kommentar/erstellen',
    component: ApplicationCommentCreate,
  },
];

export default routes;