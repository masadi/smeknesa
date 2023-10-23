<template>
  <b-modal v-model="editModalShow" size="xl" title="Edit Data CP" @hidden="hideModal" @ok="handleOk" scrollable>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Capaian Pembelajaran" label-for="desk_cp" label-cols-md="3" :invalid-feedback="feedback.desk_cp" :state="state.desk_cp">
              <b-form-textarea id="desk_cp" v-model="form.desk_cp" :state="state.desk_cp" placeholder="Deskripsi Capaian Pembelajaran" rows="3" max-rows="8"></b-form-textarea>
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
      editModalShow: false,
      loading_form: false,
      data_pembelajaran: [],
      form: {
        cp_id: '',
        desk_cp: ''
      },
      feedback: {
        desk_cp: ''
      },
      state: {
        desk_cp: null,
      },
    }
  },
  created() {
    eventBus.$on('open-modal-edit-cp', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.form.cp_id = data.cp_id
      this.form.desk_cp = data.deskripsi
      this.editModalShow = true
    },
    hideModal(){
      this.editModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.desk_cp
      this.feedback.desk_cp = ''
      this.state.desk_cp = null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.$http.post('/nilai/update-cp', this.form).then(response => {
        let getData = response.data
        if(getData.errors){
          this.feedback.desk_cp = (getData.errors.desk_cp) ? getData.errors.desk_cp.join(', ') : ''
          this.state.desk_cp = (getData.errors.desk_cp) ? false : null
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.$emit('reload')
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