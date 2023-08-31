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
          <b-col cols="12">
            <b-form-group label="Kelas" label-for="rombongan_belajar_id" label-cols-md="3">
              <b-overlay :show="loading_rombel" opacity="0.6" size="md" spinner-variant="secondary">
                <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Kelas ==" @input="changeRombel">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada kelas yang memiliki jadwal mengajar pada hari {{hari}}
                  </template>
                </v-select>
              </b-overlay>
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
      loading_rombel: false,
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
      data_rombel: [],
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
      this.getRombel()
    },
    getRombel(){
      this.data_rombel = []
      this.data_guru = []
      this.data_jam = []
      this.form.rombongan_belajar_id = ''
      this.form.guru_id = {}
      this.form.jam = {}
      this.form.absensi = {}
      this.loading_rombel = true
      eventBus.$emit('loading', true)
      this.$http.get('/presensi/get-rombel', {
        params: {
          aksi: 'absen',
          tanggal: this.form.tanggal,
        }
      }).then(response => {
        eventBus.$emit('loading', false)
        this.loading_rombel = false
        let getData = response.data
        this.data_rombel = getData.rombel
        this.form.tanggal = getData.tanggal
        this.hari = getData.tanggal_str
        this.addModalShow = true
      })
    },
    getGuru(){
      this.loading = true
      this.data_jam = []
      this.data_guru = []
      eventBus.$emit('loading', true)
      this.$http.get('/presensi/get-guru', {
        params: {
          tanggal: this.form.tanggal,
          rombongan_belajar_id: this.form.rombongan_belajar_id,
        }
      }).then(response => {
        eventBus.$emit('loading', false)
        this.loading = false
        let getData = response.data
        this.data_guru = getData
        /*const guru_absen = this.data_guru.filter((item) => {
          return item.presensi.length > 0
        })
        var _this = this
        var jml_absen = []
        var absen_a = []
        var absen_i = []
        var absen_s = []
        var absen_d = []
        guru_absen.forEach(item => {
          absen_a.push(_this.getPresensi(item.presensi, 'A'))
          absen_i.push(_this.getPresensi(item.presensi, 'I'))
          absen_s.push(_this.getPresensi(item.presensi, 'S'))
          absen_d.push(_this.getPresensi(item.presensi, 'D'))
          jml_absen.push(absen_a, absen_i, absen_s, absen_d)
        })
        const total_absen = jml_absen.filter((item) => {
          return item[0].length > 0
        })
        total_absen.forEach((item, index) => {
          var jam_guru = []
          item.forEach(presensi => {
            presensi.forEach(absensi => {
              _this.form.guru_id[index + 1] = absensi.guru_id
              _this.form.absensi[index + 1] = absensi.absen
              jam_guru.push(absensi.jam)
              _this.changeGuru(absensi.guru_id)
            })
          })
          _this.form.jam[index + 1] = jam_guru
        });
        this.data_bolos = total_absen.length + 1*/
      })
    },
    getPresensi(presensi, opsi){
      const absen_guru = presensi.filter((item) => {
        return item.absen == opsi
      })
      return absen_guru
    },
    addSelect(){
      this.data_bolos = this.data_bolos + 1
    },
    changeHari(){
      this.getRombel()
    },
    changeRombel(){
      this.getGuru()
    },
    changeGuru(val){
      var _this = this
      this.data_jam = []
      var set_data_jam = []
      if(val){
        const guru = this.data_guru.filter((item) => {
          return item.guru_id === val
        })
        if(guru.length){
          var jadwal_id = {}
          guru[0].jadwal.forEach(item => {
            jadwal_id[item.jadwal_id] = item.jadwal_id
            item.jam.forEach(item => {
              set_data_jam.push(item.jam)
            })
          })
          this.form.jadwal_id = jadwal_id
        }
      }
      this.data_jam = this.uniqueChars(set_data_jam)
    },
    uniqueChars(arr){
      return [...new Set(arr)];
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
      this.form.tanggal = ''
      this.data_rombel = []
      this.data_guru = []
      this.data_jam = []
      this.form.rombongan_belajar_id = ''
      this.form.guru_id = {}
      this.form.jam = {}
      this.form.absensi = {}
      this.data_bolos = 1
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