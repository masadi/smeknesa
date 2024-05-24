<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <template v-if="semester == 1">
          <b-alert variant="danger" show>
            <h4 class="alert-heading">Informasi</h4>
            <div class="alert-body">
              <span>Kenaikan Kelas hanya dilakukan pada semester genap!</span>
            </div>
          </b-alert>
        </template>
        <template v-else>
          <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="danger">
            <b-form @submit="onSubmit">
              <b-table-simple bordered>
                <b-thead>
                  <b-tr>
                    <b-th class="text-center">No</b-th>
                    <b-th class="text-center">Nama Siswa</b-th>
                    <b-th class="text-center">NISN</b-th>
                    <template v-if="kelulusan">
                      <b-th class="text-center">Status Kelulusan</b-th>
                    </template>
                    <template v-else>
                      <b-th class="text-center">Status Kenaikan</b-th>
                      <b-th class="text-center">Ke Kelas</b-th>
                      <b-th class="text-center">Aksi</b-th>
                    </template>
                  </b-tr>
                </b-thead>
                <b-tbody>
                  <b-tr v-for="(siswa, index) in data_siswa" :key="siswa.peserta_didik_id">
                    <b-td class="text-center">{{index + 1}}</b-td>
                    <b-td>{{siswa.nama}}</b-td>
                    <b-td class="text-center">{{siswa.nisn}}</b-td>
                    <b-td>
                      <v-select v-model="form.status_kenaikan[siswa.anggota_rombel.anggota_rombel_id]" :reduce="label => label.code" :options="opsi" @input="naikKelas(siswa.anggota_rombel.anggota_rombel_id, siswa.kelas.nama, siswa.kelas.rombongan_belajar_id)" :clearable="false" :searchable="false" placeholder="== Pilih Status Kenaikan =="></v-select>
                    </b-td>
                    <template v-if="!kelulusan">
                      <b-td class="text-center">
                        <b-badge variant="success" v-if="naik[siswa.anggota_rombel.anggota_rombel_id]">{{naik[siswa.anggota_rombel.anggota_rombel_id]}}</b-badge>
                        <b-badge variant="danger" v-if="tinggal[siswa.anggota_rombel.anggota_rombel_id]">{{tinggal[siswa.anggota_rombel.anggota_rombel_id]}}</b-badge>
                      </b-td>
                      <b-td class="text-center"></b-td>
                    </template>
                  </b-tr>
                </b-tbody>
              </b-table-simple>
              <b-button type="submit" variant="primary" class="mt-1">Simpan</b-button>
            </b-form>
          </b-overlay>
        </template>
      </div>
    </b-card-body>
    <b-modal v-model="modalShow" title="Pilih Kelas Tujuan" @ok="handleOk" cancel-title="Tutup" ok-title="Pilih">
      <v-select v-model="rombel_tujuan" label="nama" :options="data_rombel" placeholder="== Pilih Kelas Tujuan ==" @input="changeRombel"></v-select>
      <b-form-input v-model="rombel_manual" placeholder="Nama Kelas Tujuan" v-if="showManual" class="mt-2"></b-form-input>
    </b-modal>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BAlert, BForm, BOverlay, BTableSimple, BThead, BTbody, BTr, BTh, BTd, BBadge, BButton, BFormInput } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    vSelect,
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
    BFormInput,
  },
  data() {
    return {
      isBusy: true,
      loading: false,
      data_siswa: [],
      semester: null,
      form: {
        data: 'kenaikan',
        nama_kelas: {},
        status_kenaikan: {},
      },
      naik: {},
      tinggal: {},
      id_rombel: {},
      modalShow: false,
      data_rombel: [],
      anggota_rombel_id: '',
      rombel_asal: '',
      rombel_tujuan: null,
      showManual: false,
      rombel_manual: null,
      placeholder: null,
      opsi: [],
      kelulusan: false,
    }
  },
  created() {
    this.loadPostsData()
    /*
    [
                        {label: 'Tinggal Dikelas', code: 0},
                        {label: 'Naik Ke Kelas', code: 1}
                      ]
    */
  },
  methods: {
    loadPostsData() {
      this.isBusy = true
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      this.$http.post('/referensi/get-siswa', {
        data: 'kenaikan',
        guru_id: this.user.guru_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.isBusy = false
        let getData = response.data
        this.semester = getData.semester.semester
        this.data_siswa = getData.pd
        this.placeholder = getData.placeholder
        this.opsi = getData.opsi
        this.kelulusan = getData.kelulusan
        this.data_siswa.forEach(element => {
          if(element.kenaikan_kelas){
            this.form.status_kenaikan[element.kenaikan_kelas.anggota_rombel_id] = element.kenaikan_kelas.status
            this.form.nama_kelas[element.kenaikan_kelas.anggota_rombel_id] = element.kenaikan_kelas.nama_kelas
            if(element.kenaikan_kelas.status){
              this.naik[element.kenaikan_kelas.anggota_rombel_id] = element.kenaikan_kelas.nama_kelas
            } else {
              this.tinggal[element.kenaikan_kelas.anggota_rombel_id] = element.kenaikan_kelas.nama_kelas
            }
          }
        });
      })
    },
    getNextRombel(id, angka){
      if(angka === 1){
        this.$http.post('/referensi/get-rombel', {
          aksi: 'kenaikan',
          id: id,
          semester_id: this.user.semester.semester_id,
        }).then(response => {
          let getData = response.data
          this.loading = false
          this.modalShow = true
          this.data_rombel = getData
        })
      } else {
        this.loading = false
      }
    },
    naikKelas(val, nama, rombongan_belajar_id){
      this.rombel_asal = rombongan_belajar_id
      this.anggota_rombel_id = val
      if(this.form.status_kenaikan[this.anggota_rombel_id]){
        this.loading = true
        this.getNextRombel(rombongan_belajar_id, this.form.status_kenaikan[this.anggota_rombel_id])
      } else {
        this.naik[this.anggota_rombel_id] = null
        this.form.nama_kelas[this.anggota_rombel_id] = nama
        this.tinggal[this.anggota_rombel_id] = nama
      }
    },
    changeRombel(val){
      this.showManual = false
      if(val.rombongan_belajar_id === this.rombel_asal){
        this.showManual = true
      }
    },
    handleOk(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit() {
      this.modalShow = false
      this.naik[this.anggota_rombel_id] = this.rombel_manual ?? this.rombel_tujuan.nama
      this.form.nama_kelas[this.anggota_rombel_id] = this.rombel_manual ?? this.rombel_tujuan.nama
      this.tinggal[this.anggota_rombel_id] = null
    },
    onSubmit(event) {
      console.log(this.form);
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
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>