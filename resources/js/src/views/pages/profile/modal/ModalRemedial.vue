<template>
  <b-modal v-model="modalShow" title="Mapel Remedial" ok-only ok-title="Tutup" ok-variant="secondary" button-size="sm" size="lg">
    <template v-if="data.length">
      <b-button squared block variant="success" class="mb-2" @click="unduhRemedial">Unduh Formulir Remedial</b-button>
      <b-table-simple bordered striped>
        <b-thead>
          <b-tr>
            <b-th class="text-center" variant="danger">Anda memiliki nilai sangat rendah di mata pelajaran berikut!</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <b-tr v-for="(item, index) in data" :key="item.pembelajaran_id">
            <b-td>{{item.nama_mata_pelajaran}}</b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>
    </template>
    <template v-else>
      <b-alert variant="success" show>
        <h4 class="alert-heading">Selamat!</h4>
        <div class="alert-body">
          <span>Anda tidak memiliki nilai sangat rendah.</span>
        </div>
      </b-alert>
    </template>
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
  BAlert,
  BButton,
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
    BAlert,
    BButton,
  },
  data(){
    return {
      modalShow: false,
      data: [],
    }
  },
  created() {
    eventBus.$on('open-modal-remedial', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.data = data.data
      this.modalShow = true
    },
    unduhRemedial(){
      console.log(this.user);
      window.open(`/cetak/rekap-remedial/${this.user.peserta_didik_id}`, '_blank');
    }
  },
}
</script>
