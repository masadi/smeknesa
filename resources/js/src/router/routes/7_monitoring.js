export default [
  {
    path: '/monitoring/asesment-diagnosis',
    name: 'monitoring-asesment-diagnosis',
    component: () => import('@/views/monitoring/Diagnosis.vue'),
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
    component: () => import('@/views/monitoring/Formatif.vue'),
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
    component: () => import('@/views/monitoring/Sumatif.vue'),
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
  {
    path: '/monitoring/legger',
    name: 'monitoring-legger',
    component: () => import('@/views/monitoring/Legger.vue'),
    meta: {
      pageTitle: 'Legger Nilai',
      resource: 'Monitoring',
      action: 'read',
      breadcrumb: [
        {
          text: 'Monitoring',
        },
        {
          text: 'Legger Nilai',
          active: true,
        },
      ],
    },
  },
  {
    path: '/program-keahlian/rapor-pkl',
    name: 'progli-rapor-pkl',
    component: () => import('@/views/program-keahlian/RaporPkl.vue'),
    meta: {
      pageTitle: 'Rapor PKL',
      resource: 'Kajur',
      action: 'read',
      breadcrumb: [
        {
          text: 'Program Keahlian',
        },
        {
          text: 'Rapor PKL',
          active: true,
        },
      ],
    },
  },
]
