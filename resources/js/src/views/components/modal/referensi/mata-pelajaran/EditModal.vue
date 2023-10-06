<template>
  <b-modal v-model="editModalShow" title="Edit Data Mata Pelajaran" size="lg" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Nama Mata Pelajaran" label-for="nama" label-cols-md="3" :invalid-feedback="feedback.nama" :state="state.nama">
              <b-form-input id="nama" v-model="form.nama" :state="state.nama" placeholder="Nama Mata Pelajaran"></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Jurusan" label-for="jurusan_sp_id" label-cols-md="3" :invalid-feedback="feedback.jurusan_sp_id" :state="state.jurusan_sp_id">
              <v-select id="jurusan_sp_id" v-model="form.jurusan_sp_id" :reduce="alias => alias.jurusan_sp_id" label="alias" :options="data_jurusan" placeholder="== Pilih Jurusan ==" :state="state.jurusan_sp_id" multiple>
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="feedback.tingkat" :state="state.tingkat">
              <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" :state="state.tingkat" multiple>
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Jenis Mapel" label-for="jenis" label-cols-md="3" :invalid-feedback="feedback.jenis" :state="state.jenis">
              <v-select id="jenis" v-model="form.jenis" :options="['Umum', 'P5', 'PKL']" placeholder="== Pilih Jenis Mapel ==" :state="state.jenis"></v-select>
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
      form: {},
      feedback: {},
      state: {},
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
    }
  },
  created() {
    /*this.form.edit = true
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama*/
    eventBus.$on('open-modal-edit-mapel', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.$http.post('/referensi/detil-data', {
        id: data.mata_pelajaran_id,
        data: 'mapel',
      }).then(response => {
        var getData = response.data
        if(getData.errors){
          this.$swal({
            icon: 'error',
            title: 'Error',
            text: getData.errors,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
        } else {
          this.form = getData.mapel
          this.form.jurusan_sp_id = this.getJurusan(getData.mapel.mapel_tingkat);
          this.form.data = 'mapel'
          this.data_jurusan = getData.jurusan
          console.log(response.data);
          this.editModalShow = true
        }
      })
    },
    hideModal(){
      this.editModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.nama = ''
      this.form.jurusan_sp_id = []
      this.form.tingkat = []
      this.form.jenis = ''
      this.feedback.nama = ''
      this.state.nama = null
      this.feedback.jurusan_sp_id = ''
      this.state.jurusan_sp_id = null
      this.feedback.tingkat = ''
      this.state.tingkat = null
      this.feedback.jenis = ''
      this.state.jenis = null
    },
    uniqueChars(arr){
      return [...new Set(arr)];
    },
    getJurusan(array){
      var set_data_jurusan = [];
      array.forEach(element => {
        set_data_jurusan.push(element.jurusan_sp.jurusan_sp_id)
      });
      return this.uniqueChars(set_data_jurusan)
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
          this.state.jurusan_sp_id = (getData.errors.jurusan_sp_id) ? false : null
          this.state.tingkat = (getData.errors.tingkat) ? false : null
          this.state.jenis = (getData.errors.jenis) ? false : null
          this.feedback.nama = (getData.errors.nama) ? getData.errors.nama.join(', ') : ''
          this.feedback.jurusan_sp_id = (getData.errors.jurusan_sp_id) ? getData.errors.jurusan_sp_id.join(', ') : ''
          this.feedback.tingkat = (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : ''
          this.feedback.jenis = (getData.errors.jenis) ? getData.errors.jenis.join(', ') : ''
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