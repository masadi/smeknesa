<template>
  <b-modal modal-class="modal-fullscreen" v-model="pembelajaranModalShow" title="Pembelajaran" @hidden="hideModal" @ok="handleOk" scrollable>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <BTableSimple>
          <BThead>
            <BTr>
              <BTh>Mata Pelajaran</BTh>
              <BTh>Guru Mata Pelajaran</BTh>
              <BTh>Kelompok</BTh>
              <BTh>Nomor Urut</BTh>
              <BTh>Aksi</BTh>
            </BTr>
          </BThead>
          <BTbody>
            <template v-for="(mapel, index) in data_mapel">
              <BTr>
                <BTd>
                  <v-select id="mata_pelajaran_id" v-model="form.mata_pelajaran_id[mapel.mata_pelajaran_id]" :reduce="nama => nama.mata_pelajaran_id" label="nama" :options="data_mapel" placeholder="== Pilih Mata Pelajaran ==">
                    <template #no-options="{ search, searching, loading }">
                      Tidak ada data untuk ditampilkan
                    </template>
                  </v-select>
                </BTd>
                <BTd>
                  <v-select id="guru_id" v-model="form.guru_id[mapel.mata_pelajaran_id]" :reduce="nama => nama.guru_id" label="nama" :options="data_guru" placeholder="== Pilih Guru Mata Pelajaran ==">
                    <template #no-options="{ search, searching, loading }">
                      Tidak ada data untuk ditampilkan
                    </template>
                  </v-select>
                </BTd>
                <BTd>
                  <v-select id="kelompok_id" v-model="form.kelompok_id[mapel.mata_pelajaran_id]" :reduce="nama_kelompok => nama_kelompok.kelompok_id" label="nama_kelompok" :options="data_kelompok" placeholder="== Pilih Kelompok ==">
                    <template #no-options="{ search, searching, loading }">
                      Tidak ada data untuk ditampilkan
                    </template>
                  </v-select>
                </BTd>
                <BTd>
                  <b-form-input id="no_urut" type="number" v-model="form.no_urut[mapel.mata_pelajaran_id]"></b-form-input>
                </BTd>
                <BTd class="text-center">
                  <template v-if="mapel.pembelajaran">
                    <a href="javascript:void(0)"><span class="text-danger"><trash-icon @click="hapus(mapel.pembelajaran.pembelajaran_id, rombongan_belajar_id)"/></span></a>
                  </template>
                  <template v-else>
                    -
                  </template>
                </BTd>
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
          <b-button variant="success" @click="ok()">Simpan</b-button>
        </b-overlay>
      </template>
  </b-modal>
</template>

<script>
import { BOverlay, BForm, BTableSimple, BThead, BTh, BTbody, BTr, BTd, BFormInput, BButton } from 'bootstrap-vue'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
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
    BFormInput,
    BButton,
    vSelect,
  },
  data() {
    return {
      pembelajaranModalShow: false,
      loading_form: false,
      rombongan_belajar_id: '',
      form: {},
      data_guru: [],
      data_mapel: [],
      data_kelompok: [],
      form: {
        mata_pelajaran_id: {},
        guru_id: {},
        kelompok_id: {},
        no_urut: {},
      },
    }
  },
  created() {
    eventBus.$on('open-modal-pembelajaran-rombel', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.getPerangkatPembelajaran(data.rombongan_belajar_id)
    },
    getPerangkatPembelajaran(rombongan_belajar_id){
      this.rombongan_belajar_id = rombongan_belajar_id
      this.$http.post('/referensi/perangkat-pembelajaran', {
        rombongan_belajar_id: this.rombongan_belajar_id
      }).then(response => {
        let getData = response.data
        this.data_mapel = getData.mapel
        this.data_guru = getData.guru
        this.data_kelompok = getData.kelompok
        var mata_pelajaran_id = {}
        var guru_id = {}
        var kelompok_id = {}
        var no_urut = {}
        getData.mapel.forEach(element => {
          if(element.pembelajaran){
            mata_pelajaran_id[element.mata_pelajaran_id] = element.pembelajaran.mata_pelajaran_id
            guru_id[element.mata_pelajaran_id] = element.pembelajaran.guru_id
            kelompok_id[element.mata_pelajaran_id] = element.pembelajaran.kelompok_id
            no_urut[element.mata_pelajaran_id] = element.pembelajaran.no_urut
          } else {
            mata_pelajaran_id[element.mata_pelajaran_id] = element.mata_pelajaran_id
            guru_id[element.mata_pelajaran_id] = null
            kelompok_id[element.mata_pelajaran_id] = null
            no_urut[element.mata_pelajaran_id] = null
          }
        });
        this.form.mata_pelajaran_id = mata_pelajaran_id
        this.form.guru_id = guru_id
        this.form.kelompok_id = kelompok_id
        this.form.no_urut = no_urut
        this.pembelajaranModalShow = true
      })
    },
    hideModal(){
      this.pembelajaranModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.data_guru = []
      this.data_mapel = []
      this.data_kelompok = []
      this.form.mata_pelajaran_id = {}
      this.form.guru_id = {}
      this.form.kelompok_id = {}
      this.form.no_urut = {}
    },
    clean(obj){
      for (var propName in obj) {
        if (obj[propName] === null || obj[propName] === undefined) {
          delete obj[propName];
        }
      }
      return obj
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      var _this = this
      Object.keys(this.clean(this.form.guru_id)).map(function(objectKey, index) {
        _this.loading_form = true
        _this.$http.post('/referensi/simpan-pembelajaran', {
          mata_pelajaran_id: objectKey,
          rombongan_belajar_id: _this.rombongan_belajar_id,
          guru_id: _this.form.guru_id[objectKey],
          kelompok_id: _this.form.kelompok_id[objectKey],
          no_urut: _this.form.no_urut[objectKey],
          semester_id: _this.user.semester.semester_id,
        }).then(response => {
          _this.loading_form = false
          _this.$toast({
            component: ToastificationContent,
            position: 'bottom-left',
            props: {
              title: (response.data.success) ? 'Berhasil' : 'Gagal',
              icon: (response.data.success) ? 'CheckCircleIcon' : 'XIcon',
              variant: (response.data.success) ? 'success' : 'error',
              text: response.data.text,
            },
          })
        }).catch(error => {
          console.log(error);
        })
      })
    },
    hapus(pembelajaran_id, rombongan_belajar_id){
      this.$swal({
        title: 'Apakah Anda yakin?',
        text: 'Tindakan ini tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yakin!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
        allowOutsideClick: () => false,
      }).then(result => {
        if (result.value) {
          this.loading_form = true
          this.$http.post('/referensi/hapus-data', {
            data: 'pembelajaran',
            id: pembelajaran_id,
          }).then(response => {
            this.loading_form = false
            let getData = response.data
            this.$swal({
              icon: getData.icon,
              title: getData.title,
              text: getData.text,
              customClass: {
                confirmButton: 'btn btn-success',
              },
            }).then(result => {
              this.getPerangkatPembelajaran(rombongan_belajar_id)
            })
          });
        }
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