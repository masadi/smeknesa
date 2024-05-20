<template>
  <b-modal v-model="detilModalShow" title="Detil Data Paket UKK" size="xl" ok-only ok-variant="secondary" ok-text="Tutup">
    <BTableSimple v-if="detil" bordered>
      <BTr>
        <BTd>Paket UKK</BTd>
        <BTd> {{detil.paket_ukk.nama_paket_id}}</BTd>
      </BTr>
      <BTr>
        <BTd>Penguji Internal</BTd>
        <BTd> {{(detil.guru_internal) ? detil.guru_internal.nama : ''}}</BTd>
      </BTr>
      <BTr>
        <BTd>Penguji Eksternal</BTd>
        <BTd> {{(detil.asesor) ? detil.asesor.nama : ''}}</BTd>
      </BTr>
    </BTableSimple>
    <BTableSimple class="mt-2" v-if="detil" bordered>
      <BThead>
        <BTr>
          <BTh class="text-center">No</BTh>
          <BTh class="text-center">Nama Peserta Didik</BTh>
          <BTh class="text-center">NISN</BTh>
          <BTh class="text-center">Nilai</BTh>
          <BTh class="text-center">Kesimpulan</BTh>
        </BTr>
      </BThead>
      <BTbody>
        <template v-for="(item, index) in detil.pd">
          <BTr>
            <BTd class="text-center">{{index + 1}}</BTd>
            <BTd>{{ item.nama }}</BTd>
            <BTd class="text-center">{{ item.nisn }}</BTd>
            <BTd class="text-center">
              <template v-if="item.nilai_ukk">{{ item.nilai_ukk.nilai }}</template>
            </BTd>
            <BTd class="text-center">
              {{ kesimpulan_ukk(item) }}
            </BTd>
          </BTr>
        </template>
      </BTbody>
    </BTableSimple>
  </b-modal>
</template>

<script>
import { BTableSimple, BThead, BTbody, BTr, BTh, BTd, BBadge } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BTableSimple, BThead, BTbody, BTr, BTh, BTd, BBadge
  },
  data() {
    return {
      detilModalShow: false,
      detil: null,
    }
  },
  created() {
    eventBus.$on('open-modal-detil-rencana-ukk', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.$http.post('/ukk/rencana/show', {
        id: data.rencana_ukk_id
      }).then(response => {
        let getData = response.data
        if(getData.errors){
          this.$swal({
            icon: 'error',
            title: 'Error',
            text: getData.errors,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
        } else {
          this.detil = getData.rencana_ukk
          this.detilModalShow = true
        }
      })
    },
    kesimpulan_ukk(item){
      var predikat = ''
      if(item.nilai_ukk && item.nilai_ukk.nilai){
        var nilai = item.nilai_ukk.nilai
        if (nilai >= 90) {
            predikat = 'Sangat Kompeten';
        } else if (nilai >= 75 && nilai <= 89) {
            predikat = 'Kompeten';
        } else if (nilai >= 70 && nilai <= 74) {
            predikat = 'Cukup Kompeten';
        } else if (nilai < 70) {
            predikat = 'Belum Kompeten';
        }
      }
      return predikat;
    },
    generate_link(item){
      var link_cetak = null
      if(item.nilai_ukk.nilai){
        link_cetak = `/cetak/sertifikat/${item.nilai_ukk.anggota_rombel_id}/${item.nilai_ukk.rencana_ukk_id}`
      }
      return link_cetak
    },
  },
}
</script>