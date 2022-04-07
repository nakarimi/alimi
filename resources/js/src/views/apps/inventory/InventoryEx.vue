<template>
<!-- ROW 1 -->
<div>
  <div class="vx-row">
    <div>
      <data-view-sidebar :isSidebarActive="addNewDataSidebar" @closeSidebar="toggleDataSidebar" :data="sidebarData" />
      <transfer-view-sidebar :isSidebarActive="transferSidebar" @closeSidebar="toggleTSidebar" :data="sidebarData" />
    </div>
    <div class="vx-col w-full md:w-2/3 mb-base">
      <vx-card title="تغییرات در ذخایر">
        <template slot="actions">
          <feather-icon icon="RefreshCwIcon" svgClasses="w-6 h-6 text-primary" @click="storageItemsGraphData"></feather-icon>
        </template>
        <div slot="no-body" class="p-6 pb-0">
          <div class="flex">
            <div class="mr-6">
              <p class="mb-1 font-semibold">مجموع فروشات</p>
              <p class="text-3xl text-success" :key="revenueComparisonLineKey">
                {{ revenueComparisonLine.thisMonth.toLocaleString() }}
                <sup class="text-base mr-1">تن</sup>
              </p>
            </div>
            <div>
              <p class="mb-1 font-semibold">مجموع خریداری ها</p>
              <p class="text-3xl" :key="revenueComparisonLineKey">
                {{ revenueComparisonLine.lastMonth.toLocaleString() }}
                <sup class="text-base mr-1">تن</sup>
              </p>
            </div>
          </div>
          <vue-apex-charts :key="revenueComparisonLineKey" type="line" height="266" :options="revenueComparisonLine.chartOptions" :series="revenueComparisonLine.series" />
        </div>
      </vx-card>
    </div>
    <!-- RADIAL CHART -->
    <div class="vx-col w-full md:w-1/3 mb-base">
      <vx-card title="ذخایر اصلی">
        <template slot="no-body">
          <div class="mt-10">
            <vue-apex-charts :key="storageGraphDataKey" v-if="goalOverviewRadialBar" type="radialBar" height="240" :options="goalOverviewRadialBar.chartOptions" :series="goalOverviewRadialBar.series" />
          </div>
        </template>

        <!-- DATA -->
        <div class="flex justify-between text-center mt-4" slot="no-body-bottom">
          <div class="w-1/2 border border-solid d-theme-border-grey-light border-r-0 border-b-0 border-l-0">
            <p class="mt-4">تعداد ذخایر</p>
            <p class="mb-4 text-3xl font-semibold">{{totalStorageStations}}</p>
          </div>
          <div v-if="user_permissions.includes('manage_storages')" class="w-1/2 justify-center border border-solid d-theme-border-grey-light border-r-0 border-b-0">
            <div class="justify-col justify-center flex ml-5 mr-5">
              <vs-button icon-pack="feather" icon="icon-chevrons-left" icon-after class="shadow-md w-full" @click="toggleSidebar()">بررسی</vs-button>
            </div>
          </div>
        </div>
      </vx-card>
    </div>
  </div>
  <!-- Second Row -->
  <div class="vx-row">
    <!-- Start Tank Till -->
    <div v-if="user_permissions.includes('manage_fuel')" class="vx-col w-full sm:w-full md:w-1/2 lg:w-1/4 xl:w-1/4">
      <vx-card>
        <template slot="no-body">
          <div class="p-8 clearfix">
            <div class="mb-5">
              <h1>
                <span>تانک های تیل</span>
              </h1>
              <small>
                <span class="text-grey">تعداد:</span>
                <span>{{totalFuelStations}}</span>
              </small>
            </div>
            <router-link class="product-name font-medium truncate" :to="{name: 'fuel-station'}">
              <vs-button icon-pack="feather" icon="icon-chevrons-left" icon-after class="shadow-md w-full">بررسی</vs-button>
            </router-link>
          </div>
        </template>
      </vx-card>
    </div>
    <!-- End Tank Till -->
    <!-- Start Goods -->
    <div v-if="user_permissions.includes('manage_products')" class="vx-col w-full sm:w-full md:w-1/2 lg:w-1/4 xl:w-1/4">
      <vx-card>
        <template slot="no-body">
          <div class="p-8 clearfix">
            <div class="mb-5">
              <h1>
                <span>اجناس و محصولات</span>
              </h1>
              <small>
                <span class="text-grey">تعداد:</span>
                <span>{{totalGoods}}</span>
              </small>
            </div>
            <router-link class="product-name font-medium truncate" :to="{name: 'goods'}">
              <vs-button icon-pack="feather" icon="icon-chevrons-left" icon-after class="shadow-md w-full">بررسی</vs-button>
            </router-link>
          </div>
        </template>
      </vx-card>
    </div>
    <!-- End Goods -->
    <!-- Start Godams -->
    <div v-if="user_permissions.includes('manage_godams')" class="vx-col w-full sm:w-full md:w-1/2 lg:w-1/4 xl:w-1/4">
      <vx-card>
        <template slot="no-body">
          <div class="p-8 clearfix">
            <div class="mb-5">
              <h1>
                <span>گدام‌ها</span>
              </h1>
              <small>
                <span class="text-grey">اجناس:</span>
                <span>{{totalInventory}}</span>
              </small>
            </div>
            <router-link class="product-name font-medium truncate" :to="{name: 'godams'}">
              <vs-button icon-pack="feather" icon="icon-chevrons-left" icon-after class="shadow-md w-full">مدیریت</vs-button>
            </router-link>
          </div>
        </template>
      </vx-card>
    </div>
    <!-- End Godams -->
    <!-- Start Transfers -->
    <div v-if="user_permissions.includes('manage_transfers')" class="vx-col w-full sm:w-full md:w-1/2 lg:w-1/4 xl:w-1/4">
      <vx-card>
        <template slot="no-body">
          <div class="p-8 clearfix">
            <div class="mb-5">
              <h1>
                <span>انتقالات</span>
              </h1>
              <small>
                <span class="text-grey">تعداد:</span>
                <span>{{totalTransfers}}</span>
              </small>
            </div>
            <vs-button icon-pack="feather" icon="icon-chevrons-left" icon-after class="shadow-md w-full" @click="ToggleTransfer()">بررسی</vs-button>
          </div>
        </template>
      </vx-card>
    </div>
    <!-- End Transfers -->
  </div>
