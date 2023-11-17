<template>
  <b-modal size="lg" v-model="editModalShow" :title="title" @hidden="hideModal" @ok="handleOk" scrollable>
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-table-simple>
        <b-thead>
          <b-tr>
            <b-th class="text-center">No</b-th>
            <b-th class="text-center">Nama Peserta Didik</b-th>
            <b-th class="text-center">{{ nama_asesmen }}</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <template v-for="(siswa, index) in data_siswa">
            <b-tr>
              <b-td class="text-center">{{index + 1}}</b-td>
              <b-td>{{ siswa.nama }}</b-td>
              <b-td class="text-center">
                <b-form-input v-model="form.nilai[siswa.anggota_rombel.anggota_rombel_id]" placeholder="0"></b-form-input>
              </b-td>
            </b-tr>
          </template>
        </b-tbody>
      </b-table-simple>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
      <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
        <b-button variant="primary" @click="ok()">Perbaharui</b-button>
      </b-overlay>
    </template>
  </b-modal>
</template>

<script>
import { BTableSimple, BThead, BTbody, BTr, BTh, BTd, BFormInput, BOverlay, BButton } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BTableSimple,
    BThead,
    BTbody,
    BTr,
    BTh,
    BTd,
    BFormInput,
    BOverlay,
    BButton,
  },
  data() {
    return {
      editModalShow: false,
      loading_form: false,
      detil: null,
      title: null,
      form: {
        penilaian_id: null,
        nilai: {},
      },
      nama_asesmen: null,
      data_siswa: [],
    }
  },
  created() {
    eventBus.$on('open-modal-edit-nilai', this.handleEvent);
  },
  methods: {
    hideModal(){
      console.log('hideModal');
      this.form.penilaian_id = null,
      this.form.nilai = {}
      this.editModalShow = false
    },
    handleEvent(data){
      this.form.penilaian_id = data.penilaian_id
      this.$emit('loading', true)
      this.$http.post('/nilai/detil-nilai', {
        id: data.penilaian_id,
      }).then(response => {
        this.$emit('loading', false)
        let getData = response.data
        this.title = `Edit Penilaian ${getData.penilaian.jenis_penilaian.nama}`
        this.data_siswa = getData.data_siswa
        var _this = this
        this.data_siswa.forEach(item => {
          _this.form.nilai[item.anggota_rombel.anggota_rombel_id] = (item.anggota_rombel.nilai) ? item.anggota_rombel.nilai.angka : null
        });
        this.nama_asesmen = (getData.tp) ? getData.tp.deskripsi : getData.penilaian.nama
        this.editModalShow = true
      });
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/nilai/update', this.form).then(response => {
        let getData = response.data
        this.loading_form = false
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
          allowOutsideClick: () => false,
        }).then(result => {
          this.$emit('reload')
          this.hideModal()
        })
      })
    },
  },
}
</script>