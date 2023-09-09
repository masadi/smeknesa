export default [
  {
    icon: 'zoom-check-icon',
    title: 'Penilaian',
    children: [
      {
        icon: 'hand-click-icon',
        title: 'CP & TP',
        route: 'penilaian-cp-tp',
        resource: 'Penilaian',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Input Nilai',
        route: 'penilaian-penilaian',
        resource: 'Penilaian',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Input Deskripsi',
        route: 'penilaian-input-deskripsi',
        resource: 'Penilaian',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Rekap Nilai',
        route: 'penilaian-rekap',
        resource: 'Rekap_Nilai',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Modul',
        route: 'penilaian-modul',
        action: 'read',
        resource: 'Modul_HIDE',
      },
      {
        icon: 'hand-click-icon',
        title: 'Penilaian Sikap',
        route: 'penilaian-sikap',
        action: 'read',
        resource: 'Penilaian_Sikap',
      },
      {
        icon: 'hand-click-icon',
        title: 'Remedial',
        route: 'penilaian-remedial',
        action: 'read',
        resource: 'Remedial',
      },
    ]
  },
  {
    icon: 'hand-click-icon',
    title: 'Perizinan',
    route: 'perizinan',
    resource: 'Perijinan',
    action: 'read',
  },
]
  