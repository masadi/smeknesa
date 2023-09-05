<template>
  <b-card>
    <div class="text-center text-danger my-2" v-if="isBusy">
      <b-spinner class="align-middle"></b-spinner>
      <strong>Loading...</strong>
    </div>
    <div id="chart" class="text-center" v-else>
      <h2>Grafik Presensi Guru</h2>
      <vue-apex-charts type="bar" :options="this.chartOptions" :series="this.series"></vue-apex-charts>
    </div>
  </b-card>
</template>

<script>
import { BCard, BSpinner } from 'bootstrap-vue'
import VueApexCharts from 'vue-apexcharts'
export default {
  components: {
    VueApexCharts,
    BCard,
    BSpinner,
  },
  data() {
    return {
      isBusy: true,
      series: [],
      chartOptions: {
        chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        colors:['#dc3545', '#ffc107', '#28a745'],
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: [],
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return `${val} Guru`
            }
          }
        }
      },
    }
  },
  created() {
    this.loadRekap()
  },
  methods: {
    loadRekap(){
      this.isBusy = true
      this.$http.post('/presensi/get-rekap-guru').then(response => {
        this.isBusy = false
        let getData = response.data
        this.series = getData.series
        this.chartOptions.xaxis.categories = getData.categories
      })
    },
  },
}
</script>