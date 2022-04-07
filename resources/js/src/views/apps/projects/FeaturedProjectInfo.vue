<template>
<div class="vx-col w-full">
  <!-- RADIAL CHART -->
  <div class="vx-row">
    <div class="vx-col w-full md:w-1/2 lg:w-1/2 xl:w-1/2">
      <statistics-card-line class="md:mb-0 mb-base" style="height:190px;" icon="PackageIcon" icon-right :statistic="(contractRecevied.series[0] && contractRecevied.series[0]['data']) ? contractRecevied.series[0]['data'].reduce((pv, cv) => pv + cv, 0) : ''" statisticTitle="میزان تغییر قراردادهای اخیر " :chartData="contractRecevied.series" />
    </div>

    <div class="vx-col w-full md:w-1/4 mb-base">
      <vx-card title="قرارداد های فعال">
        <template slot="actions">
          <feather-icon icon="CodepenIcon" svgClasses="w-6 h-6 text-grey"></feather-icon>
        </template>
        <div class="mt-base">
          <div class="mt-4">
            <div class="flex justify-between">
              <div class="flex flex-col">
                <span class="mb-1"></span>
                <h4>{{ graphData['active'] }}%</h4>
              </div>
              <div class="flex flex-col text-right">
                <span class="flex -mr-1">
                  <span class="mr-1">سال</span>
                </span>
                <span class="text-grey">{{ new Date().getFullYear() }}</span>
              </div>
            </div>
            <vs-progress :percent="graphData['active']" color="success"></vs-progress>
          </div>
        </div>
      </vx-card>
    </div>
    <div class="vx-col w-full md:w-1/4 mb-base">
      <vx-card title="اعلانات موفق">
        <template slot="actions">
          <feather-icon icon="BellIcon" svgClasses="w-6 h-6 text-grey"></feather-icon>
        </template>
        <div class="mt-base">
          <div class="mt-4">
            <div class="flex justify-between">
              <div class="flex flex-col">
                <span class="mb-1"></span>
                <h4>{{ graphData['successPro'] }}%</h4>
              </div>
              <div class="flex flex-col text-right">
                <span class="flex -mr-1">
                  <span class="mr-1">سال</span>
                </span>
                <span class="text-grey">{{ new Date().getFullYear() }}</span>
              </div>
            </div>
            <vs-progress :percent="graphData['successPro']"></vs-progress>
          </div>
        </div>
      </vx-card>
    </div>
  </div>
</div>
</template>

<script>
// Small Line Charts
import StatisticsCardLine from '@/components/statistics-cards/StatisticsCardLine.vue'
import VueApexCharts from 'vue-apexcharts'

export default {
  name: 'vx-project-featured',
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      contractRecevied: {
        series: [],
        analyticsData: {
          orders: 0
        }
      },
      activeUsers: {},
      graphData: [],
      graphDataKey1: 0,

    }
  },
  methods: {
    createNewProject() {
      this.$router.push('/projects/add').catch(() => {})
    },
    loadGraphData() {
      this.axios
        .get("/api/graphs/contracts-graphs")
        .then((data) => {
          this.graphData = data.data;
          this.contractRecevied.series = this.graphData.contractsChange;
          this.graphDataKey1 += 1;
          this.getSaleData();
        })
        .catch(() => {document.getElementById("loading-bg").style.display = "none";});

    }
  },
  mounted() {
    this.isMounted = false
  },
  components: {
    StatisticsCardLine,
    VueApexCharts,
  },
  created() {
    this.loadGraphData()
  }
}
</script>
