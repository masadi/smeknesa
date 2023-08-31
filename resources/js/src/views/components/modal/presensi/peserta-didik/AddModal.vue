<template>
  <b-modal v-model="addModalShow" title="Input Presensi Siswa" @hidden="hideModal" @ok="handleOk" modal-class="modal-fullscreen" scrollable>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Hari, Tanggal" label-for="tanggal" label-cols-md="3">
              <b-form-datepicker id="tanggal" v-model="form.tanggal" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal"  @context="onContext" @input="changeHari" placeholder="== Pilih Tanggal ==" />
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3">
              <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" @input="changeTingkat">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Rombongan Belajar" label-for="rombongan_belajar_id" label-cols-md="3">
              <b-overlay :show="loading_select" opacity="0.6" size="md" spinner-variant="secondary">
                <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Rombongan Belajar ==" @input="changeRombel">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
          <template v-for="bolos in siswa_bolos">
            <b-col cols="12">
              <b-form-group label="Nama Siswa" label-for="anggota_rombel_id" label-cols-md="3">
                <b-input-group>
                  <b-overlay :show="loading_anggota" opacity="0.6" size="md" spinner-variant="secondary">
                    <v-select id="anggota_rombel_id" v-model="form.anggota_rombel_id[bolos]" :reduce="nama => nama.anggota_rombel.anggota_rombel_id" label="nama" :options="data_pd" placeholder="== Pilih Siswa ==" style="width:300px" class="mr-1">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-overlay>
                  <b-overlay :show="loading_anggota || loading_jam" opacity="0.6" size="md" spinner-variant="secondary">
                    <b-dropdown id="dropdown-form" text="== Pilih Jam ==" ref="dropdown" class="mr-1">
                      <b-dropdown-form>
                        <b-form-checkbox v-model="allSelected[bolos]" :indeterminate="indeterminate" aria-describedby="jam" aria-controls="jam" @change="toggleAll(bolos)" value="all" unchecked-value="none">
                          {{ allSelected[bolos] ? 'Lepas Semua' : 'Pilih Semua' }}
                        </b-form-checkbox>
                        <b-form-checkbox-group id="jam" v-model="form.jam[bolos]" :options="data_jam" name="jam" stacked></b-form-checkbox-group>
                      </b-dropdown-form>
                    </b-dropdown>
                  </b-overlay>
                  <!--v-model="form.jam[bolos]"-->
                  <b-overlay :show="loading_anggota" opacity="0.6" size="md" spinner-variant="secondary">
                    <v-select id="jam" v-model="form.absensi[bolos]" :options="['A', 'I', 'S', 'D']" placeholder="== Pilih Opsi ==" style="width:100px" class="mr-1">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-overlay>
                  <b-overlay :show="loading_anggota" opacity="0.6" size="md" spinner-variant="secondary">
                    <b-input-group-append>
                      <b-button variant="success" @click="addSelect()"><plus-icon size="12" /></b-button>
                    </b-input-group-append>
                  </b-overlay>
                </b-input-group>
              </b-form-group>
            </b-col>
          </template>
        </b-row>
        <b-table-simple bordered v-if="data_pd.length">
          <b-thead>
            <b-tr>
              <b-th class="text-center" colspan="8">Data Siswa Kelas {{nama_kelas}} yang telah di absen pada {{tanggal_str}}</b-th>
            </b-tr>
            <b-tr>
              <b-th class="text-center" rowspan="2">No</b-th>
              <b-th class="text-center" rowspan="2">Nama</b-th>
              <b-th class="text-center" rowspan="2">NISN</b-th>
              <b-th class="text-center" colspan="4">Presensi</b-th>
              <b-th class="text-center" rowspan="2">Aksi</b-th>
            </b-tr>
            <b-tr>
              <b-th class="text-center">A</b-th>
              <b-th class="text-center">I</b-th>
              <b-th class="text-center">S</b-th>
              <b-th class="text-center">D</b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <b-tr v-for="(pd, index) in pd_absen" :key="pd.peserta_didik_id">
              <b-td class="text-center">{{index + 1}}</b-td>
              <b-td><media-siswa :data="pd"></media-siswa></b-td>
              <b-td class="text-center">{{pd.nisn}}</b-td>
              <b-td class="text-center">{{getPresensi(pd.presensi, 'A').map(u => u.jam).join(', ')}}</b-td>
              <b-td class="text-center">{{getPresensi(pd.presensi, 'I').map(u => u.jam).join(', ')}}</b-td>
              <b-td class="text-center">{{getPresensi(pd.presensi, 'S').map(u => u.jam).join(', ')}}</b-td>
              <b-td class="text-center">{{getPresensi(pd.presensi, 'D').map(u => u.jam).join(', ')}}</b-td>
              <b-td class="text-center"><a @click="hapusPresensi(pd.anggota_rombel.anggota_rombel_id)" class="text-danger"><trash-icon size="20" /></a></b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
      </b-form>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
        <b-button variant="success" @click="ok()" v-if="data_pd.length">Simpan</b-button>
      </b-overlay>
    </template>
  </b-modal>
</template>

