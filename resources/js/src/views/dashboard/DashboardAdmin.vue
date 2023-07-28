<template>
  <div>
    <b-row v-if="isBusy">
      <b-col>
        <b-card no-body>
          <b-card-body>
            <div class="text-center text-danger my-2">
              <b-spinner class="align-middle"></b-spinner>
              <strong>Loading...</strong>
            </div>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
    <b-row class="match-height" v-else>
      <template v-for="(rekap, index) in rekapitulasi">
        <b-col cols="6" xl="2" md="4" sm="6">
          <b-card no-body>
            <b-card-body class="text-center">
              <div :class="`avatar bg-light-${rekap.variant} p-50 mb-1`">
                <div class="avatar-content">
                  <font-awesome-icon :icon="`fa-solid fa-${rekap.icon}`" size="2xl" />
                </div>
              </div>
              <h2 class="font-weight-bolder" v-b-tooltip.hover.html="rekap.html">{{rekap.jml}}</h2>
              <p class="card-text">{{rekap.data}}</p>
            </b-card-body>
          </b-card>
        </b-col>
      </template>
    </b-row>
  </div>
</template>

<script>
import vc from 'version_compare'
import { BRow, BCol, BCard, BCardBody, BSpinner, BTableSimple, BTr, BTd, BFormCheckbox, VBTooltip } from 'bootstrap-vue'

export default {
  components: {
    BRow, 
    BCol,
    BCard,
    BCardBody,
    BSpinner,
    BTableSimple,
    BTr, 
    BTd,
    BFormCheckbox,
    VBTooltip
  },
  directives: {
    'b-tooltip': VBTooltip,
  },
  data() {
    return {
      isBusy: true,
      rekapitulasi: [],
      sekolah: null,
      aplikasi: null,
      app: {},
    }
  },
  created() {
    this.$http.post('/dashboard', {
      sekolah_id: this.user.sekolah_id,
      semester_id: this.user.semester.semester_id,
      periode_aktif: this.user.semester.nama,
    }).then(response => {
      this.isBusy = false
      let getData = response.data
      console.log(getData);
    })
  },
  methods: {
    changeStatus(val){
      console.log(val);
      var text;
      if(val){
        text = 'Penilaian akan di aktifkan'
      } else {
        text = 'Penilaian akan di nonaktifkan'
      }
      this.$swal({
        title: 'Apakah Anda yakin?',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yakin!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
        allowOutsideClick: () => !this.$swal.isLoading(),
      }).then(result => {
        if (result.value) {
          this.$http.post('/dashboard/status-penilaian', {
            status: status,
            sekolah_id: this.user.sekolah_id,
            semester_id: this.user.semester.semester_id,
          }).then(response => {
            let data = response.data
            this.$swal({
              icon: data.icon,
              title: data.title,
              text: data.text,
              customClass: {
                confirmButton: 'btn btn-success',
              },
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
</style>