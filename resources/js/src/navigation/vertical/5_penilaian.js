export default [
  {
    icon: 'zoom-check-icon',
    title: 'Nilai Akademik',
    children: [
      {
        icon: 'hand-click-icon',
        title: 'Tujuan Pembelajaran',
        route: 'penilaian-tujuan-pembelajaran',
        resource: 'Penilaian',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Input Nilai',
        route: 'penilaian-penilaian',
        resource: 'Penilaian',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Input Deskripsi',
        route: 'penilaian-input-deskripsi',
        resource: 'Penilaian',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Rekap Nilai',
        route: 'penilaian-rekap',
        resource: 'Rekap_Nilai',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Modul',
        route: 'penilaian-modul',
        action: 'read',
        resource: 'Modul_HIDE',
      },
      {
        icon: 'hand-click-icon',
        title: 'Penilaian Sikap',
        route: 'penilaian-sikap',
        action: 'read',
        resource: 'Penilaian_Sikap',
      },
      {
        icon: 'hand-click-icon',
        title: 'Remedial',
        route: 'penilaian-remedial',
        action: 'read',
        resource: 'Remedial',
      },
    ]
  },
  {
    icon: 'zoom-check-icon',
    title: 'Nilai Ekstra',
    children: [
      {
        icon: 'hand-click-icon',
        title: 'Materi Ekstra',
        route: 'ekskul-materi',
        resource: 'Instruktur',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Entri Nilai',
        route: 'ekskul-entri-nilai',
        resource: 'Instruktur',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Entri Absensi',
        route: 'ekskul-entri-absensi',
        resource: 'Instruktur',
        action: 'read',
      },
    ],
  },
  {
    icon: 'file-search-icon',
    title: 'Nilai Projek',
    children: [
      {
        icon: 'hand-click-icon',
        title: 'Perencanaan',
        route: 'projek-perencanaan',
        resource: 'Projek',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Penilaian',
        route: 'projek-penilaian',
        resource: 'Projek',
        action: 'read',
      },
    ],
  },
  {
    icon: 'clipboard-icon',
    title: 'Nilai PKL',
    children: [
      {
        icon: 'hand-click-icon',
        title: 'CP & TP',
        route: 'prakerin-tujuan-pembelajaran',
        resource: 'Prakerin',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Perencanaan',
        route: 'prakerin-perencanaan',
        resource: 'Prakerin',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Penilaian',
        route: 'prakerin-entri-nilai',
        resource: 'Prakerin',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Absensi',
        route: 'prakerin-entri-absensi',
        resource: 'Prakerin',
        action: 'read',
      },
      {
        icon: 'hand-click-icon',
        title: 'Cetak Rapor',
        route: 'prakerin-cetak-rapor',
        resource: 'Prakerin',
        action: 'read',
      },
    ],
  },
  {
    icon: 'hand-click-icon',
    title: 'Perizinan',
    route: 'perizinan',
    resource: 'Perijinan',
    action: 'read',
  },
]
  