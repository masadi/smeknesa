<template>
  <b-row>
    <b-col cols="12">
      <b-card>
        <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
          <b-form ref="form" @submit.prevent="handleSubmit">
            <Formulir :form="form" :feedback="feedback" :state="state" @hide_form="handleHideForm" @show_form="handleShowForm" />
            <b-overlay :show="loading_table" rounded opacity="0.6" size="lg" spinner-variant="danger">
              <template v-if="show">
                <b-table-simple bordered class="mt-2">
                  <b-thead>
                    <b-tr>
                      <b-th class="text-center" style="vertical-align:middle;" rowspan="2">Nama Siswa</b-th>
                      <b-th class="text-center" style="vertical-align:middle;" rowspan="2">NISN</b-th>
                      <b-th class="text-center" :colspan="data_diagnosis.length" v-if="data_diagnosis.length">Assesment Diagnosis</b-th>
                      <b-th class="text-center" :colspan="data_tp.length" v-if="data_tp.length">Assesment Formatif</b-th>
                      <b-th class="text-center" rowspan="2" v-if="data_tp.length">NILAI AKHIR <br/>FORMATIF</b-th>
                      <b-th class="text-center" :colspan="data_sumatif.length" v-if="data_sumatif.length">Assesment Sumatif</b-th>
                      <b-th class="text-center" rowspan="2" v-if="data_sumatif.length">NILAI AKHIR <br/>SUMATIF</b-th>
                      <!--
                        <th scope="col" class="text-center font-weight-semibold" :colspan="data_tp.length" v-if="data_tp.length">
                          Assesment Formatif
                        </th>
                        <th scope="col" class="text-center font-weight-semibold" rowspan="2" v-if="data_tp.length">NILAI AKHIR <br/>FORMATIF</th>
                        <th scope="col" class="text-center font-weight-semibold" :colspan="data_sumatif.length" v-if="data_sumatif.length">
                          Assesment Sumatif
                        </th>
                        <th scope="col" class="text-center font-weight-semibold" rowspan="2" v-if="data_sumatif.length">NILAI AKHIR <br/>SUMATIF</th>
                      -->
                    </b-tr>
                    <b-tr>
                      <b-th class="text-center" v-for="(diagnosis, index) in data_diagnosis" :key="diagnosis.penilaian_id" v-if="data_diagnosis.length">
                        <a v-ripple.400="'rgba(113, 102, 240, 0.15)'" v-b-tooltip.hover.top="diagnosis.nama" variant="outline-primary">AD {{index + 1}}</a>
                      </b-th>
                      <b-th class="text-center" rowspan="2" v-for="(tp, index) in data_tp" :key="tp.tp_id" v-if="data_tp.length">
                        <a v-ripple.400="'rgba(113, 102, 240, 0.15)'" v-b-tooltip.hover.top="tp.deskripsi" variant="outline-primary">TP {{index + 1}}</a>
                      </b-th>
                      <b-th class="text-center" v-for="(sumatif, index) in data_sumatif" :key="sumatif.penilaian_id" v-if="data_sumatif.length">
                        <a v-ripple.400="'rgba(113, 102, 240, 0.15)'" v-b-tooltip.hover.top="sumatif.nama" variant="outline-primary">AS {{index + 1}}</a>
                      </b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <template v-if="data_siswa.length">
                      <b-tr v-for="data in data_siswa" :key="data.peserta_didik_id">
                        <b-td><media-siswa :data="data" /></b-td>
                        <b-td class="text-center">
                          {{ data.nisn }}
                        </b-td>
                        <b-td class="text-center" v-bind:class="setClass(showNilaiNama(diagnosis.penilaian_id, data.nilai))" v-for="diagnosis in data_diagnosis" :key="diagnosis.penilaian_id" v-if="data_diagnosis.length">
                          {{showNilaiNama(diagnosis.penilaian_id, data.nilai)}}
                        </b-td>
                        <b-td class="text-center" v-bind:class="setClass(showNilaiTp(tp.tp_id, data.nilai))" v-for="tp in data_tp" :key="tp.tp_id" v-if="data_tp.length">
                          {{ showNilaiTp(tp.tp_id, data.nilai) }}
                        </b-td>
                        <b-td class="text-center" v-bind:class="setClass(Math.ceil(findAverageAge(nilaiTp(data.nilai, data_tp))))" v-if="data_tp.length">
                          {{ Math.ceil(findAverageAge(nilaiTp(data.nilai, data_tp))) }}
                        </b-td>
                        <b-td class="text-center" v-bind:class="setClass(showNilaiNama(sumatif.penilaian_id, data.nilai))" v-for="sumatif in data_sumatif" :key="sumatif.penilaian_id" v-if="data_sumatif.length">
                          {{ showNilaiNama(sumatif.penilaian_id, data.nilai) }}
                        </b-td>
                        <b-td class="text-center" v-bind:class="setClass(Math.ceil(findAverageAge(nilaiAsesmen(data.nilai, data_sumatif))))" v-if="data_sumatif.length">
                          {{ Math.ceil(findAverageAge(nilaiAsesmen(data.nilai, data_sumatif))) }}
                        </b-td>
                      </b-tr>
                    </template>
                    <template v-else>
                      <b-tr>
                        <b-td class="text-center" colspan="2">Tidak ada data untuk ditampilkan</b-td>
                      </b-tr>
                    </template>
                  </b-tbody>
                </b-table-simple>
              </template>
            </b-overlay>
          </b-form>
        </b-overlay>
      </b-card>
    </b-col>
  </b-row>
