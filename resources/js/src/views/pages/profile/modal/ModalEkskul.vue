<template>
  <b-modal v-model="modalShow" title="Ekstrakurikuler" ok-only ok-title="Tutup" ok-variant="secondary" button-size="sm">
    <template v-if="data.data">
      <template v-if="data.data.anggota_ekskul.length">
        <b-table-simple>
          <b-thead>
            <b-tr>
              <b-th class="text-center">No</b-th>
              <b-th class="text-center">Ekstrakurikuler</b-th>
              <b-th class="text-center">Predikat</b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <template v-for="(anggota_ekskul, index) in data.data.anggota_ekskul">
              <b-tr>
                <b-td class="text-center">{{ index + 1 }}</b-td>
                <b-td>{{ anggota_ekskul.rombongan_belajar.nama }}</b-td>
                <b-td class="text-center">{{ predikat_ekstra(anggota_ekskul.nilai_ekstra_avg_angka) }}</b-td>
              </b-tr>
            </template>
          </b-tbody>
        </b-table-simple>
      </template>
    </template>
    <template v-else>
      <h3 class="text-center">Belum mengikuti kegiatan Ekstrakurikuler</h3>
    </template>
  </b-modal>
</template>

<script>
import {
  BCard, BAvatar, BButton, BTableSimple, BThead, BTbody, BTr, BTh, BTd
} from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BCard,
    BAvatar,
    BButton,
    BTableSimple, 
    BThead,
    BTbody,
    BTr,
    BTh,
    BTd
  },
  props: {
    data: {
      type: Object,
      required: true,
    },
  },
  data(){
    return {
      modalShow: false
    }
  },
  created() {
    eventBus.$on('open-modal-ekskul', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.modalShow = true
    },
    predikat_ekstra(angka){
      angka = parseInt(angka)
      var predikat = '';
      if(angka > 0 && angka < 70){
          predikat = 'Kurang';
      }
      if(angka >= 70 && angka < 80){
          predikat = 'Cukup';
      }
      if(angka >= 80 && angka < 90){
          predikat = 'Baik';
      }
      if(angka >= 90){
          predikat = 'Sangat Baik';
      }
      return predikat;
    },
  },
}
</script>
