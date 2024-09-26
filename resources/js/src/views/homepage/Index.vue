<template>
  <b-row>
    <b-col cols="12">
      <swiper class="swiper-autoplay" :options="swiperOptions" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
        <swiper-slide v-for="data in swiperData" :key="data.img">
          <b-img :src="data.img" fluid />
        </swiper-slide>
        <div slot="pagination" class="swiper-pagination" />
        <div slot="button-next" class="swiper-button-next" />
        <div slot="button-prev" class="swiper-button-prev" />
      </swiper>
    </b-col>
    <b-col cols="12" class="mt-2">
      <b-card title="LATEST POST">
        <b-row class="match-height">
          <b-col md="6" lg="4">
            <b-card :img-src="'/img/slider/04.jpg'" img-alt="Card image cap" img-top title="Card title">
              <b-card-text>
                Some quick example text to build on the card title and make up the bulk of the card's content.
              </b-card-text>
              <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary" :to="{name: 'post', params: {slug: 'satu'}}">
                Selengkapnya &raquo;
              </b-button>
            </b-card>
          </b-col>
          <b-col md="6" lg="4">
            <b-card :img-src="'/img/slider/04.jpg'" img-alt="Card image cap" img-top title="Card title">
              <b-card-text>
                Some quick example text to build on the card title and make up the bulk of the card's content.
              </b-card-text>
              <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary">
                Go Somewhere
              </b-button>
            </b-card>
          </b-col>
          <b-col md="6" lg="4">
            <b-card :img-src="'/img/slider/04.jpg'" img-alt="Card image cap" img-top title="Card title">
              <b-card-text>
                Some quick example text to build on the card title and make up the bulk of the card's content.
              </b-card-text>
              <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary">
                Go Somewhere
              </b-button>
            </b-card>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-pagination v-model="current_page" :total-rows="total" :per-page="per_page" @change="changePage" align="center"></b-pagination>
          </b-col>
        </b-row>
      </b-card>
    </b-col>
  </b-row>
</template>

<script>
import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
import { BRow, BCol, BCard, BImg, BCardText, BButton, BPagination } from 'bootstrap-vue'
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
  },
  directives: {
    Ripple,
  },
  data() {
    return {
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
      /* eslint-disable global-require */

    }
  },
  methods: {
    changePage(val) {
      console.log(val);
      
    },
  },
}
</script>