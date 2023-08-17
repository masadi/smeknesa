export default [
  {
    path: '/rekap-absensi-siswa',
    name: 'rekap-absensi-siswa',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      resource: 'Remedial',
      action: 'read',
      pageTitle: 'Rekap Absensi Siswa',
      breadcrumb: [
        {
          text: 'Kurikulum',
        },
        {
          text: 'Rekap Absensi Siswa',
          active: true,
        },
      ],
    },
  },
  {
    path: '/rekap-absensi-guru',
    name: 'rekap-absensi-guru',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      resource: 'Remedial',
      action: 'read',
      pageTitle: 'Rekap Absensi Guru',
      breadcrumb: [
        {
          text: 'Kurikulum',
        },
        {
          text: 'Rekap Absensi Guru',
          active: true,
        },
      ],
    },
  },
  {
    path: '/rekap-nilai-siswa-tidak-tuntas',
    name: 'rekap-nilai-siswa-tidak-tuntas',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      resource: 'Remedial',
      action: 'read',
      pageTitle: 'Rekap Nilai Siswa Tidak Tuntas',
      breadcrumb: [
        {
          text: 'Kurikulum',
        },
        {
          text: 'Rekap Nilai Siswa Tidak Tuntas',
          active: true,
        },
      ],
    },
  },
]
