<template>
  <b-modal v-model="addModalShow" title="Tambah Data Capaian Pembelajaran" size="xl" @show="resetForm" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
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
            {{ data_mapel }}
            <b-form-group label="Mata Pelajaran" label-for="mata_pelajaran_id" label-cols-md="3" :invalid-feedback="feedback.mata_pelajaran_id" :state="state.mata_pelajaran_id">
              <b-overlay :show="loading_mapel" rounded opacity="0.6" spinner-variant="secondary">
                <v-select id="mata_pelajaran_id" v-model="form.mata_pelajaran_id" :reduce="nama => nama.mata_pelajaran_id" label="alias" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==" :state="state.mata_pelajaran_id" @input="changeMapel">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Deskripsi Capaian Pembelajaran" label-for="deskripsi" label-cols-md="3" :invalid-feedback="feedback.deskripsi" :state="state.deskripsi">
              <b-form-textarea id="textarea" v-model="form.deskripsi" placeholder="Enter something..." rows="3" max-rows="6" :state="state.deskripsi"></b-form-textarea>
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
import { BOverlay, BForm, BFormGroup, BFormTextarea, BRow, BCol, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay,
    BForm,
    BFormGroup,
    BFormTextarea,
    BRow,
    BCol,
    BButton,
    vSelect,
  },
  data() {
    return {
      addModalShow: false,
      loading_form: false,
      loading_mapel: false,
      form: {
        tingkat: [],
        mata_pelajaran_id: '',
        deskripsi: '',
      },
      feedback: {
        tingkat: '',
        mata_pelajaran_id: '',
        deskripsi: '',
      },
      state: {
        tingkat: null,
        mata_pelajaran_id: null,
        deskripsi: null,
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
      data_mapel: [],
    }
  },
  created() {
    this.form.semester_id = this.user.semester.semester_id
    eventBus.$on('open-modal-add-ref-cp', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.addModalShow = true
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.tingkat = ''
      this.form.mata_pelajaran_id = ''
      this.form.deskripsi = ''
      this.feedback.tingkat = ''
      this.state.tingkat = null
      this.feedback.mata_pelajaran_id = ''
      this.state.mata_pelajaran_id = null
      this.feedback.deskripsi = ''
      this.state.deskripsi = null
    },
    changeTingkat(val){
      this.form.mata_pelajaran_id = ''
      this.form.deskripsi = ''
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
    changeMapel(val){
      this.form.deskripsi = ''
      if(val){

      }
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/referensi/add-cp', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.tingkat = (getData.errors.tingkat) ? false : null
          this.state.mata_pelajaran_id = (getData.errors.mata_pelajaran_id) ? false : null
          this.state.deskripsi = (getData.errors.deskripsi) ? false : null
          this.feedback.mata_pelajaran_id = (getData.errors.mata_pelajaran_id) ? getData.errors.mata_pelajaran_id.join(', ') : ''
          this.feedback.tingkat = (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : ''
          this.feedback.deskripsi = (getData.errors.deskripsi) ? getData.errors.deskripsi.join(', ') : ''
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