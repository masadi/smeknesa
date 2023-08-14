<template>
  <b-modal v-model="addModalShow" title="Tambah Data Kelas" size="lg" @show="resetForm" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Nama Kelas" label-for="nama" label-cols-md="3" :invalid-feedback="feedback.nama" :state="state.nama">
              <b-form-input id="nama" v-model="form.nama" :state="state.nama" placeholder="Nama Kelas"></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="feedback.tingkat" :state="state.tingkat">
              <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" :state="state.tingkat">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Wali Kelas" label-for="guru_id" label-cols-md="3" :invalid-feedback="feedback.guru_id" :state="state.guru_id">
              <v-select id="guru_id" v-model="form.guru_id" :reduce="nama => nama.guru_id" label="nama" :options="data_guru" placeholder="== Pilih Wali Kelas ==" :state="state.guru_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Jurusan" label-for="jurusan_sp_id" label-cols-md="3" :invalid-feedback="feedback.jurusan_sp_id" :state="state.jurusan_sp_id">
              <v-select id="jurusan_sp_id" v-model="form.jurusan_sp_id" :reduce="nama_jurusan_sp => nama_jurusan_sp.jurusan_sp_id" label="nama_jurusan_sp" :options="data_jurusan" placeholder="== Pilih Jurusan ==" :state="state.jurusan_sp_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kurikulum" label-for="kurikulum_id" label-cols-md="3" :invalid-feedback="feedback.kurikulum_id" :state="state.kurikulum_id">
              <v-select id="kurikulum_id" v-model="form.kurikulum_id" :reduce="nama => nama.kurikulum_id" label="nama" :options="data_kurikulum" placeholder="== Pilih Kurikulum ==" :state="state.kurikulum_id">
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
        jurusan_sp_id: [],
        tingkat: [],
      },
      feedback: {
        nama: '',
        jurusan_sp_id: '',
        tingkat: '',
      },
      state: {
        nama: null,
        jurusan_sp_id: null,
        tingkat: null,
      },
      data_tingkat: [
        {
          id: 10,
          nama: 'Tingkat 10',
        },
        {
          id: 11,
          nama: 'Tingkat 11',
        },
        {
          id: 12,
          nama: 'Tingkat 12',
        },
        {
          id: 13,
          nama: 'Tingkat 13',
        }
      ],
      data_jurusan: [],
      data_kurikulum: [],
      data_guru: [],
    }
  },
  created() {
    this.form.data = 'rombel'
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-add-rombel', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.getGuru()
      this.getJurusan()
      this.getKurikulum()
    },
    getGuru(){
      this.$http.get('/referensi/get-guru').then(response => {
        this.data_guru = response.data.data
      })
    },
    getJurusan(){
      this.$http.post('/referensi/get-data', {
        data: 'jurusan'
      }).then(response => {
        this.data_jurusan = response.data
      })
    },
    getKurikulum(){
      this.$http.post('/referensi/get-data', {
        data: 'kurikulum'
      }).then(response => {
        this.data_kurikulum = response.data
        this.addModalShow = true
      })
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.nama = ''
      this.form.tingkat = ''
      this.form.guru_id = ''
      this.form.jurusan_sp_id = ''
      this.form.kurikulum_id = ''
      this.feedback.nama = ''
      this.feedback.tingkat = ''
      this.feedback.guru_id = ''
      this.feedback.jurusan_sp_id = ''
      this.feedback.kurikulum_id = ''
      this.state.nama = null
      this.state.tingkat = null
      this.state.guru_id = null
      this.state.jurusan_sp_id = null
      this.state.kurikulum_id = null
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
          this.state.nama = (getData.errors.nama) ? false : null
          this.state.tingkat = (getData.errors.tingkat) ? false : null
          this.state.guru_id = (getData.errors.guru_id) ? false : null
          this.state.jurusan_sp_id = (getData.errors.jurusan_sp_id) ? false : null
          this.state.kurikulum_id = (getData.errors.kurikulum_id) ? false : null
          this.feedback.nama = (getData.errors.nama) ? getData.errors.nama.join(', ') : ''
          this.feedback.tingkat = (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : ''
          this.feedback.guru_id = (getData.errors.guru_id) ? getData.errors.guru_id.join(', ') : ''
          this.feedback.jurusan_sp_id = (getData.errors.jurusan_sp_id) ? getData.errors.jurusan_sp_id.join(', ') : ''
          this.feedback.kurikulum_id = (getData.errors.kurikulum_id) ? getData.errors.kurikulum_id.join(', ') : ''
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