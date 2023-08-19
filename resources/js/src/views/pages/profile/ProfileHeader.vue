<template>
  <b-card class="profile-header mb-2" :img-src="headerData.coverImg" img-top alt="cover photo" body-class="p-0">
    <!-- profile picture -->
    <div class="position-relative">
      <div class="profile-img-container d-flex align-items-center">
        <div class="profile-img">
          <b-img :src="headerData.avatar" rounded fluid alt="profile photo" />
        </div>
        <!-- profile title -->
        <div class="profile-title ml-3">
          <h2 class="text-white">
            {{ headerData.username }}
          </h2>
          <p class="text-white">
            {{ headerData.designation }}
          </p>
        </div>
        <!--/ profile title -->
      </div>
    </div>
    <!--/ profile picture -->

    <!-- profile navbar -->
    <div class="profile-header-nav">
      <b-navbar toggleable="md" type="light">
        <!-- toggle button -->
        <b-navbar-toggle class="ml-auto" target="nav-text-collapse">
          <feather-icon icon="AlignJustifyIcon" size="21" />
        </b-navbar-toggle>
        <!--/ toggle button -->

        <!-- collapse -->
        <b-collapse id="nav-text-collapse" is-nav>
          <b-tabs pills class="profile-tabs mt-1 mt-md-0" nav-class="mb-0">
            <template #tabs-start>
              <b-nav-item role="presentation" active class="font-weight-bold" @click="tabFeed">
                <span class="d-none d-md-block">Profile</span>
                <feather-icon icon="UserCheckIcon" class="d-block d-md-none" />
              </b-nav-item>
              <b-nav-item role="presentation" class="font-weight-bold"  @click="tabAbout">
                <span class="d-none d-md-block">Team</span>
                <feather-icon icon="InfoIcon" class="d-block d-md-none" />
              </b-nav-item>
              <b-nav-item role="presentation" class="font-weight-bold"  @click="tabPhotos">
                <span class="d-none d-md-block">Projects</span>
                <feather-icon icon="ImageIcon" class="d-block d-md-none" />
              </b-nav-item>
              <b-nav-item role="presentation" class="font-weight-bold"  @click="tabFriends">
                <span class="d-none d-md-block">Friends</span>
                <feather-icon icon="UsersIcon" class="d-block d-md-none" />
              </b-nav-item>
            </template>

            <!-- edit buttons -->
            <template #tabs-end>
              <b-button variant="danger" class="ml-auto" @click="logout">
                <feather-icon icon="LogOutIcon" class="d-block d-md-none" />
                <span class="font-weight-bold d-none d-md-block">Logout</span>
              </b-button>
            </template>
            <!-- edit buttons -->
          </b-tabs>

        </b-collapse>
        <!--/ collapse -->
      </b-navbar>
    </div>
    <!--/ profile navbar -->
  </b-card>
</template>

<script>
import {
  BCard, BImg, BNavbar, BNavbarToggle, BCollapse, BTabs, BNavItem, BButton,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import eventBus from '@core/utils/eventBus'
import { initialAbility } from '@/libs/acl/config'
import useJwt from '@/auth/jwt/useJwt'
export default {
  components: {
    BCard,
    BTabs,
    BButton,
    BNavItem,
    BNavbar,
    BNavbarToggle,
    BCollapse,
    BImg,
  },
  directives: {
    Ripple,
  },
  props: {
    headerData: {
      type: Object,
      default: () => {},
    },
  },
  methods: {
    tabFeed(){
      eventBus.$emit('open-tab-feed');
    },
    tabAbout(){
      eventBus.$emit('open-tab-about');
    },
    tabPhotos(){
      eventBus.$emit('open-tab-photos');
    },
    tabFriends(){
      eventBus.$emit('open-tab-friend');
    },
    logout() {
      // Remove userData from localStorage
      // ? You just removed token from localStorage. If you like, you can also make API call to backend to blacklist used token
      localStorage.removeItem(useJwt.jwtConfig.storageTokenKeyName)
      localStorage.removeItem(useJwt.jwtConfig.storageRefreshTokenKeyName)
      localStorage.removeItem('semester_id')
      // Remove userData from localStorage
      localStorage.removeItem('userData')

      // Reset ability
      this.$ability.update(initialAbility)

      // Redirect to login page
      this.$router.push({ name: 'auth-login' })
    },
  }
}
</script>
