export default [
    {
        icon: 'building-arch-icon',
        title: 'Magang',
        children: [
            {
                icon: 'hand-click-icon',
                title: 'Data DUDI',
                route: 'magang-dudi',
                resource: 'Magang_Dudi',
                action: 'read',
            },
            {
                icon: 'hand-click-icon',
                title: 'Data Pembimbing',
                route: 'magang-pembimbing',
                action: 'read',
                resource: 'Magang_Pembimbing',
            },
            {
                icon: 'hand-click-icon',
                title: 'Surat Permohonan',
                route: 'magang-surat-permohonan',
                action: 'read',
                resource: 'Magang_Permohonan',
            },
            {
                icon: 'hand-click-icon',
                title: 'CP & TP',
                route: 'magang-tujuan-pembelajaran',
                resource: 'Magang_Nilai',
                action: 'read',
              },
            {
                icon: 'hand-click-icon',
                title: 'Nilai',
                route: 'magang-nilai',
                action: 'read',
                resource: 'Magang_Nilai',
            },
            {
                icon: 'hand-click-icon',
                title: 'Absensi',
                route: 'magang-absensi',
                action: 'read',
                resource: 'Magang_Absensi',
            },
            /*{
                icon: 'hand-click-icon',
                title: 'Monitoring',
                route: 'magang-monitoring',
                action: 'read',
                resource: 'Magang_Monitoring',
            },
            {
                icon: 'hand-click-icon',
                title: 'Sertifikat',
                route: 'magang-sertifikat',
                action: 'read',
                resource: 'Magang_Sertifikat',
            },*/
        ]
    },
]
  