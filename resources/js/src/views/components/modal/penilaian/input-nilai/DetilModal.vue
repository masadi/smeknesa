<template>
  <b-modal size="lg" v-model="detilModalShow" :title="title" ok-only ok-title="Tutup" ok-variant="secondary" scrollable>
    <b-table-simple>
      <b-thead>
        <b-tr>
          <b-th class="text-center">No</b-th>
          <b-th class="text-center">Nama Peserta Didik</b-th>
          <b-th class="text-center">{{ nama_asesmen }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <template v-for="(siswa, index) in data_siswa">
          <b-tr>
            <b-td class="text-center">{{index + 1}}</b-td>
            <b-td>{{ siswa.nama }}</b-td>
            <b-td class="text-center">
              {{ nilai[siswa.anggota_rombel.anggota_rombel_id]}}
            </b-td>
          </b-tr>
        </template>
      </b-tbody>
    </b-table-simple>
  </b-modal>
</template>

<script>
import { BTableSimple, BThead, BTbody, BTr, BTh, BTd } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BTableSimple,
    BThead,
    BTbody,
    BTr,
    BTh,
    BTd,
  },
  data() {
    return {
      detilModalShow: false,
      detil: null,
      title: null,
      nama_asesmen: null,
      data_siswa: [],
      nilai: {},
    }
  },
  created() {
    eventBus.$on('open-modal-detil-nilai', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.$emit('loading', true)
      this.$http.post('/nilai/detil-nilai', {
        id: data.penilaian_id,
      }).then(response => {
        this.$emit('loading', false)
        let getData = response.data
        this.title = `Detil Penilaian ${getData.penilaian.jenis_penilaian.nama}`
        this.data_siswa = getData.data_siswa
        var _this = this
        this.data_siswa.forEach(item => {
          _this.nilai[item.anggota_rombel.anggota_rombel_id] = (item.anggota_rombel.nilai) ? item.anggota_rombel.nilai.angka : null
        });
        this.nama_asesmen = (getData.tp) ? getData.tp.deskripsi : getData.penilaian.nama
        this.detilModalShow = true
      });
    },
  },
}
</script>