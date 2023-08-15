export default [
  {
    icon: 'accessible-icon',
    title: 'Wali Kelas',
    children: [
      {
        icon: 'hand-click-icon',
        title: 'Absensi Siswa',
        route: 'walas-absensi-siswa',
        resource: 'Presensi_Rekap_Pd',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Kenaikan',
        route: 'walas-kenaikan',
        resource: 'Kenaikan',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Cetak Rapor',
        route: 'walas-cetak-rapor',
        resource: 'Kenaikan',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Remedial',
        route: 'walas-remedial',
        resource: 'Kenaikan',
        action: 'read',
      },
    ]
  },
]
  