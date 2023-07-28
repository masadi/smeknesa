export default [
  {
    icon: 'file-pen',
    title: 'Jadwal Mengajar',
    route: 'jadwal-mengajar',
    resource: 'Jadwal',
    action: 'read',
  },
  {
    icon: 'building-user',
    title: 'Magang',
    children: [
      {
        icon: 'hand-point-right',
        title: 'Data DUDI',
        route: 'magang-dudi',
        resource: 'Magang_Dudi',
        action: 'read',
      },
      {
        icon: 'hand-point-right',
        title: 'Data Pembimbing',
        route: 'magang-pembimbing',
        action: 'read',
        resource: 'Magang_Pembimbing',
      },
      {
        icon: 'hand-point-right',
        title: 'Surat Permohonan',
        route: 'magang-surat-permohonan',
        action: 'read',
        resource: 'Magang_Permohonan',
      },
      {
        icon: 'hand-point-right',
        title: 'Absensi',
        route: 'magang-absensi',
        action: 'read',
        resource: 'Magang_Absensi',
      },
      {
        icon: 'hand-point-right',
        title: 'Monitoring',
        route: 'magang-monitoring',
        action: 'read',
        resource: 'Magang_Monitoring',
      },
      {
        icon: 'hand-point-right',
        title: 'Nilai',
        route: 'magang-nilai',
        action: 'read',
        resource: 'Magang_Nilai',
      },
      {
        icon: 'hand-point-right',
        title: 'Sertifikat',
        route: 'magang-sertifikat',
        action: 'read',
        resource: 'Magang_Sertifikat',
      },
    ]
  },
  {
    icon: 'address-book',
    title: 'Tata Tertib Sekolah',
    children: [
      {
        icon: 'hand-point-right',
        title: 'Tata Tertib Sekolah',
        route: 'tatib-sekolah',
        resource: 'Tatib_Sekolah',
        action: 'read',
      },
      {
        icon: 'hand-point-right',
        title: 'Catatan Pelanggaran',
        route: 'tatib-catatan-pelanggaran',
        action: 'read',
        resource: 'Tatib_Pelanggaran',
      },
      {
        icon: 'hand-point-right',
        title: 'Rekap Pelanggaran',
        route: 'tatib-rekap-pelanggaran',
        action: 'read',
        resource: 'Tatib_Rekap',
      },
      {
        icon: 'address-book',
        title: 'Dokumentasi',
        children: [
          {
            icon: 'hand-point-right',
            title: 'Bukti Pelanggaran',
            route: 'tatib-bukti-pelanggaran',
            action: 'read',
            resource: 'Tatib_Bukti',
          },
          {
            icon: 'hand-point-right',
            title: 'Surat Panggilan Ortu',
            route: 'tatib-surat-panggilan-ortu',
            action: 'read',
            resource: 'Tatib_Ortu',
          },
          {
            icon: 'hand-point-right',
            title: 'Surat Pernyataan',
            route: 'tatib-surat-pernyataan',
            action: 'read',
            resource: 'Tatib_Pernyataan',
          },
          {
            icon: 'hand-point-right',
            title: 'Surat Peringatan',
            route: 'tatib-surat-peringatan',
            action: 'read',
            resource: 'Tatib_Peringatan',
          },
          {
            icon: 'hand-point-right',
            title: 'Surat Pemunduran Diri',
            route: 'tatib-surat-pemunduran-diri',
            action: 'read',
            resource: 'Tatib_Mundur',
          },
        ],
      },
    ]
  },
  {
    icon: 'whatsapp',
    type: 'fab',
    route: 'whatsapp',
    title: 'Whatsapp',
    resource: 'Whatsapp',
    action: 'read',
  },
]
  