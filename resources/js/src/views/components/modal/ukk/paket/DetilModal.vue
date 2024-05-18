<template>
  <b-modal v-model="detilModalShow" title="Detil Data Paket UKK" size="xl" ok-only ok-variant="secondary" ok-text="Tutup">
    <BTableSimple v-if="detil" bordered>
      <BTr>
        <BTd>Kompetensi Keahlian</BTd>
        <BTd> {{detil.jurusan_sp.nama_jurusan_sp}}</BTd>
      </BTr>
      <BTr>
        <BTd>Kurikulum</BTd>
        <BTd> {{detil.kurikulum.nama}}</BTd>
      </BTr>
      <BTr>
        <BTd>Nomor Paket</BTd>
        <BTd> {{detil.nomor_paket}}</BTd>
      </BTr>
      <BTr>
        <BTd>Judul Paket (ID)</BTd>
        <BTd> {{detil.nama_paket_id}}</BTd>
      </BTr>
      <BTr>
        <BTd>Judul Paket (EN)</BTd>
        <BTd> {{detil.nama_paket_en}}</BTd>
      </BTr>
      <BTr>
        <BTd>Status</BTd>
        <BTd>
          <b-badge variant="success" v-if="detil.status">Aktif</b-badge>
          <b-badge variant="danger" v-else>Tidak Aktif</b-badge>
        </BTd>
      </BTr>
      <BTr>
        <BTd>Unit UKK ({{ detil.unit_ukk.length }})</BTd>
        <BTd>
          <BTableSimple v-if="detil" bordered>
            <BThead>
              <BTr>
                <BTh>Kode Unit</BTh>
                <BTh>Nama Unit Kompetensi</BTh>
              </BTr>
            </BThead>
            <BTbody>
              <template v-for="item in detil.unit_ukk">
                <BTr>
                  <BTd>{{item.kode_unit}}</BTd>
                  <BTd>{{ item.nama_unit }}</BTd>
                </BTr>
              </template>
            </BTbody>
          </BTableSimple>
        </BTd>
      </BTr>
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
    eventBus.$on('open-modal-detil-paket-ukk', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      //this.detil = data
      //this.detilModalShow = true
      this.$http.post('/ukk/get-paket', {
        paket_ukk_id: data.paket_ukk_id
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
          this.detil = getData
          this.detilModalShow = true
        }
      })
    },
  },
}
</script>