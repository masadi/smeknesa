export default [
  {
    icon: 'clipboard-check-icon',
    title: 'Presensi',
    children: [
      {
        icon: 'hand-click-icon',
        title: 'Guru',
        route: 'presensi-guru',
        resource: 'Presensi_Guru',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Siswa',
        route: 'presensi-siswa',
        resource: 'Presensi_Pd',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Rekap Absensi',
        children: [
          {
            icon: 'hand-click-icon',
            title: 'Guru',
            route: 'presensi-rekap-guru',
            resource: 'Presensi_Rekap_Guru',
            action: 'read',
          },
          {
            icon: 'hand-click-icon',
            title: 'Siswa',
            route: 'presensi-rekap-pd',
            resource: 'Presensi_Rekap_Pd',
            action: 'read',
          },
        ]
      },
    ]
  },
]
  