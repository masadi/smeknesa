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
            <BTd class="text-center">
              <template v-if="pd.A > 0">
                <b-badge :variant="getBadge('A')">{{pd.A}}</b-badge>
              </template>
              <template v-else>
                {{pd.A}}
              </template>
            </BTd>
            <BTd class="text-center">
              <template v-if="pd.S > 0">
                <b-badge :variant="getBadge('S')">{{pd.S}}</b-badge>
              </template>
              <template v-else>
                {{pd.S}}
              </template>
            </BTd>
            <BTd class="text-center">
              <template v-if="pd.I > 0">
                <b-badge :variant="getBadge('I')">{{pd.I}}</b-badge>
              </template>
              <template v-else>
                {{pd.I}}
              </template>
            </BTd>
            <BTd class="text-center">
              <template v-if="pd.D > 0">
                <b-badge :variant="getBadge('D')">{{pd.D}}</b-badge>
              </template>
              <template v-else>
                {{pd.D}}
              </template>
            </BTd>
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
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>