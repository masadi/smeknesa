<template>
  <b-modal v-model="addModalShow" :title="title" size="xl" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Judul" label-for="judul" label-cols-md="3" :invalid-feedback="feedback.judul" :state="state.link_youtube">
              <b-form-input v-model="form.judul" placeholder="Judul"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Deskripsi" label-for="deskripsi" label-cols-md="3" :invalid-feedback="feedback.deskripsi" :state="state.deskripsi">
              <b-form-textarea id="textarea" v-model="form.deskripsi" placeholder="Deskripsi singkat..." rows="3" max-rows="6" :state="state.deskripsi"></b-form-textarea>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="URL Youtube" label-for="link_youtube" label-cols-md="3" :invalid-feedback="feedback.link_youtube" :state="state.link_youtube">
              <b-form-input v-model="form.link_youtube" placeholder="link_youtube"></b-form-input>
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
        judul: null,
        deskripsi: null,
        link_youtube: null,
      },
      feedback: {
        judul: null,
        deskripsi: null,
        link_youtube: null,
      },
      state: {
        judul: null,
        deskripsi: null,
        link_youtube: null,
      },
    }
  },
  created() {
    this.form.semester_id = this.user.semester.semester_id
    eventBus.$on('add-video', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.title = 'Tambah Video'
      if(data){
        this.title = 'Edit Video'
        this.form.id = data.id
        this.form.link_youtube = data.link_youtube
        this.form.judul = data.judul
        this.form.deskripsi = data.deskripsi
      }
      this.addModalShow = true
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.id = null
      this.form.judul = null
      this.form.deskripsi = null
      this.form.link_youtube = null
      this.state.judul = null
      this.state.deskripsi = null
      this.state.link_youtube = null
      this.feedback.judul = null
      this.feedback.deskripsi = null
      this.feedback.link_youtube = null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/referensi/add-video', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.judul = (getData.errors.judul) ? false : null
          this.state.deskripsi = (getData.errors.deskripsi) ? false : null
          this.state.link_youtube = (getData.errors.link_youtube) ? false : null
          this.feedback.judul = (getData.errors.judul) ? getData.errors.judul.join(', ') : ''
          this.feedback.deskripsi = (getData.errors.deskripsi) ? getData.errors.deskripsi.join(', ') : ''
          this.feedback.link_youtube = (getData.errors.link_youtube) ? getData.errors.link_youtube.join(', ') : ''
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