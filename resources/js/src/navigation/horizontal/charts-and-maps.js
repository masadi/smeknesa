export default [
  {
    header: 'Charts & Maps',
    icon: 'chart-pie-icon',
    children: [
      {
        title: 'Charts',
        icon: 'chart-pie-icon',
        children: [
          {
            title: 'Apex Chart',
            route: 'charts-apex-chart',
          },
          {
            title: 'Chartjs',
            route: 'charts-chartjs',
          },
          {
            title: 'Echart',
            route: 'charts-echart',
          },
        ],
      },
      {
        title: 'Leaflet',
        route: 'maps-leaflet',
        icon: 'MapIcon',
      },
    ],
  },
]
