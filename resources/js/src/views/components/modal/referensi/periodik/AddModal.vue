<template>
  <b-modal v-model="modalShow" title="Tambah Data Periodik" size="xl" @show="resetForm" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tahun Pelajaran*" label-for="tahun_ajaran_id" label-cols-md="3" :invalid-feedback="feedback.tahun_ajaran_id" :state="state.tahun_ajaran_id">
              <b-form-input v-model="form.tahun_ajaran_id" :state="state.tahun_ajaran_id" :placeholder="`Contoh: ${new Date().getFullYear()}`"></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tanggal Mulai SMT Ganjil*" label-for="tanggal_mulai_ganjil" label-cols-md="3" :invalid-feedback="feedback.tanggal_mulai_ganjil" :state="state.tanggal_mulai_ganjil">
              <b-form-datepicker v-model="form.tanggal_mulai_ganjil" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_mulai_ganjil" @context="onContext" placeholder="== Pilih Tanggal Mulai SMT Ganjil ==" :state="state.tanggal_mulai_ganjil" />
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tanggal Selesai SMT Ganjil*" label-for="tanggal_selesai_ganjil" label-cols-md="3" :invalid-feedback="feedback.tanggal_selesai_ganjil" :state="state.tanggal_selesai_ganjil">
              <b-form-datepicker v-model="form.tanggal_selesai_ganjil" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_selesai_ganjil" @context="onContext" placeholder="== Pilih Tanggal Selesai SMT Ganjil ==" :state="state.tanggal_selesai_ganjil" />
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tanggal Rapor SMT Ganjil*" label-for="tanggal_cetak_ganjil" label-cols-md="3" :invalid-feedback="feedback.tanggal_cetak_ganjil" :state="state.tanggal_cetak_ganjil">
              <b-form-datepicker v-model="form.tanggal_cetak_ganjil" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_cetak_ganjil" @context="onContext" placeholder="== Pilih Tanggal Rapor SMT Ganjil ==" :state="state.tanggal_cetak_ganjil" />
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tanggal Mulai SMT Genap*" label-for="tanggal_mulai_genap" label-cols-md="3" :invalid-feedback="feedback.tanggal_mulai_genap" :state="state.tanggal_mulai_genap">
              <b-form-datepicker v-model="form.tanggal_mulai_genap" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_mulai_genap" @context="onContext" placeholder="== Pilih Tanggal Mulai SMT Genap ==" :state="state.tanggal_mulai_genap" />
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tanggal Selesai SMT Genap*" label-for="tanggal_selesai_genap" label-cols-md="3" :invalid-feedback="feedback.tanggal_selesai_genap" :state="state.tanggal_selesai_genap">
              <b-form-datepicker v-model="form.tanggal_selesai_genap" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_selesai_genap" @context="onContext" placeholder="== Pilih Tanggal Selesai SMT Genap ==" :state="state.tanggal_selesai_genap" />
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tanggal Rapor SMT Genap*" label-for="tanggal_cetak_genap" label-cols-md="3" :invalid-feedback="feedback.tanggal_cetak_genap" :state="state.tanggal_cetak_genap">
              <b-form-datepicker v-model="form.tanggal_cetak_genap" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_cetak_genap" @context="onContext" placeholder="== Pilih Tanggal Rapor SMT Genap ==" :state="state.tanggal_cetak_genap" />
            </b-form-group>
          </b-col>
        </b-row>
      </b-form>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
          <b-button @click="cancel()">Tutup</b-button>
        </b-overlay>
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
          <b-button variant="success" @click="ok()">Simpan</b-button>
        </b-overlay>
      </template>
  </b-modal>
</template>

