<template>
  <div v-if="Object.keys(profileData).length" id="user-profile">
    <profile-header :header-data="profileData.header" />
    <!-- profile info  -->
    <section id="profile-info">
      <b-row>
        <!-- about suggested page and twitter feed -->
        <b-col md="4" cols="12" order="2" order-lg="1">
          <profile-about :about-data="profileData.userAbout" />
          <profile-twitter-feed :twitter-feed="profileData.twitterFeeds" />
        </b-col>
        <!--b-col lg="3" cols="12" order="2" order-lg="1">
          <profile-suggested-pages :pages-data="profileData.suggestedPages" />
          
        </b-col-->
        <!--/ about suggested page and twitter feed -->

        <!-- post -->
        <b-col lg="8" cols="12" order="1" order-lg="2">
          <b-row>
            <b-col cols="12">
              <profile-post :posts="profileData.post" />
            </b-col>
            <b-col md="6" cols="12">
              <profile-suggestion :suggestions="profileData.suggestions" />
            </b-col>
            <b-col md="6" cols="12">
              <profile-polls :polls-data="profileData.polls" />
            </b-col>
          </b-row>
        </b-col>
        <!-- post -->
      </b-row>
    </section>
    <!--/ profile info  -->
  </div>
</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'

import ProfileHeader from './ProfileHeader.vue'
import ProfileAbout from './ProfileAbout.vue'
import ProfileTwitterFeed from './ProfileTwitterFeed.vue'
import ProfilePost from './ProfilePost.vue'
import ProfileSuggestion from './ProfileSuggestion.vue'
import ProfilePolls from './ProfilePolls.vue'
import eventBus from '@core/utils/eventBus'
/* eslint-disable global-require */
export default {
  components: {
    BRow,
    BCol,

    ProfileHeader,
    ProfileAbout,
    ProfileTwitterFeed,
    ProfilePost,
    ProfileSuggestion,
    ProfilePolls,
  },
  data() {
    return {
      profileData: {},
    }
  },
  created() {
    eventBus.$on('open-tab-feed', this.tabFeed);
    eventBus.$on('open-tab-about', this.tabAbout);
    eventBus.$on('open-tab-photos', this.tabPhotos);
    eventBus.$on('open-tab-friend', this.tabFriends);
    this.$http.get('/profile/data').then(res => {
      this.profileData = res.data
    })
  },
  methods: {
    tabFeed(){
      console.log('tabFeed');
    },
    tabAbout(){
      console.log('tabAbout');
    },
    tabPhotos(){
      console.log('tabPhotos');
    },
    tabFriends(){
      console.log('tabFriends');
    },
  },
}
/* eslint-disable global-require */
</script>

<style lang="scss" >
@import '~@resources/scss/vue/pages/page-profile.scss';
</style>
