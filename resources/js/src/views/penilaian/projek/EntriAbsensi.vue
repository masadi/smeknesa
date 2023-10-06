<template>
  <b-row>
    <b-col cols="12">
      <b-card>
        <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
          <b-form ref="form" @submit.prevent="handleSubmit">
            <b-row>
              <b-col cols="12">
                <b-form-group label="Kelas Ekskul" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="feedback.rombongan_belajar_id" :state="state.rombongan_belajar_id">
                  <b-overlay :show="loading_rombel" opacity="0.6" size="md" spinner-variant="secondary">
                    <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Kelas Ekskul ==" :state="state.rombongan_belajar_id" @input="changeEkstra">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-overlay>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-overlay :show="loading_table" rounded opacity="0.6" size="lg" spinner-variant="danger">
                  <template v-if="show">
                    <b-table-simple bordered class="mt-2">
                      <b-thead>
                        <b-tr>
                          <b-th class="text-center">Nama Siswa</b-th>
                          <template v-for="item in ['A', 'I', 'S']">
                            <b-th class="text-center">{{ item }}</b-th>
                          </template>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <template v-if="data_siswa.length">
                          <b-tr v-for="siswa in data_siswa" :key="siswa.peserta_didik_id">
                            <b-td><media-siswa :data="siswa" /></b-td>
                            <b-td><b-form-input v-model="form.alpa[siswa.anggota_rombel.anggota_rombel_id]"></b-form-input></b-td>
                            <b-td><b-form-input v-model="form.izin[siswa.anggota_rombel.anggota_rombel_id]"></b-form-input></b-td>
                            <b-td><b-form-input v-model="form.sakit[siswa.anggota_rombel.anggota_rombel_id]"></b-form-input></b-td>
                          </b-tr>
                        </template>
                      </b-tbody>
                    </b-table-simple>
                    <b-row class="mt-2">
                      <b-col cols="12">
                        <b-button type="submit" variant="primary" class="float-right ml-1">Simpan</b-button>
                      </b-col>
                    </b-row>
                  </template>
                </b-overlay>
              </b-col>
            </b-row>
          </b-form>
        </b-overlay>
      </b-card>
    </b-col>
  </b-row>
</template>

<script>
import { BRow, BCol, BCard, BOverlay, BForm, BFormGroup, BFormInput, BButton, BTableSimple, BThead, BTbody, BTr, BTh, BTd, VBTooltip } from 'bootstrap-vue'
import vSelect from 'vue-select'
import MediaSiswa from '@/views/components/MediaSiswa.vue'
export default {
  components: {
    vSelect,
    MediaSiswa,
    BRow,
    BCol,
    BCard,
    BOverlay,
    BForm,
    BFormGroup,
    BFormInput,
    BButton, 
    BTableSimple,
    BThead,
    BTbody,
    BTr,
    BTh,
    BTd,
    VBTooltip,
  },
  directives: {
    'b-tooltip': VBTooltip,
  },
  data() {
    return {
      loading_form: false,
      loading_rombel: false,
      loading_table: false,
      show: false,
      form: {
        rombongan_belajar_id: '',
        alpa: {},
        izin: {},
        sakit: {},
      },
      feedback: {
        rombongan_belajar_id: '',
      },
      state: {
        rombongan_belajar_id: null,
      },
      data_rombel: [],
      data_siswa: [],
    }
  },
  created() {
    this.form.guru_id = this.user.guru_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    this.loadPostsData()
  },
  methods: {
    loadPostsData(){
      this.loading_rombel = true
      this.$http.post('/nilai/get-ektra', this.form).then(response => {
        this.loading_rombel = false;
        let getData = response.data
        this.data_rombel = getData.data
      })
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/nilai/add-absen-ektra', this.form).then(response => {
        this.loading_form = false;
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          html: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
          allowOutsideClick: false,
        }).then(result => {
          this.resetForm()
        })
      })
    },
    resetForm(){
      this.show = false
      this.form.rombongan_belajar_id = ''
      this.form.absen = {}
      this.feedback.rombongan_belajar_id = ''
      this.state.rombongan_belajar_id = null
    },
    changeEkstra(val){
      this.show = false
      if(val){
        this.loading_table = true
        this.$http.post('/nilai/get-siswa-ektra', this.form).then(response => {
          this.loading_table = false;
          let getData = response.data
          this.data_siswa = getData.data
          this.data_siswa.forEach(siswa => {
            this.form.alpa[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.absensi_ekstra) ? siswa.anggota_rombel.absensi_ekstra.alpa : ''
            this.form.izin[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.absensi_ekstra) ? siswa.anggota_rombel.absensi_ekstra.izin : ''
            this.form.sakit[siswa.anggota_rombel.anggota_rombel_id] = (siswa.anggota_rombel.absensi_ekstra) ? siswa.anggota_rombel.absensi_ekstra.sakit  : ''
          });
          this.show = true
        })
      }
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>