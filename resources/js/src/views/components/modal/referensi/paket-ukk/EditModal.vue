<template>
  <b-modal v-model="editModalShow" title="Edit Data Guru" size="xl" @hidden="hideModal" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Kompetensi Keahlian" label-for="jurusan" label-cols-md="3">
              <b-form-input id="jurusan" v-model="form.jurusan" disabled></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Kurikulum" label-for="kurikulum" label-cols-md="3">
              <b-form-input id="kurikulum" v-model="form.kurikulum" disabled></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Nomor Paket" label-for="nomor_paket" label-cols-md="3" :invalid-feedback="feedback.nomor_paket" :state="state.nomor_paket">
              <b-form-input id="nomor_paket" v-model="form.nomor_paket" placeholder="Nomor Paket"></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Judul Paket (ID)" label-for="nama_paket_id" label-cols-md="3" :invalid-feedback="feedback.nama_paket_id" :state="state.nama_paket_id">
              <b-form-input id="nama_paket_id" v-model="form.nama_paket_id" placeholder="Judul Paket (ID)"></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Judul Paket (EN)" label-for="nama_paket_en" label-cols-md="3" :invalid-feedback="feedback.nama_paket_en" :state="state.nama_paket_en">
              <b-form-input id="nama" v-model="form.nama_paket_en" :state="state.nama_paket_en" placeholder="Judul Paket (EN)"></b-form-input>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Status" label-for="status" label-cols-md="3" :invalid-feedback="feedback.status" :state="state.status">
              <v-select id="status" v-model="form.status" :reduce="name => name.code" label="name" :options="[{name: 'Aktif', code: 1}, {name: 'Non Aktif', code: 0}]" placeholder="== Pilih Status ==" :state="state.status">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Unit UKK" label-cols-md="3">
              <BTableSimple v-if="form.unit_ukk.length" bordered>
                <BThead>
                  <BTr>
                    <BTh>Kode Unit</BTh>
                    <BTh>Nama Unit Kompetensi</BTh>
                  </BTr>
                </BThead>
                <BTbody>
                  <template v-for="item in form.unit_ukk">
                    <BTr>
                      <BTd>
                        <b-form-input v-model="item.kode_unit" placeholder="Kode Unit UKK"></b-form-input>
                      </BTd>
                      <BTd>
                        <b-form-input v-model="item.nama_unit" placeholder="Nama Unit Kompetensi"></b-form-input>
                      </BTd>
                    </BTr>
                  </template>
                </BTbody>
              </BTableSimple>
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
          <b-button variant="success" @click="ok()">Perbaharui</b-button>
        </b-overlay>
      </template>
  </b-modal>
</template>

<script>
import { BOverlay, BForm, BFormInput, BInputGroup, BRow, BCol, BFormGroup, BButton, BTableSimple, BThead, BTbody, BTr, BTh, BTd } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    BOverlay,
    BForm,
    BFormInput,
    BInputGroup,
    BRow,
    BCol,
    BFormGroup,
    BButton,
    BTableSimple,
    BThead,
    BTbody,
    BTr,
    BTh,
    BTd,
    vSelect,
  },
  data() {
    return {
      editModalShow: false,
      loading_form: false,
      form: {
        data: 'paket-ukk',
        paket_ukk_id: '',
        jurusan: '',
        kurikulum: '',
        nomor_paket: '',
        nama_paket_id: '',
        nama_paket_en: '',
        status: '',
        unit_ukk: [],
      },
      feedback: {
        nomor_paket: '',
        nama_paket_id: '',
        nama_paket_en: '',
        status: '',
      },
      state: {
        nomor_paket: null,
        nama_paket_id: null,
        nama_paket_en: null,
        status: null,
      },
    }
  },
  created() {
    eventBus.$on('open-modal-edit-paket-ukk', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.$http.post('/ukk/get-paket', {
        paket_ukk_id: data.paket_ukk_id,
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
          this.form.paket_ukk_id = getData.paket_ukk_id
          this.form.jurusan = getData.jurusan_sp.nama_jurusan_sp
          this.form.kurikulum = getData.kurikulum.nama
          this.form.nomor_paket = getData.nomor_paket
          this.form.nama_paket_id = getData.nama_paket_id
          this.form.nama_paket_en = getData.nama_paket_en
          this.form.status = getData.status
          this.form.unit_ukk = getData.unit_ukk
          this.editModalShow = true
        }
      })
    },
    hideModal(){
      this.editModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.feedback.nomor_paket = ''
      this.feedback.nama_paket_id = ''
      this.feedback.nama_paket_en = ''
      this.feedback.status = ''
      this.state.nomor_paket = null
      this.state.nama_paket_id = null
      this.state.nama_paket_en = null
      this.state.status = null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/ukk/update', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.nomor_paket = (getData.errors.nomor_paket) ? false : null
          this.state.nama_paket_id = (getData.errors.nama_paket_id) ? false : null
          this.state.nama_paket_en = (getData.errors.nama_paket_en) ? false : null
          this.state.status = (getData.errors.status) ? false : null
          this.feedback.nomor_paket = (getData.errors.nomor_paket) ? getData.errors.nomor_paket.join(', ') : ''
          this.feedback.nama_paket_id = (getData.errors.nama_paket_id) ? getData.errors.nama_paket_id.join(', ') : ''
          this.feedback.nama_paket_en = (getData.errors.nama_paket_en) ? getData.errors.nama_paket_en.join(', ') : ''
          this.feedback.status = (getData.errors.status) ? getData.errors.status.join(', ') : ''
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.hideModal()
            this.$emit('reload')
          })
        }
      }).catch(error => {
        console.log(error);
      })
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>