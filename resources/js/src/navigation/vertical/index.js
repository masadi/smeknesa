/*

Array of object

Top level object can be:
1. Header
2. Group (Group can have navItems as children)
3. navItem

* Supported Options

/--- Header ---/

header

/--- nav Grp ---/

title
icon (if it's on top level)
tag
tagVariant
children

/--- nav Item ---/

icon (if it's on top level)
title
route: [route_obj/route_name] (I have to resolve name somehow from the route obj)
tag
tagVariant

*/
/*import dashboard from './dashboard'
import appsAndPages from './apps-and-pages'
import others from './others'
import chartsAndMaps from './charts-and-maps'
import uiElements from './ui-elements'
import formAndTable from './forms-and-table'

// Array of sections
export default [...dashboard, ...appsAndPages, ...uiElements, ...formAndTable, ...chartsAndMaps, ...others]
*/
import beranda from './1_beranda'
import referensi from './2_referensi'
import presensi from './3_presensi'
import jadwal_mengajar from './4_jadwal-mengajar'
import penilaian from './5_penilaian'
import wali_kelas from './6_wali-kelas'
import monitoring from './7_monitoring'
import magang from './8_magang'
import tatib from './9_tatib'
import bottom from './99_bottom'

export default [
    ...beranda, 
    ...referensi,
    ...presensi,
    ...jadwal_mengajar,
    ...penilaian,
    ...wali_kelas,
    ...monitoring,
    ...magang,
    ...tatib,
    ...bottom
]