<template>
  <b-modal size="xl" v-model="detilModalShow" title="Tambah Materi Ekstra" @hidden="hideModal" @ok="handleOk" scrollable>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Kelas Ekskul" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="feedback.rombongan_belajar_id" :state="state.rombongan_belajar_id">
              <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Kelas Ekskul ==" :state="state.rombongan_belajar_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group> 
          </b-col>
          <template v-for="tp in 5">
            <b-col cols="12">
              <b-form-group :label="`Materi Ekstra (${tp})`" :label-for="`materi-ekstra${tp}`" label-cols-md="3" :invalid-feedback="feedback.materi_ekstra[tp]" :state="state.materi_ekstra[tp]">
                <b-form-textarea :id="`materi-ekstra${tp}`" v-model="form.materi_ekstra[tp]" :state="state.materi_ekstra[tp]" :placeholder="`Materi Ekstra (${tp})`" rows="3" max-rows="8"></b-form-textarea>
              </b-form-group>  
            </b-col>
          </template>
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
import { BOverlay, BForm, BFormGroup, BRow, BCol, BFormTextarea, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay,
    BForm,
    BFormGroup,
    BRow,
    BCol,
    BFormTextarea,
    BButton,
    vSelect,
  },
  data() {
    return {
      detilModalShow: false,
      loading_form: false,
      data_rombel: [],
      form: {
        materi_ekstra: []
      },
      feedback: {
        materi_ekstra: {}
      },
      state: {
        materi_ekstra: {}
      },
    }
  },
  created() {
    this.resetTp()
    eventBus.$on('open-modal-add-materi-ekstra', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.$http.post('/nilai/get-ekskul', data).then(response => {
        let getData = response.data
        this.data_rombel = getData.data
        this.detilModalShow = true
      });
    },
    hideModal(){
      this.detilModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.data_pembelajaran = []
      this.form = {
        materi_ekstra: []
      }
      this.feedback = {
        materi_ekstra: {}
      }
      this.state = {
        materi_ekstra: {}
      }
    },
    resetTp(){
      for (let i = 1; i < 6; i++) {
        this.feedback.materi_ekstra[i] = ''
        this.state.materi_ekstra[i] = null
        this.form.materi_ekstra[i] = ''
      }
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.$http.post('/nilai/add-materi-ekstra', {
        rombongan_belajar_id: this.form.rombongan_belajar_id,
        materi_ekstra: JSON.stringify(this.form.materi_ekstra)
      }).then(response => {
        let getData = response.data
        if(getData.errors){
          console.log(getData.errors);
          /*
          this.state.nama = (getData.errors.nama) ? false : null
          this.state.guru_id = (getData.errors.guru_id) ? false : null
          this.state.status = (getData.errors.status) ? false : null
          this.feedback.nama = (getData.errors.nama) ? getData.errors.nama.join(', ') : ''
          this.feedback.guru_id = (getData.errors.guru_id) ? getData.errors.guru_id.join(', ') : ''
          this.feedback.status = (getData.errors.status) ? getData.errors.status.join(', ') : ''
          */
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.$emit('reload')
            this.hideModal()
          })
        }
      });
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
.modal-fullscreen .modal-dialog {
    max-width: 100%;
    margin: 0;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100vh;
    display: flex;
    position: fixed;
    z-index: 100000;
}
</style>