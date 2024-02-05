<template>
  <b-modal v-model="modalShow" title="Informasi Akademik" ok-only ok-title="Tutup" ok-variant="secondary" button-size="sm" size="lg">
    <b-table-simple bordered striped>
      <b-thead>
        <b-tr>
          <b-th class="text-center" rowspan="2" style="vertical-align:middle">Mata Pelajaran</b-th>
          <b-th class="text-center" colspan="2">Nilai Akhir</b-th>
        </b-tr>
        <b-tr>
          <b-th class="text-center">Formatif</b-th>
          <b-th class="text-center">Sumatif</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <template v-if="data.length">
          <b-tr v-for="item in data" :key="item.pembelajaran_id">
            <b-td>{{item.nama_mata_pelajaran}}</b-td>
            <b-td class="text-center">{{(item.nilai_formatif) ? parseInt(item.nilai_formatif) : ''}}</b-td>
            <b-td class="text-center">{{(item.nilai_sumatif) ? parseInt(item.nilai_sumatif) : ''}}</b-td>
          </b-tr>
          <b-tr variant="success">
            <b-td class="text-center" colspan="3"><strong>Rangking ke {{ rangking }}</strong></b-td>
          </b-tr>
        </template>
        <template v-else>
          <b-tr>
            <b-td class="text-center">Tidak ada data untuk ditampilkan</b-td>
          </b-tr>
        </template>
      </b-tbody>
    </b-table-simple>
  </b-modal>
</template>

<script>
import {
  BTableSimple,
  BTbody,
  BThead,
  BTr,
  BTd,
  BTh,
} from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BTableSimple,
    BTbody,
    BThead,
    BTr,
    BTd,
    BTh,
  },
  data(){
    return {
      modalShow: false,
      data: [],
      rangking: '',
    }
  },
  created() {
    eventBus.$on('open-modal-akademik', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.data = data.data
      this.rangking = data.pd.rangking??'-'
      this.modalShow = true
    },
  },
}
</script>
