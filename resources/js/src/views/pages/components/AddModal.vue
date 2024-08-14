<template>
  <b-modal v-model="addModalShow" :id="modalId" size="xl" @show="resetForm" @hidden="hideModal" @ok="handleOk">
    <template #modal-title>
      {{ modalTitle }}
      </template>
    <b-form-input @input="search" placeholder="Cari nama peserta didik..." size="lg" class="mb-2"></b-form-input>
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
        <b-button variant="primary" @click="proses(row.item)">Proses</b-button>
      </template>
    </b-table>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <template v-if="showProses">
        <b-form ref="form" @submit.stop.prevent="handleSubmit">
          <b-row>
            <template v-if="modalId == 'I'">
              <b-col cols="12">
                <b-form-group label="Pilihan Izin" label-for="pilihan_ijin" label-cols-md="3" :invalid-feedback="feedback.pilihan_ijin" :state="state.pilihan_ijin">
                  <b-form-radio-group id="pilihan_ijin" v-model="form.pilihan_ijin" :options="data_ijin" name="pilihan_ijin" :state="state.pilihan_ijin"></b-form-radio-group>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label="Jenis Ijin" label-for="jenis_ijin" label-cols-md="3" :invalid-feedback="feedback.jenis_ijin" :state="state.jenis_ijin">
                  <b-form-radio-group id="jenis_ijin" v-model="form.jenis_ijin" :options="data_jenis" name="jenis_ijin" :state="state.jenis_ijin" @input="changeJenis"></b-form-radio-group>
                </b-form-group>
              </b-col>
            </template>
            <template v-if="form.jenis_ijin">
              <template v-if="form.jenis_ijin == 'hari'">
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
              </template>
              <template v-else>
                <b-col cols="12">
                  <b-form-group label="Tanggal" label-for="tanggal_mulai" label-cols-md="3" :invalid-feedback="feedback.tanggal_mulai" :state="state.tanggal_mulai">
                    <b-form-datepicker v-model="form.tanggal_mulai" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_mulai" @context="onContextStart" placeholder="== Pilih Tanggal ==" :min="minMulai" :max="maxMulai" />
                  </b-form-group>  
                </b-col>
                <b-col cols="12" v-if="form.tanggal_mulai">
                <b-form-group label="Jam Ke" label-for="jam_ke" label-cols-md="3" :invalid-feedback="feedback.jam_ke" :state="state.jam_ke">
                  <b-overlay :show="loading_jam" rounded opacity="0.6" spinner-small spinner-variant="danger">
                    <b-form-checkbox v-model="allSelected" :indeterminate="indeterminate" @change="toggleAll" class="mb-1" v-if="data_jam.length">
                      {{ allSelected ? 'Lepas Semua' : 'Pilih Semua' }}
                    </b-form-checkbox>
                    <b-form-checkbox-group id="jam_ke" v-model="jam_selected" :options="data_jam" name="jam_ke" :state="state.jam_ke"></b-form-checkbox-group>
                  </b-overlay>
                </b-form-group>
            </b-col>
              </template>
            </template>
            <b-col cols="12">
              <b-form-group label="Alasan Ijin" label-for="alasan" label-cols-md="3">
                <b-form-input v-model="form.alasan" placeholder="Isi alasan (jika ada)"></b-form-input>
              </b-form-group>
            </b-col>
          </b-row>
        </b-form>
      </template>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
        <b-button variant="danger" size="lg" @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
        <b-button variant="primary" size="lg" @click="ok()" v-if="showProses">Simpan</b-button>
      </b-overlay>
    </template>
  </b-modal>
</template>

