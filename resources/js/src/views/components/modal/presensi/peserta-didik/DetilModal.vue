<template>
  <b-modal v-model="detilModalShow" :title="`Detil Presensi Siswa ${bulan_str}`" @hidden="hideModal" ok-only ok-title="Tutup" ok-variant="secondary" size="lg" scrollable>
    <BTableSimple bordered striped>
      <BThead>
        <BTr>
          <BTh class="text-center">No</BTh>
          <BTh class="text-center">Nama Siswa</BTh>
          <template v-for="absen in ['A', 'S', 'I', 'D']">
            <BTh class="text-center">{{absen}}</BTh>
          </template>
        </BTr>
      </BThead>
      <BTbody>
        <template v-for="(pd, index) in data_pd">
          <BTr>
            <BTd class="text-center">{{index + 1}}</BTd>
            <BTd>{{pd.nama}}</BTd>
            <BTd class="text-center">{{pd.A}}</BTd>
            <BTd class="text-center">{{pd.S}}</BTd>
            <BTd class="text-center">{{pd.I}}</BTd>
            <BTd class="text-center">{{pd.D}}</BTd>
          </BTr>
        </template>
      </BTbody>
    </BTableSimple>
  </b-modal>
</template>

<script>
import { BTableSimple, BThead, BTh, BTbody, BTr, BTd } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BTableSimple,
    BThead,
    BTh,
    BTbody,
    BTr,
    BTd,
  },
  data() {
    return {
      detilModalShow: false,
      bulan_str: '',
      data_pd: [],
    }
  },
  created() {
    eventBus.$on('open-modal-detil-presensi-pd', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.bulan_str = `Kelas ${data.kelas} Bulan ${data.bulan_str}`
      console.log(data);
      this.$http.post('/presensi/detil', {
        rombongan_belajar_id: data.rombongan_belajar_id,
        bulan: data.bulan,
      }).then(response => {
        let getData = response.data
        this.data_pd = getData
        this.detilModalShow = true
        //this.getGuru()
      })
    },
    hideModal(){
      this.detilModalShow = false
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>