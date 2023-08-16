<template>
  <b-modal size="lg" v-model="detilModalShow" title="Detil Data CP & TP" ok-only ok-title="Tutup" ok-variant="secondary" scrollable>
    <b-table-simple v-if="detil">
      <b-tr>
        <b-td>Capaian Pembelajaran</b-td>
        <b-td>{{detil.deskripsi}}</b-td>
      </b-tr>
      <b-tr>
        <b-td class="text-center" colspan="2">Tujuan Pembelajaran</b-td>
      </b-tr>
      <template v-for="tp in detil.tp">
        <b-tr>
          <b-td colspan="2">{{tp.deskripsi}}</b-td>
        </b-tr>
      </template>
    </b-table-simple>
  </b-modal>
</template>

<script>
import { BTableSimple, BTr, BTd } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BTableSimple,
    BTr,
    BTd,
  },
  data() {
    return {
      detilModalShow: false,
      detil: null,
    }
  },
  created() {
    eventBus.$on('open-modal-detil-cp', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.$http.post('/nilai/detil-data', {
        data: 'cp',
        id: data.cp_id,
      }).then(response => {
        let getData = response.data
        this.detil = getData.data
        this.detilModalShow = true
      });
    },
  },
}
</script>