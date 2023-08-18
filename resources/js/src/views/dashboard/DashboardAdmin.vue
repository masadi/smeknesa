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
                    <component :is="item.icon" />
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
              <component :is="item.icon" />
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
          <b-card-header>
            <b-card-title>Data Pengguna</b-card-title>
            <div>
              <b-overlay :show="loading" rounded opacity="0.6" spinner-variant="warning" spinner-small class="d-inline-block">
                <b-button variant="primary" size="sm" @click="generatePengguna">Generate Pengguna</b-button>
              </b-overlay>
              <b-overlay :show="loading" rounded opacity="0.6" spinner-variant="warning" spinner-small class="d-inline-block">
                <b-button variant="success" size="sm" @click="aksesGuru">Akses Guru</b-button>
              </b-overlay>
            </div>
          </b-card-header>
          <b-card-body>
            <datatable :loading="loading" :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" :role_id="role_id" :data_roles="data_roles" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @role="handleRole" @detil="handleDetil" @reset="handleReset" @hapus="handleHapus" />
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
    <user-modal @reload="handleReload"></user-modal>
  </div>
</template>

<script>
import { BRow, BCol, BCard, BCardHeader, BCardText, BCardBody, BCardTitle, BSpinner, BTableSimple, BTr, BTd, BFormCheckbox, VBTooltip, BAvatar, BButton, BOverlay} from 'bootstrap-vue'
import Datatable from './Datatable.vue'
import UserModal from './../components/modal/users/UserModal.vue'
import eventBus from '@core/utils/eventBus'
export default {
  components: {
    BRow, 
    BCol,
    BCard,
    BCardHeader, 
    BCardText,
    BCardBody,
    BSpinner,
    BTableSimple,
    BTr, 
    BTd,
    BFormCheckbox,
    VBTooltip,
    BAvatar,
    BButton,
    BOverlay,
    UserModal,
    BCardTitle,
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
    generatePengguna(){
      this.$swal({
        title: 'Apakah Anda yakin?',
        text: 'Tindakan ini tidak dapat dikembalikan!',
        icon: 'question',
        showConfirmButton: true,
        confirmButtonText: 'Akun PTK',
        showLoaderOnConfirm: true,
        showDenyButton: true,
        denyButtonText: 'Akun PD',
        showLoaderOnDeny: true,
        showCancelButton: true,
        cancelButtonText: 'Batal',
        customClass: {
          confirmButton: 'btn btn-primary',
          denyButton:'btn btn-danger ml-1',
          cancelButton: 'btn btn-secondary ml-1',
        },
        buttonsStyling: false,
        allowOutsideClick: false,
      }).then(result => {
        eventBus.$emit('loading', true);
        this.loading = true
        var aksi;
        if(result.isConfirmed){
          aksi = 'ptk'
        } else if(result.isDenied){
          aksi = 'pd'
        }
        if (aksi) {
          this.$http.post('/auth/users/generate', {
            jenis: aksi,
            sekolah_id: this.user.sekolah_id,
            semester_id: this.user.semester.semester_id,
            periode_aktif: this.user.semester.nama,
          }).then(response => {
            let getData = response.data
            this.$swal({
              icon: getData.icon,
              title: getData.title,
              text: getData.text,
              customClass: {
                confirmButton: 'btn btn-success',
              },
              allowOutsideClick: false,
            }).then(result => {
              this.loading = false
              eventBus.$emit('loading', false);
              this.loadPostsData()
            })
          });
        }
      })
    },
    aksesGuru(){
      this.loading = true
      this.$http.get('/pengaturan/guru-mapel').then(response => {
        this.loading = false
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
          allowOutsideClick: false,
        }).then(result => {
          //eventBus.$emit('my-event');
          eventBus.$emit('loading', false);
          this.loadPostsData()
        })
      });
    },
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
    handleReload(){
      this.loadPostsData()
    },
    handleRole(val) {
      this.role_id = val
      this.loadPostsData()
    },
    handleDetil(val){
      this.loading = true
      this.$http.post('/auth/user/detil', {
        user_id: val,
      }).then(response => {
        this.loading = false
        eventBus.$emit('open-user-modal', response.data);
      })
    },
    handleReset(val){
      this.loading = true
      this.$http.post('/auth/user/reset-password', {
        user_id: val,
      }).then(response => {
        let getData = response.data
        this.$swal({
          icon: getData.icon,
          title: getData.title,
          text: getData.text,
          customClass: {
            confirmButton: 'btn btn-success',
          },
          allowOutsideClick: false,
        }).then(result => {
          this.loadPostsData()
        })
      })
    },
    handleHapus(val){
      console.log('handleHapus');
      console.log(val);
      this.$swal({
        icon: 'error',
        title: 'Error',
        text: 'Sedang dalam pengembangan!',
        customClass: {
          confirmButton: 'btn btn-success',
        },
        allowOutsideClick: false,
      })
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>