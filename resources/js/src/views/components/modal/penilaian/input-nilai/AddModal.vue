<template>
  <b-modal modal-class="modal-fullscreen" v-model="addModalShow" title="Proses Penilaian" @hidden="hideModal" @ok="handleOk" scrollable>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="6">
            <b-row>
              <b-col cols="12">
                <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="feedback.tingkat" :state="state.tingkat">
                  <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" :state="state.tingkat" @input="changeTingkat">
                    <template #no-options="{ search, searching, loading }">
                      Tidak ada data untuk ditampilkan
                    </template>
                  </v-select>
                </b-form-group> 
              </b-col>
              <b-col cols="12">
                <b-form-group label="Kelas" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="feedback.rombongan_belajar_id" :state="state.rombongan_belajar_id">
                  <b-overlay :show="loading_kelas" rounded opacity="0.6" spinner-small spinner-variant="danger">
                    <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Kelas ==" :state="state.rombongan_belajar_id" @input="changeRombel">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-overlay>
                </b-form-group> 
              </b-col>
              <b-col cols="12">
                <b-form-group label="Mata Pelajaran" label-for="pembelajaran_id" label-cols-md="3" :invalid-feedback="feedback.pembelajaran_id" :state="state.pembelajaran_id">
                  <b-overlay :show="loading_mapel" rounded opacity="0.6" spinner-small spinner-variant="danger">
                    <v-select id="pembelajaran_id" v-model="form.pembelajaran_id" :reduce="nama_mata_pelajaran => nama_mata_pelajaran.pembelajaran_id" label="nama_mata_pelajaran" :options="data_pembelajaran" placeholder="== Pilih Mata Pelajaran ==" :state="state.pembelajaran_id" @input="changeMapel">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-overlay>
                </b-form-group> 
              </b-col>
              <b-col cols="12">
                <b-form-group label="Jenis Penilaian" label-for="jenis_id" label-cols-md="3" :invalid-feedback="feedback.jenis_id" :state="state.jenis_id">
                  <b-overlay :show="loading_jenis" rounded opacity="0.6" spinner-small spinner-variant="danger">
                    <v-select id="jenis_id" v-model="form.jenis_id" :reduce="nama => nama.id" label="nama" :options="data_jenis" placeholder="== Pilih Jenis Penilaian ==" :state="state.jenis_id" @input="changeJenis">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-overlay>
                </b-form-group> 
              </b-col>
              <b-col cols="12" v-show="form.jenis_id === 2">
                <b-form-group label="Capaian Pembelajaran" label-for="cp_id" label-cols-md="3" :invalid-feedback="feedback.cp_id" :state="state.cp_id">
                  <b-overlay :show="loading_cp" rounded opacity="0.6" spinner-small spinner-variant="danger">
                    <v-select id="cp_id" v-model="form.cp_id" :reduce="deskripsi => deskripsi.cp_id" label="deskripsi" :options="data_cp" placeholder="== Pilih Capaian Pembelajaran ==" :state="state.cp_id" @input="changeCp">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-overlay>
                </b-form-group> 
              </b-col>
            </b-row>
          </b-col>
          <b-col cols="6">
            <h3>Jenis Penilaian</h3>
            <app-timeline>
              <app-timeline-item title="Asesmen Diagnosis" subtitle="Upaya untuk memperoleh informasi tentang kondisi siswa baik dari aspek kognitif maupun nonkognitif  terkait dengan kesiapan siswa untuk menerima materi pelajaran." time="" variant="primary" />
              <app-timeline-item title="Asesmen Formatif" subtitle="Bertujuan untuk memantau dan memperbaiki proses pembelajaran, serta mengevaluasi pencapaian tujuan pembelajaran." time="" variant="success" />
              <app-timeline-item title="Asesmen Sumatif" subtitle="Asesmen yang dilakukan untuk memastikan ketercapaian keseluruhan tujuan pembelajaran." time="" variant="warning" />
            </app-timeline>
          </b-col>
        </b-row>
        <b-row class="mt-2">
          <b-col cols="12">
            <b-overlay :show="loading_siswa" rounded opacity="0.6" spinner-small spinner-variant="success">
              <b-table-simple bordered v-if="data_siswa.length">
                <b-thead>
                  <b-tr>
                    <b-th rowspan="2" class="text-center" style="vertical-align:middle;">Nama Siswa</b-th>
                    <b-th rowspan="2" class="text-center" style="vertical-align:middle;">NISN</b-th>
                    <b-th class="text-center" :colspan="data_tp.length" v-if="data_tp.length">Tujuan Pembelajaran</b-th>
                    <b-th class="text-center" :colspan="jumlah_form" v-if="jumlah_form">Nama Asesmen</b-th>
                    <b-th class="text-center" style="width: 3rem; vertical-align:middle;" rowspan="2" v-if="jumlah_form">
                      <b-button size="sm" @click="addForm" variant="success"><plus-icon size="16" /></b-button>
                    </b-th>
                  </b-tr>
                  <b-tr>
                    <template v-for="(tp, index) in data_tp" v-if="data_tp.length">
                      <b-th class="text-center">{{tp.deskripsi}}</b-th>
                    </template>
                    <template v-for="index in jumlah_form" v-if="jumlah_form">
                      <b-th class="text-center">
                        <b-form-input v-model="form.nama_asesmen[index]" :placeholder="`Nama Asesmen (${index})`"></b-form-input>
                      </b-th>
                    </template>
                  </b-tr>
                </b-thead>
                <b-tbody>
                  <template v-for="siswa in data_siswa">
                    <b-tr>
                      <b-td>
                        <media-siswa :data="siswa" />
                      </b-td>
                      <b-td>{{siswa.nisn}}</b-td>
                      <template v-for="(tp, index) in data_tp" v-if="data_tp.length">
                        <b-td class="text-center">
                          <b-form-input v-model="form.angka[tp.tp_id+'#'+siswa.anggota_rombel.anggota_rombel_id]" placeholder="0"></b-form-input>
                        </b-td>
                      </template>
                      <template v-for="index in jumlah_form" v-if="jumlah_form">
                        <b-td class="text-center">
                          <b-form-input v-model="form.angka[index+'#'+siswa.anggota_rombel.anggota_rombel_id]" placeholder="0"></b-form-input>
                        </b-td>
                      </template>
                      <b-td v-if="jumlah_form"></b-td>
                    </b-tr>
                  </template>
                </b-tbody>
              </b-table-simple>
            </b-overlay>
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
import { BOverlay, BForm, BFormGroup, BFormInput, BInputGroup, BInputGroupAppend, BRow, BCol, BFormTextarea, BButton, BTableSimple, BThead, BTbody, BTr, BTh, BTd } from 'bootstrap-vue'
import MediaSiswa from './../../../MediaSiswa.vue'
import AppTimeline from '@core/components/app-timeline/AppTimeline.vue'
import AppTimelineItem from '@core/components/app-timeline/AppTimelineItem.vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay,
    BForm,
    BFormGroup,
    BFormInput,
    BInputGroup, 
    BInputGroupAppend,
    BRow,
    BCol,
    BFormTextarea,
    BButton,
    BTableSimple,
    BThead,
    BTbody,
    BTr,
    BTh,
    BTd,
    vSelect,
    MediaSiswa,
    AppTimeline,
    AppTimelineItem,
  },
  data() {
    return {
      addModalShow: false,
      loading_form: false,
      loading_kelas: false,
      loading_mapel: false,
      loading_jenis: false,
      loading_cp: false,
      loading_siswa: false,
      data_tingkat: [
        {
          id: 10,
          nama: 'Tingkat 10',
        },
        {
          id: 11,
          nama: 'Tingkat 11',
        },
        {
          id: 12,
          nama: 'Tingkat 12',
        },
        {
          id: 13,
          nama: 'Tingkat 13',
        }
      ],
      data_rombel: [],
      data_pembelajaran: [],
      data_jenis: [],
      data_cp: [],
      data_siswa: [],
      data_tp: [],
      jumlah_form: 0,
      form: {
        semester_id: '',
        tingkat: '',
        rombongan_belajar_id: '',
        pembelajaran_id: '',
        jenis_id: '',
        cp_id: '',
        nama_asesmen: [],
        angka: {},
        nilai: true,
        penilaian: true,
      },
      feedback: {
        tingkat: '',
        rombongan_belajar_id: '',
        pembelajaran_id: '',
        jenis_id: '',
        cp_id: '',
      },
      state: {
        tingkat: null,
        rombongan_belajar_id: null,
        pembelajaran_id: null,
        jenis_id: null,
        cp_id: null,
      },
      data_penilaian: [],
    }
  },
  created() {
    eventBus.$on('open-modal-input-nilai', this.handleEvent);
    this.form.semester_id = this.user.semester.semester_id
  },
  methods: {
    handleEvent(data){
      this.addModalShow = true
    },
    hideModal(){
      this.addModalShow = false
      this.data_rombel = []
      this.data_pembelajaran = []
      this.data_jenis = []
      this.data_cp = []
      this.data_siswa = []
      this.data_tp = []
      this.jumlah_form = 0
      this.form.tingkat = ''
      this.form.rombongan_belajar_id = ''
      this.form.pembelajaran_id = ''
      this.form.jenis_id = ''
      this.form.cp_id = ''
      this.form.nama_asesmen = []
      this.form.angka = {}
      this.form.nilai = true
      this.form.penilaian = true
      this.feedback.tingkat = ''
      this.feedback.rombongan_belajar_id = ''
      this.feedback.pembelajaran_id = ''
      this.feedback.jenis_id = ''
      this.feedback.cp_id = ''
      this.state.tingkat = null
      this.state.rombongan_belajar_id = null
      this.state.pembelajaran_id = null
      this.state.jenis_id = null
      this.state.cp_id = null
      this.data_penilaian = []
    },
    changeTingkat(val){
      this.loading_kelas = true
      this.$http.post('/referensi/get-rombel', this.form).then(response => {
        this.loading_kelas = false;
        this.data_rombel = response.data
      });
    },
    changeRombel(val){
      this.loading_mapel = true
      this.$http.post('/referensi/get-mapel', this.form).then(response => {
        this.loading_mapel = false;
        this.data_pembelajaran = response.data
      });
    },
    changeMapel(val){
      this.loading_jenis = true
      this.$http.post('/nilai/get-cp', this.form).then(response => {
        this.loading_jenis = false;
        let getData = response.data
        this.data_jenis = getData.jenis
        this.data_cp = getData.cp
      });
    },
    changeJenis(val){
      this.form.cp_id = ''
      this.jumlah_form = 0
      this.form.angka = {}
      this.loading_siswa = true
      this.$http.post('/nilai/get-siswa', this.form).then(response => {
        this.loading_siswa = false;
        let getData = response.data
        this.data_siswa = getData.siswa
        this.data_tp = getData.data_tp
        var _this = this
        this.form.nama_asesmen = []
        if(this.form.jenis_id !== 2){
          this.data_penilaian = getData.penilaian
          if(getData.penilaian.length){
            this.jumlah_form = getData.penilaian.length
            this.data_siswa.forEach(item => {
              getData.penilaian.forEach((tp, index) => {
                var nomor = index + 1
                _this.form.angka[nomor+'#'+item.anggota_rombel.anggota_rombel_id] = (_this.getNilai(item.nilai, tp.penilaian_id)[0]) ? _this.getNilai(item.nilai, tp.penilaian_id)[0].angka : ''
              })
            });
            getData.penilaian.forEach((tp, index) => {
                var nomor = index + 1
                _this.form.nama_asesmen[nomor] = tp.nama
                //nilai.value[nomor+'#'+item.anggota_rombel.anggota_rombel_id] = (getNilai(item.nilai, tp.penilaian_id)[0]) ? getNilai(item.nilai, tp.penilaian_id)[0].angka : ''
              })
          } else {
            this.jumlah_form = 2
          }
        } else {
          this.data_siswa.forEach(item => {
            _this.data_tp.forEach(tp => {
              _this.form.angka[tp.tp_id+'#'+item.anggota_rombel.anggota_rombel_id] = (_this.getNilai(item.nilai, tp.tp_id)[0]) ? _this.getNilai(item.nilai, tp.tp_id)[0].angka : ''
            })
          });
        }
      });
    },
    changeCp(val){
      this.loading_siswa = true
      this.$http.post('/nilai/get-tp', this.form).then(response => {
        this.loading_siswa = false;
        let getData = response.data
        this.data_tp = getData.data_tp
        var _this = this
        this.data_siswa.forEach(item => {
          _this.data_tp.forEach(tp => {
            _this.form.angka[tp.tp_id+'#'+item.anggota_rombel.anggota_rombel_id] = (_this.getNilai(item.nilai, tp.tp_id)[0]) ? _this.getNilai(item.nilai, tp.tp_id)[0].angka : ''
          })
        });
      });
    },
    getNilai(arr, tp_id){
      const get_nilai = arr.filter((item) => {
        if(item.tp_id){
          return item.tp_id === tp_id
        } else {
          return item.penilaian_id === tp_id
        }
      })
      return get_nilai
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.$http.post('/nilai/simpan', this.form).then(response => {
        let getData = response.data
        if(getData.errors){
          console.log(getData.errors);
          this.feedback.tingkat = (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : ''
          this.feedback.rombongan_belajar_id = (getData.errors.rombongan_belajar_id) ? getData.errors.rombongan_belajar_id.join(', ') : ''
          this.feedback.pembelajaran_id = (getData.errors.pembelajaran_id) ? getData.errors.pembelajaran_id.join(', ') : ''
          this.feedback.jenis_id = (getData.errors.jenis_id) ? getData.errors.jenis_id.join(', ') : ''
          this.feedback.cp_id = (getData.errors.cp_id) ? getData.errors.cp_id.join(', ') : ''
          this.state.tingkat = (getData.errors.tingkat) ? false : null
          this.state.rombongan_belajar_id = (getData.errors.rombongan_belajar_id) ? false : null
          this.state.pembelajaran_id = (getData.errors.pembelajaran_id) ? false : null
          this.state.jenis_id = (getData.errors.jenis_id) ? false : null
          this.state.cp_id = (getData.errors.cp_id) ? false : null
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
            allowOutsideClick: () => false,
          }).then(result => {
            this.$emit('reload')
            this.hideModal()
          })
        }
      });
    },
    addForm(){
      this.jumlah_form = this.jumlah_form + 1
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