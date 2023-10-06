export default [
  /*{
    path: '/referensi/dudi',
    name: 'referensi-dudi',
    component: () => import('@/views/referensi/dudi/Index.vue'),
    meta: {
      resource: 'Ref_Dudi',
      action: 'read',
      pageTitle: 'DUDI',
      breadcrumb: [
        {
          text: 'Referensi',
        },
        {
          text: 'DUDI',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-dudi',
        link: '',
        variant: 'primary',
        text: 'Tambah Data',
        role: ['administrator'],
      },
    },
  },*/
  {
    path: '/magang/dudi',
    name: 'magang-dudi',
    component: () => import('@/views/magang/dudi/Index.vue'),
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
      ],
      tombol_add: {
        action: 'add-dudi',
        link: '',
        variant: 'primary',
        text: 'Tambah Data',
        role: ['administrator'],
      },
    },
  },
  {
    path: '/magang/pembimbing',
    name: 'magang-pembimbing',
    component: () => import('@/views/magang/pembimbing/Index.vue'),
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
      ],
      tombol_add: {
        action: 'add-pembimbing',
        link: '',
        variant: 'primary',
        text: 'Tambah Data',
        role: ['administrator'],
      },
    },
  },
  {
    path: '/magang/surat-permohonan',
    name: 'magang-surat-permohonan',
    component: () => import('@/views/pages/Blank.vue'),
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
      ],
    },
  },
  {
    path: '/magang/absensi',
    name: 'magang-absensi',
    component: () => import('@/views/pages/Blank.vue'),
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
      ],
    },
  },
  {
    path: '/magang/monitoring',
    name: 'magang-monitoring',
    component: () => import('@/views/pages/Blank.vue'),
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
      ],
    },
  },
  {
    path: '/magang/nilai',
    name: 'magang-nilai',
    component: () => import('@/views/pages/Blank.vue'),
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
      ],
    },
  },
  {
    path: '/magang/sertifikat',
    name: 'magang-sertifikat',
    component: () => import('@/views/pages/Blank.vue'),
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
      ],
    },
  },
]
