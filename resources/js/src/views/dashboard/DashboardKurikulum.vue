<template>
  <div>
    <b-row v-if="isBusy">
      <b-col>
        <b-card no-body>
          <b-card-body>
            <div class="text-center text-danger my-2">
              <b-spinner class="align-middle"></b-spinner>
              <strong>Loading...</strong>
            </div>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
    <b-row class="match-height" v-else>
      <b-col cols="12" md="8">
        <b-card no-body>
          <b-card-header>
            <h4 class="card-title">Statistik</h4>
            <b-card-text class="text-muted">
              {{periode_aktif}}
            </b-card-text>
          </b-card-header>
          <b-card-body>
            <b-row>
              <b-col v-for="item in statistik" :key="item.title" cols="6" md="3">
                <div class="d-flex">
                  <b-avatar :variant="item.color" size="4em" class="mr-1">
                    <component :is="item.icon" />
                  </b-avatar>
                  <div class="d-flex flex-column">
                    <h3>{{ item.stats }}</h3>
                    <span class="text-caption">
                      {{ item.title }}
                    </span>
                  </div>
                </div>
              </b-col>
            </b-row>
          </b-card-body>
        </b-card>
      </b-col>
      <b-col v-for="item in aplikasi" :key="item.title" cols="6" md="2">
        <b-card no-body>
          <b-card-body class="text-center">
            <b-avatar :variant="item.color" size="4em" class="mb-1">
              <component :is="item.icon" />
            </b-avatar>
            <h3>{{ item.stats }}</h3>
            <span class="text-body-2">{{ item.title }}</span>
          </b-card-body>
        </b-card>
      </b-col>
      <template v-for="(rekap, index) in rekapitulasi">
        <b-col cols="6" xl="2" md="4" sm="6">
          <b-card no-body>
            <b-card-body class="text-center">
              <div :class="`avatar bg-light-${rekap.variant} p-50 mb-1`">
                <div class="avatar-content">
                  <font-awesome-icon :icon="`fa-solid fa-${rekap.icon}`" size="2xl" />
                </div>
              </div>
              <h2 class="font-weight-bolder" v-b-tooltip.hover.html="rekap.html">{{rekap.jml}}</h2>
              <p class="card-text">{{rekap.data}}</p>
            </b-card-body>
          </b-card>
        </b-col>
      </template>
    </b-row>
    <b-row class="match-height">
      <b-col cols="12" lg="6">
        <grafik-absensi-guru />
      </b-col>
      <b-col cols="12" lg="6">
        <grafik-nilai-jurusan />
      </b-col>
    </b-row>
  </div>
</template>

<script>
import { BRow, BCol, BCard, BCardHeader, BCardText, BCardBody, BSpinner, BTableSimple, BTr, BTd, BFormCheckbox, VBTooltip, BAvatar, } from 'bootstrap-vue'
import GrafikAbsensiGuru from './charts/GrafikAbsensiGuru.vue'
import GrafikNilaiJurusan from './charts/GrafikNilaiJurusan.vue'
export default {
  components: {
    BRow, 
    BCol,
    BCard,
    BCardHeader, BCardText,
    BCardBody,
    BSpinner,
    BTableSimple,
    BTr, 
    BTd,
    BFormCheckbox,
    VBTooltip,
    BAvatar,
    GrafikAbsensiGuru,
    GrafikNilaiJurusan,
  },
  directives: {
    'b-tooltip': VBTooltip,
  },
  data() {
    return {
      periode_aktif: '',
      isBusy: true,
      statistik: [],
      aplikasi: [],
      rekapitulasi: [],
      sekolah: null,
      app: {},
    }
  },
  created() {
    this.periode_aktif = this.user.semester.nama
    this.loadStatistics()
    this.loadAplikasi()
  },
  methods: {
    loadStatistics(){
      this.$http.post('/dashboard', {
        guru_id: this.user.guru_id,
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
        periode_aktif: this.user.semester.nama,
      }).then(response => {
        this.isBusy = false
        let getData = response.data
        this.statistik = getData.statistics
      })
    },
    loadAplikasi(){
      this.$http.get('/dashboard/aplikasi').then(response => {
        let getData = response.data
        this.aplikasi = getData.aplikasi
      })
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>