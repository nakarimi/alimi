<template>
<div :key="rootKey">
  <span v-if="user_permissions.includes('main_dashboard')">
    <div class="vx-row mb-base" v-if="gPurchaseData['byDays'] && gSaleData['byDays']">
      <div class="vx-col w-full md:w-1/3 lg:w-1/3 xl:w-1/3">
        <statistics-card-line class="md:mb-0 mb-base" icon="ShoppingCartIcon" :key="componentKey1" icon-right :statistic="formatToEnPriceSimple(gPurchaseData['total_af'], 0) +  ' AFN'" statisticTitle="مصارف" :chartData="gPurchaseData['byDays'].series" />
      </div>
      <div class="vx-col w-full md:w-1/3 lg:w-1/3 xl:w-1/3">
        <statistics-card-line class="md:mb-0 mb-base" icon="DollarSignIcon" :key="componentKey2" icon-right :statistic="formatToEnPriceSimple(gSaleData['total_af'], 0) +  ' AFN'" statisticTitle="عواید" :chartData="gSaleData['byDays'].series" color="success" />
      </div>
      <div class="vx-col w-full md:w-1/3 lg:w-1/3 xl:w-1/3 custom-icon-color">
        <statistics-card-line class="md:mb-0 mb-base" icon="TrendingUpIcon" icon-right :key="componentKey3" :statistic="formatToEnPriceSimple(benefitsAtAll, 0) +  ' %'" statisticTitle="میزان مفاد دهی" :chartData="benefits.series" colorTo="lightblue" color="blue" />
      </div>
    </div>
    <div class="vx-row">
      <div class="vx-col w-full md:w-2/3 lg:w-3/4 xl:w-3/4 mb-base">
        <vx-card title="گراف فروشات">
          <template slot="actions">
            <feather-icon icon="RefreshCwIcon" svgClasses="w-6 h-6 text-primary" @click="getSaleDataLastMonth"></feather-icon>
          </template>
          <div slot="no-body" class="p-6 pb-0" :key="gSaleDataLastMonthKey">
            <div v-if="!revenueComparisonLine.series.length">
              <!-- <TableLoading></TableLoading> -->
            </div>
            <span v-if="revenueComparisonLine.series.length > 0">
              <div class="flex">
                <div class="mr-6">
                  <p class="mb-1 font-semibold text-success">مجموع فروشات </p>
                  <p class="text-3xl text-success">
                    {{ (revenueComparisonLine.series[0] && revenueComparisonLine.series[0].data) ? formatToEnPriceSimple(revenueComparisonLine.series[0].data.reduce((a, b) => a + b, 0), 0) : 0 }}
                    <sup class="text-base mr-1">افغانی</sup>
                  </p>
                </div>
                <div>
                  <p class="mb-1 font-semibold">مجموع فروشات قراردادی</p>
                  <p class="text-3xl">
                    {{ (revenueComparisonLine.series[0] && revenueComparisonLine.series[1].data) ? formatToEnPriceSimple(revenueComparisonLine.series[1].data.reduce((a, b) => a + b, 0), 0) : 0 }}
                    <sup class="text-base mr-1">افغانی</sup>
                  </p>
                </div>
              </div>
              <vue-apex-charts type="line" height="223" :options="revenueComparisonLine.chartOptions" :series="revenueComparisonLine.series" />
            </span>
          </div>
        </vx-card>
      </div>
      <div class="vx-col w-full md:w-1/3 lg:w-1/4 xl:w-1/4 mb-base">
        <div class="vx-card">
          <!---->
          <div class="vx-card__collapsible-content vs-con-loading__container">
            <div class="p-8 clearfix">
              <p class="text-3xl font-semibold" v-if="allSaleCount">
                {{ allSaleCount['thisMSales'] }}
                <sup class="text-base mr-1">فروش در ماه جاری</sup>
              </p>
              <p v-if="allSaleCount" class="mt-2 mb-8 text-xl font-medium text-success" style=" direction: ltr; text-align: right; " title="فی‌صدی تغییر در فروشات نسبت به ماه قبل">
                <span>{{ (allSaleCount['lastMSales'] > 0) ? '+' : ''}}</span><span> {{ allSaleCount['lastMSales'] }}%</span>
              </p>
              <router-link to="/sales?tab=1">
                <vs-button icon-pack="feather" icon="icon-chevrons-left" icon-after class="shadow-md w-full lg:mt-0 mt-4">لست فروشات</vs-button>
              </router-link>
              <!--<p>
              <vs-button icon-pack="feather" icon="icon-chevrons-left" icon-after class="shadow-md w-full lg:mt-0 mt-4">بررسی فروشات</vs-button>
            </p>-->
            </div>
            <div v-if="allSaleCount" class="p-8 border d-theme-border-grey-light border-solid border-r-0 border-l-0 border-b-0">
              <div class="mb-4"><small>فروشات عمده: {{ allSaleCount['parchonMSales'] }}%</small>
                <div class="vs-progress--background vs-progress-warning" style="height: 5px;">
                  <div class="vs-progress--foreground" :style="'width: ' + allSaleCount['parchonMSales'] +'%;'"></div>
                </div>
              </div>
              <div class="mb-4"><small>فروشات پرچون: {{ allSaleCount['omdeMSales'] }}%</small>
                <div class="vs-progress--background vs-progress-success" style="height: 5px;">
                  <div class="vs-progress--foreground" :style="'width: ' + allSaleCount['omdeMSales'] +'%;'"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="vx-card__code-container collapsed" style="max-height: 0px; display: none;">
            <div class="code-content">
              <pre class="language-markup"><code class="language-markup"></code></pre>
            </div>
          </div>
        </div>
      </div>
    </div>
  </span>
  <div class="vx-row">
    <LatestProjects></LatestProjects>
  </div>
  <br>
  <vs-divider />
  <!--<ProjectList></ProjectList>-->
  <div class="vx-col w-full mb-base">
    <vx-card title="میزان عواید براساس اقلام (۹۰ روز اخیر)">
      <template slot="actions">
        <feather-icon icon="RefreshCwIcon" svgClasses="w-6 h-6 text-primary" @click="allItemsSalesPrice"></feather-icon>
      </template>
      <vue-apex-charts :key="mixedChartSalesKey" type="line" height="350" :options="mixedChartSales.chartOptions" :series="mixedChartSales.series"></vue-apex-charts>
    </vx-card>
  </div>
