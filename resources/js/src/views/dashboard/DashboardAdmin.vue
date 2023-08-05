<template>
  <div>
    <b-row v-if="isBusy">
      <b-col>
        <b-card no-body>
          <b-card-body>
            <div class="text-center text-danger my-2">
              <b-spinner class="align-middle"></b-spinner>
              <strong>Loading...</strong>
            </div>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
    <b-row class="match-height" v-else>
      <b-col cols="12" md="8">
        <b-card no-body>
          <b-card-header>
            <h4 class="card-title">Statistik</h4>
            <b-card-text class="text-muted">
              {{periode_aktif}}
            </b-card-text>
          </b-card-header>
          <b-card-body>
            <b-row>
              <b-col v-for="item in statistik" :key="item.title" cols="6" md="3">
                <div class="d-flex">
                  <b-avatar :variant="item.color" size="4em" class="mr-1">
                    <font-awesome-icon :icon="`fa-solid fa-${item.icon}`" size="2xl" />
                  </b-avatar>
                  <div class="d-flex flex-column">
                    <h3>{{ item.stats }}</h3>
                    <span class="text-caption">
                      {{ item.title }}
                    </span>
                  </div>
                </div>
              </b-col>
            </b-row>
          </b-card-body>
        </b-card>
      </b-col>
      <b-col v-for="item in aplikasi" :key="item.title" cols="6" md="2">
        <b-card no-body>
          <b-card-body class="text-center">
            <b-avatar :variant="item.color" size="4em" class="mb-1">
              <font-awesome-icon :icon="`fa-solid fa-${item.icon}`" size="2xl" />
            </b-avatar>
            <h3>{{ item.stats }}</h3>
            <span class="text-body-2">{{ item.title }}</span>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
    <b-row v-if="!isBusy">
      <b-col cols="12">
        <b-card no-body>
          <b-card-body>
            <datatable :loading="loading" :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" :role_id="role_id" :data_roles="data_roles" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @role="handleRole" @detil="handleDetil" @reset="handleReset" @hapus="handleHapus" />
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import { BRow, BCol, BCard, BCardHeader, BCardText, BCardBody, BSpinner, BTableSimple, BTr, BTd, BFormCheckbox, VBTooltip, BAvatar, } from 'bootstrap-vue'
import Datatable from './Datatable.vue'
export default {
  components: {
    BRow, 
    BCol,
    BCard,
    BCardHeader, BCardText,
    BCardBody,
    BSpinner,
    BTableSimple,
    BTr, 
    BTd,
    BFormCheckbox,
    VBTooltip,
    BAvatar,
    Datatable
  },
  directives: {
    'b-tooltip': VBTooltip,
  },
  data() {
    return {
      periode_aktif: '',
      isBusy: true,
      loading: false,
      items: [],
      fields: [
        {
          key: 'name',
          label: 'Nama',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'email',
          label: 'Email',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'roles',
          label: 'Jenis Pengguna',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'last_login_at',
          label: 'Terakhir Login',
          sortable: true,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'status_password',
          label: 'Password',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'actions',
          label: 'Aksi',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center'
        },
      ],
      meta: {},
      current_page: 1,
      per_page: 10,
      search: '',
      sortBy: 'name',
      sortByDesc: false,
      role_id: '',
      data_roles: [],
      statistik: [],
      aplikasi: [],
      rekapitulasi: [],
      sekolah: null,
      app: {},
    }
  },
  created() {
    this.periode_aktif = this.user.semester.nama
    this.loadStatistics()
    this.loadAplikasi()
    this.loadPostsData()
  },
  methods: {
    loadStatistics(){
      this.$http.post('/dashboard', {
        sekolah_id: this.user.sekolah_id,
        semester_id: this.user.semester.semester_id,
        periode_aktif: this.user.semester.nama,
      }).then(response => {
        this.isBusy = false
        let getData = response.data
        this.statistik = getData.statistics
      })
    },
    loadAplikasi(){
      this.$http.get('/dashboard/aplikasi').then(response => {
        let getData = response.data
        this.aplikasi = getData.aplikasi
      })
    },
    loadPostsData() {
      this.loading = true
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      this.$http.get('/auth/users/list', {
        params: {
          role_id: this.role_id,
          periode_aktif: this.user.semester.nama,
          page: current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
        }
      }).then(response => {
        let getData = response.data.data
        this.loading = false
        this.items = getData.data
        this.data_roles = response.data.roles
        this.meta = {
          role_id: this.role_id,
          total: getData.total,
          current_page: getData.current_page,
          per_page: getData.per_page,
          from: getData.from,
          to: getData.to,
        }
      })
    },
    handlePerPage(val) {
      this.per_page = val
      this.loadPostsData()
    },
    handlePagination(val) {
      this.current_page = val
      this.loadPostsData()
    },
    handleSearch(val) {
      this.search = val
      this.loadPostsData()
    },
    handleSort(val) {
      if (val.sortBy) {
        this.sortBy = val.sortBy
        this.sortByDesc = val.sortDesc
        this.loadPostsData()
      }
    },
    handleRole(val) {
      this.role_id = val
      this.loadPostsData()
    },
    handleDetil(val){
      console.log('handleDetil');
      console.log(val);
    },
    handleReset(val){
      console.log('handleReset');
      console.log(val);
    },
    handleHapus(val){
      console.log('handleHapus');
      console.log(val);
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>