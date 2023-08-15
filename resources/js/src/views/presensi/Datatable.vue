<template>
  <div>
    <b-row>
      <template v-if="meta.isData === 'guru'">
        <b-col md="4" class="mb-2">
          <v-select v-model="meta.per_page" :options="[10, 25, 50, 100]" @input="loadPerPage" :clearable="false" :searchable="false"></v-select>
        </b-col>
        <b-col md="4">
          <v-select v-model="meta.bulan" :reduce="huruf => huruf.angka" label="huruf" :options="meta.data_bulan" placeholder="== Filter Bulan ==" @input="changeBulan">
            <template #no-options="{ search, searching, loading }">
              Tidak ada data untuk ditampilkan
            </template>
          </v-select>
        </b-col>
        <b-col md="4">
          <b-form-input @input="search" placeholder="Cari data..."></b-form-input>
        </b-col>
      </template>
      <template v-else>
        <b-col md="6" class="mb-2">
          <v-select v-model="meta.bulan" :reduce="huruf => huruf.angka" label="huruf" :options="meta.data_bulan" placeholder="== Filter Bulan ==" @input="changeBulan">
            <template #no-options="{ search, searching, loading }">
              Tidak ada data untuk ditampilkan
            </template>
          </v-select>
        </b-col>
        <b-col md="6">
          <v-select v-model="meta.tanggal" :options="meta.data_tanggal" placeholder="== Filter Tanggal ==" @input="changeTanggal">
            <template #no-options="{ search, searching, loading }">
              Tidak ada data untuk ditampilkan
            </template>
          </v-select>
        </b-col>
        <b-col md="6" class="mb-2">
          <v-select v-model="meta.per_page" :options="[10, 25, 50, 100]" @input="loadPerPage" :clearable="false" :searchable="false"></v-select>
        </b-col>
        <b-col md="6">
          <b-form-input @input="search" placeholder="Cari data..."></b-form-input>
        </b-col>
      </template>
    </b-row>
    <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="warning">
      <b-table bordered striped :items="items" :fields="fields" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" show-empty :busy="isBusy">
        <template #thead-top="data" v-if="meta.isData === 'guru'">
          <b-tr>
            <b-th colspan="2"><span class="sr-only">Nama dan NIP</span></b-th>
            <b-th colspan="5" class="text-center">Jumlah Absensi</b-th>
          </b-tr>
        </template>
        <template #empty="scope">
          <p class="text-center">Tidak ada data untuk ditampilkan</p>
        </template>
        <template #table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
          </div>
        </template>
        <template v-slot:cell(h)="row">
          {{row.item.H}}
        </template>
        <template v-slot:cell(a)="row">
          {{row.item.A}}
        </template>
        <template v-slot:cell(s)="row">
          {{row.item.S}}
        </template>
        <template v-slot:cell(i)="row">
          {{row.item.I}}
        </template>
        <template v-slot:cell(d)="row">
          {{row.item.D}}
        </template>
        <template v-slot:cell(wali_kelas)="row">
          {{row.item.wali_kelas.nama}}
        </template>
        <template v-slot:cell(actions)="row">
          <b-button size="sm" variant="primary" @click="aksi(row.item, 'detil')">Detil</b-button>
        </template>
      </b-table>
    </b-overlay>
    <b-row class="mt-2">
      <b-col md="6">
        <p>Menampilkan {{ (meta.from) ? meta.from : 0 }} sampai {{ meta.to }} dari {{ meta.total }} entri</p>
      </b-col>
      <b-col md="6">
        <b-pagination v-model="meta.current_page" :total-rows="meta.total" :per-page="meta.per_page" align="right" @change="changePage" aria-controls="dw-datatable"></b-pagination>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import _ from 'lodash'
import { BRow, BCol, BFormInput, BTable, BSpinner, BPagination, BOverlay, BTr, BTh, BButton} from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BRow,
    BCol,
    BFormInput,
    BTable,
    BSpinner,
    BPagination,
    BOverlay,
    BTr,
    BTh,
    BButton,
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
    isAsesor: {
      type: Boolean,
      default: () => false,
    }
  },
  data() {
    return {
      loading: false,
      loading_modal: false,
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
    changeBulan(val){
      this.$emit('bulan', val)
    },
    changeTanggal(val){
      this.$emit('tanggal', val)
    },
  },
}
</script>