<script>
import { BOverlay, BForm, BFormGroup, BFormInput, BRow, BCol, BButton, BFormDatepicker, BTableSimple, BThead, BTbody, BTr, BTh, BTd, BFormCheckbox, BFormCheckboxGroup, BTable, BSpinner, BFormRadioGroup } from 'bootstrap-vue'
import _ from 'lodash'
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
    BFormCheckboxGroup,
    BTable,
    BSpinner,
    BFormRadioGroup,
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
      loading_jam: false,
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
        pilihan_ijin: '',
        jenis_ijin: '',
        alasan: '',
        jam_ke: [],
        jam_terlambat: '',
      },
      feedback: {
        tanggal_mulai: '',
        tanggal_selesai: '',
        pilihan_ijin: '',
        jenis_ijin: '',
        jam_ke: '',
        jam_terlambat: '',
      },
      state: {
        tanggal_mulai: null,
        tanggal_selesai: null,
        pilihan_ijin: null,
        jenis_ijin: null,
        jam_ke: null,
        jam_terlambat: null,
      },
      jam_selected: [],
      allSelected: false,
      indeterminate: false,
      data_ijin: [
        { text: 'Sakit', value: 'S' },
        { text: 'Izin', value: 'I' },
        { text: 'Dispen', value: 'D' },
      ],
      data_jenis: [
        { text: 'Perjam', value: 'jam' },
        { text: 'Harian', value: 'hari' },
      ],
      data_jam: [],
    }
  },
  watch: {
    showModal(val){
      this.form.pilihan_ijin = this.modalId
      if(this.modalId == 'T'){
        const now = new Date()
        var year  = now.getFullYear();
        var month = (now.getMonth() + 1).toString().padStart(2, "0");
        var day   = now.getDate().toString().padStart(2, "0");
        this.form.tanggal_mulai = `${year}-${month}-${day}`
        this.form.pilihan_ijin = 'T'
        this.form.jenis_ijin = 'jam'
        this.getJam()
      } else {
        this.form.tanggal_mulai = ''
        this.form.pilihan_ijin = ''
        this.form.jenis_ijin = ''
      }
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
    },
    jam_selected(newValue, oldValue) {
      // Handle changes in individual flavour checkboxes
      if (newValue.length === 0) {
        this.indeterminate = false
        this.allSelected = false
      } else if (newValue.length === this.data_jam.length) {
        this.indeterminate = false
        this.allSelected = true
      } else {
        this.indeterminate = true
        this.allSelected = false
      }
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
      this.form.tanggal_selesai = ''
      this.form.anggota_rombel_id = ''
      this.form.jam_ke = []
      this.form.alasan = ''
      this.jam_selected = []
      this.feedback.tanggal_mulai = ''
      this.feedback.tanggal_selesai = ''
      this.feedback.pilihan_ijin = ''
      this.feedback.jenis_ijin = ''
      this.feedback.jam_ke = ''
      this.state.tanggal_mulai = null
      this.state.tanggal_selesai = null
      this.state.pilihan_ijin = null
      this.state.jenis_ijin = null
      this.state.jam_ke = null
      this.showProses = false
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.form.jam_ke = this.jam_selected
      this.loading_form = true
      this.$http.post('/perijinan/store', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.tanggal_mulai = (getData.errors.tanggal_mulai) ? false : null
          this.state.tanggal_selesai = (getData.errors.tanggal_selesai) ? false : null
          this.state.pilihan_ijin = (getData.errors.pilihan_ijin) ? false : null
          this.state.jenis_ijin = (getData.errors.jenis_ijin) ? false : null
          this.state.jam_ke = (getData.errors.jam_ke) ? false : null
          this.feedback.tanggal_mulai = (getData.errors.tanggal_mulai) ? getData.errors.tanggal_mulai.join(', ') : ''
          this.feedback.tanggal_selesai = (getData.errors.tanggal_selesai) ? getData.errors.tanggal_selesai.join(', ') : ''
          this.feedback.jam_ke = (getData.errors.jam_ke) ? getData.errors.jam_ke.join(', ') : ''
          this.feedback.pilihan_ijin = (getData.errors.pilihan_ijin) ? getData.errors.pilihan_ijin.join(', ') : ''
          this.feedback.jenis_ijin = (getData.errors.jenis_ijin) ? getData.errors.jenis_ijin.join(', ') : ''
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            html: getData.text,
            allowOutsideClick: false,
            confirmButtonText: 'CETAK',
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.hideModal()
            if(getData.ijin){
              window.open(`/cetak/perijinan/preview/${getData.ijin.ijin_id}/${getData.semester_id}`, '_blank')
            } else {
              //http://smeknesa.test/cetak/terlambat/1bf70e51-3bf8-48c0-90ea-83b2dd2c7a9c/20232
              window.open(`/cetak/terlambat/${getData.terlambat.terlambat_id}/${getData.semester_id}`, '_blank')
            }
          })
        }
      }).catch(error => {
        console.log(error);
      })
    },
    toggleAll(checked) {
      this.jam_selected = checked ? this.data_jam.slice() : []
    },
    getJam(){
      this.loading_jam = true
      this.$http.post('/perijinan/get-jam', this.form).then(response => {
        this.loading_jam = false
        var getData = response.data
        this.data_jam = getData
      })
    },
    onContextStart(ctx){
      this.formatted = ctx.selectedFormatted
      if(this.form.jenis_ijin == 'jam'){
        this.getJam()
      } else {
        const date = new Date(ctx.activeDate.getFullYear(), ctx.activeDate.getMonth(), ctx.activeDate.getDate())
        this.minSelesai = new Date(date)
        const maxDateSelesai = new Date(date)
        maxDateSelesai.setDate(maxDateSelesai.getDate() + 15)
        this.maxSelesai = maxDateSelesai
      }
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
    proses(item){
      const filtered = this.items.filter(function (el) {
        return el.peserta_didik_id == item.peserta_didik_id
      });
      this.items = filtered
      
      this.form.anggota_rombel_id = item.anggota_rombel.anggota_rombel_id
      this.showProses = false
      if(this.modalId == 'R'){
        this.$emit('detil', item)
      } else {
        this.showProses = true
      }
    },
    changeJenis(val){
      if(val == 'hari'){
        this.jam_selected = []
        this.allSelected = false
        this.indeterminate = false
        this.form.tanggal_mulai = ''
        this.form.jam_ke = []
      }
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>