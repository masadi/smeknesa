export default [
  {
    path: '/',
    name: 'homepage',
    component: () => import('@/views/homepage/Index.vue'),
    meta: {
      webTitle: 'Beranda',
      layout: 'horizontal',
    },
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/views/dashboard/Index.vue'),
    meta: {
      resource: 'Web',
      action: 'read',
      webTitle: 'Dashboard',
    },
  },
  {
    path: '/artikel',
    name: 'post-artikel',
    component: () => import('@/views/artikel/ListArtikel.vue'),
    meta: {
      resource: 'Post',
      action: 'read',
      webTitle: 'List Artikel',
    },
  },
  {
    path: '/tambah-artikel/:id?',
    name: 'post-add-artikel',
    component: () => import('@/views/artikel/TambahArtikel.vue'),
    meta: {
      resource: 'Post',
      action: 'read',
      webTitle: 'Tambah Artikel Baru',
    },
  },
  {
    path: '/kategori',
    name: 'post-kategori',
    component: () => import('@/views/artikel/Kategori.vue'),
    meta: {
      resource: 'Post',
      action: 'read',
      webTitle: 'Kategori Artikel',
    },
  },
  {
    path: '/video',
    name: 'post-video',
    component: () => import('@/views/artikel/Video.vue'),
    meta: {
      resource: 'Post',
      action: 'read',
      pageTitle: 'Video',
      breadcrumb: [
        {
          text: 'Video',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-video',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
  {
    path: '/slider',
    name: 'post-slider',
    component: () => import('@/views/artikel/Slider.vue'),
    meta: {
      resource: 'Post',
      action: 'read',
      pageTitle: 'Slider',
      breadcrumb: [
        {
          text: 'Slider',
          active: true,
        },
      ],
      tombol_add: {
        action: 'add-slider',
        link: '',
        variant: 'primary',
        text: 'Tambah Data'
      },
    },
  },
]
