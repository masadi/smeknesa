export default [
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
      {
        icon: 'hand-click-icon',
        title: 'Absensi Guru',
        route: 'rekapitulasi-absensi-guru',
        resource: 'Rekapitulasi',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Remedial',
        route: 'rekap-remedial',
        resource: 'Remedial',
        action: 'read',
      },
    ]
  },
]
  