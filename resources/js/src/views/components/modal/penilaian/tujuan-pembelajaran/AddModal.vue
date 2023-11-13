<template>
  <b-modal modal-class="modal-fullscreen" v-model="addModalShow" title="Tambah Data Tujuan Pembelajaran" @hidden="hideModal" @ok="handleOk" scrollable>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Mata Pelajaran" label-for="mata_pelajaran_id" label-cols-md="3" :invalid-feedback="feedback.mata_pelajaran_id" :state="state.mata_pelajaran_id">
              <v-select id="mata_pelajaran_id" v-model="form.mata_pelajaran_id" :reduce="nama => nama.mata_pelajaran_id" label="nama" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==" :state="state.mata_pelajaran_id" @input="changeMapel">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Capaian Pembelajaran" label-for="cp_id" label-cols-md="3" :invalid-feedback="feedback.cp_id" :state="state.cp_id">
              <b-overlay :show="loading_cp" rounded opacity="0.6" spinner-variant="secondary">
                <v-select id="cp_id" v-model="form.cp_id" :reduce="deskripsi => deskripsi.cp_id" label="deskripsi" :options="data_cp" placeholder="== Pilih Capaian Pembelajaran ==" :state="state.cp_id" @input="changeCp">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <template v-for="tp in [0,1,2,3,4]">
            <b-col cols="12">
              <b-form-group :label="`Tujuan Pembelajaran (${tp + 1})`" :label-for="`desk-tp-${tp}`" label-cols-md="3" :invalid-feedback="feedback.desk_tp[tp]" :state="state.desk_tp[tp]">
                <b-form-textarea :id="`desk-tp-${tp}`" v-model="form.desk_tp[tp]" :state="state.desk_tp[tp]" :placeholder="`Deskripsi Tujuan Pembelajaran (${tp + 1})`" rows="3" max-rows="8"></b-form-textarea>
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
      loading_cp: false,
      data_mapel: [],
      data_cp: [],
      form: {
        mata_pelajaran_id: '',
        cp_id: '',
        desk_tp: []
      },
      feedback: {
        mata_pelajaran_id: '',
        cp_id: '',
        desk_tp: {}
      },
      state: {
        mata_pelajaran_id: null,
        cp_id: null,
        desk_tp: {}
      },
    }
  },
  created() {
    this.resetTp()
    this.form.guru_id = this.user.guru_id
    this.form.semester_id = this.user.semester.semester_id
    eventBus.$on('open-modal-add-tp', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      console.log(data);
      this.form.data = data
      this.data_mapel = []
      eventBus.$emit('loading', true);
      this.$http.post('/referensi/get-group-mapel', this.form).then(response => {
        eventBus.$emit('loading', false);
        this.data_mapel = response.data
        this.addModalShow = true
      });
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.desk_tp = []
      this.feedback.desk_tp = {}
      this.state.desk_tp = {}
    },
    resetTp(){
      for (let i = 1; i < 5; i++) {
        this.feedback.desk_tp[i] = ''
        this.state.desk_tp[i] = null
        this.form.desk_tp[i] = ''
      }
    },
    changeMapel(val){
      this.data_cp = []
      this.form.cp_id = ''
      if(val){
        this.loading_cp = true
        this.$http.post('/referensi/get-cp', this.form).then(response => {
          this.data_cp = response.data
          this.loading_cp = false
        });
      }
    },
    changeCp(val){},
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      //this.form.desk_tp = JSON.stringify(this.form.desk_tp)
      let data = new FormData();
      data.append('mata_pelajaran_id', this.form.mata_pelajaran_id);
      data.append('cp_id', this.form.cp_id);
      data.append('desk_tp', JSON.stringify(this.form.desk_tp));
      this.$http.post('/nilai/add-cp', data).then(response => {
        let getData = response.data
        if(getData.errors){
          console.log(getData.errors);
          this.state.mata_pelajaran_id = (getData.errors.mata_pelajaran_id) ? false : null
          this.feedback.mata_pelajaran_id = (getData.errors.mata_pelajaran_id) ? getData.errors.mata_pelajaran_id.join(', ') : ''
          this.state.cp_id = (getData.errors.cp_id) ? false : null
          this.feedback.cp_id = (getData.errors.cp_id) ? getData.errors.cp_id.join(', ') : ''
          for (let i = 0; i < 5; i++) {
            this.feedback.desk_tp[i] = (getData.errors['desk_tp.'+i]) ? getData.errors['desk_tp.'+i].join(', ') : ''
            this.state.desk_tp[i] = (getData.errors['desk_tp.'+i]) ? false : null
          }
          //this.state.desk_tp = (getData.errors.mata_pelajaran_id) ? false : null
          //this.feedback.mata_pelajaran_id = (getData.errors.mata_pelajaran_id) ? getData.errors.mata_pelajaran_id.join(', ') : ''
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