<template>
  <b-modal v-model="editModalShow" title="Edit Data DUDI" size="lg" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Nama DUDI" label-for="nama" label-cols-md="3" :invalid-feedback="feedback.nama" :state="state.nama">
              <b-form-input id="nama" v-model="form.nama" :state="state.nama" placeholder="Nama DUDI"></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Nama Pimpinan" label-for="pimpinan" label-cols-md="3" :invalid-feedback="feedback.pimpinan" :state="state.pimpinan">
              <b-form-input id="pimpinan" v-model="form.pimpinan" :state="state.pimpinan" placeholder="Nama Pimpinan DUDI"></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="NIP Pimpinan" label-for="nip_pimpinan" label-cols-md="3" :invalid-feedback="feedback.nip_pimpinan" :state="state.nip_pimpinan">
              <b-form-input id="nip_pimpinan" v-model="form.nip_pimpinan" :state="state.nip_pimpinan" placeholder="NIP Pimpinan"></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Nama Bidang Usaha" label-for="nama_bidang_usaha" label-cols-md="3" :invalid-feedback="feedback.nama_bidang_usaha" :state="state.nama_bidang_usaha">
              <b-form-input id="nama_bidang_usaha" v-model="form.nama_bidang_usaha" :state="state.nama_bidang_usaha" placeholder="Nama Nama Bidang Usaha"></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Alamat" label-for="alamat_jalan" label-cols-md="3" :invalid-feedback="feedback.alamat_jalan" :state="state.alamat_jalan">
              <b-form-input id="alamat_jalan" v-model="form.alamat_jalan" :state="state.alamat_jalan" placeholder="Alamat DUDI"></b-form-input>
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
import { BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay,
    BForm,
    BFormInput,
    BRow,
    BCol,
    BFormGroup,
    BButton,
    vSelect,
  },
  data() {
    return {
      editModalShow: false,
      loading_form: false,
      form: {
        dudi_id: '',
        data: 'dudi',
        nama: '',
        pimpinan: '',
        nip_pimpinan: '',
        nama_bidang_usaha: '',
        alamat_jalan: '',
      },
      feedback: {
        nama: '',
        pimpinan: '',
        nip_pimpinan: '',
        nama_bidang_usaha: '',
        alamat_jalan: ''
      },
      state: {
        nama: null,
        pimpinan: null,
        nip_pimpinan: null,
        nama_bidang_usaha: null,
        alamat_jalan: null,
      },
    }
  },
  created() {
    /*this.form.edit = true
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama*/
    eventBus.$on('open-modal-edit-dudi', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      console.log(data);
      this.form.dudi_id = data.dudi_id
      this.form.nama = data.nama
      this.form.pimpinan = data.pimpinan
      this.form.nip_pimpinan = data.nip_pimpinan
      this.form.nama_bidang_usaha = data.nama_bidang_usaha
      this.form.alamat_jalan = data.alamat_jalan
      this.editModalShow = true
    },
    hideModal(){
      this.editModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.nama = ''
      this.form.pimpinan = ''
      this.form.nip_pimpinan = ''
      this.form.nama_bidang_usaha = ''
      this.form.alamat_jalan = ''
      this.feedback.nama = ''
      this.feedback.pimpinan = ''
      this.feedback.nama_bidang_usaha = ''
      this.feedback.alamat_jalan = ''
      this.state.nama = null
      this.state.pimpinan = null
      this.state.nama_bidang_usaha = null
      this.state.alamat_jalan = null
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
          this.state.nama = (getData.errors.nama) ? false : null
          this.state.pimpinan = (getData.errors.pimpinan) ? false : null
          this.state.nama_bidang_usaha = (getData.errors.nama_bidang_usaha) ? false : null
          this.state.alamat_jalan = (getData.errors.alamat_jalan) ? false : null
          this.feedback.nama = (getData.errors.nama) ? getData.errors.nama.join(', ') : ''
          this.feedback.pimpinan = (getData.errors.pimpinan) ? getData.errors.pimpinan.join(', ') : ''
          this.feedback.nama_bidang_usaha = (getData.errors.nama_bidang_usaha) ? getData.errors.nama_bidang_usaha.join(', ') : ''
          this.feedback.alamat_jalan = (getData.errors.alamat_jalan) ? getData.errors.alamat_jalan.join(', ') : ''
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