<template>
  <b-modal v-model="detilModalShow" title="Detil Data Pembimbing" size="xl" ok-only ok-title="Tutup" ok-variant="secondary">
    <b-table-simple bordered striped v-if="detil_data">
      <b-tbody>
        <b-tr>
          <b-td>Nama Pembimbing</b-td>
          <b-td>{{ detil_data.guru.nama }}</b-td>
        </b-tr>
        <b-tr>
          <b-td>Nama DUDI</b-td>
          <b-td>{{ detil_data.dudi.nama }}</b-td>
        </b-tr>
        <b-tr>
          <b-td>Nama Instruktur</b-td>
          <b-td>{{ detil_data.instruktur }}</b-td>
        </b-tr>
        <b-tr>
          <b-td>Tanggal Mulai</b-td>
          <b-td>{{ detil_data.tgl_mulai }}</b-td>
        </b-tr>
        <b-tr>
          <b-td>Tanggal Selesai</b-td>
          <b-td>{{ detil_data.tgl_selesai }}</b-td>
        </b-tr>
        <b-tr>
          <b-td style="vertical-align: top;">Anggota Magang</b-td>
          <b-td>
            <b-table-simple bordered striped>
              <b-thead>
                <b-tr>
                  <b-th class="text-center">No</b-th>
                  <b-th>Nama</b-th>
                  <b-th class="text-center">NISN</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <template v-if="data_siswa.length">
                  <b-tr v-for="(siswa, index) in data_siswa" :key="siswa.peserta_didik_id">
                    <b-td class="text-center">{{ index + 1 }}</b-td>
                    <b-td>{{ siswa.nama }}</b-td>
                    <b-td class="text-center">{{ siswa.nisn }}</b-td>
                  </b-tr>
                </template>
                <template v-else>
                  <b-tr>
                    <b-td class="text-center" colspan="3">Tidak ada data untuk ditampilkan</b-td>
                  </b-tr>
                </template>
              </b-tbody>
            </b-table-simple>
          </b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>
  </b-modal>
</template>

<script>
import { BTableSimple, BThead, BTbody, BTr, BTh, BTd } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BTableSimple, BThead, BTbody, BTr, BTh, BTd,
  },
  data() {
    return {
      detilModalShow: false,
      detil_data: null,
      data_siswa: [],
    }
  },
  created() {
    eventBus.$on('open-modal-detil-pembimbing', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.detil_data = data
      this.$http.post('/magang/detil', {
        pkl_id: data.pkl_id,
      }).then(response => {
        let getData = response.data
        this.data_siswa = getData.data_siswa
        this.$emit('stop_loading')
        this.detilModalShow = true
      })
    },
    hideModal(){
      this.detilModalShow = false
      this.resetForm()
    },
    resetForm(){
      console.log('resetForm');
    },
  },
}
</script>