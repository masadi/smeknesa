<template>
  <b-modal v-model="addModalShow" title="Input Presensi Siswa" @hidden="hideModal" @ok="handleOk" modal-class="modal-fullscreen" scrollable>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Hari, Tanggal" label-for="tanggal" label-cols-md="3">
              <b-form-datepicker id="tanggal" v-model="form.tanggal" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal"  @context="onContext" placeholder="== Pilih Tanggal ==" />
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
                  <b-overlay :show="loading_anggota" opacity="0.6" size="md" spinner-variant="secondary">
                    <v-select id="jam" v-model="form.jam[bolos]" :options="data_jam" placeholder="== Pilih Jam ==" style="width:300px" class="mr-1" multiple>
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-overlay>
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
        <!--b-overlay :show="loading_table" opacity="0.6" size="md" spinner-variant="danger">
          <BTableSimple bordered>
            <BThead>
              <BTr>
                <BTh rowspan="2" style="vertical-align:middle;">Nama Lengkap</BTh>
                <BTh rowspan="2" style="vertical-align:middle;" class="text-center">NISN</BTh>
                <BTh :colspan="jumlah_jam" class="text-center">Absensi Jam Ke</BTh>
              </BTr>
              <BTr>
                <template v-for="jam_ke in jumlah_jam">
                  <BTh class="text-center">{{jam_ke}}</BTh>
                </template>
              </BTr>
            </BThead>
            <BTbody>
              <template v-if="data_pd.length">
                <template v-for="pd in data_pd">
                  <BTr>
                    <BTd>{{pd.nama}}</BTd>
                    <BTd class="text-center">{{pd.nisn}}</BTd>
                    <template v-for="jam_ke in jumlah_jam">
                      <BTd>
                        <b-form-select v-model="absensi[pd.anggota_rombel.anggota_rombel_id+'#'+jam_ke]" :options="['A', 'S', 'I', 'D']"></b-form-select>
                      </BTd>
                    </template>
                  </BTr>
                </template>
              </template>
              <template v-else>
                <BTr>
                  <BTd :colspan="jumlah_jam + 2" class="text-center">Tidak ada data untuk ditampilkan</BTd>
                </BTr>
              </template>
            </BTbody>
          </BTableSimple>
        </b-overlay-->
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
import { BOverlay, BRow, BCol, BForm, BTableSimple, BThead, BTh, BTbody, BTr, BTd, BButton, BFormSelect, BFormDatepicker, BFormGroup, BInputGroup, BInputGroupAppend } from 'bootstrap-vue'
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
    vSelect,
  },
  data() {
    return {
      addModalShow: false,
      loading_form: false,
      loading_select: false,
      loading_table: false,
      form: {
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
    }
  },
  created() {
    eventBus.$on('open-modal-presensi-pd', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.$http.get('/presensi/get_hari').then(response => {
        let getData = response.data
        this.form.tanggal = getData.tanggal
        this.jumlah_jam = getData.jumlah_jam
        for (let i = 1; i < this.jumlah_jam; i++) {
          this.data_jam.push(i)
        } 
        this.addModalShow = true
        //this.getGuru()
      })
    },
    addSelect(){
      this.siswa_bolos = this.siswa_bolos + 1
    },
    changeTingkat(val){
      this.loading_select = true
      this.$http.post('/referensi/get-rombel', this.form).then(response => {
        this.loading_select = false
        this.data_rombel = response.data
      })
    },
    changeRombel(val){
      this.loading_anggota = true
      this.$http.post('/referensi/get-siswa', this.form).then(response => {
        this.loading_anggota = false
        this.data_pd = response.data
        var _this = this
        this.data_pd.forEach(item => {
          for (var i = 1; i < (_this.jumlah_jam + 1); i++) {
            _this.absensi[item.anggota_rombel.anggota_rombel_id+'#'+i] = (_this.getAbsen(item.presensi, i)[0]) ? _this.getAbsen(item.presensi, i)[0].absen : ''
          }
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
      this.siswa_bolos = 1
      this.data_jam = []
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