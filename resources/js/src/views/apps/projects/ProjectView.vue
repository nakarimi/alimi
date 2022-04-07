<template>
<div>
  <vs-button size="small" type="gradient" icon="print" id="printBTN" @click="cprint">چاپ</vs-button>

  <div id="print-this">
    <vs-row vs-w="12" class="project-view-header">
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="12" vs-sm="12" vs-xs="12">
        <h4>&nbsp;بخش معلومات عمومی&nbsp;</h4>
      </vs-col>
    </vs-row>
    <vs-row vs-w="12">
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            سریال نمبر:
          </strong>
          <small class="mb-5" vs-justify="right" vs-align="right"> {{ project.serial_no }}{{ (project.pro_data && project.pro_data.company_id) ? ' - ' + project.pro_data.company_id.sign : '' }}</small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            اعلان:
          </strong>
          <small class="mb-5" v-if="project.proposal_id" v-text="project.proposal_id.pro_data.title" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            تاریخ عقد قرارداد:
          </strong>
          <small class="mb-5 date" v-text="project.contract_date" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-1">
          <strong class="mr-4">
            مرجع مربوطه :
          </strong>
          <small class="mb-5" v-if="project.pro_data" v-text="project.pro_data.client.name" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            عنوان قرارداد:
          </strong>
          <small class="mb-5" v-if="project.pro_data" v-text="project.pro_data.title" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            نوعیت قرارداد:
          </strong>
          <small v-if="project.status == 'B'" class="mb-5" vs-justify="right" vs-align="right">معین</small>
          <small v-if="project.status == 'A'" class="mb-5" vs-justify="right" vs-align="right">چارچوبی</small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            شماره شناسایی قرارداد :
          </strong>
          <small class="mb-5" v-if="project.pro_data" v-text="project.pro_data.reference_no" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            تاریخ ختم قرارداد:
          </strong>
          <small class="mb-5 date" v-text="project.contract_end_date" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            تضمین حسن اجرا:
          </strong>
          <small class="mb-5" v-text="project.project_guarantee" vs-justify="right" vs-align="right"></small>
          <small style="color:#42b983;"><b>افغانی </b></small>
        </h6>
      </vs-col>
    </vs-row>
    <br>
    <!-- Ekmalat section -->
    <vs-row vs-w="12" class="project-view-header">
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="12" vs-sm="12" vs-xs="12">
        <h4>&nbsp;بخش اکمالات /اقلام&nbsp;</h4>
      </vs-col>
    </vs-row>
    <ekmalat-view-list :items="project.pro_items" ></ekmalat-view-list>

    <vs-col vs-lg="6" vs-sm="6" vs-xs="12">
      <h3 class="project-view-header pr-2 pl-2">بررسی ارزش اجناس قرارداد</h3>
      <project-items-chart ref="item_chart_type" />
    </vs-col>
    <vs-col vs-lg="6" vs-sm="6" vs-xs="12">
      <h3 class="project-view-header pl-2 pr-2">بررسی فروشات اجناس بر اساس نوعیت</h3>
      <project-items-chart ref="item_chart" />
    </vs-col>

    <vs-row vs-w="12" class="project-view-header">
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="12" vs-sm="12" vs-xs="12">
        <h4>&nbsp; بخش مصارف&nbsp;</h4>
      </vs-col>
    </vs-row>
    <vs-row ws-w="12">
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            تامینات:
          </strong>
          <span class="mb-5" v-if="project.pro_data" vs-justify="right" vs-align="right"> {{ project.pro_data.deposit | NumThreeDigit}}</span>
          <small style="color:#42b983;"><b>% </b></small>
          <small>({{project.pro_data.total_price * (project.pro_data.deposit / 100)}})</small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            مالیات:
          </strong>
          <small class="mb-5" v-if="project.pro_data" v-text="project.pro_data.tax" vs-justify="right" vs-align="right"></small>
          <small style="color:#42b983;"><b>% </b></small>
          <small>({{project.pro_data.total_price * (project.pro_data.tax/ 100)}})</small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            متفرقه:
          </strong>
          <span class="mb-5" v-if="project.pro_data" vs-justify="right" vs-align="right"> {{ project.pro_data.others | NumThreeDigit}}</span>
          <small style="color:#42b983;"><b>افغانی </b></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            ارزش قرارداد:
          </strong>
          <span class="mb-5" v-if="project.pro_data" vs-justify="right" vs-align="right"> {{ project.pro_data.pr_worth | NumThreeDigit}}</span>
          <small style="color:#42b983;"><b>افغانی </b></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            انتقالات:
          </strong>
          <span class="mb-5" v-if="project.pro_data" vs-justify="right" vs-align="right"> {{ project.pro_data.transit | NumThreeDigit}}</span>
          <small style="color:#42b983;"><b>افغانی </b></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            نرخ دهی:
          </strong>
          <span class="mb-5" v-if="project.pro_data" vs-justify="right" vs-align="right"> {{ project.pro_data.total_price | NumThreeDigit}}</span>
          <small style="color:#42b983;"><b>افغانی </b></small>
        </h6>
      </vs-col>
    </vs-row>
    <br>
    <project-sale-summary />
  </div>
</div>
</template>

<script>
import ProjectSaleSummary from './ProjectSaleSummary'
import ProjectItemsChart from './ProjectItemsChart'
import EkmalatViewList from "../shared/EkmalatViewList";


export default {
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      // init values
      project: [],
    };
  },
  components: {
    ProjectSaleSummary,
    ProjectItemsChart,
    EkmalatViewList
  },
  created() {
    this.getProject();
  },
  methods: {
    getProject() {
      // Start the Progress Bar
      this.$Progress.start()
      this.axios.get('/api/project/' + this.$route.params.id)
        .then((response) => {
          this.project = response.data;
          this.getProjectSales();
          this.getProjectItemsChart();
        })
    },
    getProjectSales() {
      // Start the Progress Bar
      this.$Progress.start()
      this.axios.get('/api/sale/project/type/' + this.$route.params.id)
        .then((response) => {
          if (response.data) {
            this.$refs.item_chart.pie.series[0].name = 'فروشات اجناس بر اساس واحد';
            for (let [key, value] of Object.entries(response.data)) {
              this.$refs.item_chart_type.pie.legend.data.push(key);
              this.$refs.item_chart_type.pie.series[0].color = ['#FF9F43', '#28C76F', '#EA5455', '#87ceeb', '#7367F0'].reverse();
              this.$refs.item_chart_type.pie.series[0].data.push({
                value: value,
                name: key
              });
            }
          }
        })
    },
    getProjectItemsChart() {
      // Start the Progress Bar
      this.$Progress.start()
      this.axios.get('/api/sale/project/item-chart/' + this.$route.params.id)
        .then((response) => {
          if (response.data) {
            this.$refs.item_chart_type.pie.series[0].name = 'فروشات اجناس بر اساس نوعیت';
            for (let [key, value] of Object.entries(response.data)) {
              this.$refs.item_chart.pie.legend.data.push(key);
              this.$refs.item_chart.pie.series[0].color = ['#FF9F43', '#28C76F', '#EA5455', '#87ceeb', '#7367F0'];
              this.$refs.item_chart.pie.series[0].data.push({
                value: value,
                name: key
              });
            }
          }
            document.getElementById("loading-bg").style.display = "none";
        })
    },
    cprint() {
      window.open(`/project/print?pid=${this.$route.params.id}&name=${localStorage.getItem("name")} ${localStorage.getItem("lastname")}`, '_blank');
    }
  },
};
</script>
