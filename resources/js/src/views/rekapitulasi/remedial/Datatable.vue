<template>
  <div>
    <b-row>
      <b-col md="6" class="mb-2">
        <v-select v-model="meta.per_page" :options="[10, 25, 50, 100]" @input="loadPerPage" :clearable="false" :searchable="false"></v-select>
      </b-col>
      <b-col md="6">
        <b-form-input @input="search" placeholder="Cari data..."></b-form-input>
      </b-col>
    </b-row>
    <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="warning">
      <b-table responsive bordered striped :items="items" :fields="fields" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" show-empty :busy="isBusy">
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
        <template v-slot:cell(no)="row">
          {{row.index + meta.current_page}}
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
import { BRow, BCol, BFormInput, BTable, BSpinner, BPagination, BOverlay, BTr, BTh, BButton, BFormDatepicker, BBadge } from 'bootstrap-vue'
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
    BFormDatepicker,
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
      loading_tombol: false,
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
    onContext(ctx) {
      this.formatted = ctx.selectedFormatted
    },
    getBadge(huruf){
      var data = {
        H: 'light',
        A: 'danger',
        D: 'warning',
        I: 'info',
        S: 'success',
      }
      return data[huruf]
    },
    downloadRekap(){
      this.loading_tombol = true
      this.$swal({
        icon: 'question',
        title: 'Pilih Jenis Rekap',
        input: 'select',
        inputOptions: {
          1: 'Per Bulan',
          2: 'Per Semester',
        },
        customClass: {
          confirmButton: 'btn btn-success mr-1',
        },
        showCancelButton: true,
        cancelButtonText: 'Batal',
        confirmButtonText: 'Download',
        allowOutsideClick: false,
        inputValidator: (value) => {
          return new Promise((resolve) => {
            if (value) {
              resolve()
            } else {
              resolve('Jenis Rekap tidak kosong!')
            }
          })
        },
      }).then(result => {
        this.loading_tombol = false
        window.open(`/export/rekap-absensi-siswa/${this.user.semester.semester_id}/${this.meta.rombongan_belajar_id}/${this.meta.bulan}/${result.value}`, '_blank'); 
        //console.log(getData);
        console.log(result.value);
      })
    },
    fetchOptions: _.debounce(function (search, loading){
      if(search){
        this.$http.post('/referensi/get-rombel', {
          nama: search,
        }).then(response => {
          this.meta.data_rombel = response.data
          console.log(response.data);
        });
      }
    }, 500),
    handleSelected(val){
      this.$emit('rombel', {
        rombongan_belajar_id: val.rombongan_belajar_id,
        data_rombel: this.meta.data_rombel,
      })
    },
    handleInput(val){
      if(!val){
        this.$emit('rombel', {
          rombongan_belajar_id: '',
          data_rombel: [],
        })
      }
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
.swal2-select {width: 100% !important;}
</style>