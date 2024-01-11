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
              <b-th class="text-center" v-for="(pembelajaran, index) in data_pembelajaran" :key="pembelajaran.pembelajaran_id">{{pembelajaran.nama_mata_pelajaran}}</b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <template v-for="(siswa, index) in data_siswa" v-if="data_siswa.length">
              <b-tr>
                <b-td class="text-center">{{index + 1}}</b-td>
                <b-td>{{siswa.nama}}</b-td>
                <b-td class="text-center">{{siswa.nisn}}</b-td>
                <b-td class="text-center" v-for="(pembelajaran, index) in data_pembelajaran" :key="pembelajaran.pembelajaran_id">{{Math.ceil(getNilai(pembelajaran.nilai, siswa.anggota_rombel.anggota_rombel_id))}}</b-td>
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
      
    }
  },
  created() {
    this.loadPostsData()
  },
  methods: {
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
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>