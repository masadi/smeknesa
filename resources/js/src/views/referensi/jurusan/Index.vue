<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <datatable :isAsesor="isAsesor" :loading="loading" :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @aksi="handleAksi" />
      </div>
    </b-card-body>
    <add-modal @reload="handleReload"></add-modal>
    <edit-modal @reload="handleReload"></edit-modal>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner } from 'bootstrap-vue'
import Datatable from './Datatable.vue' //IMPORT COMPONENT DATATABLENYA
import AddModal from './../../components/modal/referensi/jurusan/AddModal.vue'
import EditModal from './../../components/modal/referensi/jurusan/EditModal.vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    Datatable,
    AddModal,
    EditModal,
  },
  data() {
    return {
      isAsesor: false,
      isBusy: true,
      loading: false,
      fields: [
        {
          key: 'nama_konsentrasi',
          label: 'Konsentrasi Keahlian',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'nama_jurusan_sp',
          label: 'Program Keahlian',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'alias',
          label: 'Singkatan',
          sortable: true,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
        {
          key: 'jml_kelas',
          label: 'Jumlah Kelas',
          sortable: true,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
        {
          key: 'kajur',
          label: 'Ketua Jurusan',
          sortable: false,
          thClass: 'text-center',
        },
        
        {
          key: 'actions',
          label: 'Aksi',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
      ],
      items: [],
      meta: {},
      current_page: 1, //DEFAULT PAGE YANG AKTIF ADA PAGE 1
      per_page: 10, //DEFAULT LOAD PERPAGE ADALAH 10
      search: '',
      sortBy: 'nama_jurusan_sp', //DEFAULT SORTNYA ADALAH CREATED_AT
      sortByDesc: false, //ASCEDING
    }
  },
  created() {
    eventBus.$on('add-jurusan', this.handleEvent);
    this.loadPostsData()
  },
  methods: {
    handleEvent(){
      eventBus.$emit('open-modal-add-jurusan');
    },
    handleReload(){
      this.loadPostsData()
    },
    loadPostsData() {
      this.loading = true
      //let current_page = this.search == '' ? this.current_page : this.current_page != 1 ? 1 : this.current_page
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
      this.$http.get('/referensi/jurusan', {
        params: {
          user_id: this.user.user_id,
          sekolah_id: this.user.sekolah_id,
          semester_id: this.user.semester.semester_id,
          periode_aktif: this.user.semester.nama,
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
          role_id: this.role_id,
          roles: response.data.roles,
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
              data: 'jurusan',
              id: val.item.jurusan_sp_id,
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
        eventBus.$emit(`open-modal-${val.aksi}-jurusan`, val.item);
      }
      console.log(val);
      //console.log(val);
      //eventBus.$emit('open-modal-detil-guru', val);
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>