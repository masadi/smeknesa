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
          <template v-if="items.length">
            <b-col md="6" lg="4" v-for="item in items" :key="item.id">
              <b-card :img-src="`/storage/post-images/${item.post_image}`" img-alt="Card image cap" img-top :title="item.post_title">
                <b-card-text v-html="fn(item.post_content, 20)"></b-card-text>
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
      items: [],
      meta: {},
      current_page: 1, //DEFAULT PAGE YANG AKTIF ADA PAGE 1
      per_page: 3, //DEFAULT LOAD PERPAGE ADALAH 10
      sortBy: 'created_at', //DEFAULT SORTNYA ADALAH CREATED_AT
      sortByDesc: true, //ASCEDING
    }
  },
  created() {
    this.loadPostData()
  },
  methods: {
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
    changePage(val) {
      console.log(val);
      
    },
    fn(text, count){
      return text.slice(0, count) + (text.length > count ? "..." : "");
  },
  },
}
</script>