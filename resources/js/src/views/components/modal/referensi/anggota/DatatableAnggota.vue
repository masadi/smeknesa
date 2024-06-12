<template>
  <b-row>
    <b-col cols="12" md="6" class="mb-2">
      <v-select v-model="per_page" :options="[10, 25, 50, 100]" @input="loadPerPage" :clearable="false" :searchable="false"></v-select>
    </b-col>
    <b-col cols="12" md="6" class="mb-2">
      <b-form-input @input="search" v-model="cari" placeholder="Cari data..."></b-form-input>
    </b-col>
  </b-row>
  <b-table responsive bordered striped :items="items" :fields="fields" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" show-empty :busy="isBusy">
    <template #empty="scope">
      <p class="text-center">Tidak ada data untuk ditampilkan</p>
    </template>
    <template #table-busy>
      <div class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
    </template>
    <template v-slot:cell(keluarkan)="row">
      <b-button size="sm" variant="danger" @click="keluarkan(row.item.peserta_didik_id)">Keluarkan</b-button>
    </template>
  </b-table>
  <b-row class="mt-2">
    <b-col md="6">
      <p>Menampilkan {{ (from) ? from : 0 }} sampai {{ to }} dari {{ total }} entri</p>
    </b-col>
    <b-col md="6">
      <b-pagination v-model="current_page" :total-rows="total" :per-page="per_page" align="right" @change="changePage" aria-controls="dw-datatable"></b-pagination>
    </b-col>
  </b-row>
</template>

<script>
import { BRow, BCol, BTable, BPagination, BSpinner, BButton, BOverlay, BFormInput } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BRow,
    BCol,
    BTable,
    BPagination,
    BSpinner,
    BButton,
    BOverlay,
    BFormInput,
    vSelect,
  },
  props: {
    items: {
      type: Array,
      required: true
    },
    fields: {
      type: Array,
      required: true
    },
    meta: {
      required: true
    },
    isBusy: {
      type: Boolean,
      default: () => true,
    },
  },
  data() {
    return {
      sortBy: null,
      sortDesc: false,
    }
  },
  watch: {
    sortBy(val) {
      this.$emit('sort', {
        sortBy: this.sortBy,
        sortDesc: this.sortDesc
      })
    },
    sortDesc(val) {
      this.$emit('sort', {
        sortBy: this.sortBy,
        sortDesc: this.sortDesc
      })
    }
  },
  methods: {
    aksi(item, aksi){
      this.$emit('aksi', {
        aksi: aksi,
        item: item,
      })
    },
    loadPerPage(val) {
      this.$emit('per_page', this.meta.per_page)
    },
    changePage(val) {
      this.$emit('pagination', val)
    },
    search: _.debounce(function (e) {
      this.$emit('search', e)
    }, 500),
  },
}
</script>