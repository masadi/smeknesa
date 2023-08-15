export default [
  {
    path: '/presensi/guru',
    name: 'presensi-guru',
    component: () => import('@/views/presensi/Guru.vue'),
    meta: {
      resource: 'Presensi_Guru',
      action: 'read',
      pageTitle: 'Presensi Guru',
      breadcrumb: [
        {
          text: 'Presensi',
        },
        {
          text: 'Guru',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-presensi-guru',
        link: '',
        variant: 'primary',
        text: 'Tambah Data',
        role: ['piket'],
      },
    },
  },
  {
    path: '/presensi/siswa',
    name: 'presensi-siswa',
    component: () => import('@/views/presensi/Pd.vue'),
    meta: {
      resource: 'Presensi_Pd',
      action: 'read',
      pageTitle: 'Presensi Siswa',
      breadcrumb: [
        {
          text: 'Presensi',
        },
        {
          text: 'Siswa',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-presensi-pd',
        link: '',
        variant: 'primary',
        text: 'Tambah Data',
        role: ['piket'],
      },
    },
  },
]
