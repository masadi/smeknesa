<template>
  <b-modal v-model="editModalShow" title="Edit Data Mata Pelajaran" size="lg" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Nama Ekstrakurikuler" label-for="nama" label-cols-md="3" :invalid-feedback="feedback.nama" :state="state.nama">
              <b-form-input id="nama" v-model="form.nama" :state="state.nama" placeholder="Nama Ekstrakurikuler"></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Status" label-for="status" label-cols-md="3" :invalid-feedback="feedback.status" :state="state.status">
              <v-select id="status" v-model="form.status" :options="['Wajib', 'Pilihan']" placeholder="== Pilih Status ==" :state="state.status" :searchable="false">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Pembina" label-for="guru_id" label-cols-md="3" :invalid-feedback="feedback.guru_id" :state="state.guru_id">
              <v-select id="guru_id" v-model="form.guru_id" :reduce="nama => nama.guru_id" label="nama" :options="data_guru" placeholder="== Pilih Pembina ==" :state="state.guru_id">
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
        nama: '',
        tingkat: 0,
        guru_id: '',
        status: '',
      },
      feedback: {
        nama: '',
        guru_id: '',
        status: '',
      },
      state: {
        nama: null,
        guru_id: null,
        status: null,
      },
      data_guru: [],
    }
  },
  created() {
    /*this.form.edit = true
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama*/
    eventBus.$on('open-modal-edit-ekskul', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.getData('ekskul', data.rombongan_belajar_id)
      this.getData('guru')
    },
    getData(data, id){
      var params;
      var end_point;
      if(id){
        params = {
          id: id,
          data: data,
        }
        end_point = '/referensi/detil-data'
      } else {
        params = {
          data: data,
        }
        end_point = '/referensi/get-data'
      }
      this.$http.post(end_point, params).then(response => {
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
          if(data == 'ekskul'){
            this.form = getData
            this.form.data = 'ekskul'
            //this.data_jurusan = getData.jurusan
            this.editModalShow = true
          }
          if(data == 'guru'){
            this.data_guru = getData
          }
          if(data == 'jurusan'){
            this.data_jurusan = getData
          }
          if(data == 'kurikulum'){
            this.data_kurikulum = getData
          }
        }
      })
    },
    hideModal(){
      this.editModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.nama = ''
      this.form.guru_id = ''
      this.form.status = ''
      this.feedback.nama = ''
      this.feedback.guru_id = ''
      this.feedback.status = ''
      this.state.nama = null
      this.state.guru_id = null
      this.state.status = null
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
          this.state.guru_id = (getData.errors.guru_id) ? false : null
          this.state.status = (getData.errors.status) ? false : null
          this.feedback.nama = (getData.errors.nama) ? getData.errors.nama.join(', ') : ''
          this.feedback.guru_id = (getData.errors.guru_id) ? getData.errors.guru_id.join(', ') : ''
          this.feedback.status = (getData.errors.status) ? getData.errors.status.join(', ') : ''
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