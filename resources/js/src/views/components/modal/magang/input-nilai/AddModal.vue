<template>
  <b-modal v-model="addModalShow" title="Input Nilai Magang" size="xl" @show="resetForm" @hidden="hideModal" @ok="handleOk">
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
            <template v-if="data_tp.length">
              <b-table-simple bordered striped>
                <b-thead>
                  <b-tr>
                    <b-th class="text-center">No</b-th>
                    <b-th class="text-center">Nama</b-th>
                    <b-th class="text-center">NISN</b-th>
                    <b-th class="text-center">Tujuan Pembelajaran</b-th>
                    <b-th class="text-center">Nilai</b-th>
                    <b-th class="text-center">Deskripsi</b-th>
                  </b-tr>
                </b-thead>
                <b-tbody>
                  <template v-if="data_siswa.length">
                    <template v-for="(siswa, index) in data_siswa">
                      <b-tr>
                        <b-td class="text-center" style="vertical-align: top;" :rowspan="data_tp.length + 2">{{ index + 1 }}</b-td>
                        <b-td style="vertical-align: top;" :rowspan="data_tp.length + 2">{{ siswa.nama }}</b-td>
                        <b-td class="text-center" style="vertical-align: top;" :rowspan="data_tp.length + 2">{{ siswa.nisn }}</b-td>
                      </b-tr>
                      <template v-for="(tp, index) in data_tp" v-if="data_tp.length">
                        <b-tr>
                          <b-td>{{ tp.deskripsi }}</b-td>
                          <b-td>
                            <b-form-input v-model="form.peserta_didik_id[tp.tp_id+'#'+siswa.peserta_didik_id]" placeholder="0"></b-form-input>
                          </b-td>
                          <b-td>
                            <b-form-textarea v-model="form.deskripsi[tp.tp_id+'#'+siswa.peserta_didik_id]" placeholder="ketik deskripsi disini..." rows="3" max-rows="6"></b-form-textarea>
                          </b-td>
                        </b-tr>
                      </template>
                      <b-tr>
                        <b-td colspan="3">
                          <b-form-textarea v-model="form.catatan[siswa.peserta_didik_id]" placeholder="ketik catatan magang disini..." rows="3" max-rows="6"></b-form-textarea>
                        </b-td>
                      </b-tr>
                    </template>
                  </template>
                  <template v-else>
                    <b-tr>
                      <b-td class="text-center" colspan="4">Tidak ada data untuk ditampilkan</b-td>
                    </b-tr>
                  </template>
                </b-tbody>
              </b-table-simple>
            </template>
            <template v-else>
              <h3 class="text-center">Tujuan Pembelajaran tidak ditemukan. Silahkan tambahkan dulu <b-link :to="{name: 'magang-cp-tp'}">disini</b-link></h3>
            </template>
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
        data: 'pembimbing',
        pkl_id: '',
        peserta_didik_id: {},
        deskripsi: {},
        catatan: {},
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
    eventBus.$on('open-modal-add-nilai-pkl', this.handleEvent);
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
      this.data_tp = []
      this.data_siswa = []
      if(val){
        this.loading_table = true
        this.$http.post('/magang/get-pd-pkl', this.form).then(response => {
          let getData = response.data
          this.data_tp = getData.data_tp
          this.data_siswa = getData.data_siswa
          var _this = this
          this.data_siswa.forEach(siswa => {
            siswa.nilai_pkl.forEach(nilai => {
              _this.form.peserta_didik_id[`${nilai.tp_id}#${siswa.peserta_didik_id}`] = nilai.nilai
              _this.form.deskripsi[`${nilai.tp_id}#${siswa.peserta_didik_id}`] = nilai.deskripsi
            })
            _this.form.catatan[siswa.peserta_didik_id] = siswa.pd_pkl.catatan
          });
          console.log(this.form.catatan);
          this.loading_table = false
        })
      }
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.data_tp = []
      this.data_siswa = []
      this.form.pkl_id = ''
      this.form.peserta_didik_id = {}
      this.form.deskripsi = {}
      this.form.catatan = {}
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/magang/simpan-nilai', this.form).then(response => {
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