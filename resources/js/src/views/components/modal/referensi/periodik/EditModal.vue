<template>
  <b-modal v-model="editSemester" title="Edit Data Periodik" size="xl" @show="resetForm" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Semester*" label-for="nama" label-cols-md="3">
              <b-form-input id="nama" v-model="form.nama" disabled></b-form-input>
            </b-form-group>  
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tanggal Mulai*" label-for="tanggal_mulai" label-cols-md="3" :invalid-feedback="feedback.tanggal_mulai" :state="state.tanggal_mulai">
              <b-form-datepicker v-model="form.tanggal_mulai" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_mulai" @context="onContext" placeholder="== Pilih Tanggal Mulai ==" :state="state.tanggal_mulai" />
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tanggal Selesai*" label-for="tanggal_selesai" label-cols-md="3" :invalid-feedback="feedback.tanggal_selesai" :state="state.tanggal_selesai">
              <b-form-datepicker v-model="form.tanggal_selesai" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_selesai" @context="onContext" placeholder="== Pilih Tanggal Selesai ==" :state="state.tanggal_selesai" />
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tanggal Rapor*" label-for="tanggal_cetak" label-cols-md="3" :invalid-feedback="feedback.tanggal_cetak" :state="state.tanggal_cetak">
              <b-form-datepicker v-model="form.tanggal_cetak" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_cetak" @context="onContext" placeholder="== Pilih Tanggal Rapor ==" :state="state.tanggal_cetak" />
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
          <b-button variant="success" @click="ok()">Perbaharui</b-button>
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
      editSemester: false,
      loading_form: false,
      form: {
        semester_id: '',
        nama: '',
        tanggal_mulai: '',
        tanggal_selesai: '',
        tanggal_cetak: '',
      },
      feedback: {
        tanggal_mulai: '',
        tanggal_selesai: '',
        tanggal_cetak: '',
      },
      state: {
        tanggal_mulai: null,
        tanggal_selesai: null,
        tanggal_cetak: null,
      }
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-edit-semester', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.editSemester = true
      this.form.semester_id = data.semester_id
      this.form.nama = data.nama
      this.form.tanggal_mulai = data.tanggal_mulai
      this.form.tanggal_selesai = data.tanggal_selesai
      this.form.tanggal_cetak = data.tanggal_cetak
    },
    hideModal(){
      this.editSemester = false
      this.resetForm()
    },
    resetForm(){
      this.feedback.semester_id = ''
      this.feedback.tanggal_mulai = ''
      this.feedback.tanggal_selesai = ''
      this.feedback.tanggal_cetak = ''
      this.state.semester_id = null
      this.state.tanggal_mulai = null
      this.state.tanggal_selesai = null
      this.state.tanggal_cetak = null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/referensi/update-semester', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.feedback.tanggal_mulai = (getData.errors.tanggal_mulai) ? getData.errors.tanggal_mulai.join(', ') : ''
          this.state.tanggal_mulai = (getData.errors.tanggal_mulai) ? false : null
          this.feedback.tanggal_selesai = (getData.errors.tanggal_selesai) ? getData.errors.tanggal_selesai.join(', ') : ''
          this.state.tanggal_selesai = (getData.errors.tanggal_selesai) ? false : null
          this.feedback.tanggal_cetak = (getData.errors.tanggal_cetak) ? getData.errors.tanggal_cetak.join(', ') : ''
          this.state.tanggal_cetak = (getData.errors.tanggal_cetak) ? false : null
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