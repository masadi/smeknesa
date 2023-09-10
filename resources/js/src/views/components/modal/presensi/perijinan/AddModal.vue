<template>
  <b-modal v-model="modalShow" title="Tambah Perijinan" size="xl" @show="resetForm" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Nama Siswa" label-for="anggota_rombel_id" label-cols-md="3" :invalid-feedback="feedback.anggota_rombel_id" :state="state.anggota_rombel_id">
              <v-select id="anggota_rombel_id" v-model="form.anggota_rombel_id" :reduce="nama => nama.anggota_rombel.anggota_rombel_id" label="nama" :options="data_siswa" placeholder="Cari nama siswa" :state="state.anggota_rombel_id" @search="fetchOptions">
                <template slot="no-options">
                  Cari nama siswa...
                </template>
                <template slot="option" slot-scope="option">
                  <media-siswa :data="option"></media-siswa>
                </template>
                <template slot="selected-option" slot-scope="option">
                  <div class="selected d-center">
                    <media-siswa :data="option"></media-siswa>
                  </div>
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Pilihan Izin" label-for="pilihan_ijin" label-cols-md="3" :invalid-feedback="feedback.pilihan_ijin" :state="state.pilihan_ijin">
              <b-form-radio-group id="pilihan_ijin" v-model="form.pilihan_ijin" :options="data_ijin" name="pilihan_ijin" :state="state.pilihan_ijin"></b-form-radio-group>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Alasan Ijin" label-for="alasan" label-cols-md="3">
              <b-form-input v-model="form.alasan" placeholder="Isi alasan (jika ada)"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Jenis Ijin" label-for="jenis_ijin" label-cols-md="3" :invalid-feedback="feedback.jenis_ijin" :state="state.jenis_ijin">
              <b-form-radio-group id="jenis_ijin" v-model="form.jenis_ijin" :options="data_jenis" name="jenis_ijin" :state="state.jenis_ijin" @input="changeJenis"></b-form-radio-group>
            </b-form-group>
          </b-col>
          <template v-if="form.jenis_ijin == 'hari'">
            <b-col cols="12">
              <b-form-group label="Tanggal Mulai Ijin" label-for="tanggal_mulai_ijin" label-cols-md="3" :invalid-feedback="feedback.tanggal_mulai_ijin" :state="state.tanggal_mulai_ijin">
                <b-form-datepicker v-model="form.tanggal_mulai_ijin" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_mulai_ijin" @context="onContext" placeholder="== Pilih Tanggal Mulai Ijin ==" :state="state.tanggal_mulai_ijin" />
              </b-form-group>
            </b-col>
            <b-col cols="12">
              <b-form-group label="Tanggal Selesai Ijin" label-for="tanggal_selesai_ijin" label-cols-md="3" :invalid-feedback="feedback.tanggal_selesai_ijin" :state="state.tanggal_selesai_ijin">
                <b-form-datepicker v-model="form.tanggal_selesai_ijin" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_selesai_ijin" @context="onContext" placeholder="== Pilih Tanggal Selesai Ijin ==" :state="state.tanggal_selesai_ijin" />
              </b-form-group>
            </b-col>
          </template>
          <template v-if="form.jenis_ijin == 'jam'">
            <b-col cols="12">
              <b-form-group label="Tanggal Ijin" label-for="tanggal_ijin" label-cols-md="3" :invalid-feedback="feedback.tanggal_ijin" :state="state.tanggal_ijin">
                <b-form-datepicker v-model="form.tanggal_ijin" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_ijin" @context="onContext" placeholder="== Pilih Tanggal Ijin ==" :state="state.tanggal_ijin" @input="changeTanggal" />
              </b-form-group>
            </b-col>
            <b-col cols="12" v-if="form.tanggal_ijin">
              <b-form-group label="Jam Ke" label-for="jam_ke" label-cols-md="3" :invalid-feedback="feedback.jam_ke" :state="state.jam_ke">
                <b-overlay :show="loading_jam" rounded opacity="0.6" spinner-small spinner-variant="danger">
                  <b-form-checkbox v-model="allSelected" :indeterminate="indeterminate" @change="toggleAll" class="mb-1" v-if="data_jam.length">
                    {{ allSelected ? 'Lepas Semua' : 'Pilih Semua' }}
                  </b-form-checkbox>
                  <b-form-checkbox-group id="jam_ke" v-model="jam_selected" :options="data_jam" name="jam_ke" :state="state.jam_ke"></b-form-checkbox-group>
                </b-overlay>
              </b-form-group>
            </b-col>
          </template>
        </b-row>
      </b-form>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
          <b-button @click="cancel()">Batal</b-button>
        </b-overlay>
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
          <b-button variant="primary" @click="ok()">Preview</b-button>
        </b-overlay>
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
          <b-button variant="success" @click="cetak()">Cetak</b-button>
        </b-overlay>
      </template>
  </b-modal>
