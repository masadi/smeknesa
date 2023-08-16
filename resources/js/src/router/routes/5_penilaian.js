export default [
  {
    path: '/penilaian/cp-tp',
    name: 'penilaian-cp-tp',
    component: () => import('@/views/penilaian/capaian-pembelajaran/Index.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      pageTitle: 'Data CP & TP',
      breadcrumb: [
        {
          text: 'Penilaian',
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
    path: '/penilaian/penilaian',
    name: 'penilaian-penilaian',
    component: () => import('@/views/penilaian/input-nilai/Index.vue'),
    meta: {
      pageTitle: 'Input Nilai',
      resource: 'Penilaian',
      action: 'read',
      breadcrumb: [
        {
          text: 'Penilaian',
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
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Input Deskripsi',
      resource: 'Penilaian',
      action: 'read',
      breadcrumb: [
        {
          text: 'Penilaian',
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
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Rekap Nilai',
      resource: 'Rekap_Nilai',
      action: 'read',
      breadcrumb: [
        {
          text: 'Penilaian',
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
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Kelulusan',
      action: 'read',
      resource: 'Kelulusan',
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
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Remedial',
      action: 'read',
      resource: 'Remedial',
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
]
