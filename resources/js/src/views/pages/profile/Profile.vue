<template>
  <b-container v-if="Object.keys(profileData).length" id="user-profile" class="mt-2">
    <profile-header :header-data="detil_data" @reload="getDetilData" :profile-active="profileActive" :modul-active="modulActive" :quiz-active="quizActive" :feedback-active="feedbackActive"  />
    <section id="profile-info">
      <b-row>
        <b-col md="4" cols="12" order="2" order-lg="1">
          <profile-about @detil="handleDetil" @update="handleUpdate" :detil_data="detil_data" />
        </b-col>
        <b-col lg="8" cols="12" order="1" order-lg="2">
          <b-row>
            <b-col cols="12">
              <profile-timeline :posts="profileData.post" v-if="profileActive"/>
              <tab-modul v-if="modulActive"/>
              <tab-quiz v-if="quizActive"/>
              <tab-feedback v-if="feedbackActive"/>
            </b-col>
            <template v-if="profileActive">
              <b-col md="6" cols="12">
                <profile-teman :suggestions="profileData.suggestions" />
              </b-col>
              <b-col md="6" cols="12">
                <profile-guru :suggestions="profileData.suggestions" />
              </b-col>
            </template>
          </b-row>
        </b-col>
      </b-row>
    </section>
    <profile-edit :detil_data="profileData" @reload="getDetilData"></profile-edit>
    <profile-detil :detil_data="detil_data"></profile-detil>
  </b-container>
</template>

<script>
import { BContainer, BRow, BCol } from 'bootstrap-vue'

import ProfileHeader from './ProfileHeader.vue'
import ProfileAbout from './ProfileAbout.vue'
import ProfileTimeline from './ProfileTimeline.vue'
import ProfileTeman from './ProfileTeman.vue'
import ProfileGuru from './ProfileGuru.vue'
import ProfileEdit from './ProfileEdit.vue'
import ProfileDetil from './ProfileDetil.vue'
import TabFeedback from './TabFeedback.vue'
import TabModul from './TabModul.vue'
import TabQuiz from './TabQuiz.vue'
import eventBus from '@core/utils/eventBus'
/* eslint-disable global-require */
export default {
  components: {
    BContainer,
    BRow,
    BCol,

    ProfileHeader,
    ProfileAbout,
    ProfileTimeline,
    ProfileTeman,
    ProfileGuru,
    ProfileEdit,
    ProfileDetil,
    TabFeedback,
    TabModul,
    TabQuiz,
  },
  data() {
    return {
      showDetil: false,
      showUpdate: false,
      editModal: false,
      profileData: {},
      detil_data: null,
      profileActive: true,
      modulActive: false,
      quizActive: false,
      feedbackActive: false,
    }
  },
  created() {
    eventBus.$on('open-tab-profile', this.tabProfile);
    eventBus.$on('open-tab-modul', this.tabModul);
    eventBus.$on('open-tab-quiz', this.tabQuiz);
    eventBus.$on('open-tab-feedback', this.tabFeedback);
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
    tabProfile(){
      console.log('tabProfile');
      this.profileActive = true
      this.modulActive = false
      this.quizActive = false
      this.feedbackActive = false
    },
    tabModul(){
      console.log('tabModul');
      this.profileActive = false
      this.modulActive = true
      this.quizActive = false
      this.feedbackActive = false
    },
    tabQuiz(){
      console.log('tabQuiz');
      this.profileActive = false
      this.modulActive = false
      this.quizActive = true
      this.feedbackActive = false
    },
    tabFeedback(){
      console.log('tabFeedback');
      this.profileActive = false
      this.modulActive = false
      this.quizActive = false
      this.feedbackActive = true
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
