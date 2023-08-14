<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="danger">
          <b-form @submit="onSubmit">
            <b-row>
              <b-col cols="12">
                <b-form-group label="Nama" label-for="nama" label-cols-md="3" :invalid-feedback="feedback.nama" :state="state.nama">
                  <b-form-input v-model="form.nama" :state="state.nama" :disabled="!isAdmin"></b-form-input>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label="NPSN" label-for="npsn" label-cols-md="3" :invalid-feedback="feedback.npsn" :state="state.npsn">
                  <b-form-input v-model="form.npsn" :state="state.npsn" :disabled="!isAdmin"></b-form-input>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label="Alamat" label-for="alamat_jalan" label-cols-md="3" :invalid-feedback="feedback.alamat_jalan" :state="state.alamat_jalan">
                  <b-form-input v-model="form.alamat_jalan" :state="state.alamat_jalan" :disabled="!isAdmin"></b-form-input>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label="Provinsi" label-for="provinsi_id" label-cols-md="3" :invalid-feedback="feedback.provinsi_id" :state="state.provinsi_id">
                  <b-overlay :show="loading_provinsi_id" opacity="0.6" size="md" spinner-variant="secondary">
                    <v-select id="provinsi_id" v-model="form.provinsi_id" :reduce="name => name.code" label="name" :options="data_provinsi" placeholder="== Pilih Provinsi ==" @input="changeProvinsi" :state="state.provinsi_id" :disabled="!isAdmin">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-overlay>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label="Kabupaten/Kota" label-for="provinsi_id" label-cols-md="3" :invalid-feedback="feedback.kabupaten_id" :state="state.kabupaten_id">
                  <b-overlay :show="loading_kabupaten_id" opacity="0.6" size="md" spinner-variant="secondary">
                    <v-select id="kabupaten_id" v-model="form.kabupaten_id" :reduce="name => name.code" label="name" :options="data_kabupaten" placeholder="== Pilih Kabupaten/Kota ==" @input="changeKabupaten" :state="state.kabupaten_id" :disabled="!isAdmin">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-overlay>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label="Kecamatan" label-for="kecamatan_id" label-cols-md="3" :invalid-feedback="feedback.kecamatan_id" :state="state.kecamatan_id">
                  <b-overlay :show="loading_kecamatan_id" opacity="0.6" size="md" spinner-variant="secondary">
                    <v-select id="kecamatan_id" v-model="form.kecamatan_id" :reduce="name => name.code" label="name" :options="data_kecamatan" placeholder="== Pilih Kecamatan ==" @input="changeKecamatan" :state="state.kecamatan_id" :disabled="!isAdmin">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-overlay>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label="Desa/Kelurahan" label-for="desa_id" label-cols-md="3" :invalid-feedback="feedback.desa_id" :state="state.desa_id">
                  <b-overlay :show="loading_desa_id" opacity="0.6" size="md" spinner-variant="secondary">
                    <v-select id="desa_id" v-model="form.desa_id" :reduce="name => name.code" label="name" :options="data_desa" placeholder="== Pilih Desa/Kelurahan ==" :state="state.desa_id" :disabled="!isAdmin">
                      <template #no-options="{ search, searching, loading }">
                        Tidak ada data untuk ditampilkan
                      </template>
                    </v-select>
                  </b-overlay>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label="Kodepos" label-for="kode_pos" label-cols-md="3" :invalid-feedback="feedback.kode_pos" :state="state.kode_pos">
                  <b-form-input v-model="form.kode_pos" :state="state.kode_pos" :disabled="!isAdmin"></b-form-input>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label="Email" label-for="email" label-cols-md="3" :invalid-feedback="feedback.email" :state="state.email">
                  <b-form-input v-model="form.email" :state="state.email" :disabled="!isAdmin"></b-form-input>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label="Website" label-for="website" label-cols-md="3" :invalid-feedback="feedback.website" :state="state.website">
                  <b-form-input v-model="form.website" :state="state.website" :disabled="!isAdmin"></b-form-input>
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label-cols-md="3">
                  <b-button type="submit" variant="primary" class="float-right ml-1" :disabled="!isAdmin">Simpan</b-button>
                </b-form-group>
              </b-col>
            </b-row>
          </b-form>
        </b-overlay>
      </div>
    </b-card-body>
  </b-card>
