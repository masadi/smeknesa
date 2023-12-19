<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="danger">
          <b-form @submit.prevent="handleSubmit">
            <b-row>
              <b-col cols="12">
                <b-row>
                  <b-col cols="8">
                    <b-form-group label="Nama Aplikasi" label-for="app_name" :invalid-feedback="feedback.app_name" :state="state.app_name">
                      <b-form-input v-model="form.app_name" placeholder="Nama Aplikasi"></b-form-input>
                    </b-form-group>
                    <b-form-group label="Versi Aplikasi" label-for="app_version" :invalid-feedback="feedback.app_version" :state="state.app_version">
                      <b-form-input v-model="form.app_version" placeholder="Versi Aplikasi"></b-form-input>
                    </b-form-group>
                    <b-form-group label="Periode Aktif" label-for="semester_id" :invalid-feedback="feedback.semester_id" :state="state.semester_id">
                      <v-select id="semester_id" v-model="form.semester_id" :options="semester" :reduce="nama => nama.semester_id" label="nama" placeholder="== Pilih Periode Aktif ==" :clearable="false">
                        <template #no-options="{ search, searching, loading }">
                          Tidak ada data untuk ditampilkan
                        </template>
                      </v-select>
                    </b-form-group>
                    <b-form-group label="Batas Waktu Penilaian" label-for="tanggal_penilaian">
                      <b-form-datepicker v-model="form.tanggal_penilaian" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_penilaian" @context="onContext" placeholder="== Pilih Batas Waktu Penilaian ==" />
                    </b-form-group>
                    <!--b-form-group label="Tanggal Tampil Tombol Whatsapp" label-for="tanggal_whatsapp" :invalid-feedback="feedback.tanggal_whatsapp" :state="state.tanggal_whatsapp">
                      <b-form-input v-model="form.tanggal_whatsapp" placeholder="Tanggal Tampil Tombol Whatsapp"></b-form-input>
                    </b-form-group-->
                  </b-col>
                  <b-col cols="4">
                    <b-form-group label="Logo Sekolah" label-for="logo">
                      <b-img thumbnail fluid :src="logo_sekolah" alt="Logo Sekolah" class="mb-1"></b-img>
                      <b-form-file v-model="file" :state="Boolean(file)" placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..."></b-form-file>
                    </b-form-group>
                  </b-col>
                </b-row>
              </b-col>
            </b-row>
            <b-row>
              <b-col cols="12">
                <b-button type="submit" variant="primary">Simpan</b-button>
              </b-col>
            </b-row>
          </b-form>
        </b-overlay>
      </div>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BOverlay, BForm, BRow, BCol, BFormGroup, BInputGroup, BFormInput, BFormDatepicker, BButton, BFormFile, BImg } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BCard, BCardBody, BSpinner, BOverlay, BForm, BRow, BCol, BFormGroup, BInputGroup, BFormInput, BFormDatepicker, BButton, BFormFile, BImg, vSelect
  },
  data() {
    return {
      loading: false,
      isBusy: true,
      semester: [],
      form: {
        app_name: '',
        app_version: '',
        semester_id: '',
        tanggal_penilaian: '',
        tanggal_whatsapp: '',
      },
      feedback: {
        app_name: '',
        app_version: '',
        semester_id: '',
        tanggal_penilaian: '',
        tanggal_whatsapp: '',
      },
      state: {
        app_name: null,
        app_version: null,
        semester_id: null,
        tanggal_penilaian: null,
        tanggal_whatsapp: null,
      },
      file: null,
      logo_sekolah: null,
    }
  },
  created() {
    this.loadPostData()
  },
  methods: {
    loadPostData(){
      this.isBusy = true
      this.$http.get('/pengaturan/umum').then(response => {
        this.isBusy = false
        let getData = response.data
        this.form.app_name = getData.app_name
        this.form.app_version = getData.app_version
        this.form.semester_id = getData.semester_id
        this.form.tanggal_penilaian = getData.tanggal_penilaian
        this.form.tanggal_whatsapp = getData.tanggal_whatsapp
        this.semester = getData.semester
        this.logo_sekolah = getData.logo
      })
    },
    handleSubmit(){
      this.loading = true
      const data = new FormData();
      data.append('logo', (this.file) ? this.file : '')
      data.append('app_name', (this.form.app_name) ? this.form.app_name : '')
      data.append('app_version', (this.form.app_version) ? this.form.app_version : '')
      data.append('semester_id', (this.form.semester_id) ? this.form.semester_id : '')
      data.append('tanggal_penilaian', (this.form.tanggal_penilaian) ? this.form.tanggal_penilaian : '')
      //data.append('tanggal_whatsapp tanggal_whatsapp: '')
      this.$http.post('/pengaturan/umum', data).then(response => {
        this.loading = false
        let getData = response.data
        this.state.app_name = null
        this.state.app_version = null
        this.state.semester_id = null
        this.state.tanggal_penilaian = null
        this.state.tanggal_whatsapp = null
        if(getData.errors){
          this.state.app_name = (getData.errors.app_name) ? false : null
          this.state.app_version = (getData.errors.app_version) ? false : null
          this.state.semester_id = (getData.errors.semester_id) ? false : null
          this.state.tanggal_penilaian = (getData.errors.tanggal_penilaian) ? false : null
          this.state.tanggal_whatsapp = (getData.errors.tanggal_whatsapp) ? false : null
          this.feedback.app_name = (getData.errors.app_name) ? getData.errors.app_name.join(', ') : ''
          this.feedback.app_version = (getData.errors.app_version) ? getData.errors.app_version.join(', ') : ''
          this.feedback.semester_id = (getData.errors.semester_id) ? getData.errors.semester_id.join(', ') : ''
          this.feedback.tanggal_penilaian = (getData.errors.tanggal_penilaian) ? getData.errors.tanggal_penilaian.join(', ') : ''
          this.feedback.tanggal_whatsapp = (getData.errors.tanggal_whatsapp) ? getData.errors.tanggal_whatsapp.join(', ') : ''
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            html: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
            allowOutsideClick: false,
          }).then(result => {
            this.loadPostData()
          })
        }
      })
    },
    onContext(ctx) {
      this.formatted = ctx.selectedFormatted
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>