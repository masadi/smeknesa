<template>
  <b-modal v-model="modalShow" title="Catatan Sikap & Tatib" ok-only ok-title="Tutup" ok-variant="secondary" button-size="sm">
    <h2 class="text-center">Sedang dalam pengembangan</h2>
  </b-modal>
</template>

<script>
import {
  BCard, BAvatar, BButton,
} from 'bootstrap-vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BCard,
    BAvatar,
    BButton,
  },
  props: {
    data: {
      type: Object,
      required: true,
    },
  },
  data(){
    return {
      modalShow: false
    }
  },
  created() {
    eventBus.$on('open-modal-sikap', this.handleEvent);
  },
  methods: {
    handleEvent(){
      this.modalShow = true
    },
  },
}
</script>