</div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'
import DataViewSidebar from './DataViewSidebar.vue'
import TransferViewSidebar from './TransferViewSidebar.vue'
import ChangeTimeDurationDropdown from '@/components/ChangeTimeDurationDropdown.vue'

export default {
  name: 'vx-inventory-ex',
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      totalStorageStations: '',
      totalFuelStations: '',
      totalGoods: '',
      totalInventory: '',
      totalTransfers: '',

      revenueComparisonLineKey: 0,
      // Sidebar
      addNewDataSidebar: false,
      transferSidebar: false,
      toggleTransferSidebar: false,
      sidebarData: {},
      // End Sidebar
      sessionsData: {},
      productsOrder: {},
      customersData: {},
      salesRadar: {},
      supportTracker: {},
      revenueComparisonLine: {
        thisMonth: 0,
        lastMonth: 0,
        series: [],
        chartOptions: {
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
            // dashArray: [0, 8],
            width: [3, 3]
          },
          grid: {
            borderColor: '#e7e7e7'
          },
          legend: {
            show: false
          },
          colors: ['#F97788', '#b8c2cc'],
          fill: {
            type: 'gradient',
            gradient: {
              shade: 'dark',
              inverseColors: false,
              gradientToColors: ['#7367F0', '#0CB1C4'],
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
            categories: ['1-4', '5-9', '10-14', '15-19', '20-24', '25-29'].reverse(),
            axisBorder: {
              show: false
            }
          },
          yaxis: {
            tickAmount: 5,
            labels: {
              style: {
                cssClass: 'text-grey fill-current direction-ltr'
              },
              formatter(val) {
                return val > 999 ? `${(val / 1000).toFixed(0)}k` : val
              }
            }
          },
          tooltip: {
            x: {
              show: false
            }
          }
        }
      },
      goalOverviewRadialBar: {
        chartOptions: {
          plotOptions: {
            radialBar: {
              size: 110,
              startAngle: -150,
              endAngle: 150,
              hollow: {
                size: '77%'
              },
              track: {
                background: '#bfc5cc',
                strokeWidth: '50%'
              },
              dataLabels: {
                name: {
                  show: false
                },
                value: {
                  offsetY: 18,
                  color: '#99a2ac',
                  fontSize: '4rem'
                }
              }
            }
          },
          colors: ['#00db89'],
          fill: {
            type: 'gradient',
            gradient: {
              shade: 'dark',
              type: 'horizontal',
              shadeIntensity: 0.5,
              gradientToColors: ['#00b5b5'],
              inverseColors: true,
              opacityFrom: 1,
              opacityTo: 1,
              stops: [0, 100]
            }
          },
          stroke: {
            lineCap: 'round'
          },
          chart: {
            sparkline: {
              enabled: true
            },
            dropShadow: {
              enabled: true,
              blur: 3,
              left: 1,
              top: 1,
              opacity: 0.1
            }
          }
        },
        series: [0]
      },

      salesBarSession: {},
      sessionDataTime: 'lastWeek',
      todoToday: {},

      salesLine: {},
      funding: {},

      browserStatistics: [],
      clientRetentionBar: {},
      storageGraphDataKey: 0,
    }
  },
  components: {
    VueApexCharts,
    ChangeTimeDurationDropdown,
    DataViewSidebar,
    TransferViewSidebar
  },
  created() {
    // this.$vs.loading();
    this.getTotalFGGT();
    this.storageGraphData();
    this.storageItemsGraphData();
    setTimeout(() => {
      document.getElementById("loading-bg").style.display = "none";
    }, 3000);
  },
  methods: {
    getTotalFGGT() {
      this.$Progress.start();
      this.axios.get('/api/totalFGGT')
        .then((response) => {
          this.totalStorageStations = response.data.storages;
          this.totalFuelStations = response.data.fuelstations;
          this.totalGoods = response.data.items;
          this.totalInventory = response.data.inventories;
          this.totalTransfers = response.data.transfers;
          this.$Progress.set(100)
          // document.getElementById("loading-bg").style.display = "none";;
const appLoading = document.getElementById("loading-bg");
if (appLoading) {
  appLoading.style.display = "none";
}
        })

    },
    storageGraphData() {
      this.axios.get('/api/graphs/storage_graph')
        .then((response) => {
          this.goalOverviewRadialBar.series = [response.data];
          this.storageGraphDataKey += 1;
          // document.getElementById("loading-bg").style.display = "none";;
const appLoading = document.getElementById("loading-bg");
if (appLoading) {
  appLoading.style.display = "none";
}
        })
    },
    storageItemsGraphData() {
      this.axios.get('/api/graphs/storage_items_graph')
        .then((response) => {
          this.revenueComparisonLine.series = response.data.series;
          this.revenueComparisonLine.thisMonth = response.data.thisMonth;
          this.revenueComparisonLine.lastMonth = response.data.lastMonth;
          this.revenueComparisonLineKey += 1;
        })
    },
    ToggleTransfer() {
      this.sidebarData = {}
      this.toggleTSidebar(true)
    },
    toggleSidebar() {
      this.sidebarData = {}
      this.toggleDataSidebar(true);
    },
    toggleDataSidebar(val = false) {
      setTimeout(() => {
        this.getTotalFGGT();
        this.storageGraphData();
        this.storageItemsGraphData();
      }, 200);
      this.addNewDataSidebar = val

    },
    toggleTSidebar(val = false) {
      this.transferSidebar = val
    }
  },

}
</script>

<style lang="scss">
#demo-card-analytics {
  .tasks-today-container {
    .tasks-today__task {
      transition: background 0.15s ease-out;

      .tasks-today__actions {
        display: none;
      }

      &:hover {
        background: rgba(var(--vs-primary), 0.04);

        .tasks-today__actions {
          display: flex;
        }
      }
    }
  }
}
</style>
