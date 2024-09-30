<template>
  <div class="navbar-container d-flex content align-items-center">

    <!-- Nav Menu Toggler -->
    <ul class="nav navbar-nav d-xl-none">
      <li class="nav-item">
        <b-link
          class="nav-link"
          @click="toggleVerticalMenuActive"
        >
          <feather-icon
            icon="MenuIcon"
            size="21"
          />
        </b-link>
      </li>
    </ul>

    <!-- Left Col -->
    <div class="bookmark-wrapper align-items-center flex-grow-1 d-none d-lg-flex">
      <!--bookmarks /-->
      <b-link
          class="navbar-brand"
          to="/"
        >
          <span class="brand-logo">
            <b-img
              :src="appLogoImage"
              alt="logo"
            />
          </span>
          <h2 class="brand-text mb-0">
            {{ appName }}
          </h2>
        </b-link>
    </div>

    <!-- Right Col -->
    <b-navbar-nav class="nav align-items-center ml-auto">
      <dark-Toggler class="d-none d-lg-block" />
      <notification-dropdown />
      <user-dropdown />
    </b-navbar-nav>
  </div>
</template>

<script>
import {
  BLink, BImg, BNavbarNav,
} from 'bootstrap-vue'
import Bookmarks from './components/Bookmarks.vue'
import Locale from './components/Locale.vue'
import DarkToggler from './components/DarkToggler.vue'
import SearchBar from './components/SearchBar.vue'
import CartDropdown from './components/CartDropdown.vue'
import NotificationDropdown from './components/NotificationDropdown.vue'
import UserDropdown from './components/UserDropdown.vue'
import { $themeConfig } from '@themeConfig'

export default {
  components: {
    BLink,
    BImg,
    // Navbar Components
    BNavbarNav,
    Bookmarks,
    Locale,
    DarkToggler,
    SearchBar,
    CartDropdown,
    NotificationDropdown,
    UserDropdown,
  },
  props: {
    toggleVerticalMenuActive: {
      type: Function,
      default: () => {},
    },
  },
  data() {
    return {
      appName: $themeConfig.app.appName,
      appLogoImage: $themeConfig.app.appLogoImage,
    }
  }
}
</script>
<style>
.brand-logo img {
  max-width: 36px;
}
.navbar-brand .brand-text {
  padding-left: 1rem;
  -webkit-animation: 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) 0s normal forwards 1 fadein;
  animation: 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) 0s normal forwards 1 fadein;
  color: #7367f0;
  font-weight: 600;
  letter-spacing: 0.01rem;
  font-size: 1.45rem;
  margin-top: 4px;
  float: right;
}
/*.horizontal-menu .header-navbar.navbar-horizontal.floating-nav {
  top: 75px !important;
}*/
</style>