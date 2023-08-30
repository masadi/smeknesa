<template>
  <b-modal v-model="hapusModalShow" title="Hapus Presensi Siswa" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Hari, Tanggal" label-for="tanggal" label-cols-md="3">
              <b-form-datepicker id="tanggal" v-model="form.tanggal" show-decade-nav button-variant="outline-secondary" left locale="id" aria-controls="tanggal"  @context="onContext" placeholder="== Pilih Tanggal ==" />
            </b-form-group>  
          </b-col>
        </b-row>
      </b-form>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
        <b-button variant="success" @click="ok()">Hapus</b-button>
      </b-overlay>
    </template>
  </b-modal>
</template>

<script>
import { BOverlay, BRow, BCol, BForm, BButton, BFormDatepicker, BFormGroup } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BOverlay,
    BRow, 
    BCol,
    BForm,
    BButton,
    BFormDatepicker,
    BFormGroup,
  },
  data() {
    return {
      hapusModalShow: false,
      loading_form: false,
      form: {
        data: 'presensi',
        tanggal: '',
        rombongan_belajar_id: '',
      },
    }
  },
  created() {
    eventBus.$on('open-modal-hapus-presensi-pd', this.handleEvent);
  },
  methods: {
    handleEvent(rombongan_belajar_id){
      this.form.rombongan_belajar_id = rombongan_belajar_id
      console.log(rombongan_belajar_id);
      this.hapusModalShow = true
    },
    hideModal(){
      this.hapusModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.form.rombongan_belajar_id = ''
      this.form.tanggal = ''
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/presensi/hapus', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          html: getData.text,
          allowOutsideClick: false,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          if(getData.icon == 'success'){
            this.hideModal()
            this.$emit('reload')
          }
        })
      }).catch(error => {
        console.log(error);
      })
    },
    onContext(ctx) {
      this.formatted = ctx.selectedFormatted
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>