<template>
  <b-modal v-model="addModalShow" :title="title" size="xl" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Gambar" label-for="gambar" label-cols-md="3" :invalid-feedback="feedback.gambar" :state="state.gambar">
              <b-form-file id="gambar" accept="image/*" :state="state.gambar" v-model="form.gambar" />
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
import { BOverlay, BForm, BFormGroup, BFormTextarea, BRow, BCol, BButton, BFormFile, BFormInput } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    vSelect,
    BOverlay,
    BForm,
    BFormGroup,
    BFormTextarea,
    BRow,
    BCol,
    BButton,
    BFormFile,
    BFormInput,
  },
  data() {
    return {
      title: '',
      addModalShow: false,
      loading_form: false,
      loading_mapel: false,
      form: {
        id: null,
        gambar: null,
      },
      feedback: {
        gambar: null,
      },
      state: {
        gambar: null,
      },
    }
  },
  created() {
    this.form.semester_id = this.user.semester.semester_id
    eventBus.$on('add-slider', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.title = 'Tambah Data Slider'
      if(data){
        this.title = 'Edit Data Slider'
        this.form.id = data.id
      }
      this.addModalShow = true
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.id = null
      this.form.gambar = null
      this.state.gambar = null
      this.feedback.gambar = null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      console.log(this.form);
      
      this.loading_form = true
      let data = new FormData();
      data.append('id', (this.form.id) ? this.form.id : '');
      data.append('gambar', (this.form.gambar) ? this.form.gambar : '');
      this.$http.post('/referensi/add-slider', data).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.gambar = (getData.errors.gambar) ? false : null
          this.feedback.gambar = (getData.errors.gambar) ? getData.errors.gambar.join(', ') : ''
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