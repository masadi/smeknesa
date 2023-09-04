<template>
  <b-modal v-model="editKktpShow" title="Edit KKTP" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="KKTP" label-for="kktp" label-cols-md="3" :invalid-feedback="feedback.kktp" :state="state.kktp">
              <b-form-input id="kktp" v-model="form.kktp" :state="state.kktp" placeholder="KKTP"></b-form-input>
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
import { BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BOverlay,
    BForm,
    BFormInput,
    BRow,
    BCol,
    BFormGroup,
    BButton,
  },
  data() {
    return {
      editKktpShow: false,
      loading_form: false,
      form: {},
      feedback: {},
      state: {},
    }
  },
  created() {
    eventBus.$on('open-modal-kktp-mapel', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.$http.post('/referensi/detil-data', {
        id: data.mata_pelajaran_id,
        data: 'mapel',
      }).then(response => {
        var getData = response.data
        if(getData.errors){
          this.$swal({
            icon: 'error',
            title: 'Error',
            text: getData.errors,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
        } else {
          this.form = getData.mapel
          this.form.data = 'kktp'
          this.editKktpShow = true
        }
      })
    },
    hideModal(){
      this.editKktpShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.kktp = ''
      this.feedback.kktp = ''
      this.state.kktp = null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/referensi/update-data', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.kktp = (getData.errors.kktp) ? false : null
          this.feedback.kktp = (getData.errors.kktp) ? getData.errors.kktp.join(', ') : ''
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            allowOutsideClick: false,
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