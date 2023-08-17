<template>
  <b-modal v-model="detilModalShow" :title="`Detil Presensi Siswa ${nama_siswa}`" ok-only ok-title="Tutup" ok-variant="secondary" size="xl" scrollable>
    <BTableSimple bordered striped>
      <BThead>
        <BTr>
          <BTh class="text-center">No</BTh>
          <BTh class="text-center">Hari, Tanggal</BTh>
          <template v-for="jam in data_jam">
            <BTh class="text-center">{{jam}}</BTh>
          </template>
        </BTr>
      </BThead>
      <BTbody>
        <template v-for="(tanggal, index) in data_tanggal">
          <BTr>
            <BTd class="text-center">{{index + 1}}</BTd>
            <BTd class="text-center">{{tanggal.str}}</BTd>
            <template v-for="jam in data_jam">
              <BTd class="text-center"><b-badge :variant="getBadge(filterJam(filterTanggal(rekapitulasi, tanggal.date), jam))">{{filterJam(filterTanggal(rekapitulasi, tanggal.date), jam)}}</b-badge></BTd>
            </template>
          </BTr>
        </template>
      </BTbody>
    </BTableSimple>
  </b-modal>
</template>

<script>
import { BTableSimple, BThead, BTh, BTbody, BTr, BTd, BBadge } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BTableSimple,
    BThead,
    BTh,
    BTbody,
    BTr,
    BTd,
    BBadge,
  },
  data() {
    return {
      detilModalShow: false,
      nama_siswa: '',
      data_bulan: [],
      data_tanggal: [],
      data_jam: 0,
      rekapitulasi: [],
    }
  },
  created() {
    eventBus.$on('open-modal-detil-presensi-pd', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.nama_siswa = data.nama
      console.log(data);
      this.$http.post('/presensi/detil', {
        data: 'pd',
        peserta_didik_id: data.peserta_didik_id,
        bulan: data.bulan,
      }).then(response => {
        let getData = response.data
        this.data_bulan = getData.data_bulan
        this.data_tanggal = getData.data_tanggal
        this.data_jam = getData.data_jam
        this.rekapitulasi = getData.pd.presensi
        this.detilModalShow = true
        //this.getGuru()
      })
    },
    filterTanggal(arr, tanggal){
      const presensi = arr.filter((item) => {
        return item.tanggal === tanggal
      })
      return presensi
    },
    filterJam(arr, jam){
      const presensi = arr.filter((item) => {
        return item.jam === jam
      })
      //return presensi
      return presensi[0]?.absen
    },
    getBadge(huruf){
      var data = {
        H: 'light',
        A: 'danger',
        D: 'warning',
        I: 'info',
        S: 'success',
      }
      return data[huruf]
    }
  },
}
</script>