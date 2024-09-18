export default [
  {
    path: '/penilaian/tujuan-pembelajaran',
    name: 'penilaian-tujuan-pembelajaran',
    component: () => import('@/views/penilaian/tujuan-pembelajaran/Index.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      pageTitle: 'Data CP & TP',
      breadcrumb: [
        {
          text: 'Nilai Akademik',
        },
        {
          text: 'Data CP & TP',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-tp',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/penilaian/penilaian',
    name: 'penilaian-penilaian',
    component: () => import('@/views/penilaian/input-nilai/Index.vue'),
    meta: {
      pageTitle: 'Input Nilai',
      resource: 'Penilaian',
      action: 'read',
      breadcrumb: [
        {
          text: 'Nilai Akademik',
        },
        {
          text: 'Input Nilai',
          active: true,
        },
      ],
      tombol_add: {
        action: 'input-nilai',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/penilaian/input-deskripsi',
    name: 'penilaian-input-deskripsi',
    component: () => import('@/views/penilaian/InputDeskripsi.vue'),
    meta: {
      pageTitle: 'Input Deskripsi',
      resource: 'Penilaian',
      action: 'read',
      breadcrumb: [
        {
          text: 'Nilai Akademik',
        },
        {
          text: 'Input Deskripsi',
          active: true,
        },
      ],
    },
  },
  {
    path: '/penilaian/rekap',
    name: 'penilaian-rekap',
    component: () => import('@/views/penilaian/RekapNilai.vue'),
    meta: {
      pageTitle: 'Rekap Nilai',
      resource: 'Rekap_Nilai',
      action: 'read',
      breadcrumb: [
        {
          text: 'Nilai Akademik',
        },
        {
          text: 'Rekap Nilai',
          active: true,
        },
      ],
    },
  },
  {
    path: '/penilaian/modul',
    name: 'penilaian-modul',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Modul',
      action: 'read',
      resource: 'Modul',
      breadcrumb: [
        {
          text: 'Nilai Akademik',
        },
        {
          text: 'Modul',
          active: true,
        },
      ],
    },
  },
  {
    path: '/penilaian/sikap',
    name: 'penilaian-sikap',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Penilaian Sikap',
      action: 'read',
      resource: 'Penilaian_Sikap',
      breadcrumb: [
        {
          text: 'Nilai Akademik',
        },
        {
          text: 'Penilaian Sikap',
          active: true,
        },
      ],
    },
  },
  {
    path: '/penilaian/remedial',
    name: 'penilaian-remedial',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Remedial',
      action: 'read',
      resource: 'Remedial',
      breadcrumb: [
        {
          text: 'Nilai Akademik',
        },
        {
          text: 'Remedial',
          active: true,
        },
      ],
    },
  },
  {
    path: '/ekskul/materi',
    name: 'ekskul-materi',
    component: () => import('@/views/penilaian/ekstrakurikuler/Index.vue'),
    meta: {
      pageTitle: 'Materi',
      action: 'read',
      resource: 'Instruktur',
      breadcrumb: [
        {
          text: 'Ekstrakurikuler',
        },
        {
          text: 'Materi',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-materi-ekstra',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/ekskul/entri-nilai',
    name: 'ekskul-entri-nilai',
    component: () => import('@/views/penilaian/ekstrakurikuler/EntriNilai.vue'),
    meta: {
      pageTitle: 'Nilai',
      action: 'read',
      resource: 'Instruktur',
      breadcrumb: [
        {
          text: 'Ekstrakurikuler',
        },
        {
          text: 'Entri Nilai',
          active: true,
        },
      ],
    },
  },
  {
    path: '/ekskul/entri-absensi',
    name: 'ekskul-entri-absensi',
    component: () => import('@/views/penilaian/ekstrakurikuler/EntriAbsensi.vue'),
    meta: {
      pageTitle: 'Absensi',
      action: 'read',
      resource: 'Instruktur',
      breadcrumb: [
        {
          text: 'Ekstrakurikuler',
        },
        {
          text: 'Entri Absensi',
          active: true,
        },
      ],
    },
  },
  {
    path: '/projek/perencanaan',
    name: 'projek-perencanaan',
    component: () => import('@/views/penilaian/projek/Index.vue'),
    meta: {
      pageTitle: 'Perencanaan',
      action: 'read',
      resource: 'Projek',
      breadcrumb: [
        {
          text: 'Penilaian Projek',
        },
        {
          text: 'Perencanaan',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-projek',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/projek/penilaian',
    name: 'projek-penilaian',
    component: () => import('@/views/penilaian/projek/EntriNilai.vue'),
    meta: {
      pageTitle: 'Entri Nilai',
      action: 'read',
      resource: 'Projek',
      breadcrumb: [
        {
          text: 'Penilaian Projek',
        },
        {
          text: 'Entri Nilai',
          active: true,
        },
      ],
    },
  },
  {
    path: '/prakerin/tujuan-pembelajaran',
    name: 'prakerin-tujuan-pembelajaran',
    component: () => import('@/views/penilaian/prakerin/Cp.vue'),
    meta: {
      resource: 'Prakerin',
      action: 'read',
      pageTitle: 'Data CP & TP',
      breadcrumb: [
        {
          text: 'Penilaian Projek',
        },
        {
          text: 'Data CP & TP',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-cp',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/prakerin/perencanaan',
    name: 'prakerin-perencanaan',
    component: () => import('@/views/penilaian/prakerin/Index.vue'),
    meta: {
      pageTitle: 'Perencanaan',
      action: 'read',
      resource: 'Prakerin',
      breadcrumb: [
        {
          text: 'Praktik Kerja Lapangan',
        },
        {
          text: 'Perencanaan',
          active: true,
        },
      ],
    },
  },
  {
    path: '/prakerin/penilaian',
    name: 'prakerin-penilaian',
    component: () => import('@/views/penilaian/prakerin/EntriNilai.vue'),
    meta: {
      pageTitle: 'Entri Nilai',
      action: 'read',
      resource: 'Prakerin',
      breadcrumb: [
        {
          text: 'Praktik Kerja Lapangan',
        },
        {
          text: 'Entri Nilai',
          active: true,
        },
      ],
    },
  },
  {
    path: '/prakerin/entri-absensi',
    name: 'prakerin-entri-absensi',
    component: () => import('@/views/penilaian/prakerin/EntriAbsensi.vue'),
    meta: {
      pageTitle: 'Absensi',
      action: 'read',
      resource: 'Prakerin',
      breadcrumb: [
        {
          text: 'Praktik Kerja Lapangan',
        },
        {
          text: 'Entri Absensi',
          active: true,
        },
      ],
    },
  },
  {
    path: '/projek/cetak-rapor',
    name: 'prakerin-cetak-rapor',
    component: () => import('@/views/penilaian/prakerin/EntriAbsensi.vue'),
    meta: {
      pageTitle: 'Cetak Rapor',
      action: 'read',
      resource: 'Prakerin',
      breadcrumb: [
        {
          text: 'Praktik Kerja Lapangan',
        },
        {
          text: 'Cetak Rapor',
          active: true,
        },
      ],
    },
  },
  {
    path: '/perizinan',
    name: 'perizinan',
    component: () => import('@/views/presensi/perijinan/Index.vue'),
    meta: {
      pageTitle: 'Perizinan',
      resource: 'Perijinan',
      action: 'read',
      breadcrumb: [
        {
          text: 'Perizinan',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-perijinan',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/terlambat',
    name: 'terlambat',
    component: () => import('@/views/presensi/terlambat/Index.vue'),
    meta: {
      pageTitle: 'Terlambat',
      resource: 'Perijinan',
      action: 'read',
      breadcrumb: [
        {
          text: 'Terlambat',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-terlambat',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/alpha-tinggi',
    name: 'alpha-tinggi',
    component: () => import('@/views/presensi/alpha-tinggi/Index.vue'),
    meta: {
      pageTitle: 'Data Alpha Tinggi',
      resource: 'Perijinan',
      action: 'read',
      breadcrumb: [
        {
          text: 'Data Alpha Tinggi',
          active: true,
        },
      ],
    },
  },
  {
    path: '/siswa-sering-terlambat',
    name: 'siswa-sering-terlambat',
    component: () => import('@/views/presensi/siswa-sering-terlambat/Index.vue'),
    meta: {
      pageTitle: 'Siswa Sering Terlambat',
      resource: 'Perijinan',
      action: 'read',
      breadcrumb: [
        {
          text: 'Siswa Sering Terlambat',
          active: true,
        },
      ],
    },
  },
]
