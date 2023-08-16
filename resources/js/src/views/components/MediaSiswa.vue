<template>
  <b-media no-body v-if="data">
    <b-media-aside class="mr-75">
      <b-avatar rounded size="38" :src="`/storage/images/${data.photo}`" />
    </b-media-aside>
    <b-media-body class="my-auto">
      <h6 class="mb-0">{{ data.nama }}</h6>
      <small>{{ (data.cita) ? data.cita : '-' }}</small>
    </b-media-body>
  </b-media>
</template>

<script>
import { BMedia, BMediaAside, BMediaBody, BAvatar } from 'bootstrap-vue'
export default {
  components: {
    BMedia, BMediaAside, BMediaBody, BAvatar
  },
  props: {
    data: {
      required: true
    },
  },
}
</script>
