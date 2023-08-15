export default [
  {
    path: '/',
    name: 'dashboard',
    component: () => import('@/views/dashboard/Index.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      webTitle: 'Beranda',
    },
  },
]
