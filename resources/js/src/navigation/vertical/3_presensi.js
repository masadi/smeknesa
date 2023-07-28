export default [
    {
      icon: 'square-check',
      type: 'far',
      title: 'Presensi',
      children: [
        {
          icon: 'hand-point-right',
          title: 'Guru',
          route: 'presensi-guru',
          resource: 'Presensi_Guru',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Siswa',
          route: 'presensi-siswa',
          resource: 'Presensi_Pd',
          action: 'read',
        },
        {
          icon: 'hand-point-right',
          title: 'Rekap Absensi',
          children: [
            {
              icon: 'hand-point-right',
              title: 'Guru',
              route: 'presensi-rekap-guru',
              resource: 'Presensi_Rekap_Guru',
              action: 'read',
            },
            {
              icon: 'hand-point-right',
              title: 'Siswa',
              route: 'presensi-rekap-pd',
              resource: 'Presensi_Rekap_Pd',
              action: 'read',
            },
          ]
        },
      ]
    },
    {
      icon: 'users',
      title: 'Akun',
      route: 'pengguna',
      resource: 'Akun',
      action: 'read',
    },
  ]
  