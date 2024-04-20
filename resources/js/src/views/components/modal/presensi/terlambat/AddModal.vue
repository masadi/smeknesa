<template>
  <b-modal v-model="modalShow" title="Tambah Data Terlamat" size="xl" @show="resetForm" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Nama Siswa" label-for="anggota_rombel_id" label-cols-md="3" :invalid-feedback="feedback.anggota_rombel_id" :state="state.anggota_rombel_id">
              <v-select id="anggota_rombel_id" v-model="form.anggota_rombel_id" :reduce="nama => nama.anggota_rombel.anggota_rombel_id" label="nama" :options="data_siswa" placeholder="Cari nama siswa" :state="state.anggota_rombel_id" @search="fetchOptions">
                <template slot="no-options">
                  Cari nama siswa...
                </template>
                <template slot="option" slot-scope="option">
                  <!--media-siswa :data="option"></media-siswa-->
                  <strong>{{ option.nama }}</strong><br>
                  Kelas: {{ option.kelas.nama }}
                </template>
                <template slot="selected-option" slot-scope="option">
                  <div class="selected d-center">
                    <media-siswa :data="option"></media-siswa>
                  </div>
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Alasan Terlambat" label-for="keterangan" label-cols-md="3" :invalid-feedback="feedback.keterangan" :state="state.keterangan">
              <b-form-input v-model="form.keterangan" placeholder="Isi alasan Terlambat" :state="state.tanggal"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Tanggal Terlambat" label-for="tanggal" label-cols-md="3" :invalid-feedback="feedback.tanggal" :state="state.tanggal">
              <b-form-datepicker v-model="form.tanggal" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal" @context="onContext" placeholder="== Pilih Tanggal Terlambat ==" :state="state.tanggal" />
            </b-form-group>
          </b-col>
        </b-row>
      </b-form>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
          <b-button @click="cancel()">Batal</b-button>
        </b-overlay>
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
          <b-button variant="success" @click="ok()">Simpan</b-button>
        </b-overlay>
      </template>
  </b-modal>
</template>

<script>
import { BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BButton, BFormDatepicker} from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import _ from 'lodash'
import vSelect from 'vue-select'
import MediaSiswa from './../../../MediaSiswa.vue'
export default {
  components: {
    vSelect, MediaSiswa, BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BButton,
    BFormDatepicker
  },
  data() {
    return {
      modalShow: false,
      loading_form: false,
      loading_jam: false,
      form: {
        anggota_rombel_id: '',
        keterangan: '',
        tanggal: '',

      },
      feedback: {
        anggota_rombel_id: '',
        keterangan: '',
        tanggal: '',
      },
      state: {
        anggota_rombel_id: null,
        keterangan: null,
        tanggal: null,
      },
      data_siswa: [],
    }
  },
  created() {
    this.form.semester_id = this.user.semester.semester_id
    eventBus.$on('open-modal-add-terlambat', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.modalShow = true
    },
    hideModal(){
      this.modalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.anggota_rombel_id = ''
      this.form.keterangan = ''
      this.form.tanggal = ''
      this.feedback.anggota_rombel_id = ''
      this.feedback.keterangan = ''
      this.feedback.tanggal = ''
      this.state.anggota_rombel_id = null
      this.state.keterangan = null
      this.state.tanggal = null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit('cetak')
    },
    handleSubmit(aksi){
      this.form.jam_ke = this.jam_selected
      this.loading_form = true
      this.$http.post('/presensi/add-terlambat', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.feedback.anggota_rombel_id = (getData.errors.anggota_rombel_id) ? getData.errors.anggota_rombel_id.join(', ') : ''
          this.feedback.keterangan = (getData.errors.keterangan) ? getData.errors.keterangan.join(', ') : ''
          this.feedback.tanggal = (getData.errors.tanggal) ? getData.errors.tanggal.join(', ') : ''
          this.state.anggota_rombel_id = (getData.errors.anggota_rombel_id) ? false : null
          this.state.keterangan = (getData.errors.keterangan) ? false : null
          this.state.tanggal = (getData.errors.tanggal) ? false : null
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
            confirmButtonText: aksi.toUpperCase(),
            allowOutsideClick: false,
          }).then(result => {
            if(getData.success){
              this.hideModal()
              this.$emit('reload')
              if(getData.terlambat){
                window.open(`/cetak/terlambat/${getData.terlambat.terlambat_id}/${this.form.semester_id}`, '_blank')
              }
            }
          })
        }
      }).catch(error => {
        console.log(error);
      })
    },
    onContext(ctx) {
      this.formatted = ctx.selectedFormatted
    },
    getSiswa(val){
      this.loading_form = true
      this.$http.post('/presensi/get-siswa', {
        nama_siwa: val,
        semester_id: this.form.semester_id,
      }).then(response => {
        this.loading_form = false
        var getData = response.data
        console.log(getData);
      })
    },
    search: _.debounce((loading, search, vm) => {
      if(search){
        vm.$http.post('/presensi/get-siswa', {
          nama_siwa: search,
          semester_id: vm.form.semester_id,
        }).then(response => {
          //res.json().then(json => (vm.data_siswa = json.items));
          loading(false);
          var getData = response.data
          vm.data_siswa = getData.items
          //console.log(getData);
        })
      }
    }, 500),
    fetchOptions (search, loading) {
      if(search.length) {
        loading(true);
        this.search(loading, search, this);
      }
    },
  },
}
</script>