<template>
  <content-with-sidebar class="cws-container cws-sidebar-right blog-wrapper">
    <div class="blog-detail-wrapper">
      <b-row>
        <template v-if="isBusy">
          <b-col cols="12">
            <b-card>
              <div class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
              </div>
            </b-card>
          </b-col>
        </template>
        <template v-else>
          <b-col cols="12" v-if="blog">
            <b-card :img-src="`/storage/post-images/${blog.post_image}`" img-top img-alt="Blog Detail Pic"
              :title="blog.post_title">
              <b-media no-body>
                <b-media-aside vertical-align="center" class="mr-50">
                  <b-avatar href="javascript:void(0)" size="24" :src="blog.post_avatar" />
                </b-media-aside>
                <b-media-body>
                  <small class="text-muted mr-50">by</small>
                  <small>
                    <b-link class="text-body">{{ blog.post_author }}</b-link>
                  </small>
                  <span class="text-muted ml-75 mr-50">|</span>
                  <small class="text-muted">{{ new Date(blog.created_at).toLocaleString('id-ID', {
                    hour12: false,
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                  }) }}</small>
                </b-media-body>
              </b-media>
              <div class="my-1 py-25">
                <b-link v-for="tag in blog.tags" :key="tag.id">
                  <b-badge pill class="mr-75" :variant="tagsColor(tag.name)">
                    {{ tag.name }}
                  </b-badge>
                </b-link>
              </div>
              <div class="blog-content" v-html="blog.post_content" />
            </b-card>
          </b-col>
          <b-col cols="12" v-else>
            <h3 class="text-center">Artikel tidak ditemukan</h3>
          </b-col>
        </template>
      </b-row>
    </div>
    <div slot="sidebar" class="blog-sidebar py-2 py-lg-0">
      <div class="blog-recent-posts mt-3">
        <h6 class="section-label mb-75">
          Recent Posts
        </h6>
        <template v-if="isBusy">
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
          </div>
        </template>
        <template v-else>
          <b-media v-for="(recentpost, index) in recentPosts" :key="recentpost.id" no-body
            :class="index ? 'mt-2' : ''">
            <b-media-aside class="mr-2">
              <b-link :href="`/post/${recentpost.post_name}`">
                <b-img :src="`/storage/post-images/${recentpost.post_image}`" :alt="recentpost.post_title" width="100" rounded height="70" />
              </b-link>
            </b-media-aside>
            <b-media-body>
              <h6 class="blog-recent-post-title">
                <b-link class="text-body-heading" :href="`/post/${recentpost.post_name}`">
                  {{ recentpost.post_title }}
                </b-link>
              </h6>
              <span class="text-muted mb-0">
                {{ new Date(recentpost.created_at).toLocaleString('id-ID', {
                    hour12: false,
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                  }) }}
              </span>
            </b-media-body>
          </b-media>
        </template>
      </div>
    </div>
  </content-with-sidebar>
</template>

<script>
import { BRow, BCol, BCard, BImg, BCardBody, BCardTitle, BMediaAside, BMedia, BMediaBody, BLink, BAvatar, BBadge, BSpinner } from 'bootstrap-vue'
import ContentWithSidebar from '@core/layouts/components/content-with-sidebar/ContentWithSidebar.vue'
import { $themeConfig } from '@themeConfig'
export default {
  components: {
    ContentWithSidebar,
    BRow,
    BCol,
    BCard,
    BCardBody,
    BCardTitle,
    BImg,
    BMedia,
    BMediaAside,
    BMediaBody,
    BLink,
    BAvatar,
    BBadge,
    BSpinner,
  },
  data() {
    return {
      isBusy: true,
      namaSekolah: $themeConfig.app.namaSekolah,
      recentPosts: [],
      recentPosts_salah: [
        {
          id: 7,
          slug: 'satu',
          img: '/img/banner/banner-22.jpg',
          title: 'Why Should Forget Facebook?',
          createdTime: 'Jan 14 2020',
        },
        {
          id: 8,
          slug: 'dua',
          img: '/img/banner/banner-27.jpg',
          title: 'Publish your passions, your way',
          createdTime: 'Mar 04 2020',
        },
        {
          id: 9,
          slug: 'tiga',
          img: '/img/banner/banner-39.jpg',
          title: 'The Best Ways to Retain More',
          createdTime: 'Feb 18 2020',
        },
        {
          id: 10,
          slug: 'empat',
          img: '/img/banner/banner-35.jpg',
          title: 'Share a Shocking Fact or Statistic',
          createdTime: 'Oct 08 2020',
        },
      ],
      blog: null,
      blog_salah: {
        img: '/img/banner/banner-12.jpg',
        title: 'The Best Features Coming to iOS and Web design',
        avatar: '/img/portrait/small/avatar-s-7.jpg',
        userFullName: 'Administrator',
        createdTime: 'Jan 10, 2020',
        tags: ['Gaming', 'Video'],
        content:
          '<p>Before you get into the nitty-gritty of coming up with a perfect title, start with a rough draft: your working title. What is that, exactly? A lot of people confuse working titles with topics. Let\'s clear that Topics are very general and could yield several different blog posts. Think "raising healthy kids," or "kitchen storage." A writer might look at either of those topics and choose to take them in very, very different directions.A working title, on the other hand, is very specific and guides the creation of a single blog post. For example, from the topic "raising healthy kids," you could derive the following working title See how different and specific each of those is? That\'s what makes them working titles, instead of overarching topics.</p><h4>Unprecedented Challenge</h4><ul class="mb-2"><li>Preliminary thinking systems</li><li>Bandwidth efficient</li><li>Green space</li><li>Social impact</li><li>Thought partnership</li><li>Fully ethical life</li></ul>',
      }
    }
  },
  created() {
    this.getPost(this.$route.params.slug)
  },
  methods: {
    tagsColor(tag) {
      return 'light-primary'
    },
    getPost(slug) {
      this.isBusy = true
      this.$http.post('/artikel/baca', {
        slug: slug,
      }).then(res => {
        this.isBusy = false
        let getData = res.data
        this.blog = getData.post
        this.recentPosts = getData.latest
        if(this.blog){
          document.title = this.blog.post_title + ' | ' + this.namaSekolah
        }
      });
    },
  },
}
</script>