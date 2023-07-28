<template>
  <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="danger">
    <b-row>
      <b-col cols="8">
        <b-form-file v-model="file" placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..." @change="onFileChange" :state="fileState" />
        <p v-show="feedback_file" class="text-danger">{{feedback_file}}</p>
      </b-col>
        <b-col cols="4">
        <b-button block variant="warning" :href="link_excel" target="_blank">UNDUH TEMPLATE</b-button>
      </b-col>
    </b-row>
    <b-row class="mt-2" v-if="imported_data.length">
      <b-col cols="12">
        <b-table-simple hover bordered responsive>
          <b-thead>
            <b-tr>
              <b-th class="text-center">No</b-th>
              <b-th class="text-center">Nama</b-th>
              <b-th class="text-center">NUPTK</b-th>
              <b-th class="text-center">NIP</b-th>
              <b-th class="text-center">NIK</b-th>
              <b-th class="text-center">Jenis Kelamin</b-th>
              <b-th class="text-center">Tempat Lahir</b-th>
              <b-th class="text-center">Tanggal Lahir</b-th>
              <b-th class="text-center">Agama</b-th>
              <b-th class="text-center">Alamat Jalan</b-th>
              <b-th class="text-center">RT</b-th>
              <b-th class="text-center">RW</b-th>
              <b-th class="text-center">Desa/Kelurahan</b-th>
              <b-th class="text-center">Kecamatan</b-th>
              <b-th class="text-center">Kodepos</b-th>
              <b-th class="text-center">Telp/HP</b-th>
              <b-th class="text-center">Email</b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <b-tr v-for="(item, index) in imported_data" :key="item.no">
              <b-td class="text-center">{{item.no}}</b-td>
              <b-td>
                <b-form-group :invalid-feedback="feedback.nama[item.no]" :state="state.nama[item.no]">
                  <b-form-input v-model="form.nama[item.no]"></b-form-input>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group>
                  <b-form-input v-model="form.nuptk[item.no]"></b-form-input>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group>
                  <b-form-input v-model="form.nip[item.no]"></b-form-input>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group :invalid-feedback="feedback.nik[item.no]" :state="state.nik[item.no]">
                  <b-form-input v-model="form.nik[item.no]"></b-form-input>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group>
                  <b-form-input v-model="form.jenis_kelamin[item.no]"></b-form-input>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group>
                  <b-form-input v-model="form.tempat_lahir[item.no]"></b-form-input>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group :invalid-feedback="feedback.tanggal_lahir[item.no]" :state="state.tanggal_lahir[item.no]">
                  <b-form-input v-model="form.tanggal_lahir[item.no]"></b-form-input>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group :invalid-feedback="feedback.agama[item.no]" :state="state.agama[item.no]">
                  <b-form-input v-model="form.agama[item.no]"></b-form-input>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group>
                  <b-form-input v-model="form.alamat_jalan[item.no]"></b-form-input>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group>
                  <b-form-input v-model="form.rt[item.no]"></b-form-input>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group>
                  <b-form-input v-model="form.rw[item.no]"></b-form-input>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group>
                  <b-form-input v-model="form.desa_kelurahan[item.no]"></b-form-input>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group>
                  <b-form-input v-model="form.kecamatan[item.no]"></b-form-input>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group>
                  <b-form-input v-model="form.kodepos[item.no]"></b-form-input>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group>
                  <b-form-input v-model="form.telp_hp[item.no]"></b-form-input>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group :invalid-feedback="feedback.email[item.no]" :state="state.email[item.no]">
                  <b-form-input v-model="form.email[item.no]"></b-form-input>
                </b-form-group>
              </b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
      </b-col>
    </b-row>
  </b-overlay>
</template>

