export default [
  {
    icon: 'accessible-icon',
    title: 'Wali Kelas',
    children: [
      {
        icon: 'hand-click-icon',
        title: 'Absensi Siswa',
        route: 'walas-absensi-siswa',
        resource: 'Wali',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Nilai Ekskul',
        route: 'walas-nilai-ekskul',
        resource: 'Wali',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Kenaikan',
        route: 'walas-kenaikan',
        resource: 'Wali',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Cetak Rapor',
        route: 'walas-cetak-rapor',
        resource: 'Wali',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Remedial',
        route: 'walas-remedial',
        resource: 'Wali',
        action: 'read',
      },
    ]
  },
]
  