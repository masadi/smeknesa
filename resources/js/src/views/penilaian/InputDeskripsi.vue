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
                      <b-th class="text-center" style="vertical-align:middle;" rowspan="2">Nilai Akhir</b-th>
                      <b-th class="text-center" colspan="2">Capaian Kompetensi</b-th>
                    </b-tr>
                    <b-tr>
                      <b-th class="text-center">Tercapai</b-th>
                      <b-th class="text-center">Belum Tercapai</b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <template v-if="data_siswa.length">
                      <b-tr v-for="siswa in data_siswa" :key="siswa.peserta_didik_id">
                        <b-td><media-siswa :data="siswa" /></b-td>
                        <b-td class="text-center">{{ Math.ceil(siswa.anggota_rombel.rerata) }}</b-td>
                        <b-td>
                          <b-form-textarea v-model="form.deskripsi_tercapai[siswa.anggota_rombel.anggota_rombel_id]" placeholder="Deskripsi Tercapai" />
                        </b-td>
                        <b-td>
                          <b-form-textarea v-model="form.deskripsi_belum_tercapai[siswa.anggota_rombel.anggota_rombel_id]" placeholder="Deskripsi Belum Tercapai" />
                        </b-td>
                      </b-tr>
                    </template>
                    <template v-else>
                      <b-tr>
                        <b-td class="text-center" colspan="4">Tidak ada data untuk ditampilkan</b-td>
                      </b-tr>
                    </template>
                  </b-tbody>
                </b-table-simple>
                <b-row class="mt-2">
                  <b-col cols="12">
                    <b-button type="submit" variant="primary" class="float-right ml-1">Simpan</b-button>
                    <b-button @click="resetData" variant="danger" class="float-right">Reset</b-button>
                  </b-col>
                </b-row>
              </template>
            </b-overlay>
          </b-form>
        </b-overlay>
      </b-card>
    </b-col>
  </b-row>
</template>

<script>
import { BRow, BCol, BCard, BOverlay, BForm, BTableSimple, BThead, BTbody, BTr, BTh, BTd, BFormTextarea, BButton } from 'bootstrap-vue'
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
    Formulir,
    MediaSiswa,
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
      this.$http.post('/nilai/get-desc', this.form).then(response => {
        this.loading_table = false;
        this.show = true
        this.data_siswa = response.data
        var _this = this
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
        });
      });
    },
    getTp(arr, tuntas){
      const get_tp = arr.filter((item) => {
        if(tuntas){
          return item.angka >= 75
        } else {
          return item.angka < 75
        }
      })
      return get_tp
    },
    joinTp(arr){
      return arr.map(i => i.tp.deskripsi).join(', ');
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/nilai/deskripsi', this.form).then(response => {
        this.loading_form = false;
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          html: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
          allowOutsideClick: false,
        }).then(result => {
          this.resetForm()
        })
      })
    },
    resetData(){
      this.loading_table = true
      this.$http.post('/nilai/reset-deskripsi', this.form).then(response => {
        this.loading_table = false;
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          html: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
          allowOutsideClick: false,
        }).then(result => {
          this.handleShowForm()
        })
      })
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>