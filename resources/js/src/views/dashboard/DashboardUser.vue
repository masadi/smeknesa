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
    </b-row>
    <b-row class="match-height">
      <b-col cols="12" md="8">
        <b-card no-body>
          <b-card-header>
            <h4 class="card-title">Mata Pelajaran Diampu</h4>
          </b-card-header>
          <b-card-body>
            <template v-if="isBusy">
              <div class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
              </div>
            </template>
            <template v-else>
              <b-table-simple bordered striped>
                <b-thead>
                  <b-tr>
                    <b-th class="text-center">Kelas</b-th>
                    <b-th class="text-center">Mata Pelajaran</b-th>
                    <b-th class="text-center">KKTP</b-th>
                    <b-th class="text-center">Wali Kelas</b-th>
                    <b-th class="text-center">Jml Siswa</b-th>
                  </b-tr>
                </b-thead>
                <b-tbody>
                  <template v-if="data_pembelajaran.length">
                    <b-tr v-for="pembelajaran in data_pembelajaran" :key="pembelajaran.pembelajaran_id">
                      <b-td class="text-center">{{ pembelajaran.rombongan_belajar.nama }}</b-td>
                      <b-td>{{ pembelajaran.nama_mata_pelajaran }}</b-td>
                      <b-td class="text-center">{{ pembelajaran.kktp }}</b-td>
                      <b-td>{{ pembelajaran.rombongan_belajar.wali_kelas.nama }}</b-td>
                      <b-td class="text-center">{{ pembelajaran.rombongan_belajar.anggota_rombel_count }}</b-td>
                    </b-tr>
                  </template>
                  <template v-else>
                    <b-tr>
                      <b-td colspan="4" class="text-center">Tidak ada data untuk ditampilkan</b-td>
                    </b-tr>
                  </template>
                </b-tbody>
              </b-table-simple>
            </template>
          </b-card-body>
        </b-card>
      </b-col>
      <b-col cols="6" md="4">
        <b-card no-body>
          <b-card-header>
            <h4 class="card-title">Tugas Tambahan Lain</h4>
          </b-card-header>
          <b-card-body>
            <template v-if="isBusy">
              <div class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
              </div>
            </template>
            <template v-else>
              <template v-if="data_tambahan.length">
                <b-table-simple bordered striped>
                  <b-thead>
                    <b-tr>
                      <b-th class="text-center">Kelas</b-th>
                      <b-th class="text-center">Tugas Tambahan</b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <template v-if="data_tambahan.length">
                      <b-tr v-for="tambahan in data_tambahan" :key="tambahan.pembelajaran_id">
                        <b-td class="text-center">{{ tambahan.rombongan_belajar.nama }}</b-td>
                        <b-td>
                          <template v-if="tambahan.mata_pelajaran.jenis == 'P5'">
                            Koordinator P5
                          </template>
                          <template v-else>
                            Pembimbing PKL
                          </template>
                        </b-td>
                      </b-tr>
                    </template>
                    <template v-else>
                      <b-tr>
                        <b-td colspan="4" class="text-center">Tidak ada data untuk ditampilkan</b-td>
                      </b-tr>
                    </template>
                  </b-tbody>
                </b-table-simple>
              </template>
            </template>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import { BRow, BCol, BCard, BCardHeader, BCardText, BCardBody, BSpinner, BTableSimple, BThead, BTbody, BTr, BTh, BTd, BFormCheckbox, VBTooltip, BAvatar, } from 'bootstrap-vue'

export default {
  components: {
    BRow, 
    BCol,
    BCard,
    BCardHeader, BCardText,
    BCardBody,
    BSpinner,
    BTableSimple,
    BThead,
    BTbody,
    BTr,
    BTh, 
    BTd,
    BFormCheckbox,
    VBTooltip,
    BAvatar
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
      data_pembelajaran: [],
      data_tambahan: [],
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
        this.data_pembelajaran = getData.pembelajaran
        this.data_tambahan = getData.tambahan
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