<template>
  <div>
    <b-row>
      <b-col md="4" class="mb-2">
        <v-select v-model="meta.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" @input="changeTingkat" placeholder="== Filter Tingkat Kelas ==" :searchable="false"></v-select>
      </b-col>
      <b-col md="4">
        <v-select v-model="meta.jurusan_sp_id" :reduce="nama_jurusan_sp => nama_jurusan_sp.jurusan_sp_id" label="nama_jurusan_sp" :options="data_jurusan" @input="changeJurusan" placeholder="== Pilih Jurusan =="></v-select>
      </b-col>
      <b-col md="4">
        <v-select v-model="meta.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" @input="changeRombel" placeholder="== Pilih Kelas =="></v-select>
      </b-col>
    </b-row>
    <b-row>
      <b-col md="4" class="mb-2">
        <v-select v-model="meta.per_page" :options="[10, 25, 50, 100]" @input="loadPerPage" :clearable="false" :searchable="false"></v-select>
      </b-col>
      <b-col md="4" offset-md="4">
        <b-form-input @input="search" placeholder="Cari data..."></b-form-input>
      </b-col>
    </b-row>
    <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="warning">
      <b-table bordered striped :items="items" :fields="fields" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" show-empty :busy="isBusy">
        <template #empty="scope">
          <p class="text-center">Tidak ada data untuk ditampilkan</p>
        </template>
        <template #table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
          </div>
        </template>
        <template v-slot:cell(mapel)="row">
          {{row.item.pembelajaran.nama_mata_pelajaran}}
        </template>
        <template v-slot:cell(actions)="row">
          <template v-if="hasRole('administrator')">
            <b-dropdown id="dropdown-dropleft" dropleft text="Detil" variant="primary" size="sm">
              <b-dropdown-item href="javascript:void(0)" @click="aksi(row.item, 'detil')"><eye-icon /> Detil</b-dropdown-item>
              <b-dropdown-item href="javascript:void(0)" @click="aksi(row.item, 'edit')"><pencil-icon />Edit</b-dropdown-item>
              <b-dropdown-item href="javascript:void(0)" @click="aksi(row.item, 'hapus')"><trash-icon />Hapus</b-dropdown-item>
            </b-dropdown>
          </template>
          <template v-else>
            <b-button variant="primary" size="sm" @click="aksi(row.item, 'detil')">Detil</b-button>
          </template>
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
import { BRow, BCol, BFormInput, BTable, BSpinner, BPagination, BDropdown, BDropdownItem, BOverlay, BMedia, BMediaAside, BAvatar, BMediaBody, BButton } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BRow,
    BCol,
    BFormInput,
    BTable,
    BSpinner,
    BPagination,
    BDropdown,
    BDropdownItem,
    BOverlay,
    BMedia,
    BMediaAside,
    BAvatar,
    BMediaBody,
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
    data_jurusan: {
      type: Array,
      required: true
    },
    data_rombel: {
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
    loading: {
      type: Boolean,
      default: () => false,
    },
  },
  data() {
    return {
      sortBy: null,
      sortDesc: false,
      data_tingkat: [
        {
          id: 10,
          nama: 'Tingkat 10',
        },
        {
          id: 11,
          nama: 'Tingkat 11',
        },
        {
          id: 12,
          nama: 'Tingkat 12',
        },
        {
          id: 13,
          nama: 'Tingkat 13',
        }
      ],
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
    changeTingkat(val){
      this.$emit('tingkat', this.meta.tingkat)
    },
    changeJurusan(val){
      this.$emit('jurusan', this.meta.jurusan_sp_id)
    },
    changeRombel(val){
      this.$emit('kelas', this.meta.rombongan_belajar_id)
    },
  },
}
</script>