<template>
  <b-modal v-model="detilModalShow" title="Detil Guru" size="lg" ok-only ok-variant="secondary" ok-text="Tutup">
    <BTableSimple v-if="detil"> 
      <BTr>
        <BTd colspan="2">
          <b-avatar size="8rem" rounded :src="`/storage/images/${detil.photo}`" variant="light-primary">
            <feather-icon v-if="!detil.photo" icon="UserIcon" size="22"/>
          </b-avatar>
        </BTd>
      </BTr>
      <BTr>
        <BTd>Nama</BTd>
        <BTd>: {{detil.nama}}</BTd>
      </BTr>
      <BTr>
        <BTd>NIK</BTd>
        <BTd>: {{detil.nik}}</BTd>
      </BTr>
      <BTr>
        <BTd>NUPTK</BTd>
        <BTd>: {{detil.nuptk}}</BTd>
      </BTr>
      <BTr>
        <BTd>NIP</BTd>
        <BTd>: {{detil.nip}}</BTd>
      </BTr>
      <BTr>
        <BTd>Jenis Kelamin</BTd>
        <BTd>: {{(detil.jenis_kelamin === 'L') ? 'Laki-laki' : 'Perempuan'}}</BTd>
      </BTr>
      <BTr>
        <BTd>Tempat, Tanggal Lahir</BTd>
        <BTd>: {{detil.tempat_lahir}}, {{detil.tanggal_lahir_indo }}</BTd>
      </BTr>
      <BTr>
        <BTd>Agama</BTd>
        <BTd>: {{(detil.agama_id) ? detil.agama.nama : null}}</BTd>
      </BTr>
      <BTr>
        <BTd>Alamat</BTd>
        <BTd>: {{detil.alamat_jalan}}, RT/RW: {{`${detil.rt}/${detil.rw}`}}, Kodepos {{detil.kode_pos}}</BTd>
      </BTr>
      <BTr>
        <BTd>Desa/Kelurahan</BTd>
        <BTd>: {{(detil.desa_id) ? detil.desa.name : null}}</BTd>
      </BTr>
      <BTr>
        <BTd>Kecamatan</BTd>
        <BTd>: {{(detil.kecamatan_id) ? detil.kecamatan.name : null}}</BTd>
      </BTr>
      <BTr>
        <BTd>Kabupaten/Kota</BTd>
        <BTd>: {{(detil.kabupaten_id) ? detil.kabupaten.name : null}}</BTd>
      </BTr>
      <BTr>
        <BTd>Provinsi</BTd>
        <BTd>: {{(detil.provinsi_id) ? detil.provinsi.name : null}}</BTd>
      </BTr>
      <BTr>
        <BTd>Nomor HP</BTd>
        <BTd>: {{detil.no_hp}}</BTd>
      </BTr>
      <BTr>
        <BTd>Email</BTd>
        <BTd>: {{detil.email}}</BTd>
      </BTr>
    </BTableSimple>
  </b-modal>
</template>

<script>
import { BTableSimple, BTr, BTd, BImg, BAvatar } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BTableSimple, BTr, BTd, BImg, BAvatar
  },
  data() {
    return {
      detilModalShow: false,
      detil: null,
      mainProps: {width: 125, height: 125 },
    }
  },
  created() {
    eventBus.$on('open-modal-detil-guru', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      //this.detil = data
      //this.detilModalShow = true
      this.$http.post('/referensi/detil-data', {
        data: 'guru',
        wilayah: true,
        id: data.guru_id
      }).then(response => {
        let getData = response.data
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
          this.detil = getData
          this.detilModalShow = true
        }
      })
    },
  },
}
</script>