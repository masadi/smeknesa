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
]
