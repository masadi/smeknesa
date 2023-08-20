export default [
  {
    path: '/rekapitulasi/absensi-siswa',
    name: 'rekapitulasi-absensi-siswa',
    component: () => import('@/views/wali-kelas/absensi-siswa/Index.vue'),
    meta: {
      resource: 'Rekapitulasi',
      action: 'read',
      pageTitle: 'Absensi Siswa',
      breadcrumb: [
        {
          text: 'Rekapitulasi',
        },
        {
          text: 'Absensi Siswa',
          active: true,
        },
      ],
    },
  },
  {
    path: '/rekapitulasi/absensi-guru',
    name: 'rekapitulasi-absensi-guru',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      resource: 'Rekapitulasi',
      action: 'read',
      pageTitle: 'Rekap Absensi Guru',
      breadcrumb: [
        {
          text: 'Rekapitulasi',
        },
        {
          text: 'Absensi Guru',
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
