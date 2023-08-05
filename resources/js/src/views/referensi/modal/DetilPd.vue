<template>
  <b-overlay :show="loading_modal" rounded opacity="0.6" size="lg" spinner-variant="danger">
    <b-form @submit.prevent="handleSubmit">
      <b-row v-if="data">
        <b-col cols="12">
          <b-form-group label="Nama Lengkap" label-cols-md="3" :invalid-feedback="(errors.nama) ? errors.nama.join(', ') : ''" :state="(errors.nama) ? Boolean(errors.nama) : null">
            <b-form-input v-model="form.nama" :state="(errors.nama) ? !Boolean(errors.nama) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="NIS" label-cols-md="3" :invalid-feedback="(errors.nis) ? errors.nis.join(', ') : ''" :state="(errors.nis) ? Boolean(errors.nis) : null">
            <b-form-input v-model="form.nis" :state="(errors.nis) ? !Boolean(errors.nis) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="NISN" label-cols-md="3" :invalid-feedback="(errors.nisn) ? errors.nisn.join(', ') : ''" :state="(errors.nisn) ? Boolean(errors.nisn) : null">
            <b-form-input v-model="form.nisn" :state="(errors.nisn) ? !Boolean(errors.nisn) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="NIK" label-cols-md="3" :invalid-feedback="(errors.nik) ? errors.nik.join(', ') : ''" :state="(errors.nik) ? Boolean(errors.nik) : null">
            <b-form-input v-model="form.nik" :state="(errors.nik) ? !Boolean(errors.nik) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Jenis Kelamin" for="jenis_kelamin" label-cols-md="3" :invalid-feedback="(errors.jenis_kelamin) ? errors.jenis_kelamin.join(', ') : ''" :state="(errors.jenis_kelamin) ? !Boolean(errors.jenis_kelamin) : null">
            <v-select id="jenis_kelamin" v-model="form.jenis_kelamin" :options="data_kelamin" :reduce="nama => nama.id" label="nama" placeholder="== Pilih Jenis Kelamin ==" :searchable="false" :state="(errors.jenis_kelamin) ? !Boolean(errors.jenis_kelamin) : null">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Tempat Lahir" label-cols-md="3" :invalid-feedback="(errors.tempat_lahir) ? errors.tempat_lahir.join(', ') : ''" :state="(errors.tempat_lahir) ? Boolean(errors.tempat_lahir) : null">
            <b-form-input v-model="form.tempat_lahir" :state="(errors.tempat_lahir) ? !Boolean(errors.tempat_lahir) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Tanggal Lahir" for="tanggal_lahir" label-cols-md="3" :invalid-feedback="(errors.tanggal_lahir) ? errors.tanggal_lahir.join(', ') : ''" :state="(errors.tanggal_lahir) ? Boolean(errors.tanggal_lahir) : null">
            <b-form-datepicker v-model="form.tanggal_lahir" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal_lahir" @context="onContext" placeholder="== Pilih Tanggal Lahir ==" :state="(errors.tanggal_lahir) ? !Boolean(errors.tanggal_lahir) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Agama" for="agama_id" label-cols-md="3" :invalid-feedback="(errors.agama_id) ? errors.agama_id.join(', ') : ''" :state="(errors.agama_id) ? !Boolean(errors.agama_id) : null">
            <v-select id="agama_id" v-model="form.agama_id" :options="data_agama" :reduce="nama => nama.id" label="nama" placeholder="== Pilih Agama ==" :searchable="false" :state="(errors.agama_id) ? !Boolean(errors.agama_id) : null">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Status dalam keluarga" label-cols-md="3" :invalid-feedback="(errors.status) ? errors.status.join(', ') : ''" :state="(errors.status) ? !Boolean(errors.status) : null">
            <v-select id="status" v-model="form.status" :options="data_status" placeholder="== Pilih Status dalam keluarga ==" :searchable="false" :state="(errors.status) ? !Boolean(errors.status) : null">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Anak Ke" label-cols-md="3" :invalid-feedback="(errors.anak_ke) ? errors.anak_ke.join(', ') : ''" :state="(errors.anak_ke) ? Boolean(errors.anak_ke) : null">
            <b-form-input v-model="form.anak_ke" :state="(errors.anak_ke) ? !Boolean(errors.anak_ke) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Alamat" label-cols-md="3" :invalid-feedback="(errors.alamat_jalan) ? errors.alamat_jalan.join(', ') : ''" :state="(errors.alamat_jalan) ? Boolean(errors.alamat_jalan) : null">
            <b-form-input v-model="form.alamat_jalan" :state="(errors.alamat_jalan) ? !Boolean(errors.alamat_jalan) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Provinsi" label-cols-md="3" :invalid-feedback="(errors.provinsi_id) ? errors.provinsi_id.join(', ') : ''" :state="(errors.provinsi_id) ? !Boolean(errors.provinsi_id) : null">
            <v-select id="status" v-model="form.provinsi_id" :reduce="name => name.code" label="name" :options="data_provinsi" placeholder="== Pilih Provinsi ==" :state="(errors.provinsi_id) ? !Boolean(errors.provinsi_id) : null" @input="getKabupaten">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
            <div class="invalid-feedback" v-if="errors.provinsi_id">{{errors.provinsi_id.join(', ')}}</div>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Kabupaten" label-cols-md="3" :invalid-feedback="(errors.kabupaten_id) ? errors.kabupaten_id.join(', ') : ''" :state="(errors.kabupaten_id) ? !Boolean(errors.kabupaten_id) : null">
            <v-select id="status" v-model="form.kabupaten_id" :reduce="name => name.code" label="name" :options="data_kabupaten" placeholder="== Pilih Kabupaten ==" :state="(errors.kabupaten_id) ? !Boolean(errors.kabupaten_id) : null" @input="getKecamatan">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Kecamatan" label-cols-md="3" :invalid-feedback="(errors.kecamatan_id) ? errors.kecamatan_id.join(', ') : ''" :state="(errors.kecamatan_id) ? !Boolean(errors.kecamatan_id) : null">
            <v-select id="status" v-model="form.kecamatan_id" :reduce="name => name.code" label="name" :options="data_kecamatan" placeholder="== Pilih Kecamatan ==" :state="(errors.provinsi_id) ? !Boolean(errors.provinsi_id) : null" @input="getDesa">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Desa/Kelurahan" label-cols-md="3" :invalid-feedback="(errors.desa_id) ? errors.desa_id.join(', ') : ''" :state="(errors.desa_id) ? !Boolean(errors.desa_id) : null">
            <v-select id="status" v-model="form.desa_id" :reduce="name => name.code" label="name" :options="data_desa" placeholder="== Pilih Desa/Kelurahan ==" :state="(errors.desa_id) ? !Boolean(errors.desa_id) : null">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="RT" label-cols-md="3" :invalid-feedback="(errors.rt) ? errors.rt.join(', ') : ''" :state="(errors.rt) ? Boolean(errors.rt) : null">
            <b-form-input v-model="form.rt" :state="(errors.rt) ? !Boolean(errors.rt) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="RW" label-cols-md="3" :invalid-feedback="(errors.rw) ? errors.rw.join(', ') : ''" :state="(errors.rw) ? Boolean(errors.rw) : null">
            <b-form-input v-model="form.rw" :state="(errors.rw) ? !Boolean(errors.rw) : null" />
          </b-form-group>
        </b-col>
        <!--b-col cols="12">
          <b-form-group label="Desa/Kelurahan" label-cols-md="3" :invalid-feedback="(errors.desa_kelurahan) ? errors.desa_kelurahan.join(', ') : ''" :state="(errors.desa_kelurahan) ? Boolean(errors.desa_kelurahan) : null">
            <b-form-input v-model="form.desa_kelurahan" :state="(errors.desa_kelurahan) ? !Boolean(errors.desa_kelurahan) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Kecamatan" label-cols-md="3" :invalid-feedback="(errors.kecamatan) ? errors.kecamatan.join(', ') : ''" :state="(errors.kecamatan) ? Boolean(errors.kecamatan) : null">
            <b-form-input v-model="form.kecamatan" :state="(errors.kecamatan) ? !Boolean(errors.kecamatan) : null" />
          </b-form-group>
        </b-col-->
        <b-col cols="12">
          <b-form-group label="Kodepos" label-cols-md="3" :invalid-feedback="(errors.kode_pos) ? errors.kode_pos.join(', ') : ''" :state="(errors.kode_pos) ? Boolean(errors.kode_pos) : null">
            <b-form-input v-model="form.kode_pos" :state="(errors.kode_pos) ? !Boolean(errors.kode_pos) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Telp/HP" label-cols-md="3" :invalid-feedback="(errors.no_hp) ? errors.no_hp.join(', ') : ''" :state="(errors.no_hp) ? Boolean(errors.no_hp) : null">
            <b-form-input v-model="form.no_hp" :state="(errors.no_hp) ? !Boolean(errors.no_hp) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Asal Sekolah" label-cols-md="3" :invalid-feedback="(errors.sekolah_asal) ? errors.sekolah_asal.join(', ') : ''" :state="(errors.sekolah_asal) ? Boolean(errors.sekolah_asal) : null">
            <b-form-input v-model="form.sekolah_asal" :state="(errors.sekolah_asal) ? !Boolean(errors.sekolah_asal) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Diterima dikelas" label-cols-md="3" :invalid-feedback="(errors.diterima_kelas) ? errors.diterima_kelas.join(', ') : ''" :state="(errors.diterima_kelas) ? Boolean(errors.diterima_kelas) : null">
            <b-form-input v-model="form.diterima_kelas" :state="(errors.diterima_kelas) ? !Boolean(errors.diterima_kelas) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Diterima pada tanggal" for="diterima" label-cols-md="3" :invalid-feedback="(errors.diterima) ? errors.diterima.join(', ') : ''" :state="(errors.diterima) ? Boolean(errors.diterima) : null">
            <!--b-form-input v-model="form.diterima" /-->
            <b-form-datepicker v-model="form.diterima" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="diterima" @context="onContext" placeholder="== Pilih Tanggal Diterima ==" :state="(errors.diterima) ? !Boolean(errors.diterima) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Email" label-cols-md="3" :invalid-feedback="(errors.email) ? errors.email.join(', ') : ''" :state="(errors.email) ? Boolean(errors.email) : null">
            <b-form-input v-model="form.email" :state="(errors.email) ? !Boolean(errors.email) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Nama Ayah" label-cols-md="3" :invalid-feedback="(errors.nama_ayah) ? errors.nama_ayah.join(', ') : ''" :state="(errors.nama_ayah) ? Boolean(errors.nama_ayah) : null">
            <b-form-input v-model="form.nama_ayah" :state="(errors.nama_ayah) ? !Boolean(errors.nama_ayah) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Pekerjaan Ayah" for="kerja_ayah" label-cols-md="3" :invalid-feedback="(errors.kerja_ayah) ? errors.kerja_ayah.join(', ') : ''" :state="(errors.kerja_ayah) ? !Boolean(errors.kerja_ayah) : null">
            <v-select id="kerja_ayah" v-model="form.kerja_ayah" :reduce="nama => nama.id" label="nama" :options="data_kerja" placeholder="== Pilih Pekerjaan Ayah ==" :state="(errors.kerja_ayah) ? !Boolean(errors.kerja_ayah) : null">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Nama Ibu Kandung" label-cols-md="3" :invalid-feedback="(errors.nama_ibu) ? errors.nama_ibu.join(', ') : ''" :state="(errors.nama_ibu) ? Boolean(errors.nama_ibu) : null">
            <b-form-input v-model="form.nama_ibu" :state="(errors.nama_ibu) ? !Boolean(errors.nama_ibu) : null" />
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Pekerjaan Ibu" for="kerja_ibu" label-cols-md="3" :invalid-feedback="(errors.kerja_ibu) ? errors.kerja_ibu.join(', ') : ''" :state="(errors.kerja_ibu) ? !Boolean(errors.kerja_ibu) : null">
            <v-select id="kerja_ibu" v-model="form.kerja_ibu" :reduce="nama => nama.id" label="nama" :options="data_kerja" placeholder="== Pilih Pekerjaan Ibu ==" :state="(errors.kerja_ibu) ? !Boolean(errors.kerja_ibu) : null">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group label="Cita-cita" for="cita" label-cols-md="3" :invalid-feedback="(errors.cita) ? errors.cita.join(', ') : ''" :state="(errors.cita) ? !Boolean(errors.cita) : null">
            <v-select id="cita" v-model="form.cita" :options="['Bekerja', 'Kuliyah', 'Wirausaha', 'Polisi/TNI', 'Belum Tahu']" placeholder="== Pilih Cita-cita ==" :state="(errors.cita) ? !Boolean(errors.cita) : null">
              <template #no-options="{ search, searching, loading }">
                Tidak ada data untuk ditampilkan
              </template>
            </v-select>
          </b-form-group>
        </b-col>
      </b-row>
    </b-form>
  </b-overlay>
