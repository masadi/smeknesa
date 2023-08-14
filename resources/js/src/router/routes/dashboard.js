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
  {
    path: '/profile',
    name: 'profile',
    component: () => import('@/views/pages/Blank.vue'),
    //component: () => import('@/views/pengaturan/Pengguna.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      pageTitle: 'Profil Pengguna',
    },
  },
]
