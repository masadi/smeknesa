export default [
  {
    icon: 'heart-rate-monitor-icon',
    title: 'Monitoring',
    children: [
      {
        icon: 'hand-click-icon',
        title: 'Asesmen Diagnosis',
        route: 'monitoring-asesment-diagnosis',
        resource: 'Penilaian',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Asesmen Formatif',
        route: 'monitoring-asesment-formatif',
        resource: 'Penilaian',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Asesmen Sumatif',
        route: 'monitoring-asesment-sumatif',
        resource: 'Penilaian',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Legger',
        route: 'monitoring-legger',
        resource: 'Monitoring',
        action: 'read',
      },
    ]
  },
  {
    icon: 'address-book-icon',
    title: 'Program Keahlian',
    children: [
      {
        icon: 'hand-click-icon',
        title: 'Rapor PKL',
        route: 'progli-rapor-pkl',
        resource: 'Kajur',
        action: 'read',
      },
    ]
  },
  {
    icon: 'archive-icon',
    title: 'UKK',
    children: [
      {
        icon: 'hand-click-icon',
        title: 'Paket UKK',
        route: 'ukk-paket',
        resource: 'Kajur',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Perencanaan',
        route: 'ukk-perencanaan',
        resource: 'Kajur',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Penilaian',
        route: 'ukk-penilaian',
        resource: 'Kajur',
        action: 'read',
      },
    ]
  },
]
  