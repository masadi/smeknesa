export default [
  {
    path: '/jadwal-mengajar',
    name: 'jadwal-mengajar',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      pageTitle: 'Jadwal Mengajar',
      breadcrumb: [
        {
          text: 'Jadwal Mengajar',
          active: true,
        },
      ],
    },
  },
]
