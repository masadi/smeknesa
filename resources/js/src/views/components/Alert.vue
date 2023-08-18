<template>
  <b-alert :variant="variant" show>
    <h4 class="alert-heading" v-if="title">
      {{title}}
    </h4>
    <div class="alert-body" v-bind:class="bodyClass">
      <span v-html="body"></span>
    </div>
  </b-alert>
</template>

<script>
import { BAlert } from 'bootstrap-vue'
export default {
  components: {
    BAlert
  },
  props: {
    variant: {
      required: true
    },
    title: {
      required: false,
      default: () => null,
    },
    body: {
      required: true
    },
    bodyClass: {
      required: false,
      default: () => '',
    },
  },
}
</script>
<style lang="scss">
.alert-heading {box-shadow: none !important;}
</style>
