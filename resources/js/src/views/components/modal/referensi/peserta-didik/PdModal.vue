<template>
  <b-modal v-model="modalShow" title="Tambah Data Periodik" size="lg" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Unduh Template" label-cols-md="3">
              <b-button variant="success" href="/downloads/template-pd.xlsx">Unduh Template</b-button>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-form-group label="Upload Excel*" label-for="file_excel" label-cols-md="3" :invalid-feedback="feedback.file_excel" :state="state.file_excel">
              <b-form-file v-model="form.file_excel" :state="state.file_excel" placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..."></b-form-file>
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
          <b-button variant="success" @click="ok()">Proses</b-button>
        </b-overlay>
      </template>
  </b-modal>
</template>

<script>
import { BOverlay, BForm, BFormFile, BRow, BCol, BFormGroup, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BOverlay, BForm, BFormFile, BRow, BCol, BFormGroup, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton,
  },
  data() {
    return {
      modalShow: false,
      loading_form: false,
      form: {
        file_excel: null,
      },
      feedback: {
        file_excel: '',
      },
      state: {
        file_excel: null,
      }
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-pd-aktif', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.modalShow = true
    },
    hideModal(){
      this.modalShow = false
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.feedback.file_excel = ''
      this.state.file_excel = null
      if(this.form.file_excel){
        this.loading_form = true
        let formData = new FormData();
        formData.append('file', this.form.file_excel);
        formData.append('data', 'pd');
        this.$http.post('/upload', formData).then(response => {
          this.loading_form = false
          let getData = response.data
          if(getData.errors){
            this.feedback.file_excel = (getData.errors.file) ? getData.errors.file.join(', ') : ''
            this.state.file_excel = (getData.errors.file) ? false : null
          } else {
            this.$swal({
              icon: getData.icon,
              title: getData.title,
              html: getData.text,
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
      } else {
        this.feedback.file_excel = 'File excel tidak boleh kosong!'
        this.state.file_excel = false
      }
    },
  },
}
</script>