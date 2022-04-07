<template>
<e-charts autoresize :options="bar" theme="ovilia-green" ref="bar" auto-resize />
</template>

<script>
import ECharts from 'vue-echarts/components/ECharts'
import 'echarts/lib/component/tooltip'
import 'echarts/lib/component/legend'
import 'echarts/lib/chart/bar'
const theme = {
    "color": [
        "#4ea397",
        "#ea5455",
        "#7bd9a5"
    ],
};
ECharts.registerTheme('ovilia-green', theme)

export default {
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      bar: {
        legend: {},
        tooltip: {},
        dataset: {
          // Provide data.
          source: [
            ['سریال نمبر فروشات', 'قیمت مجموع', 'مالیات', 'مصارف خدمات'],
          ]
        },
        // Declare X axis, which is a category axis, mapping
        // to the first column by default.
        xAxis: {
          type: 'category'
        },
        // Declare Y axis, which is a value axis.
        yAxis: {},
        // Declare several series, each of them mapped to a
        // column of the dataset by default.
        series: [{
          type: 'bar'
        }, {
          type: 'bar'
        }, {
          type: 'bar'
        }]

      },
    }
  },
  created() {
    this.getProjectSales();
  },
  methods: {
    getProjectSales() {
      // Start the Progress Bar
      this.$Progress.start()
      this.axios.get('/api/sale/project/' + this.$route.params.id)
        .then((response) => {
        // for (let [key, value] of Object.entries(response.data)) {
          this.bar.dataset.source.push(['فروش ', response.data['total'], (response.data['total'] - (response.data['total'] * response.data['tax'] / 100)).toFixed(0), response.data['service_cost']]);
        // }
        })
    },
     randomize() {
      return [0, 0, 0].map(v => {
        return Math.round(300 + Math.random() * 700) / 10
      })
    }
  },
  components: {
    ECharts
  }
}
</script>
