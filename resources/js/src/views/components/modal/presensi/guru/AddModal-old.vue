<template>
  <b-modal v-model="addModalShow" title="Input Presensi Guru" @hidden="hideModal" @ok="handleOk" modal-class="modal-fullscreen" scrollable>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <BTableSimple bordered>
          <BThead>
            <BTr>
              <BTh rowspan="2" style="vertical-align:middle;">Nama Lengkap</BTh>
              <BTh rowspan="2" style="vertical-align:middle;" class="text-center">NIP</BTh>
              <BTh :colspan="jumlah_jam" class="text-center">Absensi Jam Ke</BTh>
            </BTr>
            <BTr>
              <template v-for="jam_ke in jumlah_jam">
                <BTh class="text-center">{{jam_ke}}</BTh>
              </template>
            </BTr>
          </BThead>
          <BTbody>
            <template v-if="data_guru.length">
              <template v-for="guru in data_guru">
                <BTr>
                  <BTd>{{guru.nama}}</BTd>
                  <BTd class="text-center">{{guru.nip}}</BTd>
                  <template v-for="jam_ke in jumlah_jam">
                    <BTd>
                      <b-form-select v-model="absensi[guru.guru_id+'#'+jam_ke]" :options="['H', 'A', 'S', 'I', 'D']"></b-form-select>
                    </BTd>
                  </template>
                </BTr>
              </template>
            </template>
            <template v-else>
              <BTr>
                <BTd :colspan="jumlah_jam + 2" class="text-center">Tidak ada guru yang memiliki jadwal mengajar hari ini, {{hari}}</BTd>
              </BTr>
            </template>
          </BTbody>
        </BTableSimple>
      </b-form>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
        <b-button variant="success" @click="ok()" v-if="data_guru.length">Simpan</b-button>
      </b-overlay>
    </template>
  </b-modal>
</template>

<script>
import { BOverlay, BForm, BTableSimple, BThead, BTh, BTbody, BTr, BTd, BButton, BFormSelect } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BOverlay,
    BForm,
    BTableSimple,
    BThead,
    BTh,
    BTbody,
    BTr,
    BTd,
    BButton,
    BFormSelect,
  },
  data() {
    return {
      addModalShow: false,
      loading_form: false,
      absensi: {},
      hari: '',
      jumlah_jam: 0,
      data_guru: [],
    }
  },
  created() {
    eventBus.$on('open-modal-presensi-guru', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.$http.get('/presensi/get-hari').then(response => {
        let getData = response.data
        this.hari = getData.tanggal_str
        this.jumlah_jam = getData.jumlah_jam
        this.getGuru()
      })
    },
    getGuru(){
      this.$http.get('/referensi/get-guru', {
        params: {
          aksi: 'absen'
        }
      }).then(response => {
        let getData = response.data
        this.data_guru = getData.data
        var _this = this
        this.data_guru.forEach(item => {
          for (var i = 1; i < (_this.jumlah_jam + 1); i++) {
            _this.absensi[item.guru_id+'#'+i] = (_this.getAbsen(item.presensi, i)[0]) ? _this.getAbsen(item.presensi, i)[0].absen : 'H'
          }
        });
        this.addModalShow = true
      })
    },
    getAbsen(arr, jam_ke){
      const absen = arr.filter((item) => {
        return item.jam === jam_ke
      })
      return absen
    },
    hideModal(){
      this.addModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.file1 = null
    },
    onFileChange(e) {
      this.file1 = e.target.files[0];
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/presensi/simpan', {
        aksi: 'guru',
        absensi: this.absensi
      }).then(response => {
        this.loading_form = false
        let getData = response.data
        console.log(getData);
        if(getData.errors){
          this.feedback_file = (getData.errors.file) ? getData.errors.file.join(', ') : ''
          this.state_file = (getData.errors.file) ? false : null
          //this.feedback.tahun_ajaran_id = (getData.errors.tahun_ajaran_id) ? getData.errors.tahun_ajaran_id.join(', ') : ''
          //this.state.tahun_ajaran_id = (getData.errors.tahun_ajaran_id) ? false : null
        } else {
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
.modal-fullscreen .modal-dialog {
    max-width: 100%;
    margin: 0;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100vh;
    display: flex;
    position: fixed;
    z-index: 100000;
}
</style>