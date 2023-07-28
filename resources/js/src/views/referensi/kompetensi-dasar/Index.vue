<template>
  <div>
    <b-card>
      <datatable :loading="loading" :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @edit="handleEdit" @hapus="handleHapus" @nonAktifkan="handleNonAktifkan" @aktifkan="handleAktifkan" @hapusGanda="handleHapusGanda" />
    </b-card>
    <b-modal ref="edit-modal" size="xl" title="Ubah Ringkasan Kompetensi Dasar" @hidden="resetModal" @ok="handleOk" ok-title="Simpan" ok-variant="primary">
      <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="danger">
        <b-form-group label="Deskripsi Kompetensi Dasar Lama" label-for="periode-aktif">
          <b-form-textarea id="textarea" v-model="kompetensi_dasar" placeholder="Deskripsi Kompetensi Dasar Lama" rows="3" max-rows="6" disabled></b-form-textarea>
        </b-form-group>
        <b-form-group label="Deskripsi Kompetensi Dasar Lama" label-for="periode-aktif">
          <b-form-textarea id="textarea" v-model="kompetensi_dasar_alias" placeholder="Deskripsi Kompetensi Dasar Baru" rows="3" max-rows="6"></b-form-textarea>
        </b-form-group>
      </b-overlay>
      <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading_modal" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
          <b-button @click="cancel()">Tutup</b-button>
        </b-overlay>
        <b-overlay :show="loading_modal" rounded opacity="0.6" spinner-small spinner-variant="primary" class="d-inline-block">
          <b-button variant="primary" @click="ok()">Simpan</b-button>
        </b-overlay>
      </template>
    </b-modal>
    <b-modal ref="add-modal" size="xl" title="Tambah Data Kompetensi Dasar" @hidden="resetModal" @ok="handleOkAdd" ok-title="Simpan" ok-variant="primary">
      <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="danger">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tahun Pelajaran" label-for="tahun_pelajaran" label-cols-md="3">
              <b-form-input id="tahun_pelajaran" :value="tahun_pelajaran" disabled />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="tingkat_feedback" :state="tingkat_state">
              <v-select id="tingkat" v-model="tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" @input="changeTingkat" :searchable="false" :state="tingkat_state">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Rombongan Belajar" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="rombongan_belajar_id_feedback" :state="rombongan_belajar_id_state">
              <v-select id="rombongan_belajar_id" v-model="rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Rombongan Belajar ==" @input="changeRombel" :state="rombongan_belajar_id_state">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Mata Pelajaran" label-for="mata_pelajaran_id" label-cols-md="3" :invalid-feedback="mata_pelajaran_id_feedback" :state="mata_pelajaran_id_state">
              <v-select id="mata_pelajaran_id" v-model="mata_pelajaran_id" :reduce="nama_mata_pelajaran => nama_mata_pelajaran.mata_pelajaran_id" label="nama_mata_pelajaran" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==" :state="mata_pelajaran_id_state">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Aspek Penilaian" label-for="kompetensi_id" label-cols-md="3" :invalid-feedback="kompetensi_id_feedback" :state="kompetensi_id_state">
              <v-select id="kompetensi_id" v-model="kompetensi_id" :reduce="nama => nama.id" label="nama" :options="data_kompetensi" placeholder="== Pilih Aspek Penilaian ==" :searchable="false" :state="kompetensi_id_state">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kode Kompetensi Dasar" label-for="id_kompetensi" label-cols-md="3" :invalid-feedback="id_kompetensi_feedback" :state="id_kompetensi_state">
              <b-form-input id="id_kompetensi" v-model="id_kompetensi" placeholder="3.x untuk pengetahuan, 4.x untuk keterampilan" :state="id_kompetensi_state"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Deskripsi Kompetensi Dasar" label-for="kompetensi_dasar" label-cols-md="3" :invalid-feedback="kompetensi_dasar_feedback" :state="kompetensi_dasar_state">
              <b-form-textarea id="kompetensi_dasar" v-model="kompetensi_dasar" placeholder="Deskripsi Kompetensi Dasar" rows="3" max-rows="6" :state="kompetensi_dasar_state"></b-form-textarea>
            </b-form-group>
          </b-col>
        </b-row>
      </b-overlay>
      <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading_modal" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
          <b-button @click="cancel()">Tutup</b-button>
        </b-overlay>
        <b-overlay :show="loading_modal" rounded opacity="0.6" spinner-small spinner-variant="primary" class="d-inline-block">
          <b-button variant="primary" @click="ok()">Simpan</b-button>
        </b-overlay>
      </template>
    </b-modal>
  </div>
</template>

