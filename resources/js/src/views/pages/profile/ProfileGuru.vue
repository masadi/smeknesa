<template>
  <b-card>
    <h5>Guru Kelas Kita</h5>
    <b-overlay :show="loading_guru" rounded opacity="0.6" size="lg" spinner-variant="warning">
      <vue-perfect-scrollbar :settings="perfectScrollbarSettings" class="scrollable-container media-list scroll-area" tagname="div">
        <template v-for="(data,index) in list_guru">
          <div class="d-flex justify-content-start align-items-center" :class="index == 0 ? 'mt-2' : 'mt-1'">
            <!--b-avatar :src="data.avatar" class="mr-50" size="40" /-->
            <b-avatar :src="data.photo ? `/storage/images/${data.photo}` : null" class="mr-50" size="40">
              <feather-icon v-if="!data.photo" icon="UserIcon" size="20" />
            </b-avatar>
            <div class="user-page-info">
              <h6 class="mb-0">
                {{ data.nama }}
              </h6>
              <small class="text-muted">Mapel: {{getMapel(data.pembelajaran)}}</small>
            </div>
          </div>
        </template>
      </vue-perfect-scrollbar>
    </b-overlay>
  </b-card>
</template>

<script>
import {
  BCard, BAvatar, BButton, BOverlay,
} from 'bootstrap-vue'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
export default {
  components: {
    BCard,
    BAvatar,
    BButton,
    BOverlay,
    VuePerfectScrollbar,
  },
  data() {
    return {
      perfectScrollbarSettings: {
        maxScrollbarLength: 3,
        wheelPropagation: false,
      },
    }
  },
  props: {
    suggestions: {
      type: Array,
      default: () => [],
    },
    list_guru: {
      type: Array,
      default: () => [],
    },
    loading_guru: {
      type: Boolean,
      default: true,
    },
  },
  methods: {
    uniqueChars(arr){
      return [...new Set(arr)];
    },
    getMapel(array){
      var mapel = [];
      array.forEach(element => {
        mapel.push(element.nama_mata_pelajaran)
      });
      return this.uniqueChars(mapel).join(', ')
    },
  },
}
</script>
<style lang="scss">
.scrollable-container {
  position: relative;
  margin: auto;
  height: 300px;
}
</style>