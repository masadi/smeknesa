export default [
    {
        icon: 'file-search-icon',
        title: 'Tata Tertib Sekolah',
        children: [
            {
                icon: 'hand-click-icon',
                title: 'Tata Tertib Sekolah',
                route: 'tatib-sekolah',
                resource: 'Tatib_Sekolah',
                action: 'read',
            },
            {
                icon: 'hand-click-icon',
                title: 'Catatan Pelanggaran',
                route: 'tatib-catatan-pelanggaran',
                action: 'read',
                resource: 'Tatib_Pelanggaran',
            },
            {
                icon: 'hand-click-icon',
                title: 'Rekap Pelanggaran',
                route: 'tatib-rekap-pelanggaran',
                action: 'read',
                resource: 'Tatib_Rekap',
            },
            {
                icon: 'file-search-icon',
                title: 'Dokumentasi',
                children: [
                {
                    icon: 'hand-click-icon',
                    title: 'Bukti Pelanggaran',
                    route: 'tatib-bukti-pelanggaran',
                    action: 'read',
                    resource: 'Tatib_Bukti',
                },
                {
                    icon: 'hand-click-icon',
                    title: 'Surat Panggilan Ortu',
                    route: 'tatib-surat-panggilan-ortu',
                    action: 'read',
                    resource: 'Tatib_Ortu',
                },
                {
                    icon: 'hand-click-icon',
                    title: 'Surat Pernyataan',
                    route: 'tatib-surat-pernyataan',
                    action: 'read',
                    resource: 'Tatib_Pernyataan',
                },
                {
                    icon: 'hand-click-icon',
                    title: 'Surat Peringatan',
                    route: 'tatib-surat-peringatan',
                    action: 'read',
                    resource: 'Tatib_Peringatan',
                },
                {
                    icon: 'hand-click-icon',
                    title: 'Surat Pemunduran Diri',
                    route: 'tatib-surat-pemunduran-diri',
                    action: 'read',
                    resource: 'Tatib_Mundur',
                },
                ],
            },
        ]
    },  
]
  