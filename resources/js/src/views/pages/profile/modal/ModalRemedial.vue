<template>
  <b-modal v-model="modalShow" title="Mapel Remedial" ok-only ok-title="Tutup" ok-variant="secondary" button-size="sm" size="lg">
    <template v-if="data.data && data.data.length">
      <b-table-simple bordered striped v-if="data.data">
        <b-thead>
          <b-tr>
            <b-th class="text-center" variant="danger">Anda memiliki nilai sangat rendah di mata pelajaran berikut!</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <b-tr v-for="(item, index) in data.data" :key="item.pembelajaran_id">
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
    eventBus.$on('open-modal-remedial', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.modalShow = true
    },
  },
}
</script>
