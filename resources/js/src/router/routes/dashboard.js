export default [
  {
    path: '/',
    name: 'homepage',
    component: () => import('@/views/homepage/Index.vue'),
    meta: {
      webTitle: 'Beranda',
      layout: 'horizontal',
    },
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/views/dashboard/Index.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      webTitle: 'Dashboard',
    },
  },
]
