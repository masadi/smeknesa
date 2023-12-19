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
        <b-table-simple bordered>
          <b-thead>
            <b-tr>
              <b-th class="text-center">No</b-th>
              <b-th class="text-center">Nama Siswa</b-th>
              <b-th class="text-center">NISN</b-th>
              <b-th class="text-center">Ekstrakurikuler</b-th>
              <b-th class="text-center">Predikat</b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <template v-for="(siswa, index) in data_siswa">
              <b-tr>
                <b-td class="text-center" :rowspan="siswa.anggota_ekskul.length + 1">{{index + 1}}</b-td>
                <b-td :rowspan="siswa.anggota_ekskul.length + 1">{{siswa.nama}}</b-td>
                <b-td :rowspan="siswa.anggota_ekskul.length + 1" class="text-center">{{siswa.nisn}}</b-td>
                <template v-if="!siswa.anggota_ekskul.length">
                  <b-td class="text-center" colspan="34">Tidak mengikuti ekskul</b-td>
                </template>
              </b-tr>
              <template v-if="siswa.anggota_ekskul.length">
                <template v-for="anggota_ekskul in siswa.anggota_ekskul">
                  <b-tr>
                    <b-td class="text-center">{{anggota_ekskul.rombongan_belajar.nama}}</b-td>
                    <b-td class="text-center">{{predikat_ekstra(anggota_ekskul.nilai_ekstra_avg_angka)}}</b-td>
                  </b-tr>
                </template>
              </template>
            </template>
          </b-tbody>
        </b-table-simple>
      </div>
    </b-card-body>
    <import-nilai @reload="handleReload"></import-nilai>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BAlert, BForm, BOverlay, BTableSimple, BThead, BTbody, BTr, BTh, BTd, BBadge, BButton } from 'bootstrap-vue'
import ImportNilai from './../components/modal/referensi/ekstrakurikuler/ImportNilai.vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    ImportNilai,
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
    eventBus.$on('upload-nilai-ekskul', this.handleEvent);
    this.loadPostsData()
  },
  methods: {
    handleEvent(){
      eventBus.$emit('open-modal-import-nilai', this.user.guru_id)
    },
    loadPostsData() {
      this.isBusy = true
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      this.$http.post('/referensi/get-siswa', {
        data: 'nilai-ekskul',
        guru_id: this.user.guru_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.isBusy = false
        let getData = response.data
        this.semester = getData.semester.semester
        this.data_siswa = getData.pd
        this.data_siswa.forEach(element => {
          /*if(element.kenaikan_kelas){
            this.form.status_kenaikan[element.kenaikan_kelas.anggota_rombel_id] = element.kenaikan_kelas.status
            this.form.nama_kelas[element.kenaikan_kelas.anggota_rombel_id] = element.kenaikan_kelas.nama_kelas
            if(element.kenaikan_kelas.status){
              this.naik[element.kenaikan_kelas.anggota_rombel_id] = element.kenaikan_kelas.nama_kelas
            } else {
              this.tinggal[element.kenaikan_kelas.anggota_rombel_id] = element.kenaikan_kelas.nama_kelas
            }
          }*/
        });
      })
    },
    handleOk(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit() {
      this.modalShow = false
      this.naik[this.anggota_rombel_id] = this.rombel_tujuan.nama
      this.form.nama_kelas[this.anggota_rombel_id] = this.rombel_tujuan.nama
    },
    onSubmit(event) {
      event.preventDefault()
      this.loading = true
      this.$http.post('/referensi/simpan-data', this.form).then(response => {
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
          allowOutsideClick: false,
        }).then(result => {
          this.loading = false
        })
      })
    },
    handleReload(){
      this.loadPostsData()
    },
    predikat_ekstra(angka){
      angka = parseInt(angka)
      var predikat = '';
      if(angka > 0 && angka < 70){
          predikat = 'Kurang';
      }
      if(angka >= 70 && angka < 80){
          predikat = 'Cukup';
      }
      if(angka >= 80 && angka < 90){
          predikat = 'Baik';
      }
      if(angka >= 90){
          predikat = 'Sangat Baik';
      }
      return predikat;
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>