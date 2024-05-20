<template>
  <b-container class="mt-2">
    <template v-if="token">
      <b-row class="match-height">
        <b-col cols="12" sm="4" v-for="(item, index) in items" :key="index">
          <b-card class="text-center" @click="showModal(item.id, item.text)">
            <b-img fluid :src="item.img" class="mb-2" />
            <div class="truncate">
              <h2 class="mb-25 font-weight-bolder">
                {{ item.text }}
              </h2>
            </div>
          </b-card>
        </b-col>
      </b-row>
      <h1 class="font-weight-bolder text-center">Arsip Dokumen</h1>
      <b-row class="match-height">
        <b-col cols="12" sm="6" v-for="(item, index) in arsip" :key="index">
          <b-card class="text-center" @click="$bvModal.show(item.id)">
            <h2 class="font-weight-bolder">{{ item.text }}</h2>
          </b-card>
          <b-modal :id="item.id" @show="resetModal" ok-only ok-title="Tutup" ok-variant="secondary" size="xl">
            <template #modal-title>
              {{ item.text }}
            </template>
            <div class="d-block text-center">
              <h3>Hello From This Modal! {{ item.text }}</h3>
            </div>
          </b-modal>
        </b-col>
      </b-row>
    </template>
    <template v-else>
      <b-card class="text-center">
        <b-form class="auth-login-form mt-2" @submit.prevent="setToken">
          <b-input-group prepend="TOKEN" class="mt-3">
            <b-form-input placeholder="Enter Token" v-model="token_text"></b-form-input>
            <b-input-group-append>
              <b-button type="submit" variant="info">Login</b-button>
            </b-input-group-append>
          </b-input-group>
        </b-form>
      </b-card>
    </template>
    <add-modal :modalId="modalId" :modalTitle="modalTitle" :showModal="show" @hidden="hideModal"></add-modal>
  </b-container>
</template>

<script>
import { BContainer, BCard, BAvatar, BImg, BRow, BCol, BButton, BForm, BInputGroup, BFormInput, BInputGroupAppend} from 'bootstrap-vue'
import { getToken } from '@/auth/utils'
import AddModal from './components/AddModal.vue'
export default {
  components: {
    BContainer, BCard, BAvatar, BImg, BRow, BCol, BButton, BForm, BInputGroup, BFormInput, BInputGroupAppend, AddModal
  },
  /*mounted() {
    this.$root.$on('bv::modal::show', (bvEvent, modalId) => {
      console.log('Modal is about to be shown', bvEvent, modalId)
    })
  },*/
  data() {
    return {
      modalId: '',
      modalTitle: '',
      show: false,
      items: [
        {
          img: '/img/pages/izin/sick.png',
          text: 'IZIN PULANG SAKIT',
          id: 'PS',
        },
        {
          img: '/img/pages/izin/bed.png',
          text: 'IZIN SAKIT',
          id: 'S',
        },
        {
          img: '/img/pages/izin/runner.png',
          text: 'IZIN TERLAMBAT',
          id: 'T',
        },
        {
          img: '/img/pages/izin/exit.png',
          text: 'IZIN KELUAR',
          id: 'K',
        },
        {
          img: '/img/pages/izin/family.png',
          text: 'IZIN ACARA KELUARGA',
          id: 'AK',
        },
        {
          img: '/img/pages/izin/snuggly.png',
          text: 'IZIN ISTIRAHAT',
          id: 'IS',
        },
      ],
      arsip: [
        {
          text: 'Surat Pemunduran Diri',
          id: 'arsip-1',
        },
        {
          text: 'Surat Pernyataan',
          id: 'arsip-2',
        },
        {
          text: 'Surat Mutasi',
          id: 'arsip-3',
        },
        {
          text: 'Pakta Integritas',
          id: 'arsip-4',
        },
      ],
      title: '',
      token: null,
      token_text: null,
    }
  },
  created() {
    this.loadPostData()
  },
  methods: {
    showModal(id, title){
      this.modalId = id
      this.modalTitle = title
      this.show = true
    },
    hideModal(){
      this.modalId = ''
      this.modalTitle = ''
      this.show = false
    },
    resetModal(){
      this.title = ''
    },
    setToken(){
      if(this.token_text != 'tesToken'){
        this.$swal({
          icon: 'error',
          title: 'Gagal',
          text: 'Token tidak terdaftar',
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.loadPostData()
        })
      }
      localStorage.setItem('userToken', this.token_text)
      this.loadPostData()
    },
    loadPostData(){
      this.token_text = null
      this.token = getToken()
      if(this.token && this.token != 'tesToken'){
        localStorage.removeItem('userToken')
        this.loadPostData()
      }
    }
  }
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>