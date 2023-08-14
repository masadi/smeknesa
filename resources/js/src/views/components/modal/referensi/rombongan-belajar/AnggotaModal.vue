<template>
  <b-modal v-model="anggotaModalShow" title="Anggota Rombel" size="xl" ok-only ok-variant="secondary" ok-title="Tutup">
    anggota
  </b-modal>
</template>

<script>
import { BTableSimple, BThead, BTh, BTbody, BTr, BTd, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BTableSimple,
    BThead,
    BTh,
    BTbody,
    BTr,
    BTd,
    BButton
  },
  data() {
    return {
      anggotaModalShow: false,
      loading_anggota: false,
      loading_non_anggota: false,
      data_anggota: [],
      data_non_anggota: [],
    }
  },
  created() {
    eventBus.$on('open-modal-anggota-rombel', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.getAnggota(data)
      this.getNonAnggota()
    },
    getAnggota(data){
      this.$http.post('/referensi/anggota-rombel', {
        rombongan_belajar_id: data.rombongan_belajar_id,
      }).then(response => {
        var getData = response.data
        if(getData.errors){
          this.$swal({
            icon: 'error',
            title: 'Error',
            text: getData.errors,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
        } else {
          this.data_anggota = getData
        }
      })
    },
    getNonAnggota(){
      this.$http.get('/referensi/non-anggota').then(response => {
        var getData = response.data
        if(getData.errors){
          this.$swal({
            icon: 'error',
            title: 'Error',
            text: getData.errors,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
        } else {
          this.data_non_anggota = getData
          this.anggotaModalShow = true
        }
      })
    },
    hideModal(){
      this.anggotaModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.data_anggota = []
      this.data_non_anggota = []
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>