<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <!--b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="danger">
          <b-form @submit="onSubmit">
            
            <b-button type="submit" variant="primary" class="mt-1">Simpan</b-button>
          </b-form>
        </b-overlay-->
        <b-table-simple responsive bordered>
          <b-thead>
            <b-tr>
              <b-th class="text-center">No</b-th>
              <b-th class="text-center">Nama Siswa</b-th>
              <b-th class="text-center">NISN</b-th>
              <template v-if="nilai_pkl">
                <b-th class="text-center">{{ nilai_pkl.praktik_kerja_lapangan.pembelajaran.nama_mata_pelajaran }}</b-th>
              </template>
              <template v-else>
                <b-th class="text-center" v-for="(pembelajaran, index) in data_pembelajaran" :key="pembelajaran.pembelajaran_id">{{pembelajaran.nama_mata_pelajaran}}</b-th>
              </template>
            </b-tr>
          </b-thead>
          <b-tbody>
            <template v-for="(siswa, index) in data_siswa" v-if="data_siswa.length">
              <b-tr>
                <b-td class="text-center">{{index + 1}}</b-td>
                <b-td>{{siswa.nama}}</b-td>
                <b-td class="text-center">{{siswa.nisn}}</b-td>
                <template v-if="nilai_pkl">
                  <b-td class="text-center">
                    {{Math.round(siswa.avg_nilai_pkl)}}
                  </b-td>
                </template>
                <template v-else>
                  <b-td class="text-center" v-for="(pembelajaran, index) in data_pembelajaran" :key="pembelajaran.pembelajaran_id">{{Math.ceil(getNilai(pembelajaran.nilai, siswa.anggota_rombel.anggota_rombel_id))}}</b-td>
                </template>
              </b-tr>
            </template>
          </b-tbody>
        </b-table-simple>
      </div>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BAlert, BForm, BOverlay, BTableSimple, BThead, BTbody, BTr, BTh, BTd, BBadge, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BAlert,
    BForm, 
    BOverlay,
    BTableSimple,
    BThead,
    BTbody,
    BTr,
    BTh,
    BTd,
    BBadge,
    BButton,
  },
  data() {
    return {
      isBusy: true,
      loading: false,
      data_siswa: [],
      data_pembelajaran: [],
      semester: null,
      form: {
        data: 'nilai-ekskul',
        nama_kelas: {},
        status_kenaikan: {},
      },
      naik: {},
      tinggal: {},
      id_rombel: {},
      modalShow: false,
      data_rombel: [],
      anggota_rombel_id: '',
      rombel_tujuan: null,
      rombongan_belajar_id: '',
      nilai_pkl: null,
      
    }
  },
  created() {
    eventBus.$on('download-legger', this.handleEvent);
    this.loadPostsData()
  },
  methods: {
    handleEvent(){
      window.open(`/export/legger/${this.rombongan_belajar_id}`, '_blank')
    },
    loadPostsData() {
      this.isBusy = true
      this.$http.post('/referensi/get-siswa', {
        data: 'legger',
        guru_id: this.user.guru_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.isBusy = false
        let getData = response.data
        this.data_siswa = getData.pd
        this.data_pembelajaran = getData.pembelajaran
        this.rombongan_belajar_id = getData.rombongan_belajar.rombongan_belajar_id
        this.nilai_pkl = getData.nilai_pkl
      })
    },
    getNilai(all_nilai, anggota_rombel_id){
      const arr = all_nilai.filter((item) => {
        return item.anggota_rombel_id === anggota_rombel_id
      })
      const { length } = arr;
      return arr.reduce((acc, val) => {
        return acc + (val.angka/length);
      }, 0);
    },
    /*rerataNilaiPkl(arr){
      const average = arr.reduce((total, next) => total + next.nilai, 0) / arr.length;
      return Math.round(average)
    },*/
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>