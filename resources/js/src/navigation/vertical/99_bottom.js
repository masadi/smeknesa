export default [
  {
    icon: 'file-circle-check',
    title: 'Jadwal Mengajar',
    route: 'jadwal-mengajar',
    resource: 'Jadwal',
    action: 'read',
  },
  {
    icon: 'file-pen',
    title: 'Penilaian',
    children: [
      {
        icon: 'hand-point-right',
        title: 'CP & TP',
        route: 'penilaian-cp-tp',
        resource: 'Penilaian',
        action: 'read',
      },
      {
        icon: 'hand-point-right',
        title: 'Input Nilai',
        route: 'penilaian-penilaian',
        resource: 'Penilaian',
        action: 'read',
      },
      {
        icon: 'hand-point-right',
        title: 'Input Deskripsi',
        route: 'penilaian-input-deskripsi',
        resource: 'Penilaian',
        action: 'read',
      },
      {
        icon: 'hand-point-right',
        title: 'Rekap Nilai',
        route: 'penilaian-rekap',
        resource: 'Rekap_Nilai',
        action: 'read',
      },
      {
        icon: 'hand-point-right',
        title: 'Modul',
        route: 'penilaian-modul',
        action: 'read',
        resource: 'Modul',
      },
      {
        icon: 'hand-point-right',
        title: 'Kelulusan',
        route: 'penilaian-kelulusan',
        action: 'read',
        resource: 'Kelulusan',
      },
      {
        icon: 'hand-point-right',
        title: 'Remedial',
        route: 'penilaian-remedial',
        action: 'read',
        resource: 'Remedial',
      },
    ]
  },
  {
    icon: 'universal-access',
    //type: 'far',
    title: 'Wali Kelas',
    children: [
      {
        icon: 'hand-point-right',
        title: 'Kenaikan',
        route: 'penilaian-kenaikan',
        resource: 'Kenaikan',
        action: 'read',
      },
      {
        icon: 'hand-point-right',
        title: 'Cetak Rapor',
        route: 'penilaian-cetak-rapor',
        resource: 'Kenaikan',
        action: 'read',
      },
      {
        icon: 'hand-point-right',
        title: 'Remedial',
        route: 'penilaian-remedial',
        resource: 'Kenaikan',
        action: 'read',
      },
    ]
  },
  {
    icon: 'chart-area',
    title: 'Monitoring',
    children: [
      {
        icon: 'hand-point-right',
        title: 'Asesmen Diagnosis',
        route: 'monitoring-asesment-diagnosis',
        resource: 'Penilaian',
        action: 'read',
      },
      {
        icon: 'hand-point-right',
        title: 'Asesmen Formatif',
        route: 'monitoring-asesment-formatif',
        resource: 'Penilaian',
        action: 'read',
      },
      {
        icon: 'hand-point-right',
        title: 'Asesmen Sumatif',
        route: 'monitoring-asesment-sumatif',
        resource: 'Penilaian',
        action: 'read',
      },
    ]
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
  