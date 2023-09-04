export default [
  {
    path: '/rekapitulasi/absensi-siswa',
    name: 'rekapitulasi-absensi-siswa',
    component: () => import('@/views/rekapitulasi/absensi-siswa/Index.vue'),
    meta: {
      resource: 'Rekap_Presensi',
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
      resource: 'Rekap_Presensi',
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
    path: '/rekapitulasi/remedial',
    name: 'rekap-remedial',
    component: () => import('@/views/rekapitulasi/remedial/Index.vue'),
    meta: {
      resource: 'Remedial',
      action: 'read',
      pageTitle: 'Rekap Remedial',
      breadcrumb: [
        {
          text: 'Rekapitulasi',
        },
        {
          text: 'Remedial',
          active: true,
        },
      ],
      tombol_add: {
        action: 'download-rekap-remedial',
        link: '',
        variant: 'primary',
        text: 'Download Rekap'
      },
    },
  },
]
