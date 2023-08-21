import Vue from 'vue'
import VueRouter from 'vue-router'

// Routes
import { canNavigate } from '@/libs/acl/routeProtection'
import { isUserLoggedIn, getUserData } from '@/auth/utils'
import dashboard from './routes/dashboard'
import referensi from './routes/2_referensi'
import presensi from './routes/3_presensi'
//import jadwal_mengajar from './routes/4_jadwal-mengajar'
import penilaian from './routes/5_penilaian'
import wali_kelas from './routes/6_wali-kelas'
import monitoring from './routes/7_monitoring'
import magang from './routes/8_magang'
import tatib from './routes/9_tatib'
import rekapitulasi from './routes/10_rekapitulasi'
import bottom from './routes/99_bottom'
import pages from './routes/pages'
/*
import apps from './routes/apps'
import uiElements from './routes/ui-elements/index'
import chartsMaps from './routes/charts-maps'
import formsTable from './routes/forms-tables'
import others from './routes/others'
*/
Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    { path: '/', redirect: { name: 'dashboard' } },
    ...dashboard,
    ...referensi,
    ...presensi,
    //...jadwal_mengajar,
    ...penilaian,
    ...wali_kelas,
    ...monitoring,
    ...magang,
    ...tatib,
    ...rekapitulasi,
    ...bottom,
    ...pages,
    /*...apps,
    ...chartsMaps,
    ...formsTable,
    ...uiElements,
    ...others,*/
    {
      path: '*',
      redirect: 'error-404',
    },
  ],
})

router.beforeEach((to, _, next) => {
  const isLoggedIn = isUserLoggedIn()
  const title = to.meta.pageTitle || to.meta.webTitle
  if (title) {
    document.title = title +' | SMKN 1 SAMPANG'
  } else {
    document.title = 'SMKN 1 SAMPANG'
  }
  if (!canNavigate(to)) {
    // Redirect to login if not logged in
    if (!isLoggedIn) return next({ name: 'auth-login' })

    // If logged in => not authorized
    return next({ name: 'misc-not-authorized' })
  }

  // Redirect if logged in
  if (to.meta.redirectIfLoggedIn && isLoggedIn) {
    const userData = getUserData()
    //next('/')
    next(getHomeRouteForLoggedInUser(userData ? userData.roles : []))
  }

  return next()
})

// ? For splash screen
// Remove afterEach hook if you are not using splash screen
router.afterEach(() => {
  // Remove initial loading
  const appLoading = document.getElementById('loading-bg')
  if (appLoading) {
    appLoading.style.display = 'none'
  }
})

export default router
