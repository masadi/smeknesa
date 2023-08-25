<template>
  <b-modal v-model="jadwalModalShow" :title="title" modal-class="modal-fullscreen" ok-only ok-variant="secondary" ok-text="Tutup" scrollable>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form @submit="onSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Mata Pelajaran" label-for="pembelajaran_id" label-cols-md="3" :invalid-feedback="feedback.pembelajaran_id" :state="state.pembelajaran_id">
              <v-select id="pembelajaran_id" v-model="form.pembelajaran_id" :reduce="nama_mata_pelajaran => nama_mata_pelajaran.pembelajaran_id" label="nama_mata_pelajaran" :options="data_pembelajaran" placeholder="== Pilih Mata Pelajaran ==" :state="state.pembelajaran_id">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Hari" label-for="hari" label-cols-md="3" :invalid-feedback="feedback.hari" :state="state.hari">
              <v-select id="hari" v-model="form.hari" :options="data_hari" placeholder="== Pilih Hari ==" :state="state.hari" @input="changeHari">
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>  
          </b-col>
          <b-col cols="12">
            <b-form-group label="Jam Ke" label-for="jam" label-cols-md="3" :invalid-feedback="feedback.jam" :state="state.jam">
              <v-select id="jam" v-model="form.jam" :options="data_jam" placeholder="== Pilih Jam Ke ==" :state="state.jam" multiple>
                <template #no-options="{ search, searching, loading }">
                  Tidak ada data untuk ditampilkan
                </template>
              </v-select>
            </b-form-group>  
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-form-group label-cols-md="3">
              <b-button type="submit" variant="primary" class="float-right ml-1">Simpan</b-button>
            </b-form-group>
          </b-col>
        </b-row>
      </b-form>
    </b-overlay>
    <b-row>
      <b-col cols="8">
        <b-overlay :show="loading_jadwal" rounded opacity="0.6" size="lg" spinner-variant="danger">
          <BTableSimple bordered responsive>
            <BThead>
              <BTr>
                <BTh>Hari</BTh>
                <template v-for="jam in 11">
                  <BTh class="text-center">{{jam}}</BTh>
                </template>
              </BTr>
            </BThead>
            <BTbody>
              <template v-for="hari in data_hari">
                <BTr>
                  <BTd>{{hari}}</BTd>
                  <template v-for="jam in 11">
                    <BTd class="text-center">
                      <template v-for="jadwal_jam in getJadwalJam(hari, jam)">
                        <template v-for="jadwal_mapel in jadwal_jam">
                          {{jadwal_mapel.jadwal.pembelajaran.nama_mata_pelajaran}}
                          <h6>
                            <a href="javascript:void(0)"><span class="text-danger"><trash-icon @click="hapusData('jam', jadwal_mapel.jam_id)"/></span></a>
                          </h6>
                        </template>
                      </template>
                      <!-- {{getJadwalJam(hari, jam)}} -->
                    </BTd>
                  </template>
                </BTr>
              </template>
            </BTbody>
          </BTableSimple>
        </b-overlay>
      </b-col>
      <b-col cols="4">
        <b-overlay :show="loading_jadwal" rounded opacity="0.6" size="lg" spinner-variant="danger">
          <BTableSimple bordered responsive>
            <BThead>
              <BTr>
                <BTh>Mata Pelajaran</BTh>
                <BTh>jam</BTh>
                <BTh>#</BTh>
              </BTr>
            </BThead>
            <BTbody>
              <template v-for="pembelajaran in data_pembelajaran">
                <BTr>
                  <BTd>{{pembelajaran.nama_mata_pelajaran}}</BTd>
                  <BTd class="text-center">{{pembelajaran.jam_count}}</BTd>
                  <BTd class="text-center">
                    <a href="javascript:void(0)" v-if="pembelajaran.jam_count"><span class="text-danger"><trash-icon @click="hapusData('jadwal-mapel', pembelajaran.pembelajaran_id)"/></span></a>
                  </BTd>
                </BTr>
              </template>
            </BTbody>
          </BTableSimple>
        </b-overlay>
      </b-col>
    </b-row>
  </b-modal>
</template>

