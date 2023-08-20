<template>
  <b-modal title="Detil Biodata" v-model="detilModal" no-close-on-backdrop hide-header-close ok-only ok-title="Tutup" ok-variant="secondary" size="lg">
    <b-table-simple v-if="detil_data">
      <b-tr>
        <b-th style="width:30%" class="pl-0">Nama Lengkap</b-th>
        <b-td style="width:70%">: {{detil_data.nama}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">NIK</b-th>
        <b-td>: {{detil_data.nik}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Nomor Induk</b-th>
        <b-td>: {{detil_data.no_induk}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">NISN</b-th>
        <b-td>: {{detil_data.nisn}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Jenis Kelamin</b-th>
        <b-td>: {{detil_data.jenis_kelamin}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Tempat Lahir</b-th>
        <b-td>: {{detil_data.tempat_lahir}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Tanggal Lahir</b-th>
        <b-td>: {{detil_data.tanggal_lahir}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Agama</b-th>
        <b-td>: {{(detil_data.agama_id) ? detil_data.agama.nama : ''}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Alamat</b-th>
        <b-td>: {{detil_data.alamat_jalan}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">RT/RW</b-th>
        <b-td>: {{`${detil_data.rt}, ${detil_data.rw}`}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Desa/Kelurahan</b-th>
        <b-td>: {{(detil_data.desa_id) ? detil_data.desa.name : ''}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Kecamatan</b-th>
        <b-td>: {{(detil_data.kecamatan_id) ? detil_data.kecamatan.name : ''}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Kabupaten/Kota</b-th>
        <b-td>: {{(detil_data.kabupaten_id) ? detil_data.kabupaten.name : ''}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Provinsi</b-th>
        <b-td>: {{(detil_data.provinsi_id) ? detil_data.provinsi.name : ''}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Kodepos</b-th>
        <b-td>: {{detil_data.kode_pos}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Nomor Whatsapp</b-th>
        <b-td>: {{detil_data.hp}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Sekolah Asal</b-th>
        <b-td>: {{detil_data.sekolah_asal}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Tanggal Masuk Sekolah</b-th>
        <b-td>: {{detil_data.diterima_indo}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Diterima Dikelas</b-th>
        <b-td>: {{detil_data.diterima_kelas}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Email</b-th>
        <b-td>: {{user.email}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Nama Ayah</b-th>
        <b-td>: {{detil_data.nama_ayah}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Pekerjaan Ayah</b-th>
        <b-td>: {{(detil_data.kerja_ayah) ? detil_data.pekerjaan_ayah.nama : ''}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Nama Ibu</b-th>
        <b-td>: {{detil_data.nama_ibu}}</b-td>
      </b-tr>
      <b-tr>
        <b-th class="pl-0">Pekerjaan Ibu</b-th>
        <b-td>: {{(detil_data.kerja_ibu) ? detil_data.pekerjaan_ibu.nama : ''}}</b-td>
      </b-tr>
    </b-table-simple>
  </b-modal>
</template>

<script>
import { BTableSimple, BTr, BTh, BTd } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BTableSimple, BTr, BTh, BTd
  },
  props: {
    detil_data: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      detilModal: false,
    }
  },
  created(){
    eventBus.$on('open-detil-modal', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.detilModal = true
    },
  }
}
</script>
