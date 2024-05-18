<template>
  <b-modal v-model="addModalShow" title="Tambah Data Paket UKK" @show="resetForm" @hidden="hideModal" @ok="handleOk" size="lg">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-button block variant="primary" href="/downloads/template-paket-ukk.xlsx" target="_blank">Download Template Excel</b-button>
          </b-col>
          <b-col cols="12" class="mt-2">
            <b-form-group label="Jurusan" label-for="jurusan_sp_id" label-cols-md="3" :invalid-feedback="feedback.jurusan_sp_id" :state="state.jurusan_sp_id">
              <v-select id="jurusan_sp_id" v-model="form.jurusan_sp_id" :reduce="nama_jurusan_sp => nama_jurusan_sp.jurusan_sp_id" label="nama_jurusan_sp" :options="data_jurusan" placeholder="== Pilih Jurusan ==" :state="state.jurusan_sp_id" @input="getKurikulum">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kurikulum" label-for="kurikulum_id" label-cols-md="3" :invalid-feedback="feedback.kurikulum_id" :state="state.kurikulum_id">
              <v-select id="kurikulum_id" v-model="form.kurikulum_id" :reduce="nama => nama.kurikulum_id" label="nama" :options="data_kurikulum" placeholder="== Pilih Kurikulum ==" :state="state.kurikulum_id" @input="setKurikulum">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
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
import { BOverlay, BForm, BFormGroup, BFormFile, BRow, BCol, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay,
    BForm,
    BFormGroup,
    BFormFile,
    BRow,
    BCol,
    BButton,
    vSelect,
  },
  data() {
    return {
      addModalShow: false,
      loading_form: false,
      file:  null,
      feedback: {
        file: '',
        jurusan_sp_id: '',
        kurikulum_id: '',
      },
      state: {
        file: null,
        jurusan_sp_id: null,
        kurikulum_id: null,
      },
      form: {
        jurusan_sp_id: '',
        kurikulum_id: '',
      },
      data_jurusan: [],
      data_kurikulum: [],
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-add-paket-ukk', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.$http.post('/ukk/get-jurusan').then(response => {
        var getData = response.data
        this.data_jurusan = getData
        this.addModalShow = true
      })
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.file = null
      this.form.jurusan_sp_id = ''
      this.form.kurikulum_id = ''
      this.feedback = {
        file: '',
        jurusan_sp_id: '',
        kurikulum_id: '',
      }
      this.state = {
        file: null,
        jurusan_sp_id: null,
        kurikulum_id: null,
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
      data.append('jurusan_sp_id', (this.form.jurusan_sp_id) ? this.form.jurusan_sp_id : '');
      data.append('kurikulum_id', (this.form.kurikulum_id) ? this.form.kurikulum_id : '');
      data.append('data', 'paket-ukk');
      this.$http.post('/upload', data).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.feedback = {
            file: (getData.errors.file) ? getData.errors.file.join(', ') : '',
            jurusan_sp_id: (getData.errors.jurusan_sp_id) ? getData.errors.jurusan_sp_id.join(', ') : '',
            kurikulum_id: (getData.errors.kurikulum_id) ? getData.errors.kurikulum_id.join(', ') : '',
          }
          this.state = {
            file: (getData.errors.file) ? false : null,
            jurusan_sp_id: (getData.errors.jurusan_sp_id) ? false : null,
            kurikulum_id: (getData.errors.kurikulum_id) ? false : null,
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
    getKurikulum(val){
      this.$http.post('/ukk/get-kurikulum', {
        jurusan_sp_id: val
      }).then(response => {
        var getData = response.data
        this.feedback.jurusan_sp_id = ''
        this.state.jurusan_sp_id = null
        this.data_kurikulum = getData
        console.log(getData);
      })
    },
    setKurikulum(val){
      if(val){
        this.feedback.kurikulum_id = ''
        this.state.kurikulum_id = null
      }
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>