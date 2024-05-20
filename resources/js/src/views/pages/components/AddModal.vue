<template>
  <b-modal v-model="addModalShow" :id="modalId" size="xl" @show="resetForm" @hidden="hideModal" @ok="handleOk">
    <template #modal-title>
      {{ modalTitle }}
      </template>
    <b-form-input @input="search" placeholder="Cari nama peserta didik..." size="lg"></b-form-input>
    <b-table responsive bordered striped :items="items" :fields="fields" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" show-empty :busy="isBusy" v-if="items.length">
      <template #empty="scope">
        <p class="text-center">Tidak ada data untuk ditampilkan</p>
      </template>
      <template #table-busy>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
      </template>
      <template v-slot:cell(rombongan_belajar)="row">
        {{row.item.anggota_rombel.rombongan_belajar.nama}}
      </template>
      <template v-slot:cell(actions)="row">
        <b-button variant="primary" @click="proses(row.item.anggota_rombel.anggota_rombel_id)">Proses</b-button>
      </template>
    </b-table>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <template v-if="showProses">
        <b-form ref="form" @submit.stop.prevent="handleSubmit">
          <b-row>
            <b-col cols="12">
              <b-form-group label="Mulai Tanggal" label-for="tanggal_mulai" label-cols-md="3" :invalid-feedback="feedback.tanggal_mulai" :state="state.tanggal_mulai">
                <b-form-datepicker v-model="form.tanggal_mulai" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_mulai" @context="onContextStart" placeholder="== Pilih Mulai Tanggal ==" :min="minMulai" :max="maxMulai" />
              </b-form-group>  
            </b-col>
            <b-col cols="12">
              <b-form-group label="Sampai Tanggal" label-for="tanggal_selesai" label-cols-md="3" :invalid-feedback="feedback.tanggal_selesai" :state="state.tanggal_selesai">
                <b-form-datepicker v-model="form.tanggal_selesai" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_selesai" @context="onContext" placeholder="== Pilih Sampai Tanggal ==" :min="minSelesai" :max="maxSelesai" />
              </b-form-group>  
            </b-col>
          </b-row>
        </b-form>
      </template>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
        <b-button variant="success" @click="ok()" v-if="showProses">Simpan</b-button>
      </b-overlay>
    </template>
  </b-modal>
</template>

<script>
import { BOverlay, BForm, BFormGroup, BFormInput, BRow, BCol, BButton, BFormDatepicker, BTableSimple, BThead, BTbody, BTr, BTh, BTd, BFormCheckbox, BTable, BSpinner } from 'bootstrap-vue'
import _ from 'lodash'
import { required } from 'vee-validate/dist/rules'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BButton,
    BFormDatepicker,
    BTableSimple, BThead, BTbody, BTr, BTh, BTd,
    BFormCheckbox,
    BTable,
    BSpinner,
    vSelect,
  },
  props: {
    modalId: {
      type: String,
      required: true
    },
    modalTitle: {
      type: String,
      required: true
    },
    showModal: {
      type: Boolean,
      required: true,
      default: false,
    }
  },
  data() {
    const now = new Date()
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
    const minDateMulai = new Date(today)
    // 15th in two months
    const maxDateMulai = new Date(today)
    maxDateMulai.setMonth(maxDateMulai.getMonth() + 2)
    maxDateMulai.setDate(15)
    const minDateSelesai = new Date(today)
    minDateSelesai.setMonth(minDateSelesai.getMonth() - 2)
    minDateSelesai.setDate(15)
    // 15th in two months
    const maxDateSelesai = new Date(today)
    maxDateSelesai.setMonth(maxDateSelesai.getMonth() + 2)
    maxDateSelesai.setDate(15)
    return {
      addModalShow: false,
      minMulai: minDateMulai,
      maxMulai: maxDateMulai,
      minSelesai: minDateSelesai,
      maxSelesai: maxDateSelesai,
      showProses: false,
      loading_form: false,
      sortBy: null,
      sortDesc: false,
      isBusy: true,
      fields: [
        {
          key: 'nama',
          label: 'Nama Peserta Didik',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'nisn',
          label: 'NISN',
          sortable: false,
          thClass: 'text-center',
        },
        {
          key: 'rombongan_belajar',
          label: 'Kelas',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'actions',
          label: 'Pilih',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
      ],
      items: [],
      meta: {},
      current_page: 1, //DEFAULT PAGE YANG AKTIF ADA PAGE 1
      per_page: 10, //DEFAULT LOAD PERPAGE ADALAH 10
      sortBy: 'nama', //DEFAULT SORTNYA ADALAH CREATED_AT
      sortByDesc: true, //ASCEDING
      form: {
        tanggal_mulai: '',
        tanggal_selesai: '',
        anggota_rombel_id: '',
      },
      feedback: {
        tanggal_mulai: '',
        tanggal_selesai: '',
      },
      state: {
        tanggal_mulai: null,
        tanggal_selesai: null,
      },
    }
  },
  watch: {
    showModal(val){
      this.addModalShow = val
    },
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
  created() {
    //eventBus.$on('open-modal-add-pembimbing', this.handleEvent);
  },
  methods: {
    hideModal(){
      this.resetForm()
      this.$emit('hidden')
    },
    resetForm(){
      this.items = []
      this.form.tanggal_mulai = ''
      this.form.tanggal_selesai = ''
      this.form.anggota_rombel_id = ''
      this.feedback.tanggal_mulai = ''
      this.feedback.tanggal_selesai = ''
      this.state.tanggal_mulai = null
      this.state.tanggal_selesai = null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/perijinan/store', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.tanggal_mulai = (getData.errors.tanggal_mulai) ? false : null
          this.state.tanggal_selesai = (getData.errors.tanggal_selesai) ? false : null
          this.feedback.tanggal_mulai = (getData.errors.tanggal_mulai) ? getData.errors.tanggal_mulai.join(', ') : ''
          this.feedback.tanggal_selesai = (getData.errors.tanggal_selesai) ? getData.errors.tanggal_selesai.join(', ') : ''
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            html: getData.text,
            allowOutsideClick: false,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.hideModal()
          })
        }
      }).catch(error => {
        console.log(error);
      })
    },
    onContextStart(ctx){
      this.formatted = ctx.selectedFormatted
      const date = new Date(ctx.activeDate.getFullYear(), ctx.activeDate.getMonth(), ctx.activeDate.getDate())
      this.minSelesai = new Date(date)
      const maxDateSelesai = new Date(date)
      maxDateSelesai.setDate(maxDateSelesai.getDate() + 15)
      this.maxSelesai = maxDateSelesai
    },
    onContext(ctx) {
      this.formatted = ctx.selectedFormatted
    },
    loadPostsData(search) {
      this.loading = true
      let current_page = this.current_page
      this.$http.get('/perijinan', {
        params: {
          page: current_page,
          per_page: this.per_page,
          q: search,
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
        }
      })
    },
    loadPerPage(val) {
      this.$emit('per_page', this.meta.per_page)
    },
    changePage(val) {
      this.$emit('pagination', val)
    },
    search: _.debounce(function (e) {
      this.loadPostsData(e)
    }, 500),
    proses(anggota_rombel_id){
      this.form.anggota_rombel_id = anggota_rombel_id
      this.showProses = true
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>