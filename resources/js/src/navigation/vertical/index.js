import beranda from './1_beranda'
import referensi from './2_referensi'
import presensi from './3_presensi'
//import jadwal_mengajar from './4_jadwal-mengajar'
import penilaian from './5_penilaian'
import wali_kelas from './6_wali-kelas'
import monitoring from './7_monitoring'
import magang from './8_magang'
import tatib from './9_tatib'
import rekapitulasi from './10_rekapitulasi'
import bottom from './99_bottom'

export default [
    ...beranda, 
    ...referensi,
    ...presensi,
    //...jadwal_mengajar,
    ...penilaian,
    ...wali_kelas,
    ...monitoring,
    ...rekapitulasi,
    ...magang,
    ...tatib,
    ...bottom
]