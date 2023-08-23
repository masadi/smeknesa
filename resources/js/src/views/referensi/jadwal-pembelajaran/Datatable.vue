<template>
  <div>
    <b-row>
      <b-col md="4" class="mb-2">
        <v-select v-model="meta.per_page" :options="[10, 25, 50, 100]" @input="loadPerPage" :clearable="false" :searchable="false"></v-select>
      </b-col>
      <b-col md="4" offset-md="4">
        <b-form-input @input="search" placeholder="Cari data..."></b-form-input>
      </b-col>
    </b-row>
    <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="warning">
      <b-table responsive bordered striped :items="items" :fields="fields" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" show-empty :busy="isBusy" thead-tr-class="hidden_header">
        <template #empty="scope">
          <p class="text-center">Tidak ada data untuk ditampilkan</p>
        </template>
        <template #table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
          </div>
        </template>
        <template #thead-top="data">
          <b-tr>
            <b-th class="text-center">Hari</b-th>
            <b-th class="text-center">Mata Pelajaran</b-th>
            <b-th class="text-center">Kelas</b-th>
            <b-th class="text-center" colspan="11">Jam Ke</b-th>
            <b-th class="text-center">Aksi</b-th>
          </b-tr>
        </template>
        <template #cell(pembelajaran)="row">
          {{row.item.pembelajaran.nama_mata_pelajaran}}
          <br>
          <small><b-badge variant="success">{{row.item.guru.nama}}</b-badge></small>
        </template>
        <template #cell(kelas)="row">
          {{row.item.kelas.nama}}
        </template>
        <template #cell(jam_1)="row">
          {{getJam(1, row.item.jam)}}
        </template>
        <template v-slot:cell(actions)="row">
          <b-dropdown id="dropdown-dropleft" dropleft text="Detil" variant="primary" size="sm">
            <b-dropdown-item href="javascript:void(0)" @click="aksi(row.item, 'anggota')"><users-icon /> Anggota Rombel</b-dropdown-item>
            <b-dropdown-item href="javascript:void(0)" @click="aksi(row.item, 'pembelajaran')"><checklist-icon /> Pembelajaran</b-dropdown-item>
            <b-dropdown-item href="javascript:void(0)" @click="aksi(row.item, 'jadwal')"><clock-icon /> Jadwal</b-dropdown-item>
            <b-dropdown-item href="javascript:void(0)" @click="aksi(row.item, 'edit')"><pencil-icon /> Edit</b-dropdown-item>
            <b-dropdown-item href="javascript:void(0)" @click="aksi(row.item, 'hapus')"><trash-icon /> Hapus</b-dropdown-item>
          </b-dropdown>
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
import { BRow, BCol, BFormInput, BTable, BTr, BTh, BSpinner, BPagination, BDropdown, BDropdownItem, BOverlay, BBadge } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BRow,
    BCol,
    BFormInput,
    BTable,
    BTr,
    BTh,
    BSpinner,
    BPagination,
    BDropdown,
    BDropdownItem,
    BOverlay,
    BBadge,
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
    uniqueChars(arr){
      return [...new Set(arr)];
    },
    getJam(jam, array){
      var set_jam = [];
      array.forEach(element => {
        if(jam == element.jam){
          set_jam.push(element.jam)
        }
      });
      return this.uniqueChars(set_jam).join(', ')
    },
  },
}
</script>
<style lang="scss">
.hidden_header {
  display: none;
}
</style>