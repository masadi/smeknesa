<template>
  <b-modal v-model="addModalShow" title="Tambah Data Rencana Penilaian UKK" @show="resetForm" @hidden="hideModal" @ok="handleOk" size="xl">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="feedback.tingkat" :state="state.tingkat">
              <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" :searchable="false" :state="state.tingkat" @input="changeTingkat">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kelas" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="feedback.rombongan_belajar_id" :state="state.rombongan_belajar_id">
              <b-overlay :show="loading_rombel" opacity="0.6" size="md" spinner-variant="secondary">
                <v-select id="tingkat" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Kelas ==" :state="state.rombongan_belajar_id" @input="changeRombel">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Penguji Internal" label-for="internal" label-cols-md="3" :invalid-feedback="feedback.internal" :state="state.internal">
              <b-overlay :show="loading_guru" opacity="0.6" size="md" spinner-variant="secondary">
                <v-select id="internal" v-model="form.internal" :reduce="nama => nama.guru_id" label="nama" :options="data_guru" placeholder="== Pilih Penguji Internal ==" :state="state.internal">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Penguji Eksternal" label-for="eksternal" label-cols-md="3" :invalid-feedback="feedback.eksternal" :state="state.eksternal">
              <b-overlay :show="loading_guru" opacity="0.6" size="md" spinner-variant="secondary">
                <v-select id="eksternal" v-model="form.eksternal" :reduce="nama => nama.guru_id" label="nama" :options="data_guru" placeholder="== Pilih Penguji Eksternal ==" :state="state.eksternal">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Tanggal Sertifikat" label-for="tanggal_sertifikat" label-cols-md="3" :invalid-feedback="feedback.tanggal_sertifikat" :state="state.tanggal_sertifikat">
              <b-form-datepicker v-model="form.tanggal_sertifikat" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_sertifikat" @context="onContext" placeholder="== Pilih Tanggal Sertifikat ==" :state="state.tanggal_sertifikat" />
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Paket Kompetensi" label-for="paket_ukk_id" label-cols-md="3" :invalid-feedback="feedback.paket_ukk_id" :state="state.paket_ukk_id">
              <b-overlay :show="loading_paket" opacity="0.6" size="md" spinner-variant="secondary">
                <v-select id="paket_ukk_id" v-model="form.paket_ukk_id" :reduce="nama_paket_id => nama_paket_id.paket_ukk_id" label="nama_paket_id" :options="data_paket" placeholder="== Pilih Paket Kompetensi ==" :state="state.paket_ukk_id" @input="changePaket">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <b-col cols="12" v-if="data_siswa.length">
            <b-form-group label="Peserta Didik" label-for="siswa_dipilih" label-cols-md="3">
              <b-table-simple>
                <b-thead>
                  <b-tr>
                    <b-th class="text-center">No</b-th>
                    <b-th class="text-center">Nama Peserta Didik</b-th>
                    <b-th class="text-center">NISN</b-th>
                    <b-th>Pilih</b-th>
                  </b-tr>
                </b-thead>
                <b-tbody>
                  <b-tr v-for="(item, index) in data_siswa" :key="index">
                    <b-td class="text-center">{{ index + 1 }}</b-td>
                    <b-td>{{ item.nama }}</b-td>
                    <b-td class="text-center">{{ item.nisn }}</b-td>
                    <b-td class="text-center">
                      <b-form-checkbox :id="`checkbox-${index}`" v-model="form.siswa_dipilih[`${item.peserta_didik_id}#${item.anggota_rombel.anggota_rombel_id}`]" :name="`checkbox-${index}`" :value="`${item.peserta_didik_id}#${item.anggota_rombel.anggota_rombel_id}`" stacked class="mx-auto"></b-form-checkbox>
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
import { BOverlay, BForm, BFormGroup, BFormFile, BRow, BCol, BButton, BFormDatepicker, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormCheckbox } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay,
    BForm,
    BFormGroup,
    BFormFile,
    BRow,
    BCol,
    BButton,
    BFormDatepicker,
    BTableSimple,
    BThead,
    BTbody,
    BTh,
    BTr,
    BTd,
    BFormCheckbox,
    vSelect,
  },
  data() {
    return {
      addModalShow: false,
      loading_form: false,
      loading_rombel: false,
      loading_mapel: false,
      loading_guru: false,
      loading_paket: false,
      loading_table: false,
      data_tingkat: [
        {
          id: 10,
          nama: 'Kelas 10',
        },
        {
          id: 11,
          nama: 'Kelas 11',
        },
        {
          id: 12,
          nama: 'Kelas 12',
        },
        {
          id: 13,
          nama: 'Kelas 13',
        },
      ],
      data_rombel: [],
      data_paket: [],
      data_guru: [],
      data_siswa: [],
      feedback: {
        tingkat: '',
        rombongan_belajar_id: '',
        internal: '',
        eksternal: '',
        tanggal_sertifikat: '',
        paket_ukk_id: '',
      },
      state: {
        tingkat: null,
        rombongan_belajar_id: null,
        internal: null,
        eksternal: null,
        tanggal_sertifikat: null,
        paket_ukk_id: null,
      },
      form: {
        tingkat: '',
        rombongan_belajar_id: '',
        internal: '',
        eksternal: '',
        tanggal_sertifikat: '',
        paket_ukk_id: '',
        siswa_dipilih: {},
      },
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-add-rencana-ukk', this.handleEvent);
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
      this.form.rombongan_belajar_id = ''
      this.form.internal = ''
      this.form.eksternal = ''
      this.form.tanggal_sertifikat = ''
      this.form.paket_ukk_id = ''
      this.form.siswa_dipilih = {}
      this.feedback = {
        tingkat: '',
        rombongan_belajar_id: '',
        internal: '',
        eksternal: '',
        tanggal_sertifikat: '',
        paket_ukk_id: '',
      }
      this.state = {
        tingkat: null,
        rombongan_belajar_id: null,
        internal: null,
        eksternal: null,
        tanggal_sertifikat: null,
        paket_ukk_id: null,
      }
      this.data_rombel = []
      this.data_paket = []
      this.data_guru = []
      this.data_siswa = []
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/ukk/rencana/store', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.feedback = {
            tingkat: (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : '',
            rombongan_belajar_id: (getData.errors.rombongan_belajar_id) ? getData.errors.rombongan_belajar_id.join(', ') : '',
            internal: (getData.errors.internal) ? getData.errors.internal.join(', ') : '',
            eksternal: (getData.errors.eksternal) ? getData.errors.eksternal.join(', ') : '',
            tanggal_sertifikat: (getData.errors.tanggal_sertifikat) ? getData.errors.tanggal_sertifikat.join(', ') : '',
            paket_ukk_id: (getData.errors.paket_ukk_id) ? getData.errors.paket_ukk_id.join(', ') : '',
          }
          this.state = {
            tingkat: (getData.errors.tingkat) ? false : null,
            rombongan_belajar_id: (getData.errors.rombongan_belajar_id) ? false : null,
            internal: (getData.errors.internal) ? false : null,
            eksternal: (getData.errors.eksternal) ? false : null,
            tanggal_sertifikat: (getData.errors.tanggal_sertifikat) ? false : null,
            paket_ukk_id: (getData.errors.paket_ukk_id) ? false : null,
          }
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
    changeTingkat(val){
      this.form.rombongan_belajar_id = ''
      this.form.internal = ''
      this.form.eksternal = ''
      if(val){
        this.loading_rombel = true
        this.$http.post('/referensi/get-rombel', this.form).then(response => {
          this.loading_rombel = false
          this.data_rombel = response.data
        })
      }
    },
    changeRombel(val){
      this.$emit('hide_form')
      this.form.internal = ''
      this.form.eksternal = ''
      if(val){
        this.loading_guru = true
        this.$http.post('/ukk/rencana/get-penguji', this.form).then(response => {
          this.loading_guru = false
          this.data_guru = response.data
        })
      }
    },
    onContext(ctx) {
      this.data_siswa = []
      this.formatted = ctx.selectedFormatted
      this.loading_paket = true
      this.$http.post('/ukk/rencana/get-paket', this.form).then(response => {
        this.loading_paket = false
        this.data_paket = response.data
      })
    },
    changePaket(val){
      this.$emit('hide_form')
      this.data_siswa = []
      this.form.siswa_dipilih = {}
      if(val){
        this.loading_table = true
        this.$http.post('/ukk/rencana/get-siswa', this.form).then(response => {
          this.loading_table = false
          let getData = response.data
          this.data_siswa = getData.pd
          var siswa_dipilih = {}
          this.data_siswa.forEach(function(item, key) {
            if(item.nilai_ukk && getData.rencana_ukk){
              siswa_dipilih[`${item.peserta_didik_id}#${item.anggota_rombel.anggota_rombel_id}`] = `${item.peserta_didik_id}#${item.anggota_rombel.anggota_rombel_id}`
            }
          })
          this.form.siswa_dipilih = siswa_dipilih
        })
      }
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>