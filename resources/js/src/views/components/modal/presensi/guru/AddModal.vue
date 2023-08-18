<template>
  <b-modal v-model="addModalShow" title="Input Presensi Guru" @hidden="hideModal" @ok="handleOk" modal-class="modal-fullscreen" scrollable>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Hari, Tanggal" label-for="tanggal" label-cols-md="3">
              <b-form-datepicker id="tanggal" v-model="form.tanggal" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal"  @context="onContext" @input="changeHari" placeholder="== Pilih Tanggal ==" />
            </b-form-group>  
          </b-col>
          <template v-for="bolos in data_bolos">
            <b-col cols="12">
              <b-form-group label="Nama Guru" label-for="guru_id" label-cols-md="3">
                <b-input-group>
                  <b-overlay :show="loading" opacity="0.6" size="md" spinner-variant="secondary">
                    <v-select id="guru_id" v-model="form.guru_id[bolos]" :reduce="nama => nama.guru_id" label="nama" :options="data_guru" placeholder="== Pilih Guru ==" style="width:400px" class="mr-1" @input="changeGuru">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada guru yang memiliki jadwal mengajar pada hari {{hari}}
                      </template>
                    </v-select>
                  </b-overlay>
                  <b-overlay :show="loading" opacity="0.6" size="md" spinner-variant="secondary">
                    <b-dropdown id="dropdown-form" text="Pilih Jam" ref="dropdown" class="mr-1">
                      <b-dropdown-form>
                        <b-form-checkbox-group v-model="form.jam[bolos]" :options="data_jam" name="jam" stacked></b-form-checkbox-group>
                      </b-dropdown-form>
                    </b-dropdown>
                  </b-overlay>
                  <b-overlay :show="loading" opacity="0.6" size="md" spinner-variant="secondary">
                    <v-select id="jam" v-model="form.absensi[bolos]" :options="['A', 'I', 'S', 'D']" placeholder="== Pilih Opsi ==" style="width:100px" class="mr-1">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-overlay>
                  <b-overlay :show="loading" opacity="0.6" size="md" spinner-variant="secondary">
                    <b-input-group-append>
                      <b-button variant="success" @click="addSelect()"><plus-icon size="12" /></b-button>
                    </b-input-group-append>
                  </b-overlay>
                </b-input-group>
              </b-form-group>
            </b-col>
          </template>
        </b-row>
      </b-form>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
        <b-button variant="success" @click="ok()" v-if="data_guru.length">Simpan</b-button>
      </b-overlay>
    </template>
  </b-modal>
</template>

<script>
import { BOverlay, BRow, BCol, BForm, BTableSimple, BThead, BTh, BTbody, BTr, BTd, BButton, BFormSelect, BFormDatepicker, BFormGroup, BInputGroup, BInputGroupAppend, BDropdown, BDropdownForm, BFormCheckboxGroup } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay,
    BForm,
    BTableSimple,
    BThead,
    BTh,
    BTbody,
    BTr,
    BTd,
    BButton,
    BFormSelect,
    BRow, 
    BCol,
    BFormDatepicker,
    BFormGroup,
    BInputGroup,
    BInputGroupAppend,
    BDropdown,
    BDropdownForm,
    BFormCheckboxGroup,
    vSelect,
  },
  data() {
    return {
      addModalShow: false,
      loading_form: false,
      loading: false,
      form: {
        aksi: 'pd',
        tingkat: '',
        rombongan_belajar_id: '',
        tanggal: '',
        guru_id: {},
        jam: {},
        absensi: {},
        jadwal_id: {},
      },
      hari: '',
      jumlah_jam: 0,
      data_guru: [],
      data_bolos: 1,
      data_jam: [],
    }
  },
  created() {
    eventBus.$on('open-modal-presensi-guru', this.handleEvent);
  },
  methods: {
    handleEvent(){
      //this.getHari()
      this.getGuru()
    },
    getHari(){
      this.data_jam = []
      this.form.jam = {}
      this.form.absensi = {}
      this.$http.get('/presensi/get-hari').then(response => {
        let getData = response.data
        this.form.tanggal = getData.tanggal
        this.hari = getData.tanggal_str
        this.jumlah_jam = getData.jumlah_jam + 1
        for (let i = 1; i < this.jumlah_jam; i++) {
          //this.data_jam.push(i)
        } 
        this.addModalShow = true
        //this.getGuru()
      })
    },
    getGuru(){
      this.loading = true
      this.data_jam = []
      this.data_guru = []
      eventBus.$emit('loading', true)
      this.$http.get('/referensi/get-guru', {
        params: {
          aksi: 'absen',
          tanggal: this.form.tanggal,
        }
      }).then(response => {
        eventBus.$emit('loading', false)
        this.loading = false
        let getData = response.data
        this.data_guru = getData.data.guru
        this.form.tanggal = getData.data.tanggal
        this.hari = getData.data.tanggal_str
        this.addModalShow = true
      })
    },
    addSelect(){
      this.data_bolos = this.data_bolos + 1
    },
    changeHari(){
      this.getGuru(this.form.tanggal)
      /*this.$http.post('/presensi/get-hari', this.form).then(response => {
        this.loading = false
        let getData = response.data
        this.jumlah_jam = getData.jumlah_jam + 1
        for (let i = 1; i < this.jumlah_jam; i++) {
          //this.data_jam.push(i)
        } 
      })*/
    },
    changeGuru(val){
      var _this = this
      this.data_jam = []
      if(val){
        const guru = this.data_guru.filter((item) => {
          return item.guru_id === val
        })
        if(guru.length){
          var jadwal_id = {}
          guru[0].jadwal.forEach(item => {
            jadwal_id[item.jadwal_id] = item.jadwal_id
            item.jam.forEach(item => {
              _this.data_jam.push(item.jam)
            })
          })
          this.form.jadwal_id = jadwal_id
        }
      }
    },
    getAbsen(arr, jam_ke){
      const absen = arr.filter((item) => {
        return item.jam === jam_ke
      })
      return absen
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.tingkat = ''
      this.form.rombongan_belajar_id = ''
      this.form.tanggal = ''
      this.form.guru_id = {}
      this.form.jam = {}
      this.data_bolos = 1
      this.data_jam = []
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/presensi/simpan', {
        aksi: 'guru',
        tanggal: this.form.tanggal,
        absensi: this.form.absensi,
        guru_id: this.form.guru_id,
        jam: this.form.jam,
        jadwal_id: this.form.jadwal_id,
        //form: this.form,
      }).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.message){
          this.$swal({
            icon: 'error',
            title: 'ERROR',
            html: 'Silahkan periksa kembali isian Anda!',
            customClass: {
              confirmButton: 'btn btn-success',
            },
            allowOutsideClick: false,
          })
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            html: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
            allowOutsideClick: false,
          }).then(result => {
            this.hideModal()
            this.$emit('reload')
          })
        }
      }).catch(error => {
        console.log(error);
      })
    },
    onContext(ctx) {
      this.formatted = ctx.selectedFormatted
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
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