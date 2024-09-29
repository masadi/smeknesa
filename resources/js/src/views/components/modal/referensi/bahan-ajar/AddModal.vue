<template>
  <b-modal v-model="addModalShow" :title="title" size="xl" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Judul" label-for="judul" label-cols-md="3" :invalid-feedback="feedback.judul" :state="state.judul">
              <b-form-input v-model="form.judul" placeholder="Judul"></b-form-input>
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
            <b-form-group label="Jurusan" label-for="jurusan_sp_id" label-cols-md="3" :invalid-feedback="feedback.jurusan_sp_id" :state="state.jurusan_sp_id">
              <v-select id="jurusan_sp_id" v-model="form.jurusan_sp_id" :reduce="nama_jurusan_sp => nama_jurusan_sp.jurusan_sp_id" label="nama_jurusan_sp" :options="data_jurusap_sp" placeholder="== Pilih Jurusan ==" :state="state.jurusan_sp_id" @input=changeJurusan>
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Mata Pelajaran" label-for="pembelajaran_id" label-cols-md="3" :invalid-feedback="feedback.pembelajaran_id" :state="state.pembelajaran_id">
              <b-overlay :show="loading_mapel" rounded opacity="0.6" spinner-variant="secondary">
                <v-select id="pembelajaran_id" v-model="form.pembelajaran_id" :reduce="nama => nama.pembelajaran.pembelajaran_id" label="nama" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==" :state="state.pembelajaran_id">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Deskripsi Bahan Ajar" label-for="deskripsi" label-cols-md="3" :invalid-feedback="feedback.deskripsi" :state="state.deskripsi">
              <b-form-textarea id="textarea" v-model="form.deskripsi" placeholder="Deskripsi singkat..." rows="3" max-rows="6" :state="state.deskripsi"></b-form-textarea>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Gambar Sampul" label-for="gambar" label-cols-md="3" :invalid-feedback="feedback.gambar" :state="state.gambar">
              <b-form-file id="gambar" accept="image/*" :state="state.gambar" v-model="form.gambar" />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Berkas PDF" label-for="file_pdf" label-cols-md="3" :invalid-feedback="feedback.file_pdf" :state="state.file_pdf">
              <b-form-file id="file_pdf" accept="application/pdf" :state="state.file_pdf" v-model="form.file_pdf" />
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
        nilai: 1,
        id: null,
        judul: null,
        jurusan_sp_id: null,
        tingkat: null,
        pembelajaran_id: null,
        deskripsi: null,
        gambar: null,
        file_pdf: null,
      },
      feedback: {
        judul: null,
        jurusan_sp_id: null,
        tingkat: null,
        pembelajaran_id: null,
        deskripsi: null,
        gambar: null,
        file_pdf: null,
      },
      state: {
        judul: null,
        jurusan_sp_id: null,
        tingkat: null,
        pembelajaran_id: null,
        deskripsi: null,
        gambar: null,
        file_pdf: null,
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
      data_jurusap_sp: [],
      data_mapel: [],
    }
  },
  created() {
    this.form.semester_id = this.user.semester.semester_id
    eventBus.$on('add-bahan-ajar', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.title = 'Tambah Data Bahan Ajar'
      if(data){
        this.title = 'Edit Data Bahan Ajar'
        this.form.id = data.id
        this.form.judul = data.judul
        this.form.tingkat = data.tingkat
        this.changeTingkat(this.form.tingkat)
        this.form.jurusan_sp_id = data.jurusan_sp_id
        this.changeJurusan(this.form.jurusan_sp_id)
        this.form.pembelajaran_id = data.pembelajaran_id
        this.form.deskripsi = data.deskripsi
      }
      this.addModalShow = true
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.judul = null
      this.form.jurusan_sp_id = null
      this.form.tingkat = null
      this.form.pembelajaran_id = null
      this.form.deskripsi = null
      this.form.gambar = null
      this.form.file_pdf = null
      this.feedback.tingkat = null
      this.state.judul = null
      this.state.jurusan_sp_id = null
      this.state.tingkat = null
      this.state.pembelajaran_id = null
      this.state.deskripsi = null
      this.state.gambar = null
      this.state.file_pdf = null
      this.feedback.judul = null
      this.feedback.jurusan_sp_id = null
      this.feedback.tingkat = null
      this.feedback.pembelajaran_id = null
      this.feedback.deskripsi = null
      this.feedback.gambar = null
      this.feedback.file_pdf = null
    },
    changeTingkat(val){
      this.form.jurusan_sp_id = null
      if(val){
        this.$http.post('/referensi/get-jurusan', this.form).then(res => {
            this.data_jurusap_sp = res.data
          }).catch(error => {
            console.log(error);
          })
      }
    },
    changeJurusan(val){
      this.form.pembelajaran_id = null
      if(val){
        this.loading_mapel = true
        this.$http.post('/referensi/get-pembelajaran', this.form).then(response => {
          this.data_mapel = response.data
          this.loading_mapel = false
        }).catch(error => {
          console.log(error);
        })
      }
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
      data.append('judul', (this.form.judul) ? this.form.judul : '');
      data.append('jurusan_sp_id', (this.form.jurusan_sp_id) ? this.form.jurusan_sp_id : '');
      data.append('tingkat', (this.form.tingkat) ? this.form.tingkat : '');
      data.append('pembelajaran_id', (this.form.pembelajaran_id) ? this.form.pembelajaran_id : '');
      data.append('deskripsi', (this.form.deskripsi) ? this.form.deskripsi : '');
      data.append('gambar', (this.form.gambar) ? this.form.gambar : '');
      data.append('file_pdf', (this.form.file_pdf) ? this.form.file_pdf : '');
      this.$http.post('/referensi/add-bahan-ajar', data).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.judul = (getData.errors.judul) ? false : null
          this.state.jurusan_sp_id = (getData.errors.jurusan_sp_id) ? false : null
          this.state.tingkat = (getData.errors.tingkat) ? false : null
          this.state.pembelajaran_id = (getData.errors.pembelajaran_id) ? false : null
          this.state.deskripsi = (getData.errors.deskripsi) ? false : null
          this.state.gambar = (getData.errors.gambar) ? false : null
          this.state.file_pdf = (getData.errors.file_pdf) ? false : null
          this.feedback.judul = (getData.errors.judul) ? getData.errors.judul.join(', ') : ''
          this.feedback.pembelajaran_id = (getData.errors.pembelajaran_id) ? getData.errors.pembelajaran_id.join(', ') : ''
          this.feedback.jurusan_sp_id = (getData.errors.jurusan_sp_id) ? getData.errors.jurusan_sp_id.join(', ') : ''
          this.feedback.tingkat = (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : ''
          this.feedback.pembelajaran_id = (getData.errors.pembelajaran_id) ? getData.errors.pembelajaran_id.join(', ') : ''
          this.feedback.deskripsi = (getData.errors.deskripsi) ? getData.errors.deskripsi.join(', ') : ''
          this.feedback.gambar = (getData.errors.gambar) ? getData.errors.gambar.join(', ') : ''
          this.feedback.file_pdf = (getData.errors.file_pdf) ? getData.errors.file_pdf.join(', ') : ''
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