<template>
  <b-container class="mt-2">
    <template v-if="token">
      <b-row class="justify-content-md-center">
        <b-col cols="4" class="text-center">
          <h2>{{ currentTime.toLocaleString('id-ID', {
          hour12: false,
          day: '2-digit',
          month: 'long',
          year: 'numeric',
        }) }}</h2>
        <h1>{{ currentTime.toLocaleString('id-ID', {
          hour12: false,
          hour: '2-digit',
          minute: '2-digit',
          second: '2-digit',
        }) }}</h1>
        </b-col>
      </b-row>
      <b-row class="match-height">
        <div class="col-md-6 col-lg-4 mb-3" v-for="(item, index) in menu_baru" :key="index">
          <div class="card h-100">
            <img class="card-img-top" :src="item.img" alt="Card image cap" />
            <div class="card-body">
              <h5 class="card-title">{{ item.title }}</h5>
              <p class="card-text">{{ item.text }}</p>
              <a href="javascript:void(0)" @click="showModal(item.id, item.title)" class="btn btn-outline-primary">{{
                item.btn_text }}</a>
            </div>
          </div>
        </div>
      </b-row>
      <b-row class="match-height" v-if="showLama">
        <b-col cols="12" sm="6" v-for="(item, index) in items" :key="index">
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
    <add-modal :modalId="modalId" :modalTitle="modalTitle" :showModal="show" @hidden="hideModal" @detil="handleDetil"></add-modal>
    <DetilPresensi></DetilPresensi>
  </b-container>
</template>

<script>
import { BContainer, BCard, BAvatar, BImg, BRow, BCol, BButton, BForm, BInputGroup, BFormInput, BInputGroupAppend} from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
import { getToken } from '@/auth/utils'
import AddModal from './components/AddModal.vue'
import DetilPresensi from './components/DetilPresensi.vue';
export default {
  components: {
    BContainer, BCard, BAvatar, BImg, BRow, BCol, BButton, BForm, BInputGroup, BFormInput, BInputGroupAppend, AddModal, DetilPresensi
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
      showLama: false,
      menu_baru: [
        {
          img: '/img/elements/1.jpg',
          title: 'TITLE IJIN',
          text: 'TEXT IJIN',
          btn_text: 'GO IJIN',
          id: 'I',
        },
        {
          img: '/img/elements/2.jpg',
          title: 'TITLE TERLAMBAT',
          text: 'TEXT TERLAMBAT',
          btn_text: 'GO TERLAMBAT',
          id: 'T',
        },
        {
          img: '/img/elements/3.jpg',
          title: 'TITLE REKAP',
          text: 'TEXT REKAP',
          btn_text: 'GO REKAP',
          id: 'R',
        }
      ],
      items: [
        {
          img: '/img/pages/izin/exit.png',
          text: 'IZIN',
          id: 'I',
        },
        {
          img: '/img/pages/izin/runner.png',
          text: 'TERLAMBAT',
          id: 'T',
        },
        /*{
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
          img: '/img/pages/izin/family.png',
          text: 'IZIN ACARA KELUARGA',
          id: 'AK',
        },
        {
          img: '/img/pages/izin/snuggly.png',
          text: 'IZIN ISTIRAHAT',
          id: 'IS',
        },*/
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
      currentTime: new Date(),
    }
  },
  created() {
    this.loadPostData()
    console.log(this.currentTime);
    setInterval(this.updateCurrentTime, 1000)
  },
  methods: {
    updateCurrentTime(){
      this.currentTime = new Date()
      console.log(this.currentTime);
    },
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
    },
    handleDetil(val){
      eventBus.$emit('open-modal-detil-presensi-pd', val)
    },
  }
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>