<script>
import { BCard, BOverlay, BButton, BRow, BCol, BFormGroup, BFormInput, BFormTextarea } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus';
import vSelect from 'vue-select'
import Datatable from './Datatable.vue' //IMPORT COMPONENT DATATABLENYA
export default {
  components: {
    BCard,
    BOverlay,
    BButton,
    BRow, BCol, BFormGroup, BFormInput,
    BFormGroup, BFormTextarea,
    vSelect,
    Datatable
  },
  data() {
    return {
      loading: false,
      loading_modal: false,
      isBusy: true,
      fields: [
        {
          key: 'mata_pelajaran_id',
          label: 'Mata Pelajaran',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'id_kompetensi',
          label: 'Kode',
          sortable: true,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'kelas',
          label: 'Kelas',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'kompetensi_dasar',
          label: 'Deskripsi Kompetensi Dasar',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'kurikulum',
          label: 'Kurikulum',
          sortable: true,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'status',
          label: 'Status',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'actions',
          label: 'Aksi',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
      ],
      items: [],
      meta: {},
      current_page: 1, //DEFAULT PAGE YANG AKTIF ADA PAGE 1
      per_page: 10, //DEFAULT LOAD PERPAGE ADALAH 10
      search: '',
      sortBy: 'updated_at', //DEFAULT SORTNYA ADALAH CREATED_AT
      sortByDesc: false, //ASCEDING
      tahun_pelajaran: '',
      kompetensi_dasar_id: '',
      kompetensi_dasar: '',
      kompetensi_dasar_alias: '',
      rombongan_belajar_id: '',
      kompetensi_id: '',
      id_kompetensi: '',
      mata_pelajaran_id: '',
      tingkat: '',
      tingkat_state: null,
      rombongan_belajar_id_state : null,
      mata_pelajaran_id_state : null,
      kompetensi_id_state : null,
      id_kompetensi_state : null,
      kompetensi_dasar_state : null,
      tingkat_feedback: '',
      rombongan_belajar_id_feedback: '',
      mata_pelajaran_id_feedback: '',
      kompetensi_id_feedback: '',
      id_kompetensi_feedback: '',
      kompetensi_dasar_feedback: '',
      data_tingkat: [
        {
          id: 10,
          nama: 'Kelas 10',
        },
        {
          id: 11,
          nama: 'Kelas 11',
        },
        {
          id: 12,
          nama: 'Kelas 12',
        },
        {
          id: 13,
          nama: 'Kelas 13',
        },
      ],
      data_rombel: [],
      data_mapel: [],
      data_kompetensi: [
        {
          id: 1,
          nama: 'Pengetahuan',
        },
        {
          id: 2,
          nama: 'Keterampilan',
        },
        {
          id: 3,
          nama: 'Pusat Keunggulan',
        },
      ],
    }
  },
  created() {
    this.loadPostsData()
    this.tahun_pelajaran = this.user.semester.nama
    eventBus.$on('add-modal', this.handleEvent);
  },
  methods: {
    changeTingkat(val){
      this.loading_modal = true
      this.$http.post('/referensi/get-rombel', {
        add_kd: 1,
        tingkat: val,
        guru_id: this.user.guru_id,
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.loading_modal = false
        let getData = response.data
        this.data_rombel = getData.data
      }).catch(error => {
        console.log(error);
      })
    },
    changeRombel(val){
      this.loading_modal = true
      this.$http.post('/referensi/get-mapel', {
        add_kd: 1,
        rombongan_belajar_id: val,
        guru_id: this.user.guru_id,
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.loading_modal = false
        let getData = response.data
        this.data_mapel = getData.data
      }).catch(error => {
        console.log(error);
      })
    },
    handleEvent(){
      this.$refs['add-modal'].show()
    },
    loadPostsData() {
      this.isBusy = true
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      this.$http.get('/referensi/kompetensi-dasar', {
        params: {
          user_id: this.user.user_id,
          guru_id: this.user.guru_id,
          sekolah_id: this.user.sekolah_id,
          semester_id: this.user.semester.semester_id,
          periode_aktif: this.user.semester.nama,
          page: current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
        }
      }).then(response => {
        let getData = response.data.data
        this.isBusy = false
        this.items = getData.data
        this.meta = {
          total: getData.total,
          current_page: getData.current_page,
          per_page: getData.per_page,
          from: getData.from,
          to: getData.to,
        }
      })
    },
    handlePerPage(val) {
      this.per_page = val 
      this.loadPostsData() 
    },
    handlePagination(val) {
      this.current_page = val 
      this.loadPostsData()
    },
    handleSearch(val) {
      this.search = val
      this.loadPostsData()
    },
    handleSort(val) {
      if (val.sortBy) {
        this.sortBy = val.sortBy
        this.sortByDesc = val.sortDesc
        this.loadPostsData()
      }
    },
    resetModal(){
      this.kompetensi_dasar_id = ''
      this.kompetensi_dasar = ''
      this.kompetensi_dasar_alias = ''
      //add
      this.rombongan_belajar_id = ''
      this.kompetensi_id = ''
      this.id_kompetensi = ''
      this.mata_pelajaran_id = ''
      this.tingkat = ''
      //validation
      this.tingkat_state = null
      this.rombongan_belajar_id_state  = null
      this.mata_pelajaran_id_state  = null
      this.kompetensi_id_state  = null
      this.id_kompetensi_state  = null
      this.kompetensi_dasar_state  = null
      this.tingkat_feedback = ''
      this.rombongan_belajar_id_feedback = ''
      this.mata_pelajaran_id_feedback = ''
      this.kompetensi_id_feedback = ''
      this.id_kompetensi_feedback = ''
      this.kompetensi_dasar_feedback = ''
    },
    handleOk(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleOkAdd(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.handleSubmitAdd()
    },
    handleSubmit(){
      var aksi = '/referensi/update-kd'
      var params = {
        kompetensi_dasar_id: this.kompetensi_dasar_id,
        kompetensi_dasar_alias: this.kompetensi_dasar_alias,
      }
      this.submitted(aksi, params, 'edit-modal', 'refresh')
    },
    handleSubmitAdd(){
      var aksi = '/referensi/add-kd'
      var params = {
        user_id: this.user.user_id,
        rombongan_belajar_id: this.rombongan_belajar_id,
        id_kompetensi: this.id_kompetensi,
        kompetensi_id: this.kompetensi_id,
        mata_pelajaran_id: this.mata_pelajaran_id,
        tingkat: this.tingkat,
        kompetensi_dasar: this.kompetensi_dasar,
      }
      this.submitted(aksi, params, 'add-modal', 'refresh')
    },
    submitted(aksi, params, ref_modal, after){
      this.loading_modal = true
      this.$http.post(aksi, params).then(response => {
        this.loading_modal = false
        let getData = response.data
        if(getData.errors){
          this.tingkat_state = (getData.errors.tingkat) ? false : null
          this.rombongan_belajar_id_state = (getData.errors.rombongan_belajar_id) ? false : null
          this.mata_pelajaran_id_state = (getData.errors.mata_pelajaran_id) ? false : null
          this.kompetensi_id_state = (getData.errors.kompetensi_id) ? false : null
          this.id_kompetensi_state = (getData.errors.id_kompetensi) ? false : null
          this.kompetensi_dasar_state = (getData.errors.kompetensi_dasar) ? false : null
          this.tingkat_feedback = (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : ''
          this.rombongan_belajar_id_feedback = (getData.errors.rombongan_belajar_id) ? getData.errors.rombongan_belajar_id.join(', ') : ''
          this.mata_pelajaran_id_feedback = (getData.errors.mata_pelajaran_id) ? getData.errors.mata_pelajaran_id.join(', ') : ''
          this.kompetensi_id_feedback = (getData.errors.kompetensi_id) ? getData.errors.kompetensi_id.join(', ') : ''
          this.id_kompetensi_feedback = (getData.errors.id_kompetensi) ? getData.errors.id_kompetensi.join(', ') : ''
          this.kompetensi_dasar_feedback = (getData.errors.kompetensi_dasar) ? getData.errors.kompetensi_dasar.join(', ') : ''
        } else {
          this.$refs[ref_modal].hide()
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.loadPostsData()
          })
        }
      }).catch(error => {
        console.log(error);
      })
    },
    handleEdit(val) {
      this.kompetensi_dasar_id = val.kompetensi_dasar_id
      this.kompetensi_dasar = val.kompetensi_dasar
      this.kompetensi_dasar_alias = val.kompetensi_dasar_alias
      this.$refs['edit-modal'].show()
      //console.log('handleEdit');
      //console.log(val);
      //
    },
    handleHapus(val) {
      this.kompetensi_dasar_id = val
      var text = 'Tindakan ini akan mengembalikan isi ringkasan ke bawaan aplikasi!'
      var aksi = '/referensi/update-kd'
      var params = {
        kompetensi_dasar_id: this.kompetensi_dasar_id,
        kompetensi_dasar_alias: null,
      }
      this.swalConfirm(text, aksi, params, 'refresh')
    },
    handleNonAktifkan(val) {
      this.kompetensi_dasar_id = val
      var text = 'Tindakan ini akan menonaktifkan data Kompetensi Dasar!'
      var aksi = '/referensi/status-kd'
      var params = {
        kompetensi_dasar_id: this.kompetensi_dasar_id,
        aktif: 0,
      }
      this.swalConfirm(text, aksi, params, 'refresh')
    },
    handleAktifkan(val) {
      this.kompetensi_dasar_id = val
      var text = 'Tindakan ini akan mengaktifkan data Kompetensi Dasar!'
      var aksi = '/referensi/status-kd'
      var params = {
        kompetensi_dasar_id: this.kompetensi_dasar_id,
        aktif: 1,
      }
      this.swalConfirm(text, aksi, params, 'refresh')
    },
    handleHapusGanda(params) {
      var text = 'Tindakan ini akan menghapus data ganda Kompetensi Dasar!'
      var aksi = '/referensi/hapus-kd-ganda'
      this.swalConfirm(text, aksi, params, 'refresh')
    },
    swalConfirm(text, aksi, params, after){
      this.$swal({
        title: 'Apakah Anda yakin?',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yakin!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
        allowOutsideClick: () => false,
      }).then(result => {
        if (result.value) {
          this.loading_modal = true
          this.$http.post(aksi, params).then(response => {
            let getData = response.data
            this.$swal({
              icon: getData.icon,
              title: getData.title,
              text: getData.text,
              customClass: {
                confirmButton: 'btn btn-success',
              },
            }).then(result => {
              if(after == 'refresh'){
                this.loadPostsData()
              }
            })
          });
        }
      })
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>