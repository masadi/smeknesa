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
    path: '/wali-kelas/nilai-ekskul',
    name: 'walas-nilai-ekskul',
    component: () => import('@/views/wali-kelas/NilaiEkskul.vue'),
    meta: {
      resource: 'Wali',
      action: 'read',
      pageTitle: 'Nilai Ekskul',
      breadcrumb: [
        {
          text: 'Wali Kelas',
        },
        {
          text: 'Nilai Ekskul',
          active: true,
        },
      ],
      /*tombol_add: {
        action: 'upload-nilai-ekskul',
        link: '',
        variant: 'primary',
        text: 'Upload Nilai Ekskul'
      },*/
    },
  },
  {
    path: '/wali-kelas/kenaikan',
    name: 'walas-kenaikan',
    component: () => import('@/views/wali-kelas/KenaikanKelas.vue'),
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
    component: () => import('@/views/wali-kelas/CetakRapor.vue'),
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
    path: '/wali-kelas/remedial',
    name: 'walas-remedial',
    component: () => import('@/views/wali-kelas/remedial/Index.vue'),
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
  {
    path: '/wali-kelas/legger',
    name: 'walas-legger',
    component: () => import('@/views/wali-kelas/Legger.vue'),
    meta: {
      pageTitle: 'Legger Nilai',
      resource: 'Wali',
      action: 'read',
      breadcrumb: [
        {
          text: 'Wali Kelas',
        },
        {
          text: 'Legger Nilai',
          active: true,
        },
      ],
      tombol_add: {
        action: 'download-legger',
        link: '',
        variant: 'primary',
        text: 'Download Legger'
      },
    },
  },
]
