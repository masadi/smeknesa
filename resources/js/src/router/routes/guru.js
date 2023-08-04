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
  {
    path: '/magang/dudi',
    name: 'magang-dudi',
    component: () => import('@/views/error/Blank.vue'),
    meta: {
      resource: 'Magang_Dudi',
      action: 'read',
      pageTitle: 'Data DUDI',
      breadcrumb: [
        {
          text: 'Magang',
        },
        {
          text: 'Data DUDI',
          active: true,
        },
      ]
    },
  },
  {
    path: '/magang/pembimbing',
    name: 'magang-pembimbing',
    component: () => import('@/views/error/Blank.vue'),
    meta: {
      resource: 'Magang_Pembimbing',
      action: 'read',
      pageTitle: 'Data Pembimbing',
      breadcrumb: [
        {
          text: 'Magang',
        },
        {
          text: 'Data Pembimbing',
          active: true,
        },
      ]
    },
  },
  {
    path: '/magang/surat-permohonan',
    name: 'magang-surat-permohonan',
    component: () => import('@/views/error/Blank.vue'),
    meta: {
      resource: 'Magang_Permohonan',
      action: 'read',
      pageTitle: 'Surat Permohonan',
      breadcrumb: [
        {
          text: 'Magang',
        },
        {
          text: 'Surat Permohonan',
          active: true,
        },
      ]
    },
  },
  {
    path: '/magang/absensi',
    name: 'magang-absensi',
    component: () => import('@/views/error/Blank.vue'),
    meta: {
      resource: 'Magang_Absensi',
      action: 'read',
      pageTitle: 'Absensi',
      breadcrumb: [
        {
          text: 'Magang',
        },
        {
          text: 'Absensi',
          active: true,
        },
      ]
    },
  },
  {
    path: '/magang/monitoring',
    name: 'magang-monitoring',
    component: () => import('@/views/error/Blank.vue'),
    meta: {
      resource: 'Magang_Monitoring',
      action: 'read',
      pageTitle: 'Monitoring',
      breadcrumb: [
        {
          text: 'Magang',
        },
        {
          text: 'Monitoring',
          active: true,
        },
      ]
    },
  },
  {
    path: '/magang/nilai',
    name: 'magang-nilai',
    component: () => import('@/views/error/Blank.vue'),
    meta: {
      resource: 'Magang_Nilai',
      action: 'read',
      pageTitle: 'Nilai',
      breadcrumb: [
        {
          text: 'Magang',
        },
        {
          text: 'Nilai',
          active: true,
        },
      ]
    },
  },
  {
    path: '/magang/sertifikat',
    name: 'magang-sertifikat',
    component: () => import('@/views/error/Blank.vue'),
    meta: {
      resource: 'Magang_Sertifikat',
      action: 'read',
      pageTitle: 'Sertifikat',
      breadcrumb: [
        {
          text: 'Magang',
        },
        {
          text: 'Sertifikat',
          active: true,
        },
      ]
    },
  },
  {
    path: '/tatib/sekolah',
    name: 'tatib-sekolah',
    component: () => import('@/views/error/Blank.vue'),
    meta: {
      resource: 'Tatib_Sekolah',
      action: 'read',
      pageTitle: 'Tata Tertib Sekolah',
      breadcrumb: [
        {
          text: 'Tatib',
        },
        {
          text: 'Tata Tertib Sekolah',
          active: true,
        },
      ]
    },
  },
  {
    path: '/tatib/rekap-pelanggaran',
    name: 'tatib-rekap-pelanggaran',
    component: () => import('@/views/error/Blank.vue'),
    meta: {
      resource: 'Tatib_Rekap',
      action: 'read',
      pageTitle: 'Catatan Pelanggaran',
      breadcrumb: [
        {
          text: 'Tatib',
        },
        {
          text: 'Catatan Pelanggaran',
          active: true,
        },
      ]
    },
  },
  {
    path: '/tatib/bukti-pelanggaran',
    name: 'tatib-bukti-pelanggaran',
    component: () => import('@/views/error/Blank.vue'),
    meta: {
      resource: 'Tatib_Bukti',
      action: 'read',
      pageTitle: 'Bukti Pelanggaran',
      breadcrumb: [
        {
          text: 'Tatib',
        },
        {
          text: 'Bukti Pelanggaran',
          active: true,
        },
      ]
    },
  },
  {
    path: '/tatib/surat-panggilan-ortu',
    name: 'tatib-surat-panggilan-ortu',
    component: () => import('@/views/error/Blank.vue'),
    meta: {
      resource: 'Tatib_Ortu',
      action: 'read',
      pageTitle: 'Surat Panggilan Ortu',
      breadcrumb: [
        {
          text: 'Tatib',
        },
        {
          text: 'Surat Panggilan Ortu',
          active: true,
        },
      ]
    },
  },
  {
    path: '/tatib/surat-pernyataan',
    name: 'tatib-surat-pernyataan',
    component: () => import('@/views/error/Blank.vue'),
    meta: {
      resource: 'Tatib_Pernyataan',
      action: 'read',
      pageTitle: 'Surat Pernyataan',
      breadcrumb: [
        {
          text: 'Tatib',
        },
        {
          text: 'Surat Pernyataan',
          active: true,
        },
      ]
    },
  },
  {
    path: '/tatib/surat-peringatan',
    name: 'tatib-surat-peringatan',
    component: () => import('@/views/error/Blank.vue'),
    meta: {
      resource: 'Tatib_Peringatan',
      action: 'read',
      pageTitle: 'Surat Peringatan',
      breadcrumb: [
        {
          text: 'Tatib',
        },
        {
          text: 'Surat Peringatan',
          active: true,
        },
      ]
    },
  },
  {
    path: '/tatib/surat-pemunduran-diri',
    name: 'tatib-surat-pemunduran-diri',
    component: () => import('@/views/error/Blank.vue'),
    meta: {
      resource: 'Tatib_Mundur',
      action: 'read',
      pageTitle: 'Surat Pemunduran Diri',
      breadcrumb: [
        {
          text: 'Tatib',
        },
        {
          text: 'Surat Pemunduran Diri',
          active: true,
        },
      ]
    },
  },
  {
    path: 'whatsapp',
    name: 'whatsapp',
    component: () => import('@/views/error/Blank.vue'),
    meta: {
      resource: 'Whatsapp',
      action: 'read',
      pageTitle: 'Whatsapp',
      breadcrumb: [
        {
          text: 'Whatsapp',
          active: true,
        },
      ]
    },
  },
]