</div>
</template>

<script>
import ECharts from 'vue-echarts/components/ECharts'
import 'echarts/lib/component/tooltip'
import 'echarts/lib/component/legend'
import 'echarts/lib/chart/line'
import theme from './theme.json'
import StatisticsCardLine from '@/components/statistics-cards/StatisticsCardLine.vue'
import VueApexCharts from 'vue-apexcharts'

// import ProjectList from './apps/projects/proposals/ProposalList.vue' 
import LatestProjects from './apps/projects/LatestProjects.vue'
// import analyticsData from './analyticsData.js'
import analyticsData from './ui-elements/card/analyticsData.js'
import TableLoading from './apps/shared/TableLoading'

ECharts.registerTheme('ovilia-green', theme)
const themeColors = ['#7367F0', '#28C76F', '#EA5455', '#FF9F43', '#1E9F43']

export default {
  props: ["currentuser"],
  components: {
    ECharts,
    VueApexCharts,
    theme,
    StatisticsCardLine,
    TableLoading,
    LatestProjects
  },
  name: 'vx-main_dashboard',
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      rootKey: 0,
      userid: localStorage.getItem('id'),
      componentKey1: 0,
      componentKey2: 0,
      componentKey3: 0,
      mixedChartSalesKey: 0,
      mixedChartSales: {
        series: [],
        chartOptions: {
          noData: {
            text: 'بارگیری اطلاعات....'
          },
          colors: themeColors,
          chart: {
            stacked: false
          },
          stroke: {
            width: [2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2],
            curve: 'smooth',
            // curve: 'straight', 
            // curve: 'stepline',
          },
          plotOptions: {
            bar: {
              columnWidth: '50%'
            }
          },

          fill: {
            opacity: [0.85, 0.25, 1],
            gradient: {
              inverseColors: false,
              shade: 'light',
              type: 'vertical',
              opacityFrom: 0.85,
              opacityTo: 0.55,
              stops: [0, 100, 100, 100]
            }
          },
          labels: ['89-80', '79-70', '69-60', '59-40', '49-40', '39-30', '29-20', '19-10', '9-0'],
          markers: {
            size: 0
          },
          xaxis: {
            type: 'string'
          },
          yaxis: {
            min: 0,
            max: 100,
            labels: {
              offsetX: -15,
              formatter: (val) => {
                return val + '%'
              },
            },
          },
          tooltip: {
            shared: true,
            intersect: false,
            y: {
              formatter(y) {
                if (typeof y !== 'undefined') {
                  return y.toFixed(0) + '%'
                }
                return y

              }
            }
          }
        }
      },
      analyticsData,
      revenueComparisonLine: {
        series: [],
        chartOptions: {
          noData: {
            text: 'بارگیری اطلاعات....'
          },
          chart: {
            toolbar: {
              show: false
            },
            dropShadow: {
              enabled: true,
              top: 5,
              left: 0,
              blur: 4,
              opacity: 0.10
            }
          },
          stroke: {
            curve: 'smooth',
            dashArray: [0, 8],
            width: [4, 2]
          },
          grid: {
            borderColor: '#e7e7e7'
          },
          legend: {
            show: false
          },
          colors: ['#F97794', '#b8c2cc'],
          fill: {
            type: 'gradient',
            gradient: {
              shade: 'dark',
              inverseColors: false,
              gradientToColors: ['#7367F0', '#b8c2cc'],
              shadeIntensity: 1,
              type: 'horizontal',
              opacityFrom: 1,
              opacityTo: 1,
              stops: [0, 100, 100, 100]
            }
          },
          markers: {
            size: 0,
            hover: {
              size: 5
            }
          },
          xaxis: {
            labels: {
              style: {
                cssClass: 'text-grey fill-current'
              }
            },
            axisTicks: {
              show: false
            },
            categories: ['1-5', '5-10', '10-15', '15-20', '20-25', '25-30'].reverse(),
            axisBorder: {
              show: false
            }
          },
          yaxis: {
            // opposite: true,
            tickAmount: 5,
            labels: {
              style: {
                cssClass: 'text-grey fill-current direction-ltr'
              },
              formatter(val) {
                return val > 999 ? `${(val / 1000).toFixed(0)} k` : val
              }
            }
          },
          tooltip: {
            x: {
              show: false
            }
          }
        },
        analyticsData: {
          thisMonth: '73683',
          lastMonth: '86589'
        }
      },
      // Line Charts
      siteTraffic: {},

      activeUsers: {
        series: [{
          name: 'Users',
          data: [60, 15, 58, 15, 74, 42, 82]
        }],
        analyticsData: {
          users: 97500
        }
      },
      newsletter: {},
      // Graphes data
      gPurchaseData: [],
      gSaleData: [],
      benefitsAtAll: 0,
      benefits: {
        series: [{
          name: 'مفاددهی',
          data: []
        }],
      },
      gSaleDataLastMonth: [],
      gSaleDataLastMonthKey: 0,
      allSaleCount: null,
      allSaleCountKeyC: 0,
    }

  },

  created() {
    this.getPurchaseData();
    this.getSaleDataLastMonth();
    this.allSalesCounterLastThisMonth();
    this.allItemsSalesPrice();
  },
  methods: {
    getPurchaseData() {
      this.$Progress.start()
      this.axios
        .get("/api/graphs/purchase")
        .then((data) => {
          this.gPurchaseData = data.data;
          this.componentKey1 += 1;
          this.getSaleData();
        })
        .catch(() => {
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
        });
    },
    getSaleData() {
      this.$Progress.start()
      this.axios
        .get("/api/graphs/sale-value")
        .then((data) => {
          this.gSaleData = data.data;
          for (const [key, value] of Object.entries(this.gSaleData['byDays'].series[0].data)) {
            let currentVal = this.gSaleData['byDays'].series[0].data[key];
            if (currentVal == 0) {
              this.benefits.series[0].data[key] = ((currentVal - this.gPurchaseData['byDays'].series[0].data[key]) * 100).toFixed(0);
            } else {
              this.benefits.series[0].data[key] = (((currentVal - this.gPurchaseData['byDays'].series[0].data[key]) * 100) / currentVal).toFixed(0);
            }
          }
          let x = this.gSaleData['total_af'] / 100;
          let y = this.gSaleData['total_af'] - this.gPurchaseData['total_af'];
          let xy = y / x;
          this.benefitsAtAll = this.formatToEnPriceSimple(xy);
          this.componentKey3 += 1;
          this.componentKey2 += 1;
        })
        .catch(() => {
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
        });
    },
    getSaleDataLastMonth() {
      this.revenueComparisonLine.series = [];
      this.gSaleDataLastMonthKey += 1;
      this.$Progress.start()
      this.axios
        .get("/api/graphs/sale-last-month-g")
        .then((data) => {
          // this.gSaleDataLastMonth = data.data;
          this.revenueComparisonLine.series = data.data;
          this.gSaleDataLastMonthKey += 1;
        })
        .catch(() => {
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
        });
    },
    allSalesCounterLastThisMonth() {
      this.revenueComparisonLine.series = [];
      this.gSaleDataLastMonthKey += 1;
      this.$Progress.start()
      this.axios
        .get("/api/graphs/allsalecount")
        .then((data) => {
          this.allSaleCount = data.data;
          this.allSaleCountKeyC += 1;
        })
        .catch(() => {
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
        });
    },
    allItemsSalesPrice() {
      this.mixedChartSales.series = [];
      this.mixedChartSalesKey += 1;
      this.axios
        .get("/api/graphs/all-items-sales-price")
        .then((data) => {
          this.mixedChartSales.series = data.data;
          this.mixedChartSalesKey += 1;
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
        })
        .catch(() => {
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
        });
    },

  },
}
</script>

<style>
.projectListDashboard {
  display: flex;
  width: 100%;
  background-color: rgb(243, 245, 247);
  border-left-width: 0.6rem;
  border-left-style: solid;
  padding: 10px;
  border-color: rgb(49, 47, 75) !important;
}
</style>
