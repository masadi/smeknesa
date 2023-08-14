export default [
  {
    path: '/wali-kelas/kenaikan',
    name: 'walas-kenaikan',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      resource: 'Kenaikan',
      action: 'read',
      pageTitle: 'Kenaikan Kelas',
      breadcrumb: [
        {
          text: 'Wali Kelas',
        },
        {
          text: 'Kenaikan Kelas',
          active: true,
        },
      ],
    },
  },
  {
    path: '/wali-kelas/cetak-rapor',
    name: 'walas-cetak-rapor',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Cetak Rapor',
      resource: 'Kenaikan',
      action: 'read',
      breadcrumb: [
        {
          text: 'Wali Kelas',
        },
        {
          text: 'Cetak Rapor',
          active: true,
        },
      ],
    },
  },
  {
    path: '/wali-kelas/-remedial',
    name: 'walas-remedial',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Remedial',
      resource: 'Kenaikan',
      action: 'read',
      breadcrumb: [
        {
          text: 'Wali Kelas',
        },
        {
          text: 'Remedial',
          active: true,
        },
      ],
    },
  },
]
