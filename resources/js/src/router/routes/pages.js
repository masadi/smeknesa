export default [
  {
    path: '/post/:slug',
    name: 'post',
    component: () => import('@/views/homepage/Post.vue'),
    meta: {
      layout: 'horizontal',
    },
  },
  {
    path: '/page/:slug',
    name: 'page',
    component: () => import('@/views/homepage/Page.vue'),
    meta: {
      layout: 'horizontal',
    },
  },
  {
    path: '/category/:category',
    name: 'category',
    component: () => import('@/views/homepage/Category.vue'),
    meta: {
      layout: 'horizontal',
    },
  },
  {
    path: '/login',
    name: 'auth-login',
    component: () => import('@/views/pages/Login.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      redirectIfLoggedIn: true,
      pageTitle: 'Login Pengguna',
    },
  },
  {
    path: '/error-404',
    name: 'error-404',
    component: () => import('@/views/error/Error404.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      action: 'read',
      pageTitle: 'Laman tidak ditemukan',
    },
  },
  {
    path: '/not-authorized',
    name: 'misc-not-authorized',
    component: () => import('@/views/error/NotAuthorized.vue'),
    meta: {
      layout: 'full',
      resource: 'Web',
      action: 'read',
      pageTitle: 'Akses Terbatas! 🔐',
    },
  },
  {
    path: '/profile',
    name: 'profile',
    component: () => import('@/views/profile/Index.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      pageTitle: 'Profil Pengguna',
    },
  },
  {
    path: '/profil-peserta-didik',
    name: 'profil-peserta-didik',
    component: () => import('@/views/pages/profile/Profile.vue'),
    meta: {
      layout: 'full',
      resource: 'Profile_Pd',
      action: 'read',
      pageTitle: 'Profil Siswa',
    },
  },
  {
    path: '/pengaturan-umum',
    name: 'pengaturan-umum',
    component: () => import('@/views/pages/Pengaturan.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      pageTitle: 'System Setting',
    },
  },
  {
    path: '/perijinan',
    name: 'perijinan',
    component: () => import('@/views/pages/TouchScreen.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      action: 'read',
      pageTitle: 'Laman tidak ditemukan',
    },
  },
]
