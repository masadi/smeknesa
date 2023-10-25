<template>
  <b-modal v-model="detilModalShow" title="Detil Nilai Magang" size="xl" ok-only ok-title="Tutup" ok-variant="secondary">
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
                  <b-th class="text-center">Nama</b-th>
                  <b-th class="text-center">NISN</b-th>
                  <b-th class="text-center">Tujuan Pembelajaran</b-th>
                  <b-th class="text-center">Nilai</b-th>
                  <b-th class="text-center">Deskripsi</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <template v-if="data_siswa.length">
                  <template v-for="(siswa, index) in data_siswa">
                    <b-tr>
                      <b-td class="text-center" style="vertical-align: top;" :rowspan="data_tp.length + 2">{{ index + 1 }}</b-td>
                      <b-td style="vertical-align: top;" :rowspan="data_tp.length + 2">{{ siswa.nama }}</b-td>
                      <b-td class="text-center" style="vertical-align: top;" :rowspan="data_tp.length + 2">{{ siswa.nisn }}</b-td>
                    </b-tr>
                    <template v-for="(tp, index) in data_tp" v-if="data_tp.length">
                      <b-tr>
                        <b-td>{{ tp.deskripsi }}</b-td>
                        <b-td class="text-center">
                          {{ getNilaiTp(tp.tp_id, siswa.nilai_pkl)}}
                        </b-td>
                        <b-td>
                          {{ getDeskTp(tp.tp_id, siswa.nilai_pkl)}}
                        </b-td>
                      </b-tr>
                    </template>
                    <b-tr>
                      <b-td colspan="3">
                        {{ (siswa.pd_pkl) ? siswa.pd_pkl.catatan : '-' }}
                      </b-td>
                    </b-tr>
                  </template>
                </template>
                <template v-else>
                  <b-tr>
                    <b-td class="text-center" colspan="6">Tidak ada data untuk ditampilkan</b-td>
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
      data_tp: [],
    }
  },
  created() {
    eventBus.$on('open-modal-detil-nilai-pkl', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.detil_data = data
      this.$http.post('/magang/detil-nilai', {
        pkl_id: data.pkl_id,
      }).then(response => {
        let getData = response.data
        this.data_siswa = getData.data_siswa
        this.data_tp = getData.data_tp
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
    getNilaiTp(tp_id, nilai_pkl){
      const arrByID = nilai_pkl.filter(function (el) {
        return el.tp_id == tp_id
      });
      if(arrByID.length){
        return arrByID[0].nilai
      }
      return '-'
    },
    getDeskTp(tp_id, nilai_pkl){
      const arrByID = nilai_pkl.filter(function (el) {
        return el.tp_id == tp_id
      });
      if(arrByID.length){
        return arrByID[0].deskripsi
      }
      return '-'
    },
  },
}
</script>