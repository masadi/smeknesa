<template>
  <b-modal v-model="editModalShow" title="Edit Kelas BK" size="lg" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Nama Guru BK" label-for="nama_guru" label-cols-md="3">
              <b-form-input id="nama_guru" v-model="form.nama_guru" disabled></b-form-input>
            </b-form-group>   
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kelas" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="feedback.rombongan_belajar_id" :state="state.rombongan_belajar_id">
              <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Kelas ==" :state="state.rombongan_belajar_id" multiple>
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
      editModalShow: false,
      loading_form: false,
      loading_rombel: false,
      form: {
        guru_id: '',
        nama_guru: '',
        rombongan_belajar_id: '',
      },
      feedback: {
        rombongan_belajar_id: '',
      },
      state: {
        rombongan_belajar_id: null,
      },
      data_rombel: [],
    }
  },
  created() {
    this.form.data = 'edit-bk'
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-edit-bk', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      console.log(data);
      this.form.guru_id = data.guru_id
      this.form.nama_guru = data.nama
      this.getKelasBk()
    },
    getKelasBk(){
      this.$http.post('/referensi/get-rombel', this.form).then(response => {
        this.loading_rombel = false
        this.data_rombel = response.data
        var temp = []
        this.data_rombel.forEach(element => {
          temp.push(element.rombongan_belajar_id)
        });
        this.form.rombongan_belajar_id = temp
        this.editModalShow = true
      })
    },
    hideModal(){
      this.editModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.guru_id = ''
      this.form.nama_guru = ''
      this.form.rombongan_belajar_id = ''
      this.feedback.rombongan_belajar_id = ''
      this.state.rombongan_belajar_id = null
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
          this.state.rombongan_belajar_id = (getData.errors.rombongan_belajar_id) ? false : null
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
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>