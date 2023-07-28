<template>
  <b-card>
    <datatable :isAsesor="isAsesor" :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" />
    <b-modal ref="add-modal" size="xl" title="Tambah Data Asesor" @show="resetModal" @hidden="resetModal" @ok="handleOk" ok-title="Simpan" cancel-title="Batal">
      <add-ptk :loading="loading" :link_excel="link_excel" :form="form" :state="state" :feedback="feedback" @loading="handleLoading" @simpan="handleSimpan" @imported_data=handleImported></add-ptk>
      <template #modal-footer="{ ok, cancel }">
        <b-button @click="cancel()">Tutup</b-button>
        <b-button variant="primary" @click="ok()" v-if="imported_data.length">Simpan</b-button>
      </template>
    </b-modal>
  </b-card>
</template>

<script>
import { BCard, BButton, BRow, BCol, BFormFile, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormGroup, BFormInput, BOverlay } from 'bootstrap-vue'
import Datatable from './Datatable.vue'
import AddPtk from './../modal/AddPtk.vue'
import eventBus from '@core/utils/eventBus';
export default {
  components: {
    BCard,
    BButton,
    BRow, 
    BCol, 
    BFormFile,
    BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormGroup, BFormInput, BOverlay,
    AddPtk,
    Datatable
  },
  data() {
    return {
      isAsesor: true,
      simpan: false,
      loading: false,
      isBusy: true,
      fields: [
        {
          key: 'nama_lengkap',
          label: 'Nama',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'jenis_kelamin',
          label: 'L/P',
          sortable: true,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
        {
          key: 'ttl',
          label: 'Tempat, Tanggal Lahir',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'email',
          label: 'Email',
          sortable: true,
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
      sortBy: 'nama', //DEFAULT SORTNYA ADALAH CREATED_AT
      sortByDesc: false, //ASCEDING
      link_excel: '/excel/format_excel_asesor.xlsx',
      form: {
        sekolah_id: '',
        jenis_gtk: 'asesor',
        nama: {},
        nuptk: {},
        nip: {},
        nik: {},
        jenis_kelamin: {},
        tempat_lahir: {},
        tanggal_lahir: {},
        agama: {},
        alamat_jalan: {},
        rt: {},
        rw: {},
        desa_kelurahan: {},
        kecamatan: {},
        kodepos: {},
        telp_hp: {},
        email: {},
      },
      state: {
        nama: null,
        nik: null,
        tanggal_lahir: null,
        agama: null,
        email: null,
      },
      feedback: {
        nama: '',
        nik: '',
        tanggal_lahir: '',
        agama: '',
        email: '',
      },
      imported_data: [],
      /*file: null,
      fileState: null,
      feedback_file: '',
      imported_data: [],
      nama: {},
      nuptk: {},
      nip: {},
      nik: {},
      jenis_kelamin: {},
      tempat_lahir: {},
      tanggal_lahir: {},
      agama: {},
      alamat_jalan: {},
      rt: {},
      rw: {},
      desa_kelurahan: {},
      kecamatan: {},
      kodepos: {},
      telp_hp: {},
      email: {},
      nama_state: null,
      nik_state: null,
      tanggal_lahir_state: null,
      agama_state: null,
      email_state: null,
      nama_feedback: '',
      nik_feedback: '',
      tanggal_lahir_feedback: '',
      agama_feedback: '',
      email_feedback: '',*/
    }
  },
  created() {
    this.form.sekolah_id = this.user.sekolah_id
    this.loadPostsData()
    eventBus.$on('modal-asesor', this.handleEvent);
  },
  methods: {
    handleEvent(){
      console.log(this.$refs);
      this.$refs['add-modal'].show()
    },
    resetModal() {
      /*this.file = null
      this.fileState = null
      this.feedback_file = ''
      this.imported_data = []
      this.nama = {}
      this.nuptk = {}
      this.nip = {}
      this.nik = {}
      this.jenis_kelamin = {}
      this.tempat_lahir = {}
      this.tanggal_lahir = {}
      this.agama = {}
      this.alamat_jalan = {}
      this.rt = {}
      this.rw = {}
      this.desa_kelurahan = {}
      this.kecamatan = {}
      this.kodepos = {}
      this.telp_hp = {}
      this.email = {}
      this.nama_state = null
      this.nik_state = null
      this.tanggal_lahir_state = null
      this.agama_state = null
      this.email_state = null
      this.nama_feedback = ''
      this.nik_feedback = ''
      this.tanggal_lahir_feedback = ''
      this.agama_feedback = ''
      this.email_feedback = ''*/
    },
    loadPostsData() {
      this.isBusy = true
      //let current_page = this.search == '' ? this.current_page : this.current_page != 1 ? 1 : this.current_page
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
      this.$http.get('/guru', {
        params: {
          jenis_gtk: 'asesor',
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
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault()
      // Trigger submit handler
      this.handleSubmit()
    },
    handleLoading(val){
      this.loading = val
    },
    handleSimpan(val){
      this.simpan = val
    },
    handleImported(val){
      this.imported_data = val
    },
    handleSubmit() {
      this.$http.post('/guru/simpan', this.form).then(response => {
        let data = response.data
        if(data.errors){
          console.log(data.errors);
          var nama_state = {}
          var nik_state = {}
          var tanggal_lahir_state = {}
          var agama_state = {}
          var email_state = {}
          var nama_feedback = {}
          var nik_feedback = {}
          var tanggal_lahir_feedback = {}
          var agama_feedback = {}
          var email_feedback = {}
          this.imported_data.forEach(function(value, key) {
            console.log(data.errors['nik.'+value.no]);
            nama_state[value.no] = (data.errors['nama.'+value.no]) ? false : null
            nik_state[value.no] = (data.errors['nik.'+value.no]) ? false : null
            tanggal_lahir_state[value.no] = (data.errors['tanggal_lahir.'+value.no]) ? false : null
            agama_state[value.no] = (data.errors['agama.'+value.no]) ? false : null
            email_state[value.no] = (data.errors['email.'+value.no]) ? false : null
            nama_feedback[value.no] = (data.errors['nama.'+value.no]) ? data.errors['nama.'+value.no].join(', ') : ''
            nik_feedback[value.no] = (data.errors['nik.'+value.no]) ? data.errors['nik.'+value.no].join(', ') : ''
            tanggal_lahir_feedback[value.no] = (data.errors['tanggal_lahir.'+value.no]) ? data.errors['tanggal_lahir.'+value.no].join(', ') : ''
            agama_feedback[value.no] = (data.errors['agama.'+value.no]) ? data.errors['agama.'+value.no].join(', ') : ''
            email_feedback[value.no] = (data.errors['email.'+value.no]) ? data.errors['email.'+value.no].join(', ') : ''
          })
          this.state.nama = nama_state
          this.state.nik = nik_state
          this.state.tanggal_lahir = tanggal_lahir_state
          this.state.agama = agama_state
          this.state.email = email_state
          this.feedback.nama = nama_feedback
          this.feedback.nik = nik_feedback
          this.feedback.tanggal_lahir = tanggal_lahir_feedback
          this.feedback.agama = agama_feedback
          this.feedback.email = email_feedback
        } else {
          this.$swal({
            icon: data.icon,
            title: data.title,
            text: data.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.$refs['add-modal'].hide()
            this.loadPostsData()
          })
        }
      })
    },
  },
}
</script>