export default [
    {
      icon: 'list',
      title: 'Referensi',
      children: [
        {
          icon: 'hand-point-right',
          title: 'Periodik',
          route: 'referensi-periodik',
          resource: 'Ref_Periodik',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Sekolah',
          route: 'referensi-sekolah',
          resource: 'Ref_Sekolah',
          action: 'read',
        },
        {
          icon: 'graduation-cap',
          title: 'Guru',
          route: 'referensi-guru',
          resource: 'Ref_Guru',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Peserta Didik',
          children: [
            {
              icon: 'hand-point-right',
              title: 'PD Aktif',
              route: 'referensi-peserta-didik-aktif',
              resource: 'Ref_Pd',
              action: 'read',
            },
            {
              icon: 'hand-point-right',
              title: 'Password PD',
              route: 'referensi-password-pd',
              resource: 'Password_pd',
              action: 'read',
            },
          ],
        },
        {
          icon: 'hand-point-right',
          title: 'Jurusan',
          route: 'referensi-jurusan',
          resource: 'Ref_Jurusan',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Mata Pelajaran',
          route: 'referensi-mata-pelajaran',
          resource: 'Ref_Mapel',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Kelas',
          route: 'referensi-kelas',
          resource: 'Ref_Kelas',
          action: 'read',
        },
      ]
    },
  ]
  