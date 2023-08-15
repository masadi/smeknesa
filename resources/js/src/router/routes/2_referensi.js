export default [
  {
    path: '/pengaturan-umum',
    name: 'pengaturan-umum',
    component: () => import('@/views/pages/Blank.vue'),
    //component: () => import('@/views/pengaturan/Umum.vue'),
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
          text: 'Periodik',
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
    path: '/referensi/sekolah',
    name: 'referensi-sekolah',
    component: () => import('@/views/referensi/sekolah/Index.vue'),
    meta: {
      resource: 'Ref_Sekolah',
      action: 'read',
      pageTitle: 'Referensi Sekolah',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Sekolah',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/guru',
    name: 'referensi-guru',
    component: () => import('@/views/referensi/guru/Index.vue'),
    meta: {
      resource: 'Ref_Guru',
      action: 'read',
      pageTitle: 'Data Guru',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Guru',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-guru',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/referensi/peserta-didik',
    name: 'referensi-peserta-didik',
    component: () => import('@/views/referensi/peserta-didik/Aktif.vue'),
    meta: {
      resource: 'Ref_Pd',
      action: 'read',
      pageTitle: 'Data Peserta Didik',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Peserta Didik',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-pd',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/referensi/password-peserta-didik',
    name: 'referensi-password-pd',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      resource: 'Password_pd',
      action: 'read',
      pageTitle: 'Data Password Peserta Didik',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Password Peserta Didik Aktif',
          active: true,
        },
      ],
    },
  },
  {
    path: '/referensi/jurusan',
    name: 'referensi-jurusan',
    component: () => import('@/views/referensi/jurusan/Index.vue'),
    meta: {
      resource: 'Ref_Jurusan',
      action: 'read',
      pageTitle: 'Jurusan',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Jurusan',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-jurusan',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/referensi/mata-pelajaran',
    name: 'referensi-mata-pelajaran',
    component: () => import('@/views/referensi/mata-pelajaran/Index.vue'),
    meta: {
      resource: 'Ref_Mapel',
      action: 'read',
      pageTitle: 'Mata Pelajaran',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Mata Pelajaran',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-mapel',
        link: '',
        variant: 'primary',
        text: 'Tambah Data',
        role: ['administrator'],
      },
    },
  },
  {
    path: '/referensi/kelas',
    name: 'referensi-kelas',
    component: () => import('@/views/referensi/rombongan-belajar/Index.vue'),
    meta: {
      resource: 'Ref_Kelas',
      action: 'read',
      pageTitle: 'Referensi Kelas',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'Kelas',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-rombel',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
]
