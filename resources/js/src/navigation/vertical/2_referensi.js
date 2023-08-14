export default [
  {
    icon: 'list-check-icon',
    title: 'Referensi',
    children: [
      {
        icon: 'hand-click-icon',
        title: 'Periodik',
        route: 'referensi-periodik',
        resource: 'Ref_Periodik',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Sekolah',
        route: 'referensi-sekolah',
        resource: 'Ref_Sekolah',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Guru',
        route: 'referensi-guru',
        resource: 'Ref_Guru',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Peserta Didik',
        children: [
          {
            icon: 'hand-click-icon',
            title: 'PD Aktif',
            route: 'referensi-peserta-didik-aktif',
            resource: 'Ref_Pd',
            action: 'read',
          },
          {
            icon: 'hand-click-icon',
            title: 'PD Keluar',
            route: 'referensi-peserta-didik-keluar',
            resource: 'Ref_Pd_Keluar',
            action: 'read',
          },
          {
            icon: 'hand-click-icon',
            title: 'Password PD',
            route: 'referensi-password-pd',
            resource: 'Password_pd',
            action: 'read',
          },
        ],
      },
      {
        icon: 'hand-click-icon',
        title: 'Jurusan',
        route: 'referensi-jurusan',
        resource: 'Ref_Jurusan',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Mata Pelajaran',
        route: 'referensi-mata-pelajaran',
        resource: 'Ref_Mapel',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Kelas',
        route: 'referensi-kelas',
        resource: 'Ref_Kelas',
        action: 'read',
      },
    ],
  },
]
  