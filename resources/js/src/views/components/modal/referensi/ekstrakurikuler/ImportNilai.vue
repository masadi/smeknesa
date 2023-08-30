<template>
  <b-modal v-model="importModalShow" title="Import Nilai Ekskul" @show="resetForm" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-button block variant="primary" :href="link_export_nilai" target="_blank">Download Template Excel</b-button>
          </b-col>
          <b-col cols="12" class="mt-2">
            <b-form-group :invalid-feedback="feedback_file" :state="state_file">
              <b-form-file v-model="file1" :state="state_file" placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..." @change="onFileChange"></b-form-file>
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
import { BOverlay, BForm, BFormGroup, BFormFile, BRow, BCol, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BOverlay,
    BForm,
    BFormGroup,
    BFormFile,
    BRow,
    BCol,
    BButton,
  },
  data() {
    return {
      importModalShow: false,
      loading_form: false,
      file1:  null,
      feedback_file: '',
      state_file: null,
      form: {},
      state: {},
      link_export_nilai: '',
    }
  },
  created() {
    eventBus.$on('open-modal-import-nilai', this.handleEvent);
  },
  methods: {
    handleEvent(guru_id){
      eventBus.$emit('loading', true)
      this.$http.post('/referensi/get-data', {
        data: 'rombel',
        guru_id: guru_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        eventBus.$emit('loading', false)
        let getData = response.data
        this.link_export_nilai = `/export/nilai-ekskul/${getData.rombongan_belajar_id}`
        this.importModalShow = true
      }).catch(error => {
        console.log(error);
      })
    },
    hideModal(){
      this.importModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.file1 = null
    },
    onFileChange(e) {
      this.file1 = e.target.files[0];
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      const data = new FormData();
      data.append('file', (this.file1) ? this.file1 : '');
      data.append('data', 'nilai-ekskul');
      this.$http.post('/upload', data).then(response => {
        this.loading_form = false
        let getData = response.data
        console.log(getData);
        if(getData.errors){
          this.feedback_file = (getData.errors.file) ? getData.errors.file.join(', ') : ''
          this.state_file = (getData.errors.file) ? false : null
          //this.feedback.tahun_ajaran_id = (getData.errors.tahun_ajaran_id) ? getData.errors.tahun_ajaran_id.join(', ') : ''
          //this.state.tahun_ajaran_id = (getData.errors.tahun_ajaran_id) ? false : null
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
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>