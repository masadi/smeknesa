<template>
  <b-card class="profile-header mb-2" img-src="/img/profile/user-uploads/timeline.jpg" img-top alt="cover photo" body-class="p-0" v-if="headerData">
    <div class="position-relative">
      <div class="profile-img-container d-flex align-items-center">
        <div class="profile-img" v-b-hover="handleHover">
          <div class="tombol">
            <b-badge @click="show=true" variant="warning" class="cursor-pointer">
              <feather-icon v-if="isHovered" icon="EditIcon" size="20"/>
            </b-badge>
          </div>
          <b-avatar rounded size="115" :src="headerData.photo ? `/storage/images/${headerData.photo}` : null" variant="light-primary">
            <feather-icon v-if="!headerData.photo" icon="UserIcon" size="100" />
          </b-avatar>
        </div>
        <div class="profile-title ml-3">
          <h2 class="text-white">
            {{ headerData.nama }}
          </h2>
          <p class="text-white">
            Cita-cita: {{ (headerData.cita) ? headerData.cita : '-' }}
          </p>
        </div>
      </div>
    </div>
    <div class="profile-header-nav">
      <b-navbar toggleable="md" type="light">
        <b-navbar-toggle class="ml-auto" target="nav-text-collapse">
          <feather-icon icon="AlignJustifyIcon" size="21" />
        </b-navbar-toggle>
        <b-collapse id="nav-text-collapse" is-nav>
          <b-tabs pills class="profile-tabs mt-1 mt-md-0" nav-class="mb-0">
            <template #tabs-start>
              <b-nav-item role="presentation" :active="profileActive" class="font-weight-bold" @click="tabProfile">
                <span class="d-none d-md-block">Profile</span>
                <feather-icon icon="UserCheckIcon" class="d-block d-md-none" />
              </b-nav-item>
              <b-nav-item role="presentation" :active="modulActive" class="font-weight-bold"  @click="tabModul">
                <span class="d-none d-md-block">Modul</span>
                <feather-icon icon="InfoIcon" class="d-block d-md-none" />
              </b-nav-item>
              <b-nav-item role="presentation" :active="quizActive" class="font-weight-bold"  @click="tabQuiz">
                <span class="d-none d-md-block">Quiz</span>
                <feather-icon icon="ImageIcon" class="d-block d-md-none" />
              </b-nav-item>
              <b-nav-item role="presentation" :active="feedbackActive"class="font-weight-bold"  @click="tabFeedback">
                <span class="d-none d-md-block">FeedBack</span>
                <feather-icon icon="UsersIcon" class="d-block d-md-none" />
              </b-nav-item>
            </template>
            <template #tabs-end>
              <b-button variant="danger" class="ml-auto" @click="logout">
                <feather-icon icon="LogOutIcon" class="d-block d-md-none" />
                <span class="font-weight-bold d-none d-md-block">Logout</span>
              </b-button>
            </template>
          </b-tabs>
        </b-collapse>
      </b-navbar>
    </div>
    <b-modal v-model="show" centered hide-footer hide-header>
      <div class="my-1">
        <b-overlay :show="loading" opacity="0.6" size="md" spinner-variant="secondary">
          <b-form-file v-model="foto" accept=".jpg, .png, .jpeg" placeholder="Upload Foto..." drop-placeholder="Drop file here..." @change="onFileChange"></b-form-file>
        </b-overlay>
      </div>
    </b-modal>
  </b-card>
</template>

<script>
import {
  BCard, BImg, BNavbar, BNavbarToggle, BCollapse, BTabs, BNavItem, BButton, BAvatar, BIcon, BBadge, BOverlay, BFormFile, VBHover
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
    BAvatar,
    BIcon,
    BBadge,
    BOverlay,
    BFormFile,
  },
  directives: {
    'b-hover': VBHover,
    Ripple,
  },
  props: {
    headerData: {
      type: Object,
      default: () => {},
    },
    profileActive: {
      type: Boolean,
      default: true,
    },
    modulActive: {
      type: Boolean,
      default: false,
    },
    quizActive: {
      type: Boolean,
      default: false,
    },
    feedbackActive: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      show: false,
      loading: false,
      foto: null,
      isHovered: false
    }
  },
  methods: {
    tabProfile(){
      eventBus.$emit('open-tab-profile');
    },
    tabModul(){
      eventBus.$emit('open-tab-modul');
    },
    tabQuiz(){
      eventBus.$emit('open-tab-quiz');
    },
    tabFeedback(){
      eventBus.$emit('open-tab-feedback');
    },
    handleHover(hovered) {
      console.log(hovered);
      this.isHovered = hovered
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
    onFileChange(e) {
      this.loading = true
      this.foto = e.target.files[0];
      const data = new FormData();
      data.append('foto', this.foto);
      data.append('user_id', this.user.user_id);
      this.$http.post('/auth/user/foto', data).then(response => {
        this.loading = false
        let getData = response.data
        this.user.photo = getData.foto
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
        }).then(result => {
          this.show = false
          this.headerData.photo = getData.foto
        })
      });
    },
  }
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
.tombol{
  position: absolute;
  top: 10px;
  left: 10px;
  z-index: 1;
}
</style>