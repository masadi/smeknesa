<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <datatable :isBusy="isBusy" :loading="loading" :items="items" :fields="fields" :meta="meta" :data_jurusan="data_jurusan" :data_rombel="data_rombel" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @aksi="handleAksi" @tingkat="handleTingkat" @jurusan="handleJurusan" @kelas="handleKelas" />
      </div>
    </b-card-body>
    <add-modal @reload="handleReload"></add-modal>
    <edit-modal @reload="handleReload" @stop_loading="handleLoading"></edit-modal>
    <edit-modal-tp @reload_cp="handleReloadCp" @stop_loading="handleLoading"></edit-modal-tp>
    <detil-modal @aksi-tp="handleAksiTp"></detil-modal>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner } from 'bootstrap-vue'
import Datatable from './Datatable.vue' //IMPORT COMPONENT DATATABLENYA
import AddModal from './../../components/modal/penilaian/tujuan-pembelajaran/AddModal.vue'
import EditModal from './../../components/modal/penilaian/tujuan-pembelajaran/EditModal.vue'
import DetilModal from './../../components/modal/penilaian/tujuan-pembelajaran/DetilModal.vue'
import EditModalTp from './../../components/modal/penilaian/tujuan-pembelajaran/EditModalTp.vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    Datatable,
    AddModal,
    EditModal,
    DetilModal,
    EditModalTp,
  },
  data() {
    return {
      isBusy: true,
      loading: false,
      fields: [
        {
          key: 'mapel',
          label: 'Mata Pelajaran',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'deskripsi',
          label: 'Capaian Pembelajaran',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'tp_count',
          label: 'Jumlah TP',
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
    eventBus.$on('add-tp', this.handleEvent);
    this.loadPostsData()
  },
  methods: {
    handleEvent(){
      eventBus.$emit('open-modal-add-tp', 'pkl');
    },
    handleReload(){
      this.loadPostsData()
    },
    loadPostsData() {
      let current_page = this.current_page
      this.$http.get('/magang/list-cp', {
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
    handleAksiTp(val){
      if(val.aksi === 'hapus'){
        this.hapusData({
          text: 'Tindakan ini dapat menghapus seluruh nilai dibawahnya!',
          data: 'tp',
          id: val.item.tp_id,
          item: val.item
        })
      } else {
        eventBus.$emit(`open-modal-${val.aksi}-tp`, val.item);
      }
    },
    handleAksi(val){
      if(val.aksi === 'hapus'){
        this.hapusData({
          text: 'Tindakan ini dapat menghapus seluruh TP dibawahnya!',
          data: 'cp',
          id: val.item.cp_id,
          item: val.item
        })
      } else {
        eventBus.$emit(`open-modal-${val.aksi}-cp`, val.item);
      }
    },
    hapusData(val){
      this.$swal({
        title: 'Apakah Anda yakin?',
        text: val.text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yakin!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
        allowOutsideClick: false,
      }).then(result => {
        if (result.value) {
          this.loading_form = true
          this.$http.post('/referensi/hapus-data', {
            data: val.data,
            id: val.id,
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
              if(val.data == 'cp'){
                this.loadPostsData()
              } else {
                eventBus.$emit(`open-modal-detil-cp`, val.item);
              }
            })
          });
        }
      })
    },
    handleReloadCp(item){
      eventBus.$emit(`open-modal-detil-cp`, item);
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
    handleLoading(){
      this.loading = false
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>