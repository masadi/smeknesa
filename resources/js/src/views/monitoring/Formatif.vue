<template>
  <b-row>
    <b-col cols="12">
      <b-card>
        <Formulir :form="form" :feedback="feedback" :state="state" @hide_form="handleHideForm" @show_form="handleShowForm" />
      </b-card>
    </b-col>
  </b-row>
</template>

<script>
import { BRow, BCol, BCard, BOverlay, BTableSimple, BThead, BTbody, BTr, BTh, BTd } from 'bootstrap-vue'
import Formulir from './../components/Formulir.vue'
import MediaSiswa from '@/views/components/MediaSiswa.vue'
export default {
  components: {
    BRow,
    BCol,
    BCard,
    BOverlay,
    BTableSimple,
    BThead,
    BTbody,
    BTr,
    BTh,
    BTd,
    Formulir,
    MediaSiswa,
  },
  data() {
    return {
      show: false,
      loading_form: false,
      data_siswa: [],
      form: {
        semester_id: '',
        nilai: true,
        penilaian: true,
        tingkat: '',
        rombongan_belajar_id: '',
        pembelajaran_id: '',
      },
      feedback: {
        tingkat: '',
        rombongan_belajar_id: '',
        pembelajaran_id: '',
      },
      state: {
        tingkat: null,
        rombongan_belajar_id: null,
        pembelajaran_id: null,
      },
    }
  },
  created(){
    this.form.semester_id = this.user.semester.semester_id
  },
  methods: {
    handleHideForm(){
      this.show = false
      console.log('handleHideForm');
    },
    handleShowForm(){
      this.show = true
      console.log('handleShowForm');
    },
  },
}
</script>