<template>
  <b-modal size="lg" v-model="editModalShow" title="Edit Materi Ekstra" @hidden="hideModal" @ok="handleOk" scrollable>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Materi Ekstra" label-for="materi-ekstra" label-cols-md="3" :invalid-feedback="feedback.materi_ekstra" :state="state.materi_ekstra">
              <b-form-textarea id="materi-ekstra" v-model="form.materi_ekstra" :state="state.materi_ekstra" placeholder="Materi Ekstra" rows="3" max-rows="8"></b-form-textarea>
            </b-form-group>  
          </b-col>
        </b-row>
      </b-form>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="primary" class="d-inline-block">
        <b-button variant="primary" @click="ok()">Perbaharui</b-button>
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
      loading_form: false,
      editModalShow: false,
      form: {
        materi_id: '',
        materi_ekstra: '',
      },
      feedback: {
        materi_ekstra: ''
      },
      state: {
        materi_ekstra: null,
      },
    }
  },
  created() {
    eventBus.$on('open-modal-edit-ekstra', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.form.materi_id = data.materi_id
      this.$http.post('/nilai/detil-materi', {
        id: data.materi_id,
      }).then(response => {
        let getData = response.data
        this.form.materi_ekstra = getData.data.deskripsi
        this.editModalShow = true
      });
    },
    hideModal(){
      this.editModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.materi_ekstra = ''
      this.feedback.materi_ekstra = ''
      this.state.materi_ekstra = null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/nilai/update-materi', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.materi_ekstra = (getData.errors.materi_ekstra) ? false : null
          this.feedback.materi_ekstra = (getData.errors.materi_ekstra) ? getData.errors.materi_ekstra.join(', ') : ''
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
</style>