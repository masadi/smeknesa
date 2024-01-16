<template>
  <b-card no-body>
    <b-card-body>
      <div v-if="isBusy" class="text-center text-danger my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Loading...</strong>
      </div>
      <div v-else>
        <b-table-simple bordered striped responsive>
          <b-thead>
            <b-tr>
              <b-th class="text-center">No</b-th>
              <b-th class="text-center">Nama Siswa</b-th>
              <b-th class="text-center">NISN</b-th>
              <b-th class="text-center">RANKING</b-th>
              <b-th class="text-center">Halaman Depan</b-th>
              <b-th class="text-center">Rapor Akademik</b-th>
              <b-th class="text-center">Rapor P5</b-th>
              <b-th class="text-center">Dokumen Pendukung</b-th>
              <b-th class="text-center">Ekstra</b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <b-tr v-for="(siswa, index) in data_siswa" :key="siswa.peserta_didik_id">
              <b-td class="text-center">{{index + 1}}</b-td>
              <b-td>{{siswa.nama}}</b-td>
              <b-td class="text-center">{{siswa.nisn}}</b-td>
              <b-td class="text-center">{{siswa.anggota_rombel.rangking}}</b-td>
              <b-td class="text-center">
                  <b-button variant="success" :href="`/cetak/rapor-cover/${siswa.anggota_rombel.anggota_rombel_id}`" target="_blank" size="sm">
                    <b-icon icon="file-earmark-pdf" aria-hidden="true"></b-icon>
                  </b-button>
                </b-td>
                <b-td class="text-center">
                  <b-button variant="warning" :href="`/cetak/rapor-semester/${siswa.anggota_rombel.anggota_rombel_id}/${user.sekolah_id}/${user.semester.semester_id}`" target="_blank" size="sm">
                    <b-icon icon="file-earmark-pdf-fill" aria-hidden="true"></b-icon>
                  </b-button>
                </b-td>
                <b-td class="text-center">
                  <b-button variant="info" :href="`/cetak/rapor-projek/${siswa.anggota_rombel.anggota_rombel_id}/${user.semester.semester_id}`" target="_blank" size="sm">
                    <b-icon icon="file-pdf" aria-hidden="true"></b-icon>
                  </b-button>
                </b-td>
                <b-td class="text-center">
                  <b-button variant="danger" :href="`/cetak/rapor-pelengkap/${siswa.anggota_rombel.anggota_rombel_id}/${siswa.kelas.rombongan_belajar_id}`" target="_blank" size="sm">
                    <b-icon icon="file-pdf-fill" aria-hidden="true"></b-icon>
                  </b-button>
                </b-td>
                <b-td class="text-center">
                  <b-button variant="primary" :href="`/cetak/sertifikat/ekskul/${siswa.peserta_didik_id}`" target="_blank" size="sm">
                    <b-icon icon="file-pdf-fill" aria-hidden="true"></b-icon>
                  </b-button>
                </b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
      </div>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BAlert, BForm, BOverlay, BTableSimple, BThead, BTbody, BTr, BTh, BTd, BBadge, BButton } from 'bootstrap-vue'
import vSelect from 'vue-select'
import eventBus from '@core/utils/eventBus';
export default {
  components: {
    vSelect,
    BCard,
    BCardBody,
    BSpinner,
    BAlert,
    BForm, 
    BOverlay,
    BTableSimple,
    BThead,
    BTbody,
    BTr,
    BTh,
    BTd,
    BBadge,
    BButton,
  },
  data() {
    return {
      isBusy: true,
      loading: false,
      data_siswa: [],
    }
  },
  created() {
    this.loadPostsData()
    eventBus.$on('rangking', this.handleEvent);
  },
  methods: {
    handleEvent(){
      eventBus.$emit('loading', true)
      this.$http.post('/nilai/rangking', {
        guru_id: this.user.guru_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        console.log(response.data);
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
          allowOutsideClick: false,
        }).then(result => {
          eventBus.$emit('loading', false)
          this.loadPostsData()
        })
      });
    },
    loadPostsData() {
      this.isBusy = true
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      this.$http.post('/referensi/get-siswa', {
        data: 'rangking',
        guru_id: this.user.guru_id,
        semester_id: this.user.semester.semester_id,
      }).then(response => {
        this.isBusy = false
        let getData = response.data
        this.data_siswa = getData.pd
      })
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>