<template>
  <b-modal v-model="addModalShow" title="Tambah Data Kelompok Mapel" size="lg" @show="resetForm" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Nama Kelompok Mapel" label-for="nama_kelompok" label-cols-md="3" :invalid-feedback="feedback.nama_kelompok" :state="state.nama_kelompok">
              <b-form-input id="nama_kelompok" v-model="form.nama_kelompok" :state="state.nama_kelompok" placeholder="Nama Kelompok Mapel"></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kurikulum" label-for="kurikulum_id" label-cols-md="3">
              <v-select id="kurikulum_id" v-model="form.kurikulum_id" :reduce="nama => nama.kurikulum_id" label="nama" :options="data_kurikulum" placeholder="== Pilih Kurikulum ==">
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
        nama_kelompok: '',
        kurikulum_id: '',
      },
      feedback: {
        nama_kelompok: '',
      },
      state: {
        nama_kelompok: null,
      },
      data_kurikulum: [],
    }
  },
  created() {
    this.form.data = 'kelompok'
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-add-kelompok', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.$http.post('/referensi/get-data', {
        data: 'kurikulum'
      }).then(response => {
        this.data_kurikulum = response.data
        eventBus.$emit('loading', false)
        this.addModalShow = true
      })
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.nama_kelompok = ''
      this.form.kurikulum_id = ''
      this.feedback.nama_kelompok = ''
      this.state.nama_kelompok = null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/referensi/add-data', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.nama_kelompok = (getData.errors.nama_kelompok) ? false : null
          this.feedback.nama_kelompok = (getData.errors.nama_kelompok) ? getData.errors.nama_kelompok.join(', ') : ''
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            html: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
            allowOutsideClick: false,
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