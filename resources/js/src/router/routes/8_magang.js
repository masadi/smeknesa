export default [
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
    path: '/magang/asesor',
    name: 'magang-asesor',
    component: () => import('@/views/magang/asesor/Index.vue'),
    meta: {
      resource: 'Magang_Dudi',
      action: 'read',
      pageTitle: 'Data Asesor',
      breadcrumb: [
        {
          text: 'Magang',
        },
        {
          text: 'Data Asesor',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-asesor',
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
    path: '/magang/tujuan-pembelajaran',
    name: 'magang-tujuan-pembelajaran',
    component: () => import('@/views/magang/tujuan-pembelajaran/Index.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      pageTitle: 'Data CP & TP',
      breadcrumb: [
        {
          text: 'Magang',
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
    path: '/magang/nilai',
    name: 'magang-nilai',
    component: () => import('@/views/magang/input-nilai/Index.vue'),
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
      tombol_add: {
        action: 'add-nilai-pkl',
        link: '',
        variant: 'primary',
        text: 'Tambah Data',
        role: ['pembimbing'],
      },
    },
  },
  {
    path: '/magang/absensi',
    name: 'magang-absensi',
    component: () => import('@/views/magang/absensi/Index.vue'),
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
      tombol_add: {
        action: 'add-absensi-pkl',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
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
