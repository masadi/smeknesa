export default [
    {
      icon: 'home-icon',
      route: 'dashboard',
      title: 'Beranda',
      resource: 'Web',
      action: 'read',
    },
    {
      icon: 'edit-icon',
      title: 'Artikel',
      children: [
        {
          icon: 'hand-click-icon',
          title: 'List Artikel',
          route: 'post-artikel',
          resource: 'Post',
          action: 'read',
        },
        {
          icon: 'hand-click-icon',
          title: 'Tambah Artikel',
          route: 'post-add-artikel',
          resource: 'Post',
          action: 'read',
        },
        {
          icon: 'hand-click-icon',
          title: 'Kategori',
          route: 'post-kategori',
          resource: 'Post',
          action: 'read',
        },
      ]
    },
    {
      icon: 'brand-youtube-icon',
      route: 'post-video',
      title: 'Video',
      resource: 'Post',
      action: 'read',
    },
    {
      icon: 'message-code-icon',
      route: 'post-slider',
      title: 'Slider',
      resource: 'Post',
      action: 'read',
    },
  ]
  