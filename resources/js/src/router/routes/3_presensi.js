export default [
  {
    path: '/presensi/guru',
    name: 'presensi-guru',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      resource: 'Presensi_Guru',
      action: 'read',
      pageTitle: 'Presensi Guru',
      breadcrumb: [
        {
          text: 'Presensi',
        },
        {
          text: 'Guru',
          active: true,
        },
      ],
    },
  },
  {
    path: '/presensi/guru',
    name: 'presensi-siswa',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      resource: 'Presensi_Pd',
      action: 'read',
      pageTitle: 'Presensi Siswa',
      breadcrumb: [
        {
          text: 'Presensi',
        },
        {
          text: 'Siswa',
          active: true,
        },
      ],
    },
  },
  {
    path: '/presensi/guru',
    name: 'presensi-rekap-guru',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      resource: 'Presensi_Rekap_Guru',
      action: 'read',
      pageTitle: 'Rekapitulasi Presensi Guru',
      breadcrumb: [
        {
          text: 'Presensi',
        },
        {
          text: 'Guru',
          active: true,
        },
      ],
    },
  },
  {
    path: '/presensi/guru',
    name: 'presensi-rekap-pd',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      resource: 'Presensi_Rekap_Pd',
      action: 'read',
      pageTitle: 'Rekapitulasi Presensi Siswa',
      breadcrumb: [
        {
          text: 'Presensi',
        },
        {
          text: 'Siswa',
          active: true,
        },
      ],
    },
  },
]
