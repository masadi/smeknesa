<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <datatable :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @bulan="handleBulan" @tanggal="handleTanggal" @aksi="handleAksi"/>
      </div>
    </b-card-body>
    <detil-modal></detil-modal>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner } from 'bootstrap-vue'
import Datatable from './Datatable.vue' //IMPORT COMPONENT DATATABLENYA
import DetilModal from '@/views/components/modal/presensi/peserta-didik/DetilWalas.vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    Datatable,
    DetilModal,
  },
  data() {
    return {
      isBusy: true,
      fields: [
        {
          key: 'nama',
          label: 'Nama Lengkap',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'nisn',
          label: 'NISN',
          sortable: true,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'A',
          label: 'a',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'S',
          label: 's',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'I',
          label: 'i',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'D',
          label: 'd',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'actions',
          label: 'Aksi',
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
      data_bulan: [],
      bulan: '',
      data_tanggal: [],
      tanggal: '',
    }
  },
  created() {
    eventBus.$on('download-rekap', this.handleEvent);
    this.bulan = this.getBulan()
    this.loadPostsData()
  },
  methods: {
    getBulan(){
      const d = new Date()
      return d.getMonth() + 1
    },
    handleEvent(){
      eventBus.$emit('loading', true);
      setTimeout(() => {
        this.alert()
      }, 2000);
      //eventBus.$emit('open-modal-presensi-pd');
    },
    alert(){
      this.$swal({
        icon: 'error',
        title: 'ERROR',
        text: 'MENUNGGU FORMAT REKAP!',
        customClass: {
          confirmButton: 'btn btn-success',
        },
        allowOutsideClick: false,
      }).then(result => {
        eventBus.$emit('loading', false);
      })
    },
    handleReload(){
      this.loadPostsData()
    },
    loadPostsData() {
      this.isBusy = true
      //let current_page = this.search == '' ? this.current_page : this.current_page != 1 ? 1 : this.current_page
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      //https://smeknesa.com/api/presensi?q=&per_page=10&page=1&sortby=nama&sortbydesc=ASC&status=false&bulan=06&data=guru&guru_id=&peserta_didik_id=&semester_id=20231&periode_aktif=2023%2F2024+Ganjil&tanggal_mulai=2023-06-01&tanggal_selesai=2023-12-31
      this.$http.get('/presensi', {
        params: {
          guru_id: this.user.guru_id,
          peserta_didik_id: this.user.peserta_didik_id,
          semester_id: this.user.semester.semester_id,
          periode_aktif: this.user.semester.nama,
          tanggal_mulai: this.user.semester.tanggal_mulai,
          tanggal_selesai: this.user.semester.tanggal_selesai,
          page: current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC',
          bulan: this.bulan,
          tanggal: this.tanggal,
          data: 'pd',
        }
      }).then(response => {
        //this.items = response.data.all_pd
        let getData = response.data.data
        this.data_bulan = response.data.data_bulan
        this.bulan = response.data.bulan
        this.data_tanggal = response.data.data_tanggal
        this.tanggal = response.data.tanggal
        this.isBusy = false
        this.items = getData.data//MAKA ASSIGN DATA POSTINGAN KE DALAM VARIABLE ITEMS
        //DAN ASSIGN INFORMASI LAINNYA KE DALAM VARIABLE META
        this.meta = {
          isData: 'pd',
          total: getData.total,
          current_page: getData.current_page,
          per_page: getData.per_page,
          from: getData.from,
          to: getData.to,
          bulan: this.bulan,
          data_bulan: this.data_bulan,
          data_tanggal: this.data_tanggal,
          tanggal: this.tanggal,
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
    handleBulan(val){
      this.bulan = val
      this.loadPostsData() //DAN LOAD DATA BARU BERDASARKAN SORT
    },
    handleTanggal(val){
      this.tanggal = val
      this.loadPostsData() //DAN LOAD DATA BARU BERDASARKAN SORT
    },
    handleAksi(val){
      console.log(this.getBulan());
      eventBus.$emit('open-modal-detil-presensi-pd', {
        peserta_didik_id: val.item.peserta_didik_id,
        nama: val.item.nama,
        bulan: this.getBulan(),
      });
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>