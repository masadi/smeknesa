<template>
  <b-card v-if="detil_data">
    <h5 class="text-capitalize mb-75">Nama Lengkap</h5>
    <b-card-text>
      {{ detil_data.nama }}
    </b-card-text>
    <h5 class="text-capitalize mb-75">Tempat, Tanggal Lahir</h5>
    <b-card-text>
      {{ detil_data.tempat_lahir }}, {{ detil_data.tanggal_lahir_indo }}
    </b-card-text>
    <h5 class="text-capitalize mb-75">Domisili</h5>
    <b-card-text>
      {{ (detil_data.alamat_jalan) ? `${detil_data.alamat_jalan} RT/RW ${detil_data.rt}/${detil_data.rw}` : '' }}
      {{(detil_data.desa_id) ? detil_data.desa.name : ''}}
      {{(detil_data.kecamatan_id) ? detil_data.kecamatan.name : ''}}
      {{(detil_data.kabupaten_id) ? detil_data.kabupaten.name : ''}}
      {{(detil_data.provinsi_id) ? detil_data.provinsi.name : ''}}
      {{ (detil_data.kode_pos) ? `Kodepos ${detil_data.kode_pos}` : '' }}
    </b-card-text>
    <h5 class="text-capitalize mb-75">Email</h5>
    <b-card-text>
      {{ detil_data.email }}
    </b-card-text>
    <h5 class="text-capitalize mb-75">Nomor Whatsapp</h5>
    <b-card-text>
      {{ detil_data.no_hp }}
    </b-card-text>
    <b-button size="sm" variant="primary" @click="detilProfile">Detil</b-button>
    <b-button size="sm" variant="success" @click="updateProfile">Update</b-button>
  </b-card>
</template>

<script>
import { BCard, BCardText, BButton } from 'bootstrap-vue'

export default {
  components: {
    BCard,
    BCardText,
    BButton,
  },
  props: {
    detil_data: {
      type: Object,
      default: () => {},
    },
  },
  methods: {
    detilProfile(){
      this.$emit('detil')
    },
    updateProfile(){
      this.$emit('update')
    },
  }
}
</script>
