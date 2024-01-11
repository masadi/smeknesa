<template>
  <b-card no-body>
    <b-card-body>
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
          <b-form-group label="Unduh Legger" label-for="rombongan_belajar_id" label-cols-md="3" v-if="show">
            <b-button variant="danger">Unduh Legger</b-button>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-table-simple responsive bordered v-if="show">
            <b-thead>
              <b-tr>
                <b-th class="text-center">No</b-th>
                <b-th class="text-center">Nama Siswa</b-th>
                <b-th class="text-center">NISN</b-th>
                <b-th class="text-center" v-for="(pembelajaran, index) in data_pembelajaran" :key="pembelajaran.pembelajaran_id">{{pembelajaran.nama_mata_pelajaran}}</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <template v-for="(siswa, index) in data_siswa" v-if="data_siswa.length">
                <b-tr>
                  <b-td class="text-center">{{index + 1}}</b-td>
                  <b-td>{{siswa.nama}}</b-td>
                  <b-td class="text-center">{{siswa.nisn}}</b-td>
                  <b-td class="text-center" v-for="(pembelajaran, index) in data_pembelajaran" :key="pembelajaran.pembelajaran_id">{{Math.ceil(getNilai(pembelajaran.nilai, siswa.anggota_rombel.anggota_rombel_id))}}</b-td>
                </b-tr>
              </template>
            </b-tbody>
          </b-table-simple>
        </b-col>
      </b-row>
    </b-card-body>
  </b-card>
</template>

<script>
import { BRow, BCol, BCard, BCardBody, BOverlay, BFormGroup, BTableSimple, BThead, BTbody, BTr, BTh, BTd, BButton } from 'bootstrap-vue'
import vSelect from 'vue-select'
export default {
  components: {
    BRow,
    BCol,
    BCard,
    BCardBody,
    BOverlay,
    BFormGroup,
    BTableSimple,
    BThead,
    BTbody,
    BTr,
    BTh,
    BTd,
    BButton,
    vSelect,
  },
  data() {
    return {
      isBusy: false,
      show: false,
      loading_rombel: false,
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
      data_siswa: [],
      data_pembelajaran: [],
      form: {
        semester_id: '',
        legger: true,
        tingkat: '',
        rombongan_belajar_id: '',
      },
      feedback: {
        tingkat: '',
        rombongan_belajar_id: '',
      },
      state: {
        tingkat: null,
        rombongan_belajar_id: null,
      },
    }
  },
  created(){
    this.form.semester_id = this.user.semester.semester_id
  },
  methods: {
    changeTingkat(val){
      this.show = false
      this.form.rombongan_belajar_id = ''
      if(val){
        this.loading_rombel = true
        this.$http.post('/referensi/get-rombel', this.form).then(response => {
          this.loading_rombel = false
          this.data_rombel = response.data
        })
      }
    },
    changeRombel(val){
      this.show = false
      if(val){
        this.loading_mapel = true
        this.loadPostsData()
      }
    },
    loadPostsData(val) {
      this.isBusy = true
      this.$http.post('/monitoring/legger', this.form).then(response => {
        this.show = true
        this.isBusy = false
        let getData = response.data
        this.data_siswa = getData.pd
        this.data_pembelajaran = getData.pembelajaran
      })
    },
    getNilai(all_nilai, anggota_rombel_id){
      const arr = all_nilai.filter((item) => {
        return item.anggota_rombel_id === anggota_rombel_id
      })
      const { length } = arr;
      return arr.reduce((acc, val) => {
          return acc + (val.angka/length);
      }, 0);
    }
  },
}
</script>