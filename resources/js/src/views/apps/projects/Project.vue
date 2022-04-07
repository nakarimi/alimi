<template>
<div class="vx-row">
  <div class="vx-col w-full md:w-1/2 mb-base">
    <vx-card>
      <div class="vx-row flex-col-reverse lg:flex-row">
        <!-- LEFT COL -->
        <div class="vx-col w-full lg:w-1/2 xl:w-1/2 flex flex-col justify-between" v-if="propBarSession.analyticsData">
          <div>
            <!-- Avg Session -->
            <h2 class="mb-1 font-bold">اعلانات</h2>
          </div>
          <router-link :to="!user_permissions.includes('manage_proposals') ? '' : 'proposal'">
            <vs-button :disabled="!user_permissions.includes('manage_proposals')" icon-pack="feather" icon="icon-chevrons-left" icon-after class="shadow-md w-full lg:mt-0 mt-4">ثبت اعلان جدید</vs-button>
          </router-link>
        </div>

        <!-- RIGHT COL -->
        <div class="vx-col w-full lg:w-1/2 xl:w-1/2 flex flex-col lg:mb-0 mb-base">
          <vue-apex-charts :key="charKey" type="bar" height="200" :options="propBarSession.chartOptions" :series="propBarSession.series" v-if="propBarSession.series" />
        </div>
      </div>
      <vs-divider class="my-6"></vs-divider>
      <div class="vx-row p-2">
        <div class="vx-col w-1/2 mb-3">
          <small>همه اعلانات: {{ projPropDataG.allProp }}</small>
          <vs-progress class="block mt-1" :percent="projPropDataG.allProp * 100 / Math.pow(10, projPropDataG.allProp.toString().length)" color="primary"></vs-progress>
        </div>
        <div class="vx-col w-1/2 mb-3">
          <small>اعلانات ماه اخیر: {{ projPropDataG.allPropM }}</small>
          <vs-progress class="block mt-1" :percent="projPropDataG.allPropM * 100 / Math.pow(10, projPropDataG.allPropM.toString().length)" color="success"></vs-progress>
        </div>
      </div>
    </vx-card>
  </div>
  <div class="vx-col w-full md:w-1/2 mb-base">
    <vx-card>
      <div class="vx-row flex-col-reverse lg:flex-row">
        <!-- LEFT COL -->
        <div class="vx-col w-full lg:w-1/2 xl:w-1/2 flex flex-col justify-between" v-if="projBarSession.analyticsData">
          <div>
            <h2 class="mb-1 font-bold">قراردادها</h2>
          </div>
        </div>

        <!-- RIGHT COL -->
        <div class="vx-col w-full lg:w-1/2 xl:w-1/2 flex flex-col lg:mb-0 mb-base">
          <vue-apex-charts :key="charKey" type="bar" height="200" :options="projBarSession.chartOptions" :series="projBarSession.series" v-if="projBarSession.series" />
        </div>
      </div>

      <vs-divider class="my-6"></vs-divider>

      <div class="vx-row">
        <div class="vx-col w-1/2 mb-3">
          <small>همه قراردادها: {{ projPropDataG.allPro }}</small>
          <vs-progress class="block mt-1" :percent="projPropDataG.allPro * 100 / Math.pow(10, projPropDataG.allPro.toString().length)" color="primary"></vs-progress>
        </div>
        <div class="vx-col w-1/2 mb-3">
          <router-link :to="!user_permissions.includes('manage_contracts')? '' : '/projects/add'">
            <vs-button :disabled="!user_permissions.includes('manage_contracts')" icon-pack="feather" icon="icon-chevrons-left" icon-after class="shadow-md w-full lg:mt-0 mt-4">مدیریت قراردادها</vs-button>
          </router-link>
        </div>
      </div>
    </vx-card>
  </div>
  <!-- Project Begin -->
  <FeaturedProjectInfo></FeaturedProjectInfo>
  <vs-col vx-row="12">
    <ActiveProjects></ActiveProjects>
  </vs-col>
  <br>
  <vs-divider />
