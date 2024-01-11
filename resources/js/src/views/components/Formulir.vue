<template>
  <b-row>
    <b-col cols="12">
      <b-form-group label="Tingkat Kelas" label-for="tingkat" label-cols-md="3" :invalid-feedback="feedback.tingkat" :state="state.tingkat">
        <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat Kelas ==" :searchable="false" :state="state.tingkat" @input="changeTingkat">
          <template #no-options="{ search, searching, loading }">
            Tidak ada data untuk ditampilkan
          </template>
        </v-select>
      </b-form-group>
      <b-form-group label="Kelas" label-for="rombongan_belajar_id" label-cols-md="3" :invalid-feedback="feedback.rombongan_belajar_id" :state="state.rombongan_belajar_id">
        <b-overlay :show="loading_rombel" opacity="0.6" size="md" spinner-variant="secondary">
          <v-select id="tingkat" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Kelas ==" :state="state.rombongan_belajar_id" @input="changeRombel">
            <template #no-options="{ search, searching, loading }">
              Tidak ada data untuk ditampilkan
            </template>
          </v-select>
        </b-overlay>
      </b-form-group>
      <b-form-group label="Mata Pelajaran" label-for="pembelajaran_id" label-cols-md="3" :invalid-feedback="feedback.pembelajaran_id" :state="state.pembelajaran_id">
        <b-overlay :show="loading_mapel" opacity="0.6" size="md" spinner-variant="secondary">
          <v-select id="pembelajaran_id" v-model="form.pembelajaran_id" :reduce="nama_mata_pelajaran => nama_mata_pelajaran.pembelajaran_id" label="nama_mata_pelajaran" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==" :state="state.pembelajaran_id" @input="changeMapel">
            <template #no-options="{ search, searching, loading }">
              Tidak ada data untuk ditampilkan
            </template>
          </v-select>
        </b-overlay>
      </b-form-group>
    </b-col>
  </b-row>
</template>

<script>
import { BRow, BCol, BOverlay, BFormGroup } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BRow, BCol, BOverlay, BFormGroup, vSelect
  },
  props: {
    form: {
      type: Object,
      required: true
    },
    feedback: {
      type: Object,
      required: true
    },
    state: {
      type: Object,
      required: true
    },
  },
  data() {
    return {
      loading_rombel: false,
      loading_mapel: false,
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
      data_mapel: [],
    }
  },
  methods: {
    changeTingkat(val){
      this.$emit('hide_form')
      this.form.rombongan_belajar_id = ''
      this.form.pembelajaran_id = ''
      if(val){
        this.loading_rombel = true
        this.$http.post('/referensi/get-rombel', this.form).then(response => {
          this.loading_rombel = false
          this.data_rombel = response.data
        })
      }
    },
    changeRombel(val){
      this.$emit('hide_form')
      this.form.pembelajaran_id = ''
      if(val){
        this.loading_mapel = true
        this.$http.post('/referensi/get-mapel', this.form).then(response => {
          this.loading_mapel = false
          this.data_mapel = response.data
        })
      }
    },
    changeMapel(val){
      this.$emit('hide_form')
      if(val){
        this.$emit('show_form', val)
      }
    },
  },
}
</script>
