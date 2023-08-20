<template>
  <div v-if="Object.keys(profileData).length" id="user-profile">
    <profile-header :header-data="detil_data" @reload="getDetilData"  />
    <!-- profile info  -->
    <section id="profile-info">
      <b-row>
        <!-- about suggested page and twitter feed -->
        <b-col md="4" cols="12" order="2" order-lg="1">
          <profile-about @detil="handleDetil" @update="handleUpdate" :detil_data="detil_data" />
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
    <profile-edit :detil_data="profileData" @reload="getDetilData"></profile-edit>
    <profile-detil :detil_data="detil_data"></profile-detil>
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
import ProfileEdit from './ProfileEdit.vue'
import ProfileDetil from './ProfileDetil.vue'
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
    ProfileEdit,
    ProfileDetil,
  },
  data() {
    return {
      showDetil: false,
      showUpdate: false,
      editModal: false,
      profileData: {},
      detil_data: null,
    }
  },
  created() {
    eventBus.$on('open-tab-feed', this.tabFeed);
    eventBus.$on('open-tab-about', this.tabAbout);
    eventBus.$on('open-tab-photos', this.tabPhotos);
    eventBus.$on('open-tab-friend', this.tabFriends);
    this.getDetilData()
  },
  methods: {
    getDetilData(){
      this.$http.get('/profile/data', {
        params: {
          peserta_didik_id: this.user.peserta_didik_id,
        }
      }).then(res => {
        this.detil_data = res.data.detil
        this.profileData = res.data
      })
    },
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
    handleDetil(){
      eventBus.$emit('open-detil-modal');
      console.log('handleDetil');
    },
    handleUpdate(){
      eventBus.$emit('open-edit-modal');
      console.log('handleUpdate');
    },
    handleFoto(foto){
      console.log(foto);
    },
  },
}
/* eslint-disable global-require */
</script>

<style lang="scss" >
@import '~@resources/scss/vue/pages/page-profile.scss';
</style>
