<template>
  <b-modal v-model="addModalShow" title="Input Absensi Magang" size="xl" @show="resetForm" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Judul Magang" label-for="pkl_id" label-cols-md="3" :invalid-feedback="feedback.pkl_id" :state="state.pkl_id">
              <v-select id="pkl_id" v-model="form.pkl_id" :reduce="nama => nama.pkl_id" label="nama" :options="data_pkl" placeholder="== Pilih Judul Magang ==" :state="state.pkl_id" @input="changePkl">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12" v-if="loading_table" class="text-center">
            <b-overlay :show="loading_table" rounded opacity="0.6" spinner-small spinner-variant="danger" class="d-inline-block"></b-overlay>
          </b-col>
          <b-col cols="12" v-if="data_siswa.length">
            <b-table-simple bordered striped>
              <b-thead>
                <b-tr>
                  <b-th class="text-center">No</b-th>
                  <b-th class="text-center">Nama</b-th>
                  <b-th class="text-center">NISN</b-th>
                  <b-th class="text-center">Sakit</b-th>
                  <b-th class="text-center">Izin</b-th>
                  <b-th class="text-center">Alpa</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <template v-if="data_siswa.length">
                  <template v-for="(siswa, index) in data_siswa">
                    <b-tr>
                      <b-td class="text-center">{{ index + 1 }}</b-td>
                      <b-td>{{ siswa.nama }}</b-td>
                      <b-td class="text-center">{{ siswa.nisn }}</b-td>
                      <b-td>
                        <b-form-input v-model="form.sakit[siswa.peserta_didik_id]" placeholder="0"></b-form-input>
                      </b-td>
                      <b-td>
                        <b-form-input v-model="form.izin[siswa.peserta_didik_id]" placeholder="0"></b-form-input>
                      </b-td>
                      <b-td>
                        <b-form-input v-model="form.alpa[siswa.peserta_didik_id]" placeholder="0"></b-form-input>
                      </b-td>
                    </b-tr>
                  </template>
                </template>
                <template v-else>
                  <b-tr>
                    <b-td class="text-center" colspan="6">Tidak ada data untuk ditampilkan</b-td>
                  </b-tr>
                </template>
              </b-tbody>
            </b-table-simple>
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
import { BOverlay, BForm, BFormGroup, BFormInput, BRow, BCol, BButton, BFormTextarea, BTableSimple, BThead, BTbody, BTr, BTh, BTd, BFormCheckbox, BLink } from 'bootstrap-vue'
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
    BFormTextarea,
    BTableSimple, BThead, BTbody, BTr, BTh, BTd,
    BFormCheckbox,
    BLink,
    vSelect,
  },
  data() {
    return {
      addModalShow: false,
      loading_form: false,
      loading_table: false,
      data_pkl: [],
      data_siswa: [],
      form: {
        data: 'absensi',
        pkl_id: '',
        sakit: {},
        izin: {},
        alpa: {},
      },
      feedback: {
        pkl_id: '',
      },
      state: {
        pkl_id: null,
      },
    }
  },
  created() {
    this.form.guru_id = this.user.guru_id
    this.form.semester_id = this.user.semester.semester_id
    eventBus.$on('open-modal-add-absensi-pkl', this.handleEvent);
  },
  methods: {
    handleEvent(){
      eventBus.$emit('loading', true);
      this.getPkl()
    },
    getPkl(){
      this.$http.post('/magang/get-pkl', this.form).then(response => {
        let getData = response.data
        this.addModalShow = true
        this.data_pkl = getData
        eventBus.$emit('loading', false);
      })
    },
    changePkl(val){
      this.data_siswa = []
      if(val){
        this.loading_table = true
        this.$http.post('/magang/get-pd-pkl', this.form).then(response => {
          let getData = response.data
          this.data_siswa = getData.data_siswa
          var _this = this
          this.data_siswa.forEach(siswa => {
            _this.form.sakit[siswa.peserta_didik_id] = (siswa.absensi_pkl) ? siswa.absensi_pkl.sakit : 0
            _this.form.izin[siswa.peserta_didik_id] = (siswa.absensi_pkl) ? siswa.absensi_pkl.izin : 0
            _this.form.alpa[siswa.peserta_didik_id] = (siswa.absensi_pkl) ? siswa.absensi_pkl.alpa : 0
          });
          this.loading_table = false
        })
      }
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.data_siswa = []
      this.form.pkl_id = ''
      this.form.sakit = {}
      this.form.izin = {}
      this.form.alpa = {}
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/magang/simpan-absensi', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.pkl_id = (getData.errors.pkl_id) ? false : null
          this.feedback.pkl_id = (getData.errors.pkl_id) ? getData.errors.pkl_id.join(', ') : ''
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
            if(getData.success){
              this.hideModal()
              this.$emit('reload')
            }
          })
        }
      }).catch(error => {
        console.log(error);
      })
    },
    onContext(ctx) {
      this.formatted = ctx.selectedFormatted
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>