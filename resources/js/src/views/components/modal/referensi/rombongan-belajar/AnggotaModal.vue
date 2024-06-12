<template>
  <b-modal v-model="anggotaModalShow" title="Anggota Rombel" size="xl" ok-only ok-variant="secondary" ok-title="Tutup" modal-class="modal-fullscreen" scrollable>
    <b-row>
      <b-col cols="6">
        <h3>Anggota Rombel</h3>
        <datatable :isBusy="loadingAnggota" :items="itemsAnggota" :fields="fieldsAnggota" :meta="metaAnggota" @per_page="handlePerPageAnggota" @pagination="handlePaginationAnggota" @search="handleSearchAnggota" @aksi="handleAksiAnggota" />
      </b-col>
      <b-col cols="6">
        <h3>Non Anggota Rombel</h3>
        <datatable :isBusy="loadingNonAnggota" :items="itemsNonAnggota" :fields="fieldsNonAnggota" :meta="metaNonAnggota" @per_page="handlePerPageNonAnggota" @pagination="handlePaginationNonAnggota" @search="handleSearchNonAnggota" @aksi="handleAksiNonAnggota" />
      </b-col>
    </b-row>
  </b-modal>
</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import Datatable from './Datatable.vue'
import vSelect from 'vue-select'
export default {
  components: {
    BRow,
    BCol,
    Datatable,
    vSelect,
  },
  data() {
    return {
      anggotaModalShow: false,
      loadingAnggota: false,
      loadingNonAnggota: false,
      loading_table: false,
      data_anggota: [],
      data_non_anggota: [],
      rombongan_belajar_id: '',
      filter_nama: '',
      perPageAnggota: 10,
      fromAnggota: 1,
      toAnggota: 0,
      totalAnggota: 0,
      fieldsAnggota: [
        {
          key: 'nama',
          label: 'Nama',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'nisn',
          label: 'NISN',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
        {
          key: 'action',
          label: 'Keluarkan',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
      ],
      itemsAnggota: [],
      metaAnggota: {},
      sortDescAnggota: false,
      currentPageAnggota: 1, //DEFAULT PAGE YANG AKTIF ADA PAGE 1
      cariAnggota: '',
      loadingNonAnggota: true,
      perPageNonAnggota: 10,
      currentPageNonAnggota: 1,
      itemsNonAnggota: [],
      fieldsNonAnggota: [
        {
          key: 'nama',
          label: 'Nama',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'nisn',
          label: 'NISN',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
        {
          key: 'action',
          label: 'Masukkan',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
      ],
      metaNonAnggota: {},
      cariNonAnggota: '',
    }
  },
  created() {
    eventBus.$on('open-modal-anggota-rombel', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.rombongan_belajar_id = data.rombongan_belajar_id
      this.getAnggota()
      this.getNonAnggota()
    },
    getAnggota(){
      this.loadingAnggota = true
      let current_page = this.cariAnggota == '' ? this.currentPageAnggota:1
      this.$http.post('/referensi/anggota-rombel', {
        rombongan_belajar_id: this.rombongan_belajar_id,
        semester_id: this.user.semester.semester_id,
        page: current_page,
        per_page: this.perPageAnggota,
        q: this.cariAnggota,
      }).then(response => {
        this.loadingAnggota = false
        var getData = response.data
        if(getData.errors){
          this.$swal({
            icon: 'error',
            title: 'Error',
            text: getData.errors,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
        } else {
          this.itemsAnggota = getData.data
          this.metaAnggota = {
            actionText: 'Keluarkan',
            variant: 'danger',
            total: getData.total,
            current_page: getData.current_page,
            per_page: getData.per_page,
            from: getData.from,
            to: getData.to,
          }
        }
      })
    },
    getNonAnggota(){
      this.loadingNonAnggota = true
      let current_page = this.cariNonAnggota == '' ? this.currentPageNonAnggota:1
      this.$http.post('/referensi/non-anggota', {
        rombongan_belajar_id: this.rombongan_belajar_id,
        semester_id: this.user.semester.semester_id,
        page: current_page,
        per_page: this.perPageNonAnggota,
        q: this.cariNonAnggota,
      }).then(response => {
        this.loadingNonAnggota = false
        var getData = response.data
        if(getData.errors){
          this.$swal({
            icon: 'error',
            title: 'Error',
            text: getData.errors,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
        } else {
          this.itemsNonAnggota = getData.data
          this.metaNonAnggota = {
            actionText: 'Masukkan',
            variant: 'success',
            total: getData.total,
            current_page: getData.current_page,
            per_page: getData.per_page,
            from: getData.from,
            to: getData.to,
          }
          this.anggotaModalShow = true
        }
      })
    },
    hideModal(){
      this.$emit('reload')
      this.anggotaModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.data_anggota = []
      this.data_non_anggota = []
    },
    setAnggota(data, peserta_didik_id){
      this.loading_table = true
      this.$http.post('/referensi/set-anggota', {
        data: data,
        peserta_didik_id: peserta_didik_id,
        rombongan_belajar_id: this.rombongan_belajar_id,
      }).then(response => {
        this.loading_table = false
        this.getAnggota()
        this.getNonAnggota()
      })
    },
    handlePerPageAnggota(val){
      this.perPageAnggota = val
      this.getAnggota()
    },
    handlePaginationAnggota(val){
      this.currentPageAnggota = val
      this.getAnggota()
    },
    handleSearchAnggota(val){
      this.cariAnggota = val
      this.getAnggota()
    },
    handleAksiAnggota(val){
      this.setAnggota('keluarkan', val)
    },
    handlePerPageNonAnggota(val){
      this.perPageNonAnggota = val
      this.getNonAnggota()
    },
    handleSearchNonAnggota(val){
      this.cariNonAnggota = val
      this.getNonAnggota()
    },
    handlePaginationNonAnggota(val){
      this.currentPageNonAnggota = val
      this.getNonAnggota()
    },
    handleAksiNonAnggota(val){
      this.setAnggota('masukkan', val)
    },
  },
}
</script>
<style lang="scss">
.modal-fullscreen .modal-dialog {
    max-width: 100%;
    margin: 0;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100vh;
    display: flex;
    position: fixed;
    z-index: 100000;
}
</style>