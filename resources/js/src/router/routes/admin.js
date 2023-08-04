export default [
  {
    path: '/pengaturan-umum',
    name: 'pengaturan-umum',
    component: () => import('@/views/pengaturan/Umum.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      pageTitle: 'Pengaturan Umum',
      breadcrumb: [
        {
          text: 'Pengaturan',
        },
        {
          text: 'Pengaturan Umum',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/periodik',
    name: 'referensi-periodik',
    component: () => import('@/views/referensi/periodik/Index.vue'),
    meta: {
      resource: 'Ref_Periodik',
      action: 'read',
      pageTitle: 'Referensi Periodik',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Referensi Periodik',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-semester',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/referensi/guru',
    name: 'referensi-guru',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/gtk/Guru.vue'),
    meta: {
      resource: 'Ref_Guru',
      action: 'read',
      pageTitle: 'Data Guru',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Guru',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/sekolah',
    name: 'referensi-sekolah',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/gtk/Instruktur.vue'),
    meta: {
      resource: 'Ref_Sekolah',
      action: 'read',
      pageTitle: 'Data Sekolah',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Sekolah',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/peserta-didik-aktif',
    name: 'referensi-peserta-didik-aktif',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/peserta-didik/Aktif.vue'),
    meta: {
      resource: 'Ref_Pd',
      action: 'read',
      pageTitle: 'Data Peserta Didik Aktif',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Peserta Didik Aktif',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/password-pd',
    name: 'referensi-password-pd',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/peserta-didik/Keluar.vue'),
    meta: {
      resource: 'Password_pd',
      action: 'read',
      pageTitle: 'Data Password Peserta Didik',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Password Peserta Didik',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/jurusan',
    name: 'referensi-jurusan',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/gtk/Asesor.vue'),
    meta: {
      resource: 'Ref_Jurusan',
      action: 'read',
      pageTitle: 'Data Jurusan',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Jurusan',
          active: true,
        },
      ],
      tombol_add: {
        action: 'modal-jurusan',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/referensi/mata-pelajaran',
    name: 'referensi-mata-pelajaran',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/mata-pelajaran/Index.vue'),
    meta: {
      resource: 'Ref_Mapel',
      action: 'read',
      pageTitle: 'Data Mata Pelajaran',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Mata Pelajaran',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/kelas',
    name: 'referensi-kelas',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/rombongan-belajar/Reguler.vue'),
    meta: {
      resource: 'Ref_Kelas',
      action: 'read',
      pageTitle: 'Data Kelas',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Kelas',
          active: true,
        },
      ],
    },
  },
  {
    path: '/whatsapp',
    name: 'whatsapp',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/rombongan-belajar/MatpelPilihan.vue'),
    meta: {
      resource: 'Whatsapp',
      action: 'read',
      pageTitle: 'Data Pesan Whatsapp',
      breadcrumb: [
        {
          text: 'Data Pesan Whatsapp',
          active: true,
        },
      ],
    },
  },
  {
    path: '/presensi/guru',
    name: 'presensi-guru',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/ekstrakurikuler/Index.vue'),
    meta: {
      resource: 'Presensi_Guru',
      action: 'read',
      pageTitle: 'Presensi Guru',
      breadcrumb: [
        {
          text: 'Presensi',
        },
        {
          text: 'Presensi Guru',
          active: true,
        },
      ],
    },
  },
  {
    path: '/presensi/siswa',
    name: 'presensi-siswa',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/dudi/Index.vue'),
    meta: {
      resource: 'Presensi_Pd',
      action: 'read',
      pageTitle: 'Presensi Siswa',
      breadcrumb: [
        {
          text: 'Presensi',
        },
        {
          text: 'Presensi Siswa',
          active: true,
        },
      ],
    },
  },
  {
    path: '/presensi/rekap-guru',
    name: 'presensi-rekap-guru',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/dudi/Index.vue'),
    meta: {
      resource: 'Presensi_Rekap_Guru',
      action: 'read',
      pageTitle: 'Rekap Presensi Guru',
      breadcrumb: [
        {
          text: 'Presensi',
        },
        {
          text: 'Rekap Presensi Guru',
          active: true,
        },
      ],
    },
  },
  {
    path: '/presensi/rekap-siswa',
    name: 'presensi-rekap-pd',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/dudi/Index.vue'),
    meta: {
      resource: 'Presensi_Rekap_Pd',
      action: 'read',
      pageTitle: 'Rekap Presensi Siswa',
      breadcrumb: [
        {
          text: 'Presensi',
        },
        {
          text: 'Rekap Presensi Siswa',
          active: true,
        },
      ],
    },
  },
  {
    path: '/hak-akses',
    name: 'hak-akses',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/pengaturan/Pengguna.vue'),
    meta: {
      resource: 'Hak_Akses',
      action: 'read',
      pageTitle: 'Akses Pengguna',
      breadcrumb: [
        {
          text: 'Pengaturan',
        },
        {
          text: 'Akses Pengguna',
          active: true,
        },
      ]
    },
  },
  {
    path: '/pengguna',
    name: 'pengguna',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/pengaturan/Pengguna.vue'),
    meta: {
      resource: 'Akun',
      action: 'read',
      pageTitle: 'Data Pengguna',
      breadcrumb: [
        {
          text: 'Pengaturan',
        },
        {
          text: 'Data Pengguna',
          active: true,
        },
      ],
      tombol_add: {
        action: 'generatePengguna',
        link: '',
        variant: 'success',
        text: 'Atur Ulang Pengguna'
      },
    },
  },
  {
    path: '/profile',
    name: 'profile',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/pengaturan/Pengguna.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      pageTitle: 'Profil Pengguna',
    },
  },
]
