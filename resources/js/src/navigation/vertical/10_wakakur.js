export default [
  {
    icon: 'accessible-icon',
    title: 'Kurikulum',
    children: [
      {
        icon: 'hand-click-icon',
        title: 'Rekap Absensi Siswa',
        route: 'rekap-absensi-siswa',
        resource: 'Remedial',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Rekap Absensi Guru',
        route: 'rekap-absensi-guru',
        resource: 'Remedial',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Rekap Nilai Siswa Tidak Tuntas',
        route: 'rekap-nilai-siswa-tidak-tuntas',
        resource: 'Remedial',
        action: 'read',
      },
    ]
  },
  {
    icon: 'list-icon',
    title: 'Rekapitulasi',
    children: [
      {
        icon: 'hand-click-icon',
        title: 'Absensi Siswa',
        route: 'rekapitulasi-absensi-siswa',
        resource: 'Rekapitulasi',
        action: 'read',
      },
    ]
  },
]
  