</template>

<script>
import { BRow, BCol, BCard, BCardBody, BOverlay, BForm, BFormGroup, BFormInput, BSpinner, BButton } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BRow,
    BCol,
    BCard,
    BCardBody, 
    BOverlay, 
    BForm,
    BFormGroup, 
    BFormInput,
    BSpinner,
    BButton,
    vSelect,
  },
  data() {
    return {
      isAdmin: true,
      periode_aktif: '',
      isBusy: true,
      loading: false,
      data: null,
      form: {},
      feedback: {},
      state: {},
      loading_provinsi_id: false,
      loading_kabupaten_id: false,
      loading_kecamatan_id: false,
      loading_desa_id: false,
      data_provinsi: [],
      data_kabupaten: [],
      data_kecamatan: [],
      data_desa: [],
    }
  },
  created() {
    this.form.sekolah_id = this.user.sekolah_id
    this.loadPostsData()
  },
  methods: {
    loadPostsData() {
      this.isBusy = true
      this.$http.get('/referensi/sekolah', {
        params: this.form
      }).then(response => {
        let getData = response.data
        this.isBusy = false
        this.form = getData.sekolah
        this.data_provinsi = getData.provinsi
        this.data_kabupaten = getData.kabupaten
        this.data_kecamatan = getData.kecamatan
        this.data_desa = getData.desa
        this.isAdmin = getData.isAdmin
      })
    },
    changeProvinsi(val){
      //
    },
    changeKabupaten(val){
      //
    },
    changeKecamatan(val){
      // 
    },
    onSubmit(event) {
      event.preventDefault()
      this.loading = true
      this.$http.post('/referensi/sekolah', this.form).then(response => {
        this.loading = false
        let getData = response.data
        if(getData.errors){
          this.state.nama = (getData.errors.nama) ? false : null
          this.state.npsn = (getData.errors.npsn) ? false : null
          this.state.alamat_jalan = (getData.errors.alamat_jalan) ? false : null
          this.state.kode_pos = (getData.errors.kode_pos) ? false : null
          this.state.provinsi_id = (getData.errors.provinsi_id) ? false : null
          this.state.kabupaten_id = (getData.errors.kabupaten_id) ? false : null
          this.state.kecamatan_id = (getData.errors.kecamatan_id) ? false : null
          this.state.desa_id = (getData.errors.desa_id) ? false : null
          this.state.email = (getData.errors.email) ? false : null
          this.state.website = (getData.errors.website) ? false : null
          this.feedback.nama = (getData.errors.nama) ? getData.errors.nama.join(', ') : ''
          this.feedback.npsn = (getData.errors.npsn) ? getData.errors.npsn.join(', ') : ''
          this.feedback.alamat_jalan = (getData.errors.alamat_jalan) ? getData.errors.alamat_jalan.join(', ') : ''
          this.feedback.kode_pos = (getData.errors.kode_pos) ? getData.errors.kode_pos.join(', ') : ''
          this.feedback.provinsi_id = (getData.errors.provinsi_id) ? getData.errors.provinsi_id.join(', ') : ''
          this.feedback.kabupaten_id = (getData.errors.kabupaten_id) ? getData.errors.kabupaten_id.join(', ') : ''
          this.feedback.kecamatan_id = (getData.errors.kecamatan_id) ? getData.errors.kecamatan_id.join(', ') : ''
          this.feedback.desa_id = (getData.errors.desa_id) ? getData.errors.desa_id.join(', ') : ''
          this.feedback.email = (getData.errors.email) ? getData.errors.email.join(', ') : ''
          this.feedback.website = (getData.errors.website) ? getData.errors.website.join(', ') : ''
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.loadPostsData()
          })
        }
      })
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>