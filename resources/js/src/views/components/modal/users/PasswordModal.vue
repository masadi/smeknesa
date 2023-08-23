<template>
  <b-modal v-model="passwordModalShow" title="Detil Pengguna" size="lg" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Tingkat" label-for="tingkat" label-cols-md="3">
              <v-select id="tingkat" v-model="form.tingkat" :reduce="nama => nama.id" label="nama" :options="data_tingkat" placeholder="== Pilih Tingkat ==" @input="changeTingkat">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kelas" label-for="nama" label-cols-md="3">
              <b-overlay :show="loading_rombel" opacity="0.6" size="md" spinner-variant="secondary">
                <v-select id="rombongan_belajar_id" v-model="form.rombongan_belajar_id" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Kelas ==">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-overlay>
            </b-form-group>
          </b-col>
        </b-row>
      </b-form>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
          <b-button @click="cancel()">Tutup</b-button>
        </b-overlay>
        <template v-if="form.rombongan_belajar_id">
          <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
            <b-button variant="success" @click="ok()">Download</b-button>
          </b-overlay>
        </template>
      </template>
  </b-modal>
</template>

<script>
import { BOverlay, BForm, BRow, BCol, BFormGroup, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    vSelect, BOverlay, BForm, BRow, BCol, BFormGroup, BButton
  },
  data() {
    return {
      passwordModalShow: false,
      loading_form: false,
      loading_rombel: false,
      data_tingkat: [
        {
          id: 10,
          nama: 'Tingkat 10',
        },
        {
          id: 11,
          nama: 'Tingkat 11',
        },
        {
          id: 12,
          nama: 'Tingkat 12',
        },
        {
          id: 13,
          nama: 'Tingkat 13',
        }
      ],
      data_rombel: [],
      form: {
        tingkat: '',
        rombongan_belajar_id: '',
      },
    }
  },
  created() {
    eventBus.$on('open-password-pd-modal', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.passwordModalShow = true
    },
    hideModal(){
      this.passwordModalShow = false
    },
    changeTingkat(val){
      this.form.rombongan_belajar_id = ''
      if(val){
        this.loading_rombel = true
        this.$http.post('/referensi/get-rombel', this.form).then(response => {
          this.loading_rombel = false
          this.data_rombel = response.data
        })
      }
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.hideModal()
      window.open(`/export/password-pd/${this.form.rombongan_belajar_id}`, '_blank')
      /*this.loading_form = true
      this.$http.post('/auth/user/update-role', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          html: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.hideModal()
          this.$emit('reload')
        })
      }).catch(error => {
        console.log(error);
      })*/
    },
  },
}
</script>
<style lang="scss">
.custom-control {
  margin-bottom: 5px;
}
</style>