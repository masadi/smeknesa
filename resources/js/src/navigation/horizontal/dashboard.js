export default [
  {
    title: 'Beranda',
    icon: 'home-icon',
    route: 'homepage',
  },
  {
    header: 'Dashboards',
    icon: 'HomeIcon',
    children: [
      {
        title: 'eCommerce',
        route: 'dashboard-ecommerce',
        icon: 'ShoppingCartIcon',
      },
      {
        title: 'Analytics',
        route: 'dashboard-analytics',
        icon: 'ActivityIcon',
      },
    ],
  },
]
