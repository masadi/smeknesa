export default [
  {
    path: '/whatsapp',
    name: 'whatsapp',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      pageTitle: 'Whatsapp',
      breadcrumb: [
        {
          text: 'Whatsapp',
          active: true,
        },
      ],
    },
  },
]
