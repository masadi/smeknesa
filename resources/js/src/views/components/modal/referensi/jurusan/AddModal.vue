<template>
  <b-modal v-model="addModalShow" title="Tambah Data Jurusan" size="xl" @show="resetForm" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Nama Jurusan" label-for="nama_jurusan_sp" label-cols-md="3" :invalid-feedback="feedback.nama_jurusan_sp" :state="state.nama_jurusan_sp">
              <b-form-input id="nama_jurusan_sp" v-model="form.nama_jurusan_sp" :state="state.nama_jurusan_sp" placeholder="Nama Jurusan"></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Nama Singkatan" label-for="alias" label-cols-md="3" :invalid-feedback="feedback.alias" :state="state.alias">
              <b-form-input id="alias" v-model="form.alias" placeholder="Nama Singkatan"></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Ketua Jurusan" label-for="guru_id" label-cols-md="3" :invalid-feedback="feedback.guru_id" :state="state.guru_id">
              <v-select id="guru_id" v-model="form.guru_id" :reduce="nama => nama.guru_id" label="nama" :options="data_guru" placeholder="== Pilih Ketua Jurusan ==" :state="state.guru_id">
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
        nama_jurusan_sp: '',
        alias: '',
        guru_id: '',
      },
      feedback: {
        nama_jurusan_sp: '',
        alias: '',
        guru_id: '',
      },
      state: {
        nama_jurusan_sp: null,
        alias: null,
        guru_id: null,
      },
      data_guru: [],
    }
  },
  created() {
    this.form.data = 'jurusan'
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-add-jurusan', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.$http.get('/referensi/get-guru').then(response => {
        let getData = response.data
        this.data_guru = getData.data.guru
        this.addModalShow = true
      })
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.nama_jurusan_sp = ''
      this.form.alias = ''
      this.form.guru_id = ''
      this.feedback.nama_jurusan_sp = ''
      this.state.nama_jurusan_sp = null
      this.feedback.alias = ''
      this.state.alias = null
      this.feedback.guru_id = ''
      this.state.guru_id = null
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
          this.state.nama_jurusan_sp = (getData.errors.nama_jurusan_sp) ? false : null
          this.state.alias = (getData.errors.alias) ? false : null
          this.state.guru_id = (getData.errors.guru_id) ? false : null
          this.feedback.nama_jurusan_sp = (getData.errors.nama_jurusan_sp) ? getData.errors.nama_jurusan_sp.join(', ') : ''
          this.feedback.alias = (getData.errors.alias) ? getData.errors.alias.join(', ') : ''
          this.feedback.guru_id = (getData.errors.guru_id) ? getData.errors.guru_id.join(', ') : ''
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