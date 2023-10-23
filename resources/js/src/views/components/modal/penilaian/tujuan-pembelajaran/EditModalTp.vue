<template>
  <b-modal v-model="editTpModalShow" size="xl" title="Edit Data Tujuan Pembelajaran" @hidden="hideModal" @ok="handleOk" scrollable>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tujuan Pembelajaran" label-for="deskripsi" label-cols-md="3" :invalid-feedback="feedback.deskripsi" :state="state.deskripsi">
              <b-form-textarea id="deskripsi" v-model="form.deskripsi" :state="state.deskripsi" placeholder="Deskripsi Tujuan Pembelajaran" rows="3" max-rows="8"></b-form-textarea>
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
import { BOverlay, BForm, BFormGroup, BRow, BCol, BFormTextarea, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BOverlay,
    BForm,
    BFormGroup,
    BRow,
    BCol,
    BFormTextarea,
    BButton,
  },
  data() {
    return {
      editTpModalShow: false,
      loading_form: false,
      data_pembelajaran: [],
      form: {
        tp_id: '',
        cp_id: '',
        deskripsi: ''
      },
      feedback: {
        deskripsi: ''
      },
      state: {
        deskripsi: null,
      },
    }
  },
  created() {
    eventBus.$on('open-modal-edit-tp', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.form.tp_id = data.tp_id
      this.form.cp_id = data.cp_id
      this.form.deskripsi = data.deskripsi
      this.editTpModalShow = true
    },
    hideModal(){
      this.editTpModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.deskripsi
      this.feedback.deskripsi = ''
      this.state.deskripsi = null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.$http.post('/nilai/update-tp', this.form).then(response => {
        let getData = response.data
        if(getData.errors){
          this.feedback.deskripsi = (getData.errors.deskripsi) ? getData.errors.deskripsi.join(', ') : ''
          this.state.deskripsi = (getData.errors.deskripsi) ? false : null
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.$emit('reload_cp', getData.cp)
            this.hideModal()
          })
        }
      });
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
.modal-fullscreen .modal-dialog {
    max-width: 100%;
    margin: 0;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100vh;
    display: flex;
    position: fixed;
    z-index: 100000;
}
</style>