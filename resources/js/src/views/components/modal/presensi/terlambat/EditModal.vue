<template>
  <b-modal v-model="modalShow" title="Edit Data Terlambat" size="xl" @show="resetForm" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Nama*" label-for="nama" label-cols-md="3">
              <b-form-input id="nama" v-model="form.nama" disabled></b-form-input>
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
          <b-button @click="cancel()">Tutup</b-button>
        </b-overlay>
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
          <b-button variant="success" @click="ok()">Perbaharui</b-button>
        </b-overlay>
      </template>
  </b-modal>
</template>

<script>
import { BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BButton, BFormDatepicker } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BButton,
    BFormDatepicker,
  },
  data() {
    return {
      modalShow: false,
      loading_form: false,
      form: {
        nama: '',
        terlambat_id: '',
        keterangan: '',
        tanggal: '',

      },
      feedback: {
        keterangan: '',
        tanggal: '',
      },
      state: {
        keterangan: null,
        tanggal: null,
      },
    }
  },
  created() {
    this.form.semester_id = this.user.semester.semester_id
    eventBus.$on('open-modal-edit-terlambat', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.modalShow = true
      this.form.nama = data.pd.nama
      this.form.terlambat_id = data.terlambat_id
      this.form.tanggal = data.tanggal
      this.form.keterangan = data.keterangan
    },
    hideModal(){
      this.modalShow = false
      this.resetForm()
    },
    resetForm(){
      this.feedback.tanggal = ''
      this.feedback.keterangan = ''
      this.state.tanggal = null
      this.state.keterangan = null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/presensi/update-terlambat', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.feedback.tanggal = (getData.errors.tanggal) ? getData.errors.tanggal.join(', ') : ''
          this.state.tanggal = (getData.errors.tanggal) ? false : null
          this.feedback.keterangan = (getData.errors.keterangan) ? getData.errors.keterangan.join(', ') : ''
          this.state.keterangan = (getData.errors.keterangan) ? false : null
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
            if(getData.terlambat){
                window.open(`/cetak/terlambat/${getData.terlambat.terlambat_id}/${this.form.semester_id}`, '_blank')
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
  },
}
</script>