<script>
import { BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BFormSelect, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton, BFormDatepicker } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BFormSelect, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton,
    BFormDatepicker,
  },
  data() {
    return {
      modalShow: false,
      loading_form: false,
      form: {
        tahun_ajaran_id: '',
        tanggal_mulai_ganjil: '',
        tanggal_selesai_ganjil: '',
        tanggal_cetak_ganjil: '',
        tanggal_mulai_genap: '',
        tanggal_selesai_genap: '',
        tanggal_cetak_genap: '',

      },
      feedback: {
        tahun_ajaran_id: '',
        tanggal_mulai_ganjil: '',
        tanggal_selesai_ganjil: '',
        tanggal_cetak_ganjil: '',
        tanggal_mulai_genap: '',
        tanggal_selesai_genap: '',
        tanggal_cetak_genap: '',
      },
      state: {
        tahun_ajaran_id: null,
        tanggal_mulai_ganjil: null,
        tanggal_selesai_ganjil: null,
        tanggal_cetak_ganjil: null,
        tanggal_mulai_genap: null,
        tanggal_selesai_genap: null,
        tanggal_cetak_genap: null,
      }
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-semester', this.handleEvent);
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
      this.form.tahun_ajaran_id = ''
      this.form.tanggal_mulai_ganjil = ''
      this.form.tanggal_selesai_ganjil = ''
      this.form.tanggal_cetak_ganjil = ''
      this.form.tanggal_mulai_genap = ''
      this.form.tanggal_selesai_genap = ''
      this.form.tanggal_cetak_genap = ''
      this.feedback.tahun_ajaran_id = ''
      this.feedback.tanggal_mulai_ganjil = ''
      this.feedback.tanggal_selesai_ganjil = ''
      this.feedback.tanggal_cetak_ganjil = ''
      this.feedback.tanggal_mulai_genap = ''
      this.feedback.tanggal_selesai_genap = ''
      this.feedback.tanggal_cetak_genap = ''
      this.state.tahun_ajaran_id = null
      this.state.tanggal_mulai_ganjil = null
      this.state.tanggal_selesai_ganjil = null
      this.state.tanggal_cetak_ganjil = null
      this.state.tanggal_mulai_genap = null
      this.state.tanggal_selesai_genap = null
      this.state.tanggal_cetak_genap = null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/referensi/add-semester', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.feedback.tahun_ajaran_id = (getData.errors.tahun_ajaran_id) ? getData.errors.tahun_ajaran_id.join(', ') : ''
          this.state.tahun_ajaran_id = (getData.errors.tahun_ajaran_id) ? false : null
          this.feedback.tanggal_mulai_ganjil = (getData.errors.tanggal_mulai_ganjil) ? getData.errors.tanggal_mulai_ganjil.join(', ') : ''
          this.state.tanggal_mulai_ganjil = (getData.errors.tanggal_mulai_ganjil) ? false : null
          this.feedback.tanggal_selesai_ganjil = (getData.errors.tanggal_selesai_ganjil) ? getData.errors.tanggal_selesai_ganjil.join(', ') : ''
          this.state.tanggal_selesai_ganjil = (getData.errors.tanggal_selesai_ganjil) ? false : null
          this.feedback.tanggal_cetak_ganjil = (getData.errors.tanggal_cetak_ganjil) ? getData.errors.tanggal_cetak_ganjil.join(', ') : ''
          this.state.tanggal_cetak_ganjil = (getData.errors.tanggal_cetak_ganjil) ? false : null
          this.feedback.tanggal_mulai_genap = (getData.errors.tanggal_mulai_genap) ? getData.errors.tanggal_mulai_genap.join(', ') : ''
          this.state.tanggal_mulai_genap = (getData.errors.tanggal_mulai_genap) ? false : null
          this.feedback.tanggal_selesai_genap = (getData.errors.tanggal_selesai_genap) ? getData.errors.tanggal_selesai_genap.join(', ') : ''
          this.state.tanggal_selesai_genap = (getData.errors.tanggal_selesai_genap) ? false : null
          this.feedback.tanggal_cetak_genap = (getData.errors.tanggal_cetak_genap) ? getData.errors.tanggal_cetak_genap.join(', ') : ''
          this.state.tanggal_cetak_genap = (getData.errors.tanggal_cetak_genap) ? false : null
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.hideModal()
            this.$emit('reload')
          })
        }
      }).catch(error => {
        console.log(error);
      })
    },
    onContext(ctx) {
      this.formatted = ctx.selectedFormatted
    },
  },
}
</script>