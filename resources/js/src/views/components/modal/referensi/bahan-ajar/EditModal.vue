<template>
  <b-modal v-model="editModalShow" title="Edit Data Mata Pelajaran" size="lg" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Deskripsi Capaian Pembelajaran" label-for="deskripsi" label-cols-md="3" :invalid-feedback="feedback.deskripsi" :state="state.deskripsi">
              <b-form-textarea id="textarea" v-model="form.deskripsi" placeholder="Enter something..." rows="3" max-rows="6" :state="state.deskripsi"></b-form-textarea>
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
import { BOverlay, BForm, BFormTextarea, BRow, BCol, BFormGroup, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BOverlay,
    BForm,
    BFormTextarea,
    BRow,
    BCol,
    BFormGroup,
    BButton,
  },
  data() {
    return {
      editModalShow: false,
      loading_form: false,
      form: {
        cp_id: '',
        deskripsi: '',
      },
      feedback: {
        deskripsi: '',
      },
      state: {
        deskripsi: null,
      },
    }
  },
  created() {
    /*this.form.edit = true
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama*/
    eventBus.$on('open-modal-edit-ref-cp', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      console.log(data);
      this.form.cp_id = data.cp_id
      this.form.deskripsi = data.deskripsi
      this.editModalShow = true
    },
    hideModal(){
      this.editModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.cp_id = ''
      this.form.deskripsi = ''
      this.feedback.nama = ''
      this.feedback.deskripsi = ''
      this.state.deskripsi = null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/referensi/update-cp', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.cp_id = (getData.errors.cp_id) ? false : null
          this.state.deskripsi = (getData.errors.deskripsi) ? false : null
          this.feedback.cp_id = (getData.errors.cp_id) ? getData.errors.cp_id.join(', ') : ''
          this.feedback.deskripsi = (getData.errors.deskripsi) ? getData.errors.deskripsi.join(', ') : ''
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
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>