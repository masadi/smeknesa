<template>
  <b-card>
    <alert :variant="`info`" :body="`<p>Jadikan Informasi ini sebagai bahan introspeksi diri kita!</p>`" :bodyClass="`text-center`"></alert>
    <b-table-simple bordered>
      <b-thead>
        <b-tr>
          <b-th class="text-center" rowspan="2" style="vertical-align:middle;">Mata Pelajaran</b-th>
          <b-th class="text-center" rowspan="2" style="vertical-align:middle;">Kelas</b-th>
          <b-th class="text-center" rowspan="2" style="vertical-align:middle;">Hari</b-th>
          <b-th class="text-center" rowspan="2" style="vertical-align:middle;">Jam Ke</b-th>
          <b-th class="text-center" colspan="3">Kehadiran</b-th>
        </b-tr>
        <b-tr>
          <b-th class="text-center">Alpha</b-th>
          <b-th class="text-center">Sakit</b-th>
          <b-th class="text-center">Izin</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <template v-if="data_jadwal.length">
          <template v-for="jadwal in data_jadwal">
            <b-tr>
              <b-td>{{jadwal.pembelajaran.nama_mata_pelajaran}}</b-td>
              <b-td class="text-center">{{jadwal.pembelajaran.nama_rombel}}</b-td>
              <b-td>{{jadwal.hari}}</b-td>
              <b-td class="text-center">{{getJam(jadwal.jam)}}</b-td>
              <b-td class="text-center">
                <template v-if="jadwal.alpha > 0">
                  <b-badge :variant="getBadge('A')">{{jadwal.alpha}}</b-badge>
                </template>
                <template v-else>
                  {{jadwal.alpha}}
                </template>
              </b-td>
              <b-td class="text-center">
                <template v-if="jadwal.sakit > 0">
                  <b-badge :variant="getBadge('S')">{{jadwal.sakit}}</b-badge>
                </template>
                <template v-else>
                  {{jadwal.sakit}}
                </template>
              </b-td>
              <b-td class="text-center">
                <template v-if="jadwal.izin > 0">
                  <b-badge :variant="getBadge('S')">{{jadwal.izin}}</b-badge>
                </template>
                <template v-else>
                  {{jadwal.izin}}
                </template>
              </b-td>
            </b-tr>
          </template>
        </template>
        <template v-else>
          <b-tr>
            <b-td class="text-center" colspan="4">Tidak ada data untuk ditampilkan</b-td>
          </b-tr>
        </template>
      </b-tbody>
    </b-table-simple>
  </b-card>
</template>

<script>
import { BCard, BTableSimple, BThead, BTh, BTbody, BTr, BTd, BBadge } from 'bootstrap-vue'
import Alert from '@/views/components/Alert.vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BCard,
    BTableSimple,
    BThead,
    BTh,
    BTbody,
    BTr,
    BTd,
    BBadge,
    Alert,
  },
  data(){
    return {
      data_jadwal: [],
    }
  },
  created() {
    eventBus.$on('data_jadwal', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.data_jadwal = data;
    },
    uniqueChars(arr){
      return [...new Set(arr)];
    },
    getJam(array){
      var set_jam = [];
      array.forEach(element => {
        set_jam.push(element.jam)
      });
      return this.uniqueChars(set_jam).join(', ')
    },
    filterJam(arr, jadwal_id){
      const jam = arr.filter((item) => {
        return item.jadwal_id === jadwal_id
      })
      return jam
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