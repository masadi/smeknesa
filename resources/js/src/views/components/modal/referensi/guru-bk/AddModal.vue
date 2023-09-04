<template>
  <b-modal v-model="addModalShow" title="Tambah Data Guru BK" size="lg" @show="resetForm" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Guru BK" label-for="guru_id" label-cols-md="3" :invalid-feedback="feedback.guru_id" :state="state.guru_id">
              <v-select id="guru_id" v-model="form.guru_id" :reduce="nama => nama.guru_id" label="nama" :options="data_guru" placeholder="== Pilih Guru BK ==" :state="state.guru_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="feedback.tingkat" :state="state.tingkat">
              <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" :state="state.tingkat" @input="changeTingkat">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group> 
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kelas" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="feedback.rombongan_belajar_id" :state="state.rombongan_belajar_id">
              <b-overlay :show="loading_rombel" opacity="0.6" size="md" spinner-variant="secondary">
                <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Kelas ==" :state="state.rombongan_belajar_id" multiple>
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
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
      loading_rombel: false,
      form: {
        guru_id: '',
        tingkat: '',
        rombongan_belajar_id: '',
      },
      feedback: {
        guru_id: '',
        tingkat: '',
        rombongan_belajar_id: '',
      },
      state: {
        guru_id: null,
        tingkat: null,
        rombongan_belajar_id: null,
      },
      data_guru: [],
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
      data_rombel: [],
    }
  },
  created() {
    this.form.data = 'bk'
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-add-bk', this.handleEvent);
  },
  methods: {
    handleEvent(){
      eventBus.$emit('loading', true);
      this.getGuru()
    },
    getGuru(){
      this.$http.get('/referensi/get-guru').then(response => {
        this.data_guru = response.data.data.guru
        this.addModalShow = true
        eventBus.$emit('loading', false);
      })
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.guru_id = ''
      this.form.tingkat = ''
      this.form.rombongan_belajar_id = ''
      this.feedback.guru_id = ''
      this.feedback.tingkat = ''
      this.feedback.rombongan_belajar_id = ''
      this.state.guru_id = null
      this.state.tingkat = null
      this.state.rombongan_belajar_id = null
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
          this.state.guru_id = (getData.errors.guru_id) ? false : null
          this.state.tingkat = (getData.errors.tingkat) ? false : null
          this.state.rombongan_belajar_id = (getData.errors.rombongan_belajar_id) ? false : null
          this.feedback.guru_id = (getData.errors.guru_id) ? getData.errors.guru_id.join(', ') : ''
          this.feedback.tingkat = (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : ''
          this.feedback.rombongan_belajar_id = (getData.errors.rombongan_belajar_id) ? getData.errors.rombongan_belajar_id.join(', ') : ''
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
    changeTingkat(val){
      this.form.rombongan_belajar_id = ''
      this.data_rombel = []
      if(val){
        this.loading_rombel = true
        this.$http.post('/referensi/get-rombel', this.form).then(response => {
          this.loading_rombel = false
          this.data_rombel = response.data
        })
      }
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>