<script>
import { BOverlay, BForm, BFormFile, BFormInput, BRow, BCol, BFormGroup, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton, } from 'bootstrap-vue'
export default {
  components: {
    BOverlay, BForm, BFormFile, BFormInput, BRow, BCol, BFormGroup, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton,
  },
  props: {
    form: {
      type: Object,
      required: true,
    },
    state: {
      type: Object,
      required: true
    },
    feedback: {
      type: Object,
      required: true
    },
    link_excel: {
      type: String,
      required: true,
    },
    loading: {
      type: Boolean,
      default: () => false,
    },
  },
  data() {
    return {
      file: null,
      fileState: null,
      feedback_file: '',
      imported_data: [],
      data_status: [
        { value: null, text: '== Pilih Status ==' },
        { value: '1', text: 'Aktif' },
        { value: '0', text: 'Tidak Aktif' },
      ],
    }
  },
  methods: {
    onFileChange(e) {
      this.$emit('loading', true)
      this.$emit('simpan', false)
      this.file = e.target.files[0];
      const data = new FormData();
      data.append('file_excel', (this.file) ? this.file : '');
      this.$http.post('/guru/upload', data).then(response => {
        this.$emit('loading', false)
        let data = response.data
        this.fileState = null
        this.feedback_file = ''
        if(data.errors){
          this.fileState = (data.errors.file_excel) ? false : null
          this.feedback_file = (data.errors.file_excel) ? data.errors.file_excel.join(', ') : ''
        } else {
          this.$emit('simpan', true)
          this.imported_data = data.imported_data
          this.$emit('imported_data', this.imported_data)
          var nama = {}
          var nuptk = {}
          var nip = {}
          var nik = {}
          var jenis_kelamin = {}
          var tempat_lahir = {}
          var tanggal_lahir = {}
          var agama = {}
          var alamat_jalan = {}
          var rt = {}
          var rw = {}
          var desa_kelurahan = {}
          var kecamatan = {}
          var kodepos = {}
          var telp_hp = {}
          var email = {}
          var nama_state = {}
          var nik_state = {}
          var tanggal_lahir_state = {}
          var agama_state = {}
          var email_state = {}
          var nama_feedback = {}
          var nik_feedback = {}
          var tanggal_lahir_feedback = {}
          var agama_feedback = {}
          var email_feedback = {}
          this.imported_data.forEach(function(value, key) {
            nama[value.no] = value.nama
            nuptk[value.no] = value.nuptk
            nip[value.no] = value.nip
            nik[value.no] = value.nik
            jenis_kelamin[value.no] = value.jenis_kelamin
            tempat_lahir[value.no] = value.tempat_lahir
            tanggal_lahir[value.no] = value.tanggal_lahir
            agama[value.no] = value.agama
            alamat_jalan[value.no] = value.alamat_jalan
            rt[value.no] = value.rt
            rw[value.no] = value.rw
            desa_kelurahan[value.no] = value.desa_kelurahan
            kecamatan[value.no] = value.kecamatan
            kodepos[value.no] = value.kodepos
            telp_hp[value.no] = value.telp_hp
            email[value.no] = value.email
            nama_state[value.no] = null
            nik_state[value.no] = null
            tanggal_lahir_state[value.no] = null
            agama_state[value.no] = null
            email_state[value.no] = null
            nama_feedback[value.no] = ''
            nik_feedback[value.no] = ''
            tanggal_lahir_feedback[value.no] = ''
            agama_feedback[value.no] = ''
            email_feedback[value.no] = ''
          });
          this.form.nama = nama
          this.form.nuptk = nuptk
          this.form.nip = nip
          this.form.nik = nik
          this.form.jenis_kelamin = jenis_kelamin
          this.form.tempat_lahir = tempat_lahir
          this.form.tanggal_lahir = tanggal_lahir
          this.form.agama = agama
          this.form.alamat_jalan = alamat_jalan
          this.form.rt = rt
          this.form.rw = rw
          this.form.desa_kelurahan = desa_kelurahan
          this.form.kecamatan = kecamatan
          this.form.kodepos = kodepos
          this.form.telp_hp = telp_hp
          this.form.email = email
          this.state.nama = nama_state
          this.state.nik = nik_state
          this.state.tanggal_lahir = tanggal_lahir_state
          this.state.agama = agama_state
          this.state.email = email_state
          this.feedback.nama = nama_feedback
          this.feedback.nik = nik_feedback
          this.feedback.tanggal_lahir = tanggal_lahir_feedback
          this.feedback.agama = agama_feedback
          this.feedback.email = email_feedback
        }
      }).catch(error => {
        console.log(error);
        this.fileState = false
        this.feedback_file = 'Isian salah. Silahkan periksa kembali!!!'
      })
    },
  },
}
</script>