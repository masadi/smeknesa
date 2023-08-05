<template>
  <b-modal v-model="modalShow" title="Detil Pengguna" size="lg" @ok="handleOk">
    <b-overlay :show="loading_form" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-form ref="form" @submit.stop.prevent="handleSubmit">
        <b-row>
          <b-col cols="12">
            <b-form-group label="Nama Lengkap" label-for="name" label-cols-md="3">
              <b-form-input v-model="data.name" disabled></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-form-group label="Email" label-for="email" label-cols-md="3">
              <b-form-input v-model="data.email" disabled></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-form-group label="Password" label-for="password" label-cols-md="3">
              <template v-if="getStatus(data.status_password)">
                <b-form-input v-model="data.status_password" disabled></b-form-input>
              </template>
              <template v-else>
                <b-badge variant="danger">Custom</b-badge>
              </template>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-form-group label="Hak Akses" v-slot="{ ariaDescribedby }" label-cols-md="3">
              <b-form-checkbox-group v-model="form.hak_akses" :options="data_roles" value-field="name" text-field="display_name" :aria-describedby="ariaDescribedby" name="hak-akses" class="mb-2" stacked></b-form-checkbox-group>
            </b-form-group>
          </b-col>
        </b-row>
      </b-form>
    </b-overlay>
    <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
          <b-button @click="cancel()">Tutup</b-button>
        </b-overlay>
        <template v-if="data.guru_id">
          <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
            <b-button variant="success" @click="ok()">Perbaharui</b-button>
          </b-overlay>
        </template>
      </template>
  </b-modal>
</template>

<script>
import { BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton, BBadge, BFormCheckboxGroup } from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BOverlay, BForm, BFormInput, BRow, BCol, BFormGroup, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BButton, BBadge, BFormCheckboxGroup
  },
  data() {
    return {
      modalShow: false,
      loading_form: false,
      data_roles: [],
      data: {
        name: '',
        guru_id: '',
      },
      form: {
        user_id: '',
        hak_akses: [],
      },
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    eventBus.$on('open-user-modal', this.handleEvent);
  },
  methods: {
    getStatus(password){
      if(password === 'custom'){
        return false;
      }
      return true;
    },
    handleEvent(val){
      console.log(val);
      this.data = val.user
      var hak_akses = []
      this.data.roles.forEach(function(value, key) {
          hak_akses.push(value.name)
      })
      this.form.hak_akses = hak_akses
      this.form.user_id = val.user.user_id
      this.data_roles = val.roles
      this.modalShow = true
    },
    hideModal(){
      this.modalShow = false
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit(){
      this.loading_form = true
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
      })
    },
  },
}
</script>
<style lang="scss">
.custom-control {
  margin-bottom: 5px;
}
</style>