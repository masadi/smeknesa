export default [
  {
    path: '/tatib/sekolah',
    name: 'tatib-sekolah',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      pageTitle: 'Tata Tertib Sekolah',
      breadcrumb: [
        {
          text: 'Tata Tertib',
        },
        {
          text: 'Sekolah',
          active: true,
        },
      ],
    },
  },
  {
    path: '/tatib/catatan-pelanggaran',
    name: 'tatib-catatan-pelanggaran',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Catatan Pelanggaran',
      action: 'read',
      resource: 'Tatib_Pelanggaran',
      breadcrumb: [
        {
          text: 'Tata Tertib',
        },
        {
          text: 'Sekolah',
          active: true,
        },
      ],
    },
  },
  {
    path: '/tatib/rekap-pelanggaran',
    name: 'tatib-rekap-pelanggaran',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Rekap Pelanggaran',
      action: 'read',
      resource: 'Tatib_Rekap',
      breadcrumb: [
        {
          text: 'Tata Tertib',
        },
        {
          text: 'Sekolah',
          active: true,
        },
      ],
    },
  },
  {
    path: '/tatib/bukti-pelanggaran',
    name: 'tatib-bukti-pelanggaran',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Bukti Pelanggaran',
      action: 'read',
      resource: 'Tatib_Bukti',
      breadcrumb: [
        {
          text: 'Tata Tertib',
        },
        {
          text: 'Sekolah',
          active: true,
        },
      ],
    },
  },
  {
    path: '/tatib/surat-panggilan-ortu',
    name: 'tatib-surat-panggilan-ortu',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Surat Panggilan Ortu',
      action: 'read',
      resource: 'Tatib_Ortu',
      breadcrumb: [
        {
          text: 'Tata Tertib',
        },
        {
          text: 'Sekolah',
          active: true,
        },
      ],
    },
  },
  {
    path: '/tatib/surat-pernyataan',
    name: 'tatib-surat-pernyataan',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Surat Pernyataan',
      action: 'read',
      resource: 'Tatib_Pernyataan',
      breadcrumb: [
        {
          text: 'Tata Tertib',
        },
        {
          text: 'Sekolah',
          active: true,
        },
      ],
    },
  },
  {
    path: '/tatib/surat-peringatan',
    name: 'tatib-surat-peringatan',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Surat Peringatan',
      action: 'read',
      resource: 'Tatib_Peringatan',
      breadcrumb: [
        {
          text: 'Tata Tertib',
        },
        {
          text: 'Sekolah',
          active: true,
        },
      ],
    },
  },
  {
    path: '/tatib/surat-pemunduran-diri',
    name: 'tatib-surat-pemunduran-diri',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Surat Pemunduran Diri',
      action: 'read',
      resource: 'Tatib_Mundur',
      breadcrumb: [
        {
          text: 'Tata Tertib',
        },
        {
          text: 'Sekolah',
          active: true,
        },
      ],
    },
  },
]
