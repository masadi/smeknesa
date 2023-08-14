export default [
  {
    path: '/monitoring/asesment-diagnosis',
    name: 'monitoring-asesment-diagnosis',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Asesmen Diagnosis',
      resource: 'Penilaian',
      action: 'read',
      breadcrumb: [
        {
          text: 'Asesmen',
        },
        {
          text: 'Diagnosis',
          active: true,
        },
      ],
    },
  },
  {
    path: '/monitoring/asesment-formatif',
    name: 'monitoring-asesment-formatif',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Asesmen Formatif',
      resource: 'Penilaian',
      action: 'read',
      breadcrumb: [
        {
          text: 'Asesmen',
        },
        {
          text: 'Formatif',
          active: true,
        },
      ],
    },
  },
  {
    path: '/monitoring/asesment-sumatif',
    name: 'monitoring-asesment-sumatif',
    component: () => import('@/views/pages/Blank.vue'),
    meta: {
      pageTitle: 'Asesmen Sumatif',
      resource: 'Penilaian',
      action: 'read',
      breadcrumb: [
        {
          text: 'Asesmen',
        },
        {
          text: 'Sumatif',
          active: true,
        },
      ],
    },
  },
]