<script>
import { BOverlay, BRow, BCol, BForm, BTableSimple, BThead, BTh, BTbody, BTr, BTd, BButton, BFormSelect, BFormDatepicker, BFormGroup, BInputGroup, BInputGroupAppend, BDropdown, BDropdownForm, BFormCheckbox, BFormCheckboxGroup } from 'bootstrap-vue'
import MediaSiswa from './../../../MediaSiswa.vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    MediaSiswa,
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
    BFormCheckbox,
    BFormCheckboxGroup,
    vSelect,
  },
  data() {
    return {
      addModalShow: false,
      loading_form: false,
      loading_select: false,
      loading_table: false,
      loading_jam: false,
      form: {
        data: 'presensi',
        aksi: 'pd',
        tingkat: '',
        rombongan_belajar_id: '',
        tanggal: '',
        anggota_rombel_id: {},
        jam: {},
        absensi: {},
      },
      absensi: {},
      hari: '',
      jumlah_jam: 0,
      data_pd: [],
      pd_absen: [],
      data_tingkat: [
        {
          id: 10,
          nama: 'Tingkat 10',
        },
        {
          id: 11,
          nama: 'Tingkat 11',
        },
        {
          id: 12,
          nama: 'Tingkat 12',
        },
        {
          id: 13,
          nama: 'Tingkat 13',
        }
      ],
      data_rombel: [],
      siswa_bolos: 1,
      loading_anggota: false,
      data_jam: [],
      nama_kelas: '',
      tanggal_str: '',
      allSelected: {},
      indeterminate: false,
    }
  },
  created() {
    this.form.jam[1] = []
    eventBus.$on('open-modal-presensi-pd', this.handleEvent);
  },
  /*watch: {
    selected(newValue, oldValue) {
      console.log(newValue);
      console.log(oldValue);
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
  },*/
  methods: {
    toggleAll(bolos) {
      if(this.allSelected[bolos] == 'all'){
        this.data_jam.forEach(element => {
          this.form.jam[bolos].push(element)
        });
      }
      this.form.jam[bolos] = []
    },
    handleEvent(){
      this.$http.get('/presensi/get-hari').then(response => {
        let getData = response.data
        this.form.tanggal = getData.tanggal
        this.tanggal_str = getData.tanggal_str
        this.jumlah_jam = getData.jumlah_jam + 1
        for (let i = 1; i < this.jumlah_jam; i++) {
          this.data_jam.push(i)
        } 
        this.addModalShow = true
        //this.getGuru()
      })
    },
    addSelect(){
      this.siswa_bolos = this.siswa_bolos + 1
      this.form.jam[this.siswa_bolos] = []
    },
    changeHari(){
      this.loading_jam = true
      this.data_jam = []
      this.$http.post('/presensi/get-hari', this.form).then(response => {
        this.loading_jam = false
        let getData = response.data
        this.jumlah_jam = getData.jumlah_jam + 1
        for (let i = 1; i < this.jumlah_jam; i++) {
          this.data_jam.push(i)
        } 
      })
    },
    changeTingkat(val){
      this.loading_select = true
      this.$http.post('/referensi/get-rombel', this.form).then(response => {
        this.loading_select = false
        this.data_rombel = response.data
      })
    },
    changeRombel(val){
      this.data_pd = []
      this.loading_anggota = true
      this.$http.post('/referensi/get-siswa', this.form).then(response => {
        this.loading_anggota = false
        let getData = response.data
        this.nama_kelas = getData.rombel.nama
        this.data_pd = getData.pd
        var _this = this
        this.data_pd.forEach(item => {
          for (var i = 1; i < (_this.jumlah_jam + 1); i++) {
            _this.absensi[item.anggota_rombel.anggota_rombel_id+'#'+i] = (_this.getAbsen(item.presensi, i)[0]) ? _this.getAbsen(item.presensi, i)[0].absen : ''
          }
        });
        this.pd_absen = this.data_pd.filter((item) => {
          return item.presensi.length > 0
        });
      })
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
      this.form.anggota_rombel_id = {}
      this.form.jam = {}
      this.form.absensi = {}
      this.siswa_bolos = 1
      this.allSelected = {}
      this.data_jam = []
      this.data_pd = []
      this.pd_absen = []
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/presensi/simpan', {
        aksi: 'pd',
        tanggal: this.form.tanggal,
        absensi: this.form.absensi,
        anggota_rombel_id: this.form.anggota_rombel_id,
        jam: this.form.jam,
        //form: this.form,
      }).then(response => {
        this.loading_form = false
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          html: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.hideModal()
          this.$emit('reload')
        })
      }).catch(error => {
        console.log(error);
      })
    },
    onContext(ctx) {
      this.formatted = ctx.selectedFormatted
    },
    getPresensi(presensi, opsi){
      return presensi.filter((item) => {
        return item.absen == opsi
      })
    },
    hapusPresensi(anggota_rombel_id){
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
            this.$http.post('/presensi/hapus', {
              anggota_rombel_id: anggota_rombel_id,
              tanggal: this.form.tanggal,
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
                this.changeRombel(this.form.rombongan_belajar_id)
              })
            });
          }
        })
    }
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