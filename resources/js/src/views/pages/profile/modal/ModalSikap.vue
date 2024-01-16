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
  data(){
    return {
      modalShow: false,
      data: [],
    }
  },
  created() {
    eventBus.$on('open-modal-sikap', this.handleEvent);
  },
  methods: {
    handleEvent(data){
      console.log(data);
      this.modalShow = true
    },
  },
}
</script>
