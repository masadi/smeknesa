<template>
  <b-card no-body>
    <b-overlay :show="isBusy" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-card-body>
        <b-form @submit="onSubmit">
          <b-row>
            <b-col cols="12">
              <b-form-group label="Tahun Pelajaran" label-for="tahun_pelajaran" label-cols-md="3">
                <b-form-input id="tahun_pelajaran" :value="form.periode_aktif" disabled />
              </b-form-group>
            </b-col>
            <b-col cols="12">
              <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="tingkat_feedback" :state="tingkat_state">
                <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" :searchable="false" :state="tingkat_state" @input="changeTingkat">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-form-group>
            </b-col>
            <b-col cols="12">
              <b-form-group label="Rombongan Belajar" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="rombongan_belajar_id_feedback" :state="rombongan_belajar_id_state">
                <b-overlay :show="loading_rombel" opacity="0.6" size="md" spinner-variant="secondary">
                  <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Rombongan Belajar ==" @input="changeRombel" :state="rombongan_belajar_id_state">
                    <template #no-options="{ search, searching, loading }">
                      Tidak ada data untuk ditampilkan
                    </template>
                  </v-select>
                </b-overlay>
              </b-form-group>
            </b-col>
            <b-col cols="12" v-if="show_table">
              <b-table-simple bordered responsive>
                <b-thead>
                  <b-tr>
                    <b-th class="text-center" rowspan="2">Nama Peserta Didik</b-th>
                    <template v-for="rencana in rencana_projek" v-if="rencana.elemen_rencana_projek.length">
                      <b-th class="text-center" :colspan="rencana.elemen_rencana_projek.length">
                        {{`${rencana.nama} (${rencana.pembelajaran.nama_mata_pelajaran})`}}
                      </b-th>
                    </template>
                  </b-tr>
                  <b-tr>
                    <template v-for="rencana in rencana_projek">
                      <template v-for="(elemen, index) in rencana.elemen_rencana_projek">
                        <b-th class="text-center" style="font-style: normal;">
                          <a href="javascript:void(0)" v-b-tooltip.hover.html="tipData[elemen.elemen_rencana_id]">{{index + 1}}</a>
                        </b-th>
                      </template>
                    </template>
                  </b-tr>
                </b-thead>
                <b-tbody>
                  <template v-for="item in data_siswa">
                    <b-tr>
                      <b-td rowspan="2">{{item.nama}}</b-td>
                      <template v-for="rencana in rencana_projek">
                        <template v-for="(elemen, index) in rencana.elemen_rencana_projek">
                          <b-td>
                            <b-form-select v-model="form.nilai[item.anggota_rombel.anggota_rombel_id+'#'+elemen.elemen_rencana_id]" style="min-width:100px">
                              <b-form-select-option :value="null" disabled>-</b-form-select-option>
                              <template v-for="opsi in data_opsi">
                                <b-form-select-option :value="`${opsi.id}#${elemen.elemen_projek.id}`">{{opsi.nama}}</b-form-select-option>
                              </template>
                            </b-form-select>
                          </b-td>
                        </template>
                      </template>
                    </b-tr>
                    <b-tr>
                      <template v-for="rencana in rencana_projek" v-if="rencana.elemen_rencana_projek.length">
                        <b-td :colspan="rencana.elemen_rencana_projek.length">
                          <b-form-textarea title="Catatan proses" placeholder="Catatan proses" v-model="form.deskripsi[rencana.rencana_projek_id+'#'+item.anggota_rombel.anggota_rombel_id]"></b-form-textarea>
                        </b-td>
                      </template>
                    </b-tr>
                  </template>
                </b-tbody>
              </b-table-simple>
            </b-col>
            <b-col cols="12">
              <b-form-group>
                <b-button type="submit" variant="primary" class="float-right ml-1" v-if="show_table">Simpan</b-button>
              </b-form-group>
            </b-col>
          </b-row>
        </b-form>
      </b-card-body>
    </b-overlay>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BOverlay, BForm, BFormGroup, BInputGroup, BFormInput, BFormSelect, BFormSelectOption, BFormTextarea, BRow, BCol, BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd, VBTooltip } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BCard,
    BCardBody,
    BOverlay,
    BForm, BFormGroup, BFormInput, BFormSelect, BFormSelectOption, BFormTextarea, BRow, BCol, BButton, BTableSimple, BThead, BTbody, BTh, BTr, BTd,
    BInputGroup, 
    VBTooltip,
    vSelect,
  },
  directives: {
    'b-tooltip': VBTooltip,
  },
  data() {
    return {
      isBusy: false,
      loading_rombel: false,
      form: {
        aksi: 'nilai',
        user_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
        tingkat: '',
        rombongan_belajar_id: '',
        pembelajaran_id: '',
        rencana_penilaian_id: '',
        nilai: {},
        deskripsi: {},
      },
      tingkat_feedback: '',
      tingkat_state: null,
      rombongan_belajar_id_feedback: '',
      rombongan_belajar_id_state: null,
      data_tingkat: [
        {
          id: 10,
          nama: 'Kelas 10',
        },
        {
          id: 11,
          nama: 'Kelas 11',
        },
        {
          id: 12,
          nama: 'Kelas 12',
        },
        {
          id: 13,
          nama: 'Kelas 13',
        },
      ],
      data_rombel: [],
      data_elemen: [],
      show_table: false,
      jumlah_elemen: 0,
      rencana_projek: [],
      data_opsi: [],
      data_siswa: [],
      tipData: {}
    }
  },
  created() {
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
  },
  methods: {
    onSubmit(event){
      event.preventDefault()
      this.isBusy = true
      this.tingkat_feedback = ''
      this.tingkat_state = null
      this.rombongan_belajar_id_state  = null
      this.rombongan_belajar_id_feedback = ''
      this.$http.post('/projek/simpan-nilai', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        if(getData.errors){
          this.tingkat_feedback = (getData.errors.tingkat) ? getData.errors.tingkat.join(', ') : ''
          this.tingkat_state = (getData.errors.tingkat) ? false : null
          this.rombongan_belajar_id_state  = (getData.errors.rombongan_belajar_id) ? false : null
          this.rombongan_belajar_id_feedback = (getData.errors.rombongan_belajar_id) ? getData.errors.rombongan_belajar_id.join(', ') : ''
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.form.tingkat = ''
            this.form.rombongan_belajar_id = ''
            this.form.pembelajaran_id = ''
            this.form.rencana_penilaian_id = ''
            this.form.nilai = {}
            this.form.deskripsi = {}
            this.data_elemen = []
            this.show_table = false
            this.jumlah_elemen = 0
            this.rencana_projek = []
            this.data_opsi = []
            this.data_siswa = []
          })
        }
      })
    },
    changeTingkat(val){
      if(val){
        this.form.rombongan_belajar_id = ''
        this.form.pembelajaran_id = ''
        this.form.mata_pelajaran_id = ''
        this.loading_rombel = true
        this.$http.post('/projek/get-rombel', this.form).then(response => {
          this.loading_rombel = false
          this.data_rombel = response.data
        }).catch(error => {
          console.log(error);
        })
      }
    },
    changeRombel(val){
      this.show_table = false
      if(val){
        this.form.mata_pelajaran_id = ''
        this.form.pembelajaran_id = ''
        this.isBusy = true
        this.$http.post('/projek/get-rencana', this.form).then(response => {
          this.isBusy = false
          let getData = response.data
          this.show_table = true
          this.data_opsi = getData.data_opsi
          this.rencana_projek = getData.rencana_projek
          this.jumlah_elemen = getData.jumlah_elemen
          this.data_siswa = getData.data_siswa
          var tooltip = {}
          /*this.rencana_projek.forEach(function(rencana, key) {
            rencana.elemen_rencana_projek.forEach(function(elemen, key) {
              tooltip[elemen.elemen_rencana_id] = `<p><strong>Dimensi:</strong>${elemen.budaya_kerja.elemen}</p>
              <p><strong>Elemen:</strong> ${elemen.elemen_budaya_kerja.elemen}</p>
              <p><strong>Sub Elemen:</strong> ${elemen.elemen_budaya_kerja.deskripsi}</p>`
            })
          })*/
          this.rencana_projek.forEach(function(rencana, key) {
            rencana.elemen_rencana_projek.forEach(function(elemen, key) {
              tooltip[elemen.elemen_rencana_id] = `<p><strong>Dimensi:</strong>${elemen.dimensi_projek.nama}</p>
              <p><strong>Elemen:</strong> ${elemen.elemen_projek.parent.nama}</p>
              <p><strong>Sub Elemen:</strong> ${elemen.elemen_projek.nama}</p>`
            })
          })
          this.tipData = tooltip
          var nilai = {}
          var deskripsi = {}
          var _this = this
          this.data_siswa.forEach(function(siswa, key) {
            siswa.anggota_rombel.nilai_projek.forEach(function(nilai_projek, key) {
              nilai[siswa.anggota_rombel.anggota_rombel_id+'#'+nilai_projek.elemen_rencana_id] = nilai_projek.opsi_id+'#'+nilai_projek.elemen_id
            })
            siswa.anggota_rombel.catatan_projek.forEach(function(catatan_projek, key) {
              deskripsi[catatan_projek.rencana_projek_id+'#'+siswa.anggota_rombel.anggota_rombel_id] = catatan_projek.catatan
            })
          })
          this.form.nilai = nilai
          this.form.deskripsi = deskripsi
        }).catch(error => {
          console.log(error);
        })
      }
    },
    filterDeskripsi(catatan_budaya_kerja, rencana_projek_id){
      var newArray = catatan_budaya_kerja.filter(function (el) {
        return el.rencana_projek_id === rencana_projek_id;
      });
      return (newArray.length) ? newArray[0].catatan : null
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>