<script>
import { BRow, BCol, BTableSimple, BThead, BTh, BTbody, BTr, BTd, BButton, BForm, BFormGroup, BOverlay } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import vSelect from 'vue-select'
export default {
  components: {
    BRow, 
    BCol,
    BTableSimple,
    BThead,
    BTh,
    BTbody,
    BTr,
    BTd,
    BButton,
    BForm,
    BFormGroup,
    BOverlay,
    vSelect
  },
  data() {
    return {
      loading_form: false,
      loading_jadwal: false,
      jadwalModalShow: false,
      rombongan_belajar_id: '',
      form: {
        rombongan_belajar_id: '',
        pembelajaran_id: '',
        hari: '',
        jam: '',
      },
      feedback: {
        pembelajaran_id: '',
        hari: '',
        jam: '',
      },
      state: {
        pembelajaran_id: null,
        hari: null,
        jam: null,
      },
      jam: [],
      data_jam: [],
      data_hari: [
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu',
        'Minggu'
      ],
      data_jadwal: [],
      data_pembelajaran: [],
      title: '',
    }
  },
  created() {
    eventBus.$on('open-modal-jadwal-rombel', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.title = `Jadwal Pembelajaran Kelas ${data.nama}`
      this.rombongan_belajar_id = data.rombongan_belajar_id
      this.form.rombongan_belajar_id = this.rombongan_belajar_id
      this.getJadwalPembelajaran()
    },
    getJadwalPembelajaran(){
      this.loading_jadwal = true
      this.$http.post('/referensi/get-data', {
        data: 'jadwal',
        rombongan_belajar_id: this.rombongan_belajar_id,
      }).then(response => {
        this.loading_jadwal = false
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
          this.data_jadwal = getData.jadwal
          this.data_pembelajaran = getData.pembelajaran
          this.jadwalModalShow = true
        }
      })
    },
    hideModal(){
      this.jadwalModalShow = false
      this.resetForm()
    },
    resetForm(){
      this.data_jadwal = []
      this.data_pembelajaran = []
    },
    changeHari(hari){
      this.jam = []
      this.data_jam = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
      if(hari == 'Jumat'){
        this.data_jam = [1, 2, 3, 4, 5, 6]
      }
    },
    getJamId(hari, jam){
      var get_jadwal = this.getJadwal(hari)
      if(get_jadwal.length){
        var arrayJam = get_jadwal[0].jam.filter(function (el) {
          return el.jam === jam;
        });
        return (arrayJam.length) ? arrayJam[0].jam_id : null;
      }
    },
    getJadwal(hari){
      var newArray = this.data_jadwal.filter(function (el) {
        return el.hari === hari;
      });
      return newArray;
    },
    getJadwalJam(hari, jam){
      var get_jadwal = this.getJadwal(hari);
      var pushArray = []
      get_jadwal.forEach(element => {
        var arrayJam = element.jam.filter(function (el) {
          return el.jam === jam;
        })
        pushArray.push(arrayJam)
      });
      var clean_jadwal = pushArray.filter(function (el) {
        return el.length;
      })
      var nama_mata_pelajaran = []
      return clean_jadwal;
      /*clean_jadwal.forEach(element => {
        nama_mata_pelajaran.push(element.jadwal)
      });
      return nama_mata_pelajaran;
      return clean_jadwal.length > 1 ? clean_jadwal : '1'
      if(get_jadwal.length){
        var arrayJam = get_jadwal[0].jam.filter(function (el) {
          return el.jam === jam;
        });
        return arrayJam;
        //return (arrayJam.length) ? arrayJam[0].jadwal.pembelajaran.nama_mata_pelajaran : null;
      }*/
    },
    hapusData(data, id){
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
          this.loading_jadwal = true
          this.$http.post('/referensi/hapus-data', {
            data: data,
            id: id,
          }).then(response => {
            this.loading_jadwal = false
            let getData = response.data
            this.$swal({
              icon: getData.icon,
              title: getData.title,
              text: getData.text,
              customClass: {
                confirmButton: 'btn btn-success',
              },
            }).then(result => {
              this.getJadwalPembelajaran()
            })
          });
        }
      })
    },
    onSubmit(event) {
      event.preventDefault()
      this.loading_form = true
      this.$http.post('/jadwal/add', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.state.pembelajaran_id = (getData.errors.pembelajaran_id) ? false : null
          this.feedback.pembelajaran_id = (getData.errors.pembelajaran_id) ? getData.errors.pembelajaran_id.join(', ') : ''
          this.state.hari = (getData.errors.hari) ? false : null
          this.feedback.hari = (getData.errors.hari) ? getData.errors.hari.join(', ') : ''
          this.state.jam = (getData.errors.jam) ? false : null
          this.feedback.jam = (getData.errors.jam) ? getData.errors.jam.join(', ') : ''
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.form.rombongan_belajar_id = ''
            this.form.pembelajaran_id = ''
            this.form.hari = ''
            this.form.jam = ''
            this.feedback.pembelajaran_id = ''
            this.feedback.hari = ''
            this.feedback.jam = ''
            this.state.pembelajaran_id = null
            this.state.hari = null
            this.state.jam = null
            this.getJadwalPembelajaran()
          })
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