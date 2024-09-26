<template>
  <content-with-sidebar class="cws-container cws-sidebar-right blog-wrapper">
    <div class="blog-detail-wrapper">
      <b-row>
        <b-col cols="12">
          <b-card :img-src="blog.img" img-top img-alt="Blog Detail Pic" :title="blog.title">
            <b-media no-body>
              <b-media-aside vertical-align="center" class="mr-50">
                <b-avatar href="javascript:void(0)" size="24" :src="blog.avatar" />
              </b-media-aside>
              <b-media-body>
                <small class="text-muted mr-50">by</small>
                <small>
                  <b-link class="text-body">{{ blog.userFullName }}</b-link>
                </small>
                <span class="text-muted ml-75 mr-50">|</span>
                <small class="text-muted">{{ blog.createdTime }}</small>
              </b-media-body>
            </b-media>
            <div class="my-1 py-25">
              <b-link v-for="tag in blog.tags" :key="tag">
                <b-badge pill class="mr-75" :variant="tagsColor(tag)">
                  {{ tag }}
                </b-badge>
              </b-link>
            </div>
            <div class="blog-content" v-html="blog.content" />
          </b-card>
        </b-col>
      </b-row>
    </div>
    <div slot="sidebar" class="blog-sidebar py-2 py-lg-0">
      <div class="blog-recent-posts mt-3">
        <h6 class="section-label mb-75">
          Recent Posts
        </h6>
        <b-media v-for="(recentpost, index) in recentPosts" :key="recentpost.img" no-body :class="index ? 'mt-2' : ''">
          <b-media-aside class="mr-2">
            <b-link :href="`/post/${recentpost.slug}`">
              <b-img :src="recentpost.img" :alt="recentpost.img.slice(6)" width="100" rounded height="70" />
            </b-link>
          </b-media-aside>
          <b-media-body>
            <h6 class="blog-recent-post-title">
              <b-link class="text-body-heading" :href="`/post/${recentpost.slug}`">
                {{ recentpost.title }}
              </b-link>
            </h6>
            <span class="text-muted mb-0">
              {{ recentpost.createdTime }}
            </span>
          </b-media-body>
        </b-media>
      </div>
    </div>
  </content-with-sidebar>
</template>

<script>
import { BRow, BCol, BCard, BImg, BCardBody, BCardTitle, BMediaAside, BMedia, BMediaBody, BLink, BAvatar, BBadge } from 'bootstrap-vue'
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
  },
  data() {
    return {
      namaSekolah: $themeConfig.app.namaSekolah,
      recentPosts: [
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
      blog: {
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
    document.title = this.blog.title + ' | ' + this.namaSekolah
  },
  methods: {
    tagsColor(tag) {
      return 'light-primary'
    },
  },
}
</script>