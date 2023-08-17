<template>
  <b-modal modal-class="modal-fullscreen" v-model="addModalShow" title="Tambah Data CP & TP" @hidden="hideModal" @ok="handleOk" scrollable>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Mata Pelajaran" label-for="mata_pelajaran_id" label-cols-md="3" :invalid-feedback="feedback.mata_pelajaran_id" :state="state.mata_pelajaran_id">
              <v-select id="mata_pelajaran_id" v-model="form.mata_pelajaran_id" :reduce="nama_mata_pelajaran => nama_mata_pelajaran.mata_pelajaran_id" label="nama_mata_pelajaran" :options="data_pembelajaran" placeholder="== Pilih Mata Pelajaran ==" :state="state.mata_pelajaran_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group> 
          </b-col>
          <b-col cols="12">
            <b-form-group label="Capaian Pembelajaran" label-for="desk_cp" label-cols-md="3" class="form-success" :invalid-feedback="feedback.desk_cp" :state="state.desk_cp">
              <b-form-textarea id="desk_cp"  class="form-success" v-model="form.desk_cp" :state="state.desk_cp" placeholder="Deskripsi Capaian Pembelajaran" rows="3" max-rows="8"></b-form-textarea>
            </b-form-group>  
          </b-col>
          <template v-for="tp in 5">
            <b-col cols="12">
              <b-form-group :label="`Tujuan Pembelajaran (${tp})`" :label-for="`desk-tp-${tp}`" label-cols-md="3" class="form-warning" :invalid-feedback="feedback.desk_tp[tp]" :state="state.desk_tp[tp]">
                <b-form-textarea :id="`desk-tp-${tp}`" class="form-warning" v-model="form.desk_tp[tp]" :state="state.desk_tp[tp]" :placeholder="`Deskripsi Tujuan Pembelajaran (${tp})`" rows="3" max-rows="8"></b-form-textarea>
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
      addModalShow: false,
      loading_form: false,
      data_pembelajaran: [],
      form: {
        desk_tp: []
      },
      feedback: {
        desk_tp: {}
      },
      state: {
        desk_tp: {}
      },
    }
  },
  created() {
    this.resetTp()
    eventBus.$on('open-modal-add-cp', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.$http.post('/referensi/get-group-mapel').then(response => {
        this.data_pembelajaran = response.data
        this.addModalShow = true
      });
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.data_pembelajaran = []
      this.form = {
        desk_tp: []
      }
      this.feedback = {
        desk_tp: {}
      }
      this.state = {
        desk_tp: {}
      }
    },
    resetTp(){
      for (let i = 1; i < 6; i++) {
        this.feedback.desk_tp[i] = ''
        this.state.desk_tp[i] = null
        this.form.desk_tp[i] = ''
      }
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.$http.post('/nilai/add-cp', {
        mata_pelajaran_id: this.form.mata_pelajaran_id,
        desk_cp: this.form.desk_cp,
        desk_tp: JSON.stringify(this.form.desk_tp)
      }).then(response => {
        let getData = response.data
        if(getData.errors){
          console.log(getData.errors);
          //
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