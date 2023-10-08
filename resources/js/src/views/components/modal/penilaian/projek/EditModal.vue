<template>
  <b-modal v-model="editModalShow" title="Edit Perencanaan Projek Pancasila" scrollable size="xl" @ok="handleOk">
    <b-table-simple bordered responsive v-if="data">
      <b-tr>
        <b-td>Kelas</b-td>
        <b-td>{{data.rombongan_belajar.nama}}</b-td>
      </b-tr>
      <b-tr>
        <b-td>Tema</b-td>
        <b-td>{{data.tema.nama}}</b-td>
      </b-tr>
      <b-tr>
        <b-td>Nama Projek</b-td>
        <b-td>
          <b-form-group label-for="nama" :invalid-feedback="feedback.nama" :state="state.nama">
            <b-form-input id="nama" v-model="form.nama" :state="state.nama" />
          </b-form-group>
        </b-td>
      </b-tr>
      <b-tr>
        <b-td>Deskripsi Projek</b-td>
        <b-td>
          <b-form-group label-for="deskripsi" :invalid-feedback="feedback.deskripsi" :state="state.deskripsi">
            <b-form-textarea id="deskripsi" v-model="form.deskripsi" :state="state.deskripsi"></b-form-textarea>
          </b-form-group>
        </b-td>
      </b-tr>
      <b-tr>
        <b-td>Nomor Urut Projek</b-td>
        <b-td>
          <b-form-group label-for="deskripsi" :invalid-feedback="feedback.no_urut" :state="state.no_urut">
            <b-form-input id="deskripsi" v-model="form.no_urut" :state="state.no_urut"></b-form-input>
          </b-form-group>
        </b-td>
      </b-tr>
      <b-tr>
        <b-td>Dimensi/Elemen/Sub Elemen</b-td>
        <b-td>
          <b-table-simple bordered responsive>
            <b-thead>
              <b-tr>
                <b-th class="text-center">Dimensi</b-th>
                <b-th class="text-center">Elemen</b-th>
                <b-th class="text-center">Sub Elemen</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <template v-for="elemen in data.elemen_rencana_projek">
                <b-tr>
                  <b-td>{{elemen.dimensi_projek.nama}}</b-td>
                  <b-td>{{elemen.elemen_projek.parent.nama}}</b-td>
                  <b-td>{{elemen.elemen_projek.nama}}</b-td>
                </b-tr>
              </template>
            </b-tbody>
          </b-table-simple>
        </b-td>
      </b-tr>
    </b-table-simple>
    <template #modal-footer="{ ok, cancel }">
      <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="primary">
        <b-button variant="primary" @click="ok">Perbaharui</b-button>
      </b-overlay>
      <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="secondary">
        <b-button @click="cancel()">Tutup</b-button>
      </b-overlay>
    </template>
  </b-modal>
</template>

<script>
import { 
  BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormGroup, BFormInput, BFormTextarea, BOverlay, BButton
} from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BTableSimple, BThead, BTbody, BTh, BTr, BTd, BFormGroup, BFormInput, BFormTextarea, BOverlay, BButton
  },
  data() {
    return {
      form: {
        rencana_projek_id: '',
        nama: '',
        deskripsi: '',
        no_urut: '',
      },
      feedback: {
        nama: '',
        deskripsi: '',
        no_urut: '',
      },
      state: {
        nama: null,
        deskripsi: null,
        no_urut: null,
      },
      data: null,
      loading_form: false,
      editModalShow: false,
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-modal-edit-projek', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      this.data = data
      this.form.rencana_projek_id = data.rencana_projek_id
      this.form.nama = data.nama
      this.form.deskripsi = data.deskripsi
      this.form.no_urut = data.no_urut
      this.editModalShow = true
    },
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault()
      // Trigger submit handler
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
      this.$http.post('/projek/update-projek', this.form).then(response => {
        this.loading_form = false
        let getData = response.data
        if(getData.errors){
          this.feedback.nama = (getData.errors.nama) ? getData.errors.nama.join(', ') : ''
          this.state.nama = (getData.errors.nama) ? false : null
          this.feedback.deskripsi = (getData.errors.deskripsi) ? getData.errors.deskripsi.join(', ') : ''
          this.state.deskripsi = (getData.errors.deskripsi) ? false : null
          this.feedback.no_urut = (getData.errors.no_urut) ? getData.errors.no_urut.join(', ') : ''
          this.state.no_urut = (getData.errors.no_urut) ? false : null
        } else {
          this.$swal({
            icon: getData.icon,
            title: getData.title,
            text: getData.text,
            customClass: {
              confirmButton: 'btn btn-success',
            },
          }).then(result => {
            this.editModalShow = false
            this.$emit('reload');
            this.resetForm()
          })
        }
      }).catch(error => {
        console.log(error);
      })
    },
    resetForm(){
      this.form.tingkat = ''
      this.form.rombongan_belajar_id = ''
      this.form.pembelajaran_id = ''
      this.form.nama = ''
      this.form.deskripsi = ''
      this.form.sub_elemen = {}
    },
  }
}
</script>