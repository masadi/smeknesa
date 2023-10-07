<template>
  <b-modal size="lg" v-model="detilModalShow" title="Detil Data CP & TP" ok-only ok-title="Tutup" ok-variant="secondary" scrollable>
    <b-table-simple v-if="detil">
      <b-tr variant="success">
        <b-td>Capaian Pembelajaran</b-td>
        <b-td>{{detil.deskripsi}}</b-td>
      </b-tr>
    </b-table-simple>
    <b-table-simple v-if="detil">
      <b-thead>
        <b-tr>
          <b-th class="text-center">Tujuan Pembelajaran</b-th>
          <b-th class="text-center">Aksi</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <template v-for="tp in detil.tp">
          <b-tr>
            <b-td>{{ tp.deskripsi }}</b-td>
            <b-td class="text-center">
              <b-dropdown id="dropdown-dropleft" dropleft text="Aksi" variant="primary" size="sm">
                <b-dropdown-item href="javascript:void(0)" @click="aksi(tp, 'edit')"><pencil-icon />Edit</b-dropdown-item>
                <b-dropdown-item href="javascript:void(0)" @click="aksi(tp, 'hapus')"><trash-icon />Hapus</b-dropdown-item>
              </b-dropdown>
            </b-td>
          </b-tr>
        </template>
      </b-tbody>
    </b-table-simple>
  </b-modal>
</template>

<script>
import { BTableSimple, BThead, BTbody, BTr, BTh, BTd, BDropdown, BDropdownItem } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BTableSimple,
    BThead,
    BTbody,
    BTr,
    BTh,
    BTd,
    BDropdown, 
    BDropdownItem,
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
    aksi(item, aksi){
      this.$emit('aksi-tp', {
        aksi: aksi,
        item: item,
      })
    },
  },
}
</script>