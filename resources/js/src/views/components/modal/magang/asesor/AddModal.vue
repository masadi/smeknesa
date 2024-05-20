<template>
  <b-modal v-model="addModalShow" title="Tambah Data Asesor" size="lg" @show="resetForm" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Nama Asesor" label-for="nama" label-cols-md="3" :invalid-feedback="feedback.nama" :state="state.nama">
              <b-form-input id="nama" v-model="form.nama" :state="state.nama" placeholder="Nama Asesor"></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="DUDI" label-for="dudi_id" label-cols-md="3" :invalid-feedback="feedback.dudi_id" :state="state.dudi_id">
              <v-select id="dudi_id" v-model="form.dudi_id" :reduce="nama => nama.dudi_id" label="nama" :options="data_dudi" placeholder="== Pilih DUDI ==" :state="state.dudi_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
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
import { BOverlay, BForm, BFormGroup, BFormInput, BRow, BCol, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BButton,
    vSelect,
  },
  data() {
    return {
      addModalShow: false,
      loading_form: false,
      form: {
        nama: '',
        dudi_id: '',
      },
      feedback: {
        nama: '',
        dudi_id: '',
      },
      state: {
        nama: null,
        dudi_id: null,
      },
      data_dudi: [],
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-add-asesor', this.handleEvent);
  },
  methods: {
    handleEvent(){
      if(this.data_dudi.length){
        this.addModalShow = true
      } else {
        this.$http.get('/magang/asesor/dudi').then(response => {
          this.data_dudi = response.data
          this.addModalShow = true
        })
      }
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.nama = ''
      this.form.dudi_id = ''
      this.feedback.nama = ''
      this.feedback.dudi_id = ''
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/magang/asesor/store', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.nama = (getData.errors.nama) ? false : null
          this.state.dudi_id = (getData.errors.dudi_id) ? false : null
          this.feedback.nama = (getData.errors.nama) ? getData.errors.nama.join(', ') : ''
          this.feedback.dudi_id = (getData.errors.dudi_id) ? getData.errors.dudi_id.join(', ') : ''
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            html: getData.text,
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