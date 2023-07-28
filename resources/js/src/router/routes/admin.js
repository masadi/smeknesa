export default [
  {
    path: '/pengaturan-umum',
    name: 'pengaturan-umum',
    component: () => import('@/views/pengaturan/Umum.vue'),
    meta: {
      resource: 'Administrator',
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
    path: '/referensi/guru',
    name: 'referensi-guru',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/gtk/Guru.vue'),
    meta: {
      resource: 'Administrator',
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
    path: '/referensi/tendik',
    name: 'referensi-tendik',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/gtk/Tendik.vue'),
    meta: {
      resource: 'Administrator',
      action: 'read',
      pageTitle: 'Data Tendik',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Tenaga kependidikan',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/instruktur',
    name: 'referensi-instruktur',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/gtk/Instruktur.vue'),
    meta: {
      resource: 'Administrator',
      action: 'read',
      pageTitle: 'Data Instruktur',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Instruktur',
          active: true,
        },
      ],
      tombol_add: {
        action: 'modal-instruktur',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/referensi/asesor',
    name: 'referensi-asesor',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/gtk/Asesor.vue'),
    meta: {
      resource: 'Administrator',
      action: 'read',
      pageTitle: 'Data Asesor',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Asesor',
          active: true,
        },
      ],
      tombol_add: {
        action: 'modal-asesor',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/referensi/rombongan-belajar',
    name: 'referensi-rombongan-belajar',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/rombongan-belajar/Reguler.vue'),
    meta: {
      resource: 'Rombel',
      action: 'read',
      pageTitle: 'Data Rombongan Belajar',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Rombongan Belajar',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/rombel-pilihan',
    name: 'referensi-rombel-pilihan',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/rombongan-belajar/MatpelPilihan.vue'),
    meta: {
      resource: 'Rombel',
      action: 'read',
      pageTitle: 'Rombel Matpel Pilihan',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Rombel Matpel Pilihan',
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
      resource: 'Web',
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
    path: '/referensi/peserta-didik-keluar',
    name: 'referensi-peserta-didik-keluar',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/peserta-didik/Keluar.vue'),
    meta: {
      resource: 'Administrator',
      action: 'read',
      pageTitle: 'Data Peserta Didik Keluar',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Peserta Didik Keluar',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/mata-pelajaran',
    name: 'referensi-mata-pelajaran',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/mata-pelajaran/Index.vue'),
    meta: {
      resource: 'Administrator',
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
    path: '/referensi/ekstrakurikuler',
    name: 'referensi-ekstrakurikuler',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/ekstrakurikuler/Index.vue'),
    meta: {
      resource: 'Administrator',
      action: 'read',
      pageTitle: 'Data Ekstrakurikuler',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data Ekstrakurikuler',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/dudi',
    name: 'referensi-dudi',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/referensi/dudi/Index.vue'),
    meta: {
      resource: 'Administrator',
      action: 'read',
      pageTitle: 'Data DUDI',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Data DUDI',
          active: true,
        },
      ],
    },
  },
  {
    path: '/pengguna',
    name: 'pengguna',
    component: () => import('@/views/error/Blank.vue'),
    //component: () => import('@/views/pengaturan/Pengguna.vue'),
    meta: {
      resource: 'Administrator',
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
      ],
      tombol_add: {
        action: 'generatePengguna',
        link: '',
        variant: 'success',
        text: 'Atur Ulang Pengguna'
      },
    },
  },
]
