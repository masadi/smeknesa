<template>
  <b-modal v-model="addModalShow" title="Tambah Data Unit UKK" @hidden="hideModal" @ok="handleOk" size="lg">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12" class="mt-2">
            <b-form-group label="Jurusan" label-cols-md="3">
              <b-form-input v-model="form.jurusan" disabled></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kurikulum" label-cols-md="3">
              <b-form-input v-model="form.kurikulum" disabled></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Nomor Paket" label-cols-md="3">
              <b-form-input v-model="form.nomor_paket" disabled></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Judul Paket" label-cols-md="3">
              <b-form-input v-model="form.nama_paket_id" disabled></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Download Template" label-cols-md="3">
              <b-button block variant="primary" :href="`/export/template-unit-ukk/${form.paket_ukk_id}`" target="_blank">Download Template Excel</b-button>
            </b-form-group>
          </b-col>
          <b-col cols="12" class="mt-2">
            <b-form-group :invalid-feedback="feedback.file" :state="state.file">
              <b-form-file v-model="file" :state="state.file" placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..." @change="onFileChange"></b-form-file>
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
import { BOverlay, BForm, BFormGroup, BFormInput, BFormFile, BRow, BCol, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BOverlay,
    BForm,
    BFormGroup,
    BFormInput,
    BFormFile,
    BRow,
    BCol,
    BButton,
  },
  data() {
    return {
      jmlForm: 5,
      addModalShow: false,
      loading_form: false,
      form: {
        paket_ukk_id: '',
        jurusan: '',
        kurikulum: '',
        nomor_paket: '',
        nama_paket_id: '',
      },
      file:  null,
      feedback: {
        file: '',
      },
      state: {
        file: null,
      },
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-add-unit-paket-ukk', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.$http.post('/ukk/get-paket', {
        paket_ukk_id: data.paket_ukk_id,
      }).then(response => {
        var getData = response.data
        this.form.paket_ukk_id = getData.paket_ukk_id
        this.form.jurusan = getData.jurusan_sp.nama_jurusan_sp
        this.form.kurikulum = getData.kurikulum.nama
        this.form.nomor_paket = getData.nomor_paket
        this.form.nama_paket_id = getData.nama_paket_id
        console.log(this.form);
        this.addModalShow = true
      })
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form = {
        paket_ukk_id: '',
        jurusan: '',
        kurikulum: '',
        nomor_paket: '',
        nama_paket_id: '',
      }
      this.file = null
      this.feedback = {
        file: '',
      }
      this.state = {
        file: null,
      }
    },
    onFileChange(e) {
      this.file = e.target.files[0];
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      const data = new FormData();
      data.append('file', (this.file) ? this.file : '');
      data.append('paket_ukk_id', (this.form.paket_ukk_id) ? this.form.paket_ukk_id : '');
      data.append('data', 'unit-ukk');
      this.$http.post('/upload', data).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.feedback = {
            file: (getData.errors.file) ? getData.errors.file.join(', ') : '',
          }
          this.state = {
            file: (getData.errors.file) ? false : null,
          }
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