</div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'
import ActiveProjects from './ActiveProjects.vue'
import FeaturedProjectInfo from './FeaturedProjectInfo.vue'

import StatisticsCardLine from '@/components/statistics-cards/StatisticsCardLine.vue'
import ChangeTimeDurationDropdown from '@/components/ChangeTimeDurationDropdown.vue'

export default {
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      announceimg: '/announce.svg',
      contractimg: '/contract.svg',
      projBarSession: {
        series: [{
          name: 'قراردادها',
          data: []
        }],
        analyticsData: {
          comparison: {
            str: '۷ روز اخیر',
          }
        },
        chartOptions: {
          grid: {
            show: false,
            padding: {
              left: 0,
              right: 0
            }
          },
          chart: {
            type: 'bar',
            sparkline: {
              enabled: true
            },
            toolbar: {
              show: false
            }
          },
          states: {
            hover: {
              filter: 'none'
            }
          },
          colors: ['#7367f0', '#7367f0', '#7367f0', '#7367f0', '#7367f0', '#7367f0'],
          plotOptions: {
            bar: {
              columnWidth: '45%',
              distributed: true,
              endingShape: 'rounded' // Deprecated
              // borderRadius: '20px', // Coming Soon
            }
          },
          tooltip: {
            x: {
              show: false
            }
          }
        }
      },
      propBarSession: {
        series: [{
          name: 'آفرها',
          data: []
        }],
        analyticsData: {
          comparison: {
            str: '۷ روز اخیر',
          }
        },
        chartOptions: {
          grid: {
            show: false,
            padding: {
              left: 0,
              right: 0
            }
          },
          chart: {
            type: 'bar',
            sparkline: {
              enabled: true
            },
            toolbar: {
              show: false
            }
          },
          states: {
            hover: {
              filter: 'none'
            }
          },
          colors: ['#7367f0', '#7367f0', '#7367f0', '#7367f0', '#7367f0', '#7367f0'],
          plotOptions: {
            bar: {
              columnWidth: '45%',
              distributed: true,
              endingShape: 'rounded' // Deprecated
              // borderRadius: '20px', // Coming Soon
            }
          },
          tooltip: {
            x: {
              show: false
            }
          }
        }
      },
      sessionDataTime: 'lastWeek',
      todoToday: {},
      projPropDataG: {
        allProp: 0,
        allPropM: 0,
        allPro: 0,
      },
      charKey: 0,
    }
  },
  components: {
    VueApexCharts,
    StatisticsCardLine,
    ChangeTimeDurationDropdown,
    // ProjectList,
    ActiveProjects,
    FeaturedProjectInfo
  },
  created() {
    this.getProjPropData();
  },
  methods: {
    getProjPropData() {
      this.axios.get('/api/graphs/pro-prop').then((response) => {
        this.projPropDataG = response.data;
        this.propBarSession.series[0].data = response.data.daysAgoProp;
        this.projBarSession.series[0].data = response.data.daysAgoProj;
        this.charKey +=1
      })
    }
  }
}
</script>

<style lang="scss">
.vs-table {
  border-collapse: separate;
  border-spacing: 0 1.3rem;
  padding: 0 1rem;

  tr {
    box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);

    td {
      padding: 20px;

      &:first-child {
        border-top-left-radius: 0.5rem;
        border-bottom-left-radius: 0.5rem;
      }

      &:last-child {
        border-top-right-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
      }

      &.img-container {
        // width: 1rem;
        // background: #fff;

        span {
          display: flex;
          justify-content: flex-start;
        }
      }
    }

    td.td-check {
      padding: 20px !important;
    }
  }
}

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
</style><style>
.projectListDashboard {
  display: flex;
  width: 100%;
  background-color: rgb(243, 245, 247);
  border-left-width: 0.4rem;
  border-left-style: solid;
  padding: 10px;
  border-color: rgb(49, 47, 75) !important;
}
</style>
