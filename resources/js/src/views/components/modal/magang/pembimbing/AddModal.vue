<template>
  <b-modal v-model="addModalShow" title="Tambah Data Pembimbing" size="lg" @show="resetForm" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Pembimbing" label-for="guru_id" label-cols-md="3" :invalid-feedback="feedback.guru_id" :state="state.guru_id">
              <v-select id="guru_id" v-model="form.guru_id" :reduce="nama => nama.guru_id" label="nama" :options="data_guru" placeholder="== Pilih Pembimbing ==" :state="state.guru_id" @input="changeGuru">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="DUDI" label-for="dudi_id" label-cols-md="3" :invalid-feedback="feedback.dudi_id" :state="state.dudi_id">
              <b-overlay :show="loading_dudi" rounded opacity="0.6" spinner-small spinner-variant="secondary">
                <v-select id="dudi_id" v-model="form.dudi_id" :reduce="nama => nama.dudi_id" label="nama" :options="data_dudi" placeholder="== Pilih DUDI ==" :state="state.dudi_id" @input="changeDudi">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Nama Instruktur" label-for="instruktur" label-cols-md="3" :invalid-feedback="feedback.instruktur" :state="state.instruktur">
              <b-form-input id="instruktur" v-model="form.instruktur" :state="state.instruktur" placeholder="Nama Instruktur DUDI"></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Tanggal Mulai" label-for="tanggal_mulai" label-cols-md="3" :invalid-feedback="feedback.tanggal_mulai" :state="state.tanggal_mulai">
              <b-form-datepicker v-model="form.tanggal_mulai" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_mulai" @context="onContext" placeholder="== Pilih Tanggal Mulai ==" />
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Tanggal Selesai" label-for="tanggal_selesai" label-cols-md="3" :invalid-feedback="feedback.tanggal_selesai" :state="state.tanggal_selesai">
              <b-form-datepicker v-model="form.tanggal_selesai" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_selesai" @context="onContext" placeholder="== Pilih Tanggal Selesai ==" />
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kelas" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="feedback.rombongan_belajar_id" :state="state.rombongan_belajar_id">
              <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Kelas ==" :state="state.rombongan_belajar_id" @input="changeRombel">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group> 
          </b-col>
          <b-col cols="12" v-if="data_siswa.length">
            <b-form-group label="Peserta Magang" label-cols-md="3">
              <b-table-simple>
                <b-thead>
                  <b-tr>
                    <b-th class="text-center">No</b-th>
                    <b-th>Nama</b-th>
                    <b-th class="text-center">NISN</b-th>
                    <b-th class="text-center">
                      <b-form-checkbox v-model="allSelected" :indeterminate="indeterminate" aria-describedby="peserta_didik_id" aria-controls="peserta_didik_id" @change="toggleAll"></b-form-checkbox>
                    </b-th>
                  </b-tr>
                </b-thead>
                <b-tbody>
                  <b-tr v-for="(siswa, index) in data_siswa" :key="siswa.peserta_didik_id">
                    <b-td class="text-center">{{ index + 1 }}</b-td>
                    <b-td>{{ siswa.nama }}</b-td>
                    <b-td class="text-center">{{ siswa.nisn }}</b-td>
                    <b-td class="text-center">
                      <b-form-checkbox :id="`peserta_didik_id-${siswa.peserta_didik_id}`" v-model="selected" :name="`peserta_didik_id-${siswa.peserta_didik_id}`" :value="siswa.peserta_didik_id"></b-form-checkbox>
                    </b-td>
                  </b-tr>
                </b-tbody>
              </b-table-simple>
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
import { BOverlay, BForm, BFormGroup, BFormInput, BRow, BCol, BButton, BFormDatepicker, BTableSimple, BThead, BTbody, BTr, BTh, BTd, BFormCheckbox } from 'bootstrap-vue'
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
    BFormDatepicker,
    BTableSimple, BThead, BTbody, BTr, BTh, BTd,
    BFormCheckbox,
    vSelect,
  },
  data() {
    return {
      addModalShow: false,
      loading_form: false,
      loading_dudi: false,
      data_guru: [],
      data_rombel: [],
      data_dudi: [],
      data_siswa: [],
      form: {
        data: 'pembimbing',
        guru_id: '',
        rombongan_belajar_id: '',
        dudi_id: '',
        instruktur: '',
        tanggal_mulai: '',
        tanggal_selesai: '',
        peserta_didik_id: [],
      },
      selected: [],
      feedback: {
        guru_id: '',
        rombongan_belajar_id: '',
        dudi_id: '',
        instruktur: '',
        tanggal_mulai: '',
        tanggal_selesai: '',
      },
      state: {
        guru_id: null,
        rombongan_belajar_id: null,
        dudi_id: null,
        instruktur: null,
        tanggal_mulai: null,
        tanggal_selesai: null,
      },
      allSelected: false,
      indeterminate: false,
    }
  },
  watch: {
    selected(newValue, oldValue) {
    // Handle changes in individual flavour checkboxes
      if (newValue.length === 0) {
        this.indeterminate = false
        this.allSelected = false
      } else if (newValue.length === this.data_siswa.length) {
        this.indeterminate = false
        this.allSelected = true
      } else {
        this.indeterminate = true
        this.allSelected = false
      }
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-add-pembimbing', this.handleEvent);
  },
  methods: {
    toggleAll(checked) {
      this.selected = []
      if(checked){
        for (let i in this.data_siswa) {
					this.selected.push(this.data_siswa[i].peserta_didik_id);
				}
      }
    },
    handleEvent(){
      eventBus.$emit('loading', true);
      this.getGuru()
    },
    getGuru(){
      this.$http.post('/magang/get-guru', this.form).then(response => {
        let getData = response.data
        this.addModalShow = true
        this.data_guru = getData
        eventBus.$emit('loading', false);
      })
    },
    changeGuru(val){
      this.data_dudi = [];
      this.form.dudi_id = ''
      if(val){
        this.loading_dudi = true
        this.$http.get('/magang/get-dudi').then(response => {
          this.data_dudi = response.data
          this.loading_dudi = false
        })
      }
    },
    changeDudi(val){
      this.data_rombel = []
      this.form.rombongan_belajar_id = ''
      if(val){
        this.$http.post('/magang/get-rombel', this.form).then(response => {
          this.data_rombel = response.data
        })
      }
    },
    changeRombel(val){
      if(val){
        this.$http.post('/magang/get-siswa', this.form).then(response => {
          this.data_siswa = response.data
        })
      }
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.data_rombel = []
      this.data_dudi = []
      this.data_siswa = []
      this.selected = []
      this.form.guru_id = ''
      this.form.rombongan_belajar_id = ''
      this.form.dudi_id = ''
      this.form.instruktur = ''
      this.form.tanggal_mulai = ''
      this.form.tanggal_selesai = ''
      this.form.peserta_didik_id = []
      this.feedback.guru_id = ''
      this.feedback.rombongan_belajar_id = ''
      this.feedback.dudi_id = ''
      this.feedback.tanggal_mulai = ''
      this.feedback.tanggal_selesai = ''
      this.state.guru_id = null
      this.state.rombongan_belajar_id = null
      this.state.dudi_id = null
      this.state.tanggal_mulai = null
      this.state.tanggal_selesai = null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.form.peserta_didik_id = this.selected
      this.$http.post('/magang/simpan-data', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.guru_id = (getData.errors.guru_id) ? false : null
          this.state.rombongan_belajar_id = (getData.errors.rombongan_belajar_id) ? false : null
          this.state.dudi_id = (getData.errors.dudi_id) ? false : null
          this.state.instruktur = (getData.errors.instruktur) ? false : null
          this.state.tanggal_mulai = (getData.errors.tanggal_mulai) ? false : null
          this.state.tanggal_selesai = (getData.errors.tanggal_selesai) ? false : null
          this.feedback.guru_id = (getData.errors.guru_id) ? getData.errors.guru_id.join(', ') : ''
          this.feedback.rombongan_belajar_id = (getData.errors.rombongan_belajar_id) ? getData.errors.rombongan_belajar_id.join(', ') : ''
          this.feedback.dudi_id = (getData.errors.dudi_id) ? getData.errors.dudi_id.join(', ') : ''
          this.feedback.instruktur = (getData.errors.instruktur) ? getData.errors.instruktur.join(', ') : ''
          this.feedback.tanggal_mulai = (getData.errors.tanggal_mulai) ? getData.errors.tanggal_mulai.join(', ') : ''
          this.feedback.tanggal_selesai = (getData.errors.tanggal_selesai) ? getData.errors.tanggal_selesai.join(', ') : ''
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