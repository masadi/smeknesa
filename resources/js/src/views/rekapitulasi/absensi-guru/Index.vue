<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <datatable :loading="loading" :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" />
      </div>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner } from 'bootstrap-vue'
import Datatable from './Datatable.vue' 
import eventBus from '@core/utils/eventBus'
import moment from 'moment'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    Datatable,
  },
  data() {
    return {
      loading: false,
      isBusy: true,
      fields: [
        {
          key: 'nama',
          label: 'Nama Lengkap',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'alpha',
          label: 'alpha',
          sortable: true,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'izin',
          label: 'izin',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'sakit',
          label: 'sakit',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
      ],
      items: [],
      meta: {},
      current_page: 1, //DEFAULT PAGE YANG AKTIF ADA PAGE 1
      per_page: 10, //DEFAULT LOAD PERPAGE ADALAH 10
      search: '',
      sortBy: 'nama', //DEFAULT SORTNYA ADALAH CREATED_AT
      sortByDesc: false, //ASCEDING
    }
  },
  created() {
    moment.locale('id');
    console.log(moment.months());
    console.log(moment.monthsShort());
    eventBus.$on('download-rekap-guru', this.handleEvent);
    this.loadPostsData()
  },
  methods: {
    getBulan(){
      const d = new Date()
      return d.getMonth() + 1
    },
    handleEvent(){
      console.log(this.getBulan());
      this.$swal({
        icon: 'question',
        title: 'Pilih Bulan',
        input: 'select',
        inputOptions: Object.assign({}, moment.months()),
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
              resolve('Bulan tidak kosong!')
            }
          })
        },
      }).then(result => {
        if(result.isConfirmed){
          window.open(`/export/rekap-absensi-guru/${result.value}`, '_blank'); 
        }
      })
    },
    loadPostsData() {
      this.loading = true
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      this.$http.get('/rekapitulasi/absen-guru', {
        params: {
          page: current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC',
        }
      }).then(response => {
        let getData = response.data.data
        this.loading = false
        this.isBusy = false
        this.items = getData.data
        this.meta = {
          total: getData.total,
          current_page: getData.current_page,
          per_page: getData.per_page,
          from: getData.from,
          to: getData.to,
        }
      })
    },
    //JIKA ADA EMIT TERKAIT LOAD PERPAGE, MAKA FUNGSI INI AKAN DIJALANKAN
    handlePerPage(val) {
      this.per_page = val //SET PER_PAGE DENGAN VALUE YANG DIKIRIM DARI EMIT
      this.loadPostsData() //DAN REQUEST DATA BARU KE SERVER
    },
    //JIKA ADA EMIT PAGINATION YANG DIKIRIM, MAKA FUNGSI INI AKAN DIEKSEKUSI
    handlePagination(val) {
      this.current_page = val //SET CURRENT PAGE YANG AKTIF
      this.loadPostsData()
    },
    //JIKA ADA DATA PENCARIAN
    handleSearch(val) {
      this.search = val //SET VALUE PENCARIAN KE VARIABLE SEARCH
      this.loadPostsData() //REQUEST DATA BARU
    },
    //JIKA ADA EMIT SORT
    handleSort(val) {
      if (val.sortBy) {
        this.sortBy = val.sortBy
        this.sortByDesc = val.sortDesc
        this.loadPostsData()
      }
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
.swal2-select {width: 100% !important;}
</style>