<template>
  <b-overlay :show="loading" rounded opacity="0.6" size="lg" spinner-variant="warning">
    <b-card title="Riwayat Akademik">
      <app-timeline>
        <app-timeline-body icon="fa-solid fa-user-graduate" variant="success">
          <div class="d-flex flex-sm-row flex-column flex-wrap justify-content-between mb-1 mb-sm-0">
            <h6>Informasi Akademik</h6>
          </div>
          <p class="mb-0">
            Pantau semua informasi <b-badge variant="primary"><b-link @click="openModal('akademik')">pencapaian akademik</b-link></b-badge> Anda.
          </p>
        </app-timeline-body>
        <app-timeline-body icon="fa-regular fa-calendar-days" variant="primary">
          <div class="d-flex flex-sm-row flex-column flex-wrap justify-content-between mb-1 mb-sm-0">
            <h6>Mapel Remedial</h6>
          </div>
          <p class="mb-0">
            <b-badge variant="primary"><b-link @click="openModal('remedial')">Cetak lembar remedial</b-link></b-badge> dan segera lakukan remedial ke guru.
          </p>
        </app-timeline-body>
        <app-timeline-body icon="fa-regular fa-calendar-check" variant="danger">
          <div class="d-flex flex-sm-row flex-column flex-wrap justify-content-between mb-1 mb-sm-0">
            <h6>Informasi Presensi</h6>
          </div>
          <p class="mb-0">
            Informasi Presensi diperbaharui setiap hari, <b-badge variant="primary"><b-link @click="openModal('presensi')">cek disini</b-link></b-badge>.
          </p>
        </app-timeline-body>
        <app-timeline-body icon="fa-solid fa-heart-circle-check" variant="warning">
          <div class="d-flex flex-sm-row flex-column flex-wrap justify-content-between mb-1 mb-sm-0">
            <h6>Catatan Sikap &amp; Tatib</h6>
          </div>
          <p class="mb-0">
            Lihat riwayat catatan <b-badge variant="primary"><b-link @click="openModal('sikap')">sikap dan tata tertib</b-link></b-badge> anda.
          </p>
        </app-timeline-body>
        <app-timeline-body icon="fa-solid fa-person-skating" variant="info">
          <div class="d-flex flex-sm-row flex-column flex-wrap justify-content-between mb-1 mb-sm-0">
            <h6>Ekstrakurikuler</h6>
          </div>
          <p class="mb-0">
            Pantau perkembangan mapel ekstra anda <b-badge variant="primary"><b-link @click="openModal('ekskul')">disini</b-link></b-badge>.
          </p>
        </app-timeline-body>
      </app-timeline>
      <modal-akademik></modal-akademik>
      <modal-ekskul></modal-ekskul>
      <modal-remedial></modal-remedial>
      <modal-sikap></modal-sikap>
      <modal-presensi></modal-presensi>
    </b-card>
  </b-overlay>
</template>

<script>
import { BCard, BLink, BOverlay, BBadge } from 'bootstrap-vue'
import AppTimeline from '@core/components/app-timeline/AppTimeline.vue'
import AppTimelineBody from '@core/components/app-timeline/AppTimelineBody.vue'
import ModalAkademik from './modal/ModalAkademik.vue'
import ModalEkskul from './modal/ModalEkskul.vue'
import ModalRemedial from './modal/ModalRemedial.vue'
import ModalSikap from './modal/ModalSikap.vue'
import ModalPresensi  from './modal/ModalPresensi.vue'
import eventBus from '@core/utils/eventBus'

export default {
  components: {
    BCard,
    BLink,
    BOverlay,
    BBadge,
    AppTimeline,
    AppTimelineBody,
    ModalAkademik,
    ModalEkskul,
    ModalRemedial,
    ModalSikap,
    ModalPresensi,
  },
  data(){
    return {
      akademikShow: false,
      loading: false,
    }
  },
  methods: {
    openModal(aksi){
      this.loading = true
      this.$http.post('/siswa', {
        peserta_didik_id: this.user.peserta_didik_id,
        semester_id: this.user.semester.semester_id,
        aksi: aksi,
      }).then(res => {
        this.loading = false
        //this.data = res.data
        eventBus.$emit(`open-modal-${aksi}`, res.data)
      })
    },
  }
}
</script>
