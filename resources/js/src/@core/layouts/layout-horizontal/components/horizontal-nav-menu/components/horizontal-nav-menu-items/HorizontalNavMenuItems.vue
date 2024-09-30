<template>
  <ul
    id="main-menu-navigation"
    class="nav navbar-nav"
  >
    <component
      :is="resolveNavComponent(item)"
      v-for="item in items"
      :key="item.header || item.title"
      :item="item"
    />
  </ul>
</template>

<script>
import HorizontalNavMenuHeaderLink from '../horizontal-nav-menu-header-link/HorizontalNavMenuHeaderLink.vue'
import HorizontalNavMenuHeaderGroup from '../horizontal-nav-menu-header-group/HorizontalNavMenuHeaderGroup.vue'
import HorizontalNavMenuLink from '../horizontal-nav-menu-link/HorizontalNavMenuLink.vue'

export default {
  components: {
    HorizontalNavMenuHeaderLink,
    HorizontalNavMenuHeaderGroup,
    HorizontalNavMenuLink,
  },
  props: {
    items: {
      type: Array,
      required: true,
    },
  },
  setup() {
    const resolveNavComponent = item => {
      if (item.header) return 'horizontal-nav-menu-header-group'
      if (item.children) return 'horizontal-nav-menu-header-group'
      return 'horizontal-nav-menu-link'
    }

    return {
      resolveNavComponent,
    }
  },
}
</script>
