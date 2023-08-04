export default [
  {
    path: '/jadwal-mengajar',
    name: 'jadwal-mengajar',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/peserta-didik/Password.vue'),
    meta: {
      resource: 'Jadwal',
      action: 'read',
      pageTitle: 'Referensi Jadwal Mengajar',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Referensi Jadwal Mengajar',
          active: true,
        },
      ],
    },
  },
  {
    path: '/penilaian/cp-tp',
    name: 'penilaian-cp-tp',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/kompetensi-dasar/Index.vue'),
    meta: {
      resource: 'Penilaian',
      action: 'read',
      pageTitle: 'CP & TP',
      breadcrumb: [
        {
          text: 'Penilaian',
        },
        {
          text: 'CP & TP',
          active: true,
        },
      ]
    },
  },
  {
    path: '/penilaian/input-nilai',
    name: 'penilaian-penilaian',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/capaian-pembelajaran/Index.vue'),
    meta: {
      resource: 'Penilaian',
      action: 'read',
      pageTitle: 'Input Nilai',
      breadcrumb: [
        {
          text: 'Penilaian',
        },
        {
          text: 'Input Nilai',
          active: true,
        },
      ]
    },
  },
  {
    path: '/penilaian/input-deskripsi',
    name: 'penilaian-input-deskripsi',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/tujuan-pembelajaran/Index.vue'),
    meta: {
      resource: 'Penilaian',
      action: 'read',
      pageTitle: 'Input Deskripsi',
      breadcrumb: [
        {
          text: 'Penilaian',
        },
        {
          text: 'Input Deskripsi',
          active: true,
        },
      ]
    },
  },
  {
    path: '/penilaian/rekap',
    name: 'penilaian-rekap',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/ukk/Index.vue'),
    meta: {
      resource: 'Rekap_Nilai',
      action: 'read',
      pageTitle: 'Rekapitulasi Nilai',
      breadcrumb: [
        {
          text: 'Penilaian',
        },
        {
          text: 'Rekapitulasi Nilai',
          active: true,
        },
      ]
    },
  },
  {
    path: '/penilaian/modul',
    name: 'penilaian-modul',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/penilaian/InputNilai.vue'),
    meta: {
      resource: 'Modul',
      action: 'read',
      pageTitle: 'Modul',
      breadcrumb: [
        {
          text: 'Penilaian',
        },
        {
          text: 'Modul',
          active: true,
        },
      ],
    },
  },
  {
    path: '/penilaian/kelulusan',
    name: 'penilaian-kelulusan',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/penilaian/NilaiAkhir.vue'),
    meta: {
      resource: 'Kelulusan',
      action: 'read',
      pageTitle: 'Kelulusan',
      breadcrumb: [
        {
          text: 'Penilaian',
        },
        {
          text: 'Kelulusan',
          active: true,
        },
      ],
    },
  },
  {
    path: '/penilaian/remedial',
    name: 'penilaian-remedial',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/penilaian/CapaianKompetensi.vue'),
    meta: {
      resource: 'Remedial',
      action: 'read',
      pageTitle: 'Remedial',
      breadcrumb: [
        {
          text: 'Penilaian',
        },
        {
          text: 'Remedial',
          active: true,
        },
      ],
    },
  },
  {
    path: '/penilaian/sikap',
    name: 'penilaian-sikap',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/penilaian/PenilaianSikap.vue'),
    meta: {
      resource: 'Guru',
      action: 'read',
      pageTitle: 'Penilaian Sikap',
      breadcrumb: [
        {
          text: 'Penilaian',
        },
        {
          text: 'Penilaian Sikap',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-modal',
        link: '',
        variant: 'success',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/wali-kelas/kenaikan',
    name: 'walas-kenaikan',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/penilaian/InputPenilaianSikap.vue'),
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
      navActiveLink: 'penilaian-sikap',
    },
  },
  {
    path: '/wali-kelas/cetak-rapor',
    name: 'walas-cetak-rapor',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/penilaian/Ekstrakurikuler.vue'),
    meta: {
      resource: 'Kenaikan',
      action: 'read',
      pageTitle: 'Cetak Rapor',
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
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/penilaian/projek/Perencanaan.vue'),
    meta: {
      resource: 'Kenaikan',
      action: 'read',
      pageTitle: 'Remedial',
      breadcrumb: [
        {
          text: 'Wali Kelas',
        },
        {
          text: 'Remedial',
          active: true,
        },
      ]
    },
  },
  {
    path: '/monitoring/asesment-diagnosis',
    name: 'monitoring-asesment-diagnosis',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/penilaian/projek/Penilaian.vue'),
    meta: {
      resource: 'Penilaian',
      action: 'read',
      pageTitle: 'Asesmen Diagnosis',
      breadcrumb: [
        {
          text: 'Monitoring',
        },
        {
          text: 'Asesmen Diagnosis',
          active: true,
        },
      ],
    },
  },
  {
    path: '/monitoring/asesment-formatif',
    name: 'monitoring-asesment-formatif',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/penilaian/ukk/Perencanaan.vue'),
    meta: {
      resource: 'Penilaian',
      action: 'read',
      pageTitle: 'Asesmen Formatif',
      breadcrumb: [
        {
          text: 'Monitoring',
        },
        {
          text: 'Asesmen Formatif',
          active: true,
        },
      ]
    },
  },
  {
    path: '/monitoring/asesment-sumatif',
    name: 'monitoring-asesment-sumatif',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/penilaian/ukk/Penilaian.vue'),
    meta: {
      resource: 'Penilaian',
      action: 'read',
      pageTitle: 'Asesmen Sumatif',
      breadcrumb: [
        {
          text: 'Monitoring',
        },
        {
          text: 'Asesmen Sumatif',
          active: true,
        },
      ],
    },
  },
]
