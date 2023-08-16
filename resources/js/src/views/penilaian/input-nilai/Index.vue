<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <alert :variant="`warning`" :title="`Informasi Batas Waktu Penilaian`" :body="`<p>Penilaian dibuka sampai tanggal ${tanggal_deadline}</p>`" v-if="deadline"></alert>
        <alert :variant="`danger`" :title="`Informasi Batas Waktu Penilaian`" :body="`<p>Waktu entri nilai sudah habis</p>`" v-else></alert>
        <datatable :isBusy="isBusy" :loading="loading" :items="items" :fields="fields" :meta="meta" :data_jurusan="data_jurusan" :data_rombel="data_rombel" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @aksi="handleAksi" @tingkat="handleTingkat" @jurusan="handleJurusan" @kelas="handleKelas" />
      </div>
    </b-card-body>
    <add-modal @reload="handleReload"></add-modal>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BAlert } from 'bootstrap-vue'
import Datatable from './Datatable.vue' //IMPORT COMPONENT DATATABLENYA
import AddModal from './../../components/modal/penilaian/input-nilai/AddModal.vue'
import Alert from './../../components/Alert.vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BAlert,
    Datatable,
    AddModal,
    Alert,
  },
  data() {
    return {
      deadline: false,
      tanggal_deadline: '',
      isBusy: true,
      loading: false,
      fields: [
        {
          key: 'nama_mata_pelajaran',
          label: 'Mata Pelajaran',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'cp_count',
          label: 'Jumlah CP',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'anggota_rombel_count',
          label: 'Siswa Dinilai',
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
      sortBy: 'updated_at', //DEFAULT SORTNYA ADALAH CREATED_AT
      sortByDesc: true, //ASCEDING
      tingkat: '',
      jurusan_sp_id: '',
      rombongan_belajar_id: '',
      data_jurusan: [],
      data_rombel: [],
    }
  },
  created() {
    eventBus.$on('input-nilai', this.handleEvent);
    this.loadPengaturan()
    this.loadPostsData()
  },
  methods: {
    handleEvent(){
      eventBus.$emit('open-modal-input-nilai');
    },
    handleReload(){
      this.loadPostsData()
    },
    loadPengaturan(){
      this.$http.get('/pengaturan/umum').then(response => {
        let getData = response.data
        let selisih = this.dateDiff(getData.tanggal_penilaian)
        this.deadline = this.isNotGreaterThanZero(selisih)
        if(!this.deadline){
          eventBus.$emit('hide-button');
        }
        this.tanggal_deadline = getData.deadline
        console.log(getData);
      })
    },
    dateDiff(deadline){
      if(deadline){
        var d = new Date(),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
        if (month.length < 2) 
          month = '0' + month;
        if (day.length < 2) 
          day = '0' + day;
        var tgl_hari_ini = [year, month, day].join('-')
        var a = moment(tgl_hari_ini);
        var b = moment(deadline);
        var result = a.diff(b, 'days') // 1
        return Number.parseInt(result)
      }
      return 0
    },
    isNotGreaterThanZero(num){
      return num <= 0;
    },
    loadPostsData() {
      let current_page = this.current_page
      this.$http.get('/nilai', {
        params: {
          tingkat: this.tingkat,
          jurusan_sp_id: this.jurusan_sp_id,
          rombongan_belajar_id: this.rombongan_belajar_id,
          page: current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
        }
      }).then(response => {
        //this.items = response.data.all_pd
        let getData = response.data.data
        this.isBusy = false
        this.loading = false
        this.items = getData.data//MAKA ASSIGN DATA POSTINGAN KE DALAM VARIABLE ITEMS
        //DAN ASSIGN INFORMASI LAINNYA KE DALAM VARIABLE META
        this.meta = {
          total: getData.total,
          current_page: getData.current_page,
          per_page: getData.per_page,
          from: getData.from,
          to: getData.to,
          tingkat: this.tingkat,
          jurusan_sp_id: this.jurusan_sp_id,
          rombongan_belajar_id: this.rombongan_belajar_id,
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
      this.search = val
      this.loadPostsData()
    },
    //JIKA ADA EMIT SORT
    handleSort(val) {
      if (val.sortBy) {
        this.sortBy = val.sortBy
        this.sortByDesc = val.sortDesc
        this.loadPostsData() //DAN LOAD DATA BARU BERDASARKAN SORT
      }
    },
    handleAksi(val){
      if(val.aksi === 'hapus'){
        this.$swal({
          title: 'Apakah Anda yakin?',
          text: 'Tindakan ini tidak dapat dikembalikan!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yakin!',
          customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-outline-danger ml-1',
          },
          buttonsStyling: false,
          allowOutsideClick: () => false,
        }).then(result => {
          if (result.value) {
            this.loading_form = true
            this.$http.post('/referensi/hapus-data', {
              data: 'cp',
              id: val.item.cp_id,
            }).then(response => {
              this.loading_form = false
              let getData = response.data
              this.$swal({
                icon: getData.icon,
                title: getData.title,
                text: getData.text,
                customClass: {
                  confirmButton: 'btn btn-success',
                },
              }).then(result => {
                this.loadPostsData()
              })
            });
          }
        })
      } else {
        eventBus.$emit(`open-modal-${val.aksi}-cp`, val.item);
      }
    },
    handleTingkat(val){
      this.tingkat = val
      this.loading = true
      this.$http.post('/referensi/get-jurusan', {
        tingkat: val,
      }).then(response => {
        this.data_jurusan = response.data
      })
      this.loadPostsData()
    },
    handleJurusan(val){
      this.jurusan_sp_id = val
      this.loading = true
      this.$http.post('/referensi/get-rombel', {
        jurusan_sp_id: val,
      }).then(response => {
        this.data_rombel = response.data
      })
      this.loadPostsData()
    },
    handleKelas(val){
      this.rombongan_belajar_id = val
      this.loadPostsData()
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>