</template>

<script>
import { BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BFormSelect, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton, BFormDatepicker, BFormRadioGroup, BFormCheckbox, BFormCheckboxGroup } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import _ from 'lodash'
import vSelect from 'vue-select'
import MediaSiswa from './../../../MediaSiswa.vue'
export default {
  components: {
    vSelect, MediaSiswa, BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BFormSelect, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton,
    BFormDatepicker, BFormRadioGroup, BFormCheckbox, BFormCheckboxGroup,
  },
  data() {
    return {
      modalShow: false,
      loading_form: false,
      loading_jam: false,
      form: {
        anggota_rombel_id: '',
        pilihan_ijin: '',
        alasan: '',
        jenis_ijin: '',
        tanggal_ijin: '',
        tanggal_mulai_ijin: '',
        tanggal_selesai_ijin: '',
        jam_ke: [],

      },
      feedback: {
        anggota_rombel_id: '',
        pilihan_ijin: '',
        jenis_ijin: '',
        tanggal_ijin: '',
        tanggal_mulai_ijin: '',
        tanggal_selesai_ijin: '',
        jam_ke: '',
      },
      state: {
        anggota_rombel_id: null,
        pilihan_ijin: null,
        jenis_ijin: null,
        tanggal_ijin: null,
        tanggal_mulai_ijin: null,
        tanggal_selesai_ijin: null,
        jam_ke: null,
      },
      data_siswa: [],
      data_jam: [],
      data_ijin: [
        { text: 'Sakit', value: 'S' },
        { text: 'Izin', value: 'I' },
        { text: 'Dispen', value: 'D' },
      ],
      data_jenis: [
        { text: 'Perjam', value: 'jam' },
        { text: 'Harian', value: 'hari' },
      ],
      jam_selected: [],
      allSelected: false,
      indeterminate: false,
    }
  },
  watch: {
    jam_selected(newValue, oldValue) {
      // Handle changes in individual flavour checkboxes
      if (newValue.length === 0) {
        this.indeterminate = false
        this.allSelected = false
      } else if (newValue.length === this.data_jam.length) {
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
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-add-perijinan', this.handleEvent);
  },
  methods: {
    toggleAll(checked) {
      this.jam_selected = checked ? this.data_jam.slice() : []
    },
    handleEvent(){
      this.modalShow = true
    },
    hideModal(){
      this.modalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.anggota_rombel_id = ''
      this.form.pilihan_ijin = ''
      this.form.jenis_ijin = ''
      this.form.tanggal_mulai_ijin = ''
      this.form.tanggal_selesai_ijin = ''
      this.form.jam_ke = []
      this.jam_selected = []
      this.feedback.anggota_rombel_id = ''
      this.feedback.pilihan_ijin = ''
      this.feedback.jenis_ijin = ''
      this.feedback.tanggal_mulai_ijin = ''
      this.feedback.tanggal_selesai_ijin = ''
      this.feedback.tanggal_ijin = ''
      this.feedback.jam_ke = ''
      this.state.anggota_rombel_id = null
      this.state.pilihan_ijin = null
      this.state.jenis_ijin = null
      this.state.tanggal_mulai_ijin = null
      this.state.tanggal_selesai_ijin = null
      this.state.tanggal_ijin = null
      this.state.jam_ke = null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit('preview')
    },
    cetak(){
      this.handleSubmit('cetak')
    },
    handleSubmit(aksi){
      this.form.jam_ke = this.jam_selected
      this.loading_form = true
      this.$http.post('/presensi/add-perijinan', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.feedback.anggota_rombel_id = (getData.errors.anggota_rombel_id) ? getData.errors.anggota_rombel_id.join(', ') : ''
          this.feedback.pilihan_ijin = (getData.errors.pilihan_ijin) ? getData.errors.pilihan_ijin.join(', ') : ''
          this.feedback.jenis_ijin = (getData.errors.jenis_ijin) ? getData.errors.jenis_ijin.join(', ') : ''
          this.feedback.tanggal_mulai_ijin = (getData.errors.tanggal_mulai_ijin) ? getData.errors.tanggal_mulai_ijin.join(', ') : ''
          this.feedback.tanggal_selesai_ijin = (getData.errors.tanggal_selesai_ijin) ? getData.errors.tanggal_selesai_ijin.join(', ') : ''
          this.feedback.tanggal_ijin = (getData.errors.tanggal_ijin) ? getData.errors.tanggal_ijin.join(', ') : ''
          this.feedback.jam_ke = (getData.errors.jam_ke) ? getData.errors.jam_ke.join(', ') : ''
          this.state.anggota_rombel_id = (getData.errors.anggota_rombel_id) ? false : null
          this.state.pilihan_ijin = (getData.errors.pilihan_ijin) ? false : null
          this.state.jenis_ijin = (getData.errors.jenis_ijin) ? false : null
          this.state.tanggal_mulai_ijin = (getData.errors.tanggal_mulai_ijin) ? false : null
          this.state.tanggal_selesai_ijin = (getData.errors.tanggal_selesai_ijin) ? false : null
          this.state.tanggal_ijin = (getData.errors.tanggal_ijin) ? false : null
          this.state.jam_ke = (getData.errors.jam_ke) ? false : null
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
            confirmButtonText: aksi.toUpperCase(),
            allowOutsideClick: false,
          }).then(result => {
            if(getData.success){
              this.hideModal()
              this.$emit('reload')
              if(getData.ijin){
                window.open(`/cetak/perijinan/${aksi}/${getData.ijin.ijin_id}/${this.user.user_id}`, '_blank')
              }
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
    getSiswa(val){
      this.loading_form = true
      this.$http.post('/presensi/get-siswa', {
        nama_siwa: val,
      }).then(response => {
        this.loading_form = false
        var getData = response.data
        console.log(getData);
      })
    },
    search: _.debounce((loading, search, vm) => {
      if(search){
        vm.$http.post('/presensi/get-siswa', {
          nama_siwa: search,
        }).then(response => {
          //res.json().then(json => (vm.data_siswa = json.items));
          loading(false);
          var getData = response.data
          vm.data_siswa = getData.items
          //console.log(getData);
        })
      }
    }, 500),
    fetchOptions (search, loading) {
      if(search.length) {
        loading(true);
        this.search(loading, search, this);
      }
    },
    changeTanggal(val){
      this.loading_jam = true
      this.$http.post('/presensi/get-jam', this.form).then(response => {
        this.loading_jam = false
        var getData = response.data
        this.data_jam = getData
      })
    },
    changeJenis(val){
      if(val == 'hari'){
        this.jam_selected = []
        this.allSelected = false
        this.indeterminate = false
        this.form.tanggal_ijin = ''
        this.form.jam_ke = []
      }
    },
  },
}
</script>