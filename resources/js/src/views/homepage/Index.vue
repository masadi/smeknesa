<template>
  <b-row>
    <template v-if="data_slider.length">
      <b-col cols="12">
        <swiper class="swiper-autoplay" :options="swiperOptions" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
          <swiper-slide v-for="slider in data_slider" :key="slider.id">
            <b-img :src="`/storage/images/${slider.gambar}`" fluid />
          </swiper-slide>
          <div slot="pagination" class="swiper-pagination" />
        </swiper>
      </b-col>
  </template>
    <b-col cols="12" class="mt-2">
      <b-card title="LATEST POST">
        <b-row class="match-height">
          <template v-if="items.length">
            <b-col md="6" lg="4" v-for="item in items" :key="item.id">
              <b-card :img-src="`/storage/post-images/${item.post_image}`" img-alt="Card image cap" img-top :title="item.post_title">
                <b-card-text v-html="fn(item.post_content, 150)"></b-card-text>
                <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary" :to="{name: 'post', params: {slug: item.post_name}}">
                  Selengkapnya &raquo;
                </b-button>
              </b-card>
            </b-col>
          </template>
          <template v-else>
            <b-col cols="12">
              <b-card>
                <h2 class="text-center">Tidak ada data untuk ditampilkan</h2>
              </b-card>
            </b-col>
          </template>
        </b-row>
        <b-row v-if="items.length">
          <b-col cols="12">
            <b-pagination v-model="current_page" :total-rows="meta.total" :per-page="per_page" @change="changePage" align="center"></b-pagination>
          </b-col>
        </b-row>
      </b-card>
    </b-col>
    <b-col cols="12" class="mt-2">
      <b-card title="MODUL AJAR">
        <b-row class="match-height">
          <template v-if="modul_ajar.length">
            <b-col md="6" lg="4" v-for="modul in modul_ajar" :key="modul.id">
              <b-card no-body :img-src="`/storage/images/${modul.gambar}`" img-alt="Card image cap" img-top>
                <b-card-body>
                  <b-row>
                    <b-col>{{ modul.pembelajaran.nama_mata_pelajaran }}</b-col>
                    <b-col class="text-right">{{ modul.download_count }} <font-awesome-icon icon="fa-solid fa-cloud-arrow-down" /></b-col>
                  </b-row>
                  <b-card-title class="mt-2">{{ modul.judul }}</b-card-title>
                  <b-card-text>{{ modul.deskripsi }}</b-card-text>
                </b-card-body>
                <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary" :href="`/unduh-modul/${modul.id}`">
                  UNDUH
                </b-button>
              </b-card>
            </b-col>
          </template>
          <template v-else>
            <b-col cols="12">
              <b-card>
                <h2 class="text-center">Tidak ada data untuk ditampilkan</h2>
              </b-card>
            </b-col>
          </template>
        </b-row>
        <b-row v-if="modul_ajar.length">
          <b-col cols="12">
            <b-pagination v-model="current_page_modul_ajar" :total-rows="meta_modul_ajar.total" :per-page="modul_ajar_per_page" @change="changePageModuleAjar" align="center"></b-pagination>
          </b-col>
        </b-row>
      </b-card>
    </b-col>
    <b-col cols="12" class="mt-2">
      <b-card class="pb-2">
        <b-row class="match-height">
          <template v-if="data_video.length">
            <b-col md="6" lg="4" v-for="video in data_video" :key="video.id">
              <div class="videowrapper mb-1">
                <youtube :video-id="$youtube.getIdFromURL(video.link_youtube)"></youtube>
              </div>
              <h4>{{ video.judul }}</h4>
              <p v-html="video.deskripsi"></p>
            </b-col>
          </template>
          <template v-else>
            <b-col cols="12">
              <b-card>
                <h2 class="text-center">Tidak ada data untuk ditampilkan</h2>
              </b-card>
            </b-col>
          </template>
        </b-row>
      </b-card>
    </b-col>
  </b-row>
</template>

<script>
import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
import { BRow, BCol, BCard, BImg, BCardText, BButton, BPagination, BCardBody, BCardTitle } from 'bootstrap-vue'
import 'swiper/css/swiper.css'
import Ripple from 'vue-ripple-directive'
export default {
  components: {
    Swiper,
    SwiperSlide,
    BRow,
    BCol,
    BCard,
    BImg,
    BCardText,
    BButton,
    BPagination,
    BCardBody,
    BCardTitle,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      videoId: null,
      current_page: 1,
      total: 10,
      per_page: 3,
      swiperOptions: {
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: {
          delay: 2500,
          disableOnInteraction: false,
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
      },

      /* eslint-disable global-require */
      swiperData: [
        { img: '/img/banner/banner-20.jpg' },
        { img: '/img/banner/banner-7.jpg' },
        { img: '/img/banner/banner-8.jpg' },
        { img: '/img/banner/banner-9.jpg' },
        { img: '/img/banner/banner-10.jpg' },
        { img: '/img/banner/banner-11.jpg' },
      ],
      items: [],
      meta: {},
      current_page: 1, //DEFAULT PAGE YANG AKTIF ADA PAGE 1
      per_page: 3, //DEFAULT LOAD PERPAGE ADALAH 10
      sortBy: 'created_at', //DEFAULT SORTNYA ADALAH CREATED_AT
      sortByDesc: true, //ASCEDING
      modul_ajar: [],
      meta_modul_ajar: {},
      current_page_modul_ajar: 1,
      modul_ajar_per_page: 9,
      data_video: [],
      data_slider: [],
    }
  },
  created() {
    this.loadPostData()
    this.loadModuleData()
    this.loadVideo()
    this.loadSlider()
  },
  methods: {
    loadVideo(){
      this.$http.get('/module/video').then(res => {
        let getData = res.data
        this.data_video = getData
      })
    },
    loadSlider(){
      this.$http.get('/module/slider').then(res => {
        let getData = res.data
        this.data_slider = getData
      })
    },
    loadPostData() {
      this.$http.get('/artikel', {
        params: {
          page: this.current_page,
          per_page: this.per_page,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
        }
      }).then(response => {
        let getData = response.data.data
        this.items = getData.data
        this.meta = {
          total: getData.total,
          current_page: getData.current_page,
          per_page: getData.per_page,
          from: getData.from,
          to: getData.to,
        }
      })
    },
    loadModuleData() {
      this.$http.get('/module', {
        params: {
          page: this.current_page,
          per_page: this.per_page,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
        }
      }).then(response => {
        let getData = response.data.data
        this.modul_ajar = getData.data
        this.meta_modul_ajar = {
          total: getData.total,
          current_page: getData.current_page,
          per_page: getData.per_page,
          from: getData.from,
          to: getData.to,
        }
      })
    },
    changePage(val) {
      console.log(val);
    },
    changePageModuleAjar(val){
      console.log(val);
    },
    fn(text, count){
      return text.slice(0, count) + (text.length > count ? "..." : "");
  },
  },
}
</script>
<style>
.videowrapper { float: none; clear: both; width: 100%; position: relative; padding-bottom: 56.25%; padding-top: 25px; height: 0; } .videowrapper iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
</style>