</template>

<script>
import { BOverlay, BRow, BCol, BForm, BFormGroup, BFormInput, BFormDatepicker } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay,
    BRow, BCol, BForm, BFormGroup, BFormInput, BFormDatepicker,
    vSelect
  },
  props: {
    form: {
      type: Object,
      required: true
    },
    errors: {
      type: Object,
      required: true
    },
    data: {
      required: true
    },
    pekerjaan: {
      type: Array,
      required: true
    },
    loading_modal: {
      type: Boolean,
      default: () => false,
    }
  },
  data() {
    return {
      data_status: ['Anak Kandung', 'Anak Tiri', 'Anak Angkat'],
      data_agama: [
        {
          id: 1,
          nama : "Islam",
        },
        {
          id: 2,
          nama : "Kristen",
        },
        {
          id: 3,
          nama : "Katholik",
        },
        {
          id: 4,
          nama : "Hindu",
        },
        {
          id: 5,
          nama : "Buddha",
        },
        {
          id: 6,
          nama : "Kong Hu Chu",
        },
        {
          id: 7,
          nama : "Kepercayaan kpd Tuhan YME",
        },
        {
          id: 98,
          nama : "Tidak diisi",
        },
        {
          id: 99,
          nama : "Lainnya"
        }
      ],
      data_kerja: [
        {
          id: 1,
          nama: 'Tidak bekerja',
        },
        {
          id: 2,
          nama: 'Nelayan'
        },
        {
          id: 3,
          nama: 'Petani'
        },
        {
          id: 4,
          nama: 'Peternak'
        },
        {
          id: 5,
          nama: 'PNS/TNI/Polri'
        },
        {
          id: 6,
          nama: 'Karyawan Swasta'
        },
        {
          id: 7,
          nama: 'Pedagang Kecil'
        },
        {
          id: 8,
          nama: 'Pedagang Besar'
        },
        {
          id: 9,
          nama: 'Wiraswasta'
        },
        {
          id: 10,
          nama: 'Wirausaha'
        },
        {
          id: 11,
          nama: 'Buruh'
        },
        {
          id: 12,
          nama: 'Pensiunan'
        },
        {
          id: 13,
          nama: 'Tenaga Kerja Indonesia'
        },
        {
          id: 14,
          nama: 'Karyawan BUMN'
        },
        {
          id: 90,
          nama: 'Tidak dapat diterapkan'
        },
        {
          id: 98,
          nama: 'Sudah Meninggal'
        },
        {
          id: 99,
          nama: 'Lainnya'
        }
      ],
      data_kelamin: [{nama: 'Laki-laki', id: 'L'}, {nama: 'Perempuan', id: 'P'}],
      data_provinsi: [],
      data_kabupaten: [],
      data_kecamatan: [],
      data_desa: [],
    }
  },
  created(){
    this.getProvinsi()
  },
  methods: {
    onContext(ctx) {
      this.formatted = ctx.selectedFormatted
    },
    getProvinsi(){
      this.$http.post('/referensi/wilayah').then(response => {
        this.data_provinsi = response.data
      })
    },
    getKabupaten(val){
      this.form.kabupaten_id = ''
      this.form.kecamatan_id = ''
      this.form.desa_id = ''
      if(val){
        this.$http.post('/referensi/wilayah', {
          provinsi_id: val,
        }).then(response => {
          this.data_kabupaten = response.data
        })
      }
    },
    getKecamatan(val){
      this.form.kecamatan_id = ''
      this.form.desa_id = ''
      if(val){
        this.$http.post('/referensi/wilayah', {
          kabupaten_id: val,
        }).then(response => {
          this.data_kecamatan = response.data
        })
      }
    },
    getDesa(val){
      this.form.desa_id = ''
      if(val){
        this.$http.post('/referensi/wilayah', {
          kecamatan_id: val,
        }).then(response => {
          this.data_desa = response.data
        })
      }
    },
  },
}
</script>