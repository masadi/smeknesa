export default [
  {
    path: '/wali-kelas/absensi-siswa',
    name: 'walas-absensi-siswa',
    component: () => import('@/views/wali-kelas/absensi-siswa/Index.vue'),
    meta: {
      resource: 'Wali',
      action: 'read',
      pageTitle: 'Rekapitulasi Absensi Siswa',
      breadcrumb: [
        {
          text: 'Wali Kelas',
        },
        {
          text: 'Rekapitulasi Absensi Siswa',
          active: true,
        },
      ],
      tombol_add: {
        action: 'download-rekap',
        link: '',
        variant: 'primary',
        text: 'Download Rekap'
      },
    },
  },
  {
    path: '/wali-kelas/kenaikan',
    name: 'walas-kenaikan',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      resource: 'Wali',
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
      resource: 'Wali',
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
      resource: 'Wali',
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
