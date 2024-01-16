<template>
  <b-modal v-model="modalShow" title="Update Presensi" ok-only ok-title="Tutup" ok-variant="secondary" button-size="sm">
    <b-table-simple bordered striped v-if="data">
      <b-thead>
        <b-tr>
          <b-th class="text-center" colspan="2">Rekap Presensi</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr>
          <b-td>Alpha</b-td>
          <b-td class="text-center">{{data.alpha}}</b-td>
        </b-tr>
        <b-tr>
          <b-td>Izin</b-td>
          <b-td class="text-center">{{data.izin}}</b-td>
        </b-tr>
        <b-tr>
          <b-td>Sakit</b-td>
          <b-td class="text-center">{{data.sakit}}</b-td>
        </b-tr>
        <b-tr>
          <b-td>Dispen</b-td>
          <b-td class="text-center">{{data.dispen}}</b-td>
        </b-tr>
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
      data: null,
    }
  },
  created() {
    eventBus.$on('open-modal-presensi', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.data = data.data
      this.modalShow = true
    },
  },
}
</script>
