<template>
  <div>
    <template v-if="hasRole(['wakasiswa', 'wakahumas'])">
      <dashboard-waka></dashboard-waka>
    </template>
    <template v-else-if="hasRole('administrator')">
      <dashboard-admin></dashboard-admin>
    </template>
    <template v-else-if="hasRole('wakakur')">
      <dashboard-kurikulum></dashboard-kurikulum>
    </template>
    <template v-else-if="hasRole('siswa')">
      <dashboard-siswa></dashboard-siswa>
    </template>
    <template v-else>
      <dashboard-user></dashboard-user>
    </template>
  </div>
</template>

<script>
import DashboardWaka from './DashboardWaka.vue'
import DashboardAdmin from './DashboardAdmin.vue'
import DashboardKurikulum from './DashboardKurikulum.vue'
import DashboardSiswa from './DashboardSiswa.vue'
import DashboardUser from './DashboardUser.vue'
import { getHomeRouteForLoggedInUser } from '@/auth/utils'
export default {
  components: {
    DashboardWaka,
    DashboardAdmin, 
    DashboardKurikulum,
    DashboardSiswa,
    DashboardUser,
  },
  created() {
    if(this.hasRole('pd')){
      this.$router.replace(getHomeRouteForLoggedInUser(this.user.roles))
    }
    if(!this.hasRole(['administrator', 'kepsek', 'piket', 'wakakur', 'walas', 'kajur', 'wakahumas', 'wakasiswa', 'guru'])){
      this.$router.replace('/profile')
    }
  },
}
</script>