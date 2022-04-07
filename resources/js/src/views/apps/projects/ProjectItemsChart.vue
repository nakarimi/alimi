<template>
<div>

  <p class="pt-5" v-if="!pie.series[0].data.length">اطلاعات موجود نمی باشد.</p>
  <e-charts autoresize :options="pie" ref="pie" auto-resize />
</div>
</template>

<script>
import ECharts from 'vue-echarts/components/ECharts'
import 'echarts/lib/component/tooltip'
import 'echarts/lib/component/legend'
import 'echarts/lib/chart/pie'

export default {

  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      pie: {
        tooltip: {
          trigger: 'item',
          formatter: '{a} <br/>{b} : {c} ({d}%)'
        },
        legend: {
          orient: 'horizontal',
          left: 'center',
          // data: ['Direct interview', 'Email marketing', 'Alliance advertising', 'Video ad', 'Search engine']
          data: []
        },
        series: [{
          name: '',
          type: 'pie',
          radius: '55%',
          center: ['50%', '60%'],
          color: ['#FF9F43', '#28C76F', '#EA5455', '#87ceeb', '#7367F0'],
          // color: [],
          data: [],
          itemStyle: {
            emphasis: {
              shadowBlur: 10,
              shadowOffsetX: 0,
              shadowColor: 'rgba(0, 0, 0, 0.5)'
            }
          }
        }]
      },
    }
  },
  components: {
    ECharts
  },
  created() {},
  methods: {
    setChart() {
      let dataIndex = -1
      let pie = this.$refs.pie
      let dataLen = pie.options.series[0].data.length
      setInterval(() => {
        pie.dispatchAction({
          type: 'downplay',
          seriesIndex: 0,
          dataIndex
        })
        dataIndex = (dataIndex + 1) % dataLen
        pie.dispatchAction({
          type: 'highlight',
          seriesIndex: 0,
          dataIndex
        })
        pie.dispatchAction({
          type: 'showTip',
          seriesIndex: 0,
          dataIndex
        })
      }, 1000)

    }
  }
}
</script>