</template>

<script>
import { BRow, BCol, BCard, BOverlay, BForm, BTableSimple, BThead, BTbody, BTr, BTh, BTd, BFormTextarea, BButton, VBTooltip } from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import Formulir from './../components/Formulir.vue'
import MediaSiswa from '@/views/components/MediaSiswa.vue'
export default {
  components: {
    BRow,
    BCol,
    BCard,
    BOverlay,
    BForm,
    BTableSimple,
    BThead,
    BTbody,
    BTr,
    BTh,
    BTd,
    BFormTextarea,
    BButton,
    VBTooltip,
    Formulir,
    MediaSiswa,
  },
  directives: {
    'b-tooltip': VBTooltip,
    Ripple,
  },
  data() {
    return {
      show: false,
      loading_form: false,
      loading_table: false,
      data_siswa: [],
      form: {
        tingkat: '',
        rombongan_belajar_id: '',
        pembelajaran_id: '',
        nilai: true,
        penilaian: true,
        deskripsi_tercapai: {},
        deskripsi_belum_tercapai: {},
      },
      feedback: {
        tingkat: '',
        rombongan_belajar_id: '',
        pembelajaran_id: '',
      },
      state: {
        tingkat: null,
        rombongan_belajar_id: null,
        pembelajaran_id: null,
      },
      data_diagnosis: [],
      data_tp: [],
      data_sumatif: [],
    }
  },
  methods: {
    resetForm(){
      this.handleHideForm()
      this.form.tingkat = ''
      this.form.rombongan_belajar_id = ''
      this.form.pembelajaran_id = ''
      this.form.nilai = true
      this.form.penilaian = true
      this.form.deskripsi_tercapai = {}
      this.form.deskripsi_belum_tercapai = {}
    },
    handleHideForm(){
      this.show = false
      console.log('handleHideForm');
    },
    handleShowForm(){
      this.loading_table = true
      this.$http.post('/nilai/rekap', this.form).then(response => {
        this.loading_table = false;
        this.show = true
        let getData = response.data
        this.data_siswa = getData.data
        this.data_diagnosis = getData.diagnosis
        this.data_tp = getData.data_tp
        this.data_sumatif = getData.sumatif
        /*var _this = this
        this.data_siswa.forEach(item => {
          if(item.anggota_rombel.deskripsi_tercapai || item.anggota_rombel.deskripsi_belum_tercapai){
            _this.form.deskripsi_tercapai[item.anggota_rombel.anggota_rombel_id] = (item.anggota_rombel.deskripsi_tercapai) ? item.anggota_rombel.deskripsi_tercapai.deskripsi : ''
            _this.form.deskripsi_belum_tercapai[item.anggota_rombel.anggota_rombel_id] = (item.anggota_rombel.deskripsi_belum_tercapai) ? item.anggota_rombel.deskripsi_belum_tercapai.deskripsi : ''
          } else {
            if(_this.getTp(item.anggota_rombel.all_nilai, true).length){
              _this.form.deskripsi_tercapai[item.anggota_rombel.anggota_rombel_id] = `Menunjukkan penguasaan yang baik dalam ${_this.joinTp(_this.getTp(item.anggota_rombel.all_nilai, true))}.`
            }
            if(_this.getTp(item.anggota_rombel.all_nilai, false).length){
              _this.form.deskripsi_belum_tercapai[item.anggota_rombel.anggota_rombel_id] = `Perlu ditingkatkan dalam ${_this.joinTp(_this.getTp(item.anggota_rombel.all_nilai, false))}`
            }
          }
        });*/
      });
    },
    setClass(angka){
      if(angka < 75){
        return 'text-danger'
      }
      return ''
    },
    showNilaiNama(penilaian_id, nilai){
      for (var i = 0; i < nilai.length; i++) {
        if(nilai[i] && nilai[i].penilaian_id === penilaian_id){
          return nilai[i].angka
        }
      }
      return '-'
    },
    showNilaiTp(tp_id, nilai) {
      for (var i = 0; i < nilai.length; i++) {
        if(nilai[i] && nilai[i].tp_id === tp_id){
          return nilai[i].angka
        }
      }
      return '-'
    },
    findAverageAge(arr){
      const { length } = arr;
      return arr.reduce((acc, val) => {
          return acc + (val.angka/length);
      }, 0);
    },
    nilaiTp(data_nilai, data_tp){
      var push_nilai = [];
      for (var a = 0; a < data_tp.length; a++) {
        for (var i = 0; i < data_nilai.length; i++) {
          if(data_nilai[i] && data_nilai[i].tp_id === data_tp[a].tp_id){
            if(data_nilai[i].tp_id){
              push_nilai.push(data_nilai[i])
            }
          }
        }
      }
      return push_nilai
    },
    nilaiAsesmen(data_nilai, data_asesmen){
      var push_nilai = [];
      for (var a = 0; a < data_asesmen.length; a++) {
        for (var i = 0; i < data_nilai.length; i++) {
          if(data_nilai[i] && data_nilai[i].penilaian_id === data_asesmen[a].penilaian_id){
            if(data_nilai[i].penilaian_id){
              push_nilai.push(data_nilai[i])
            }
          }
        }
      }
      return push_nilai
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>