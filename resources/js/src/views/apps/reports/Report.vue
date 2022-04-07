<template>
<vx-card>
  <div class="mt-base mb-base flex justify-between">
    <div class="vx-col w-1/2">
      <h1>راپورها</h1>
    </div>
    <div class="vx-col w-1/2">
      <div class="sm:w-1 md:w-1/2 lg:w-1/4 float-right xl:w-1/4">
        <div class="radio-group w-full">
          <div class="w-1/2">
            <input type="radio" v-model="curr_display" @change="mcurr_display = curr_display" value='afn' id="report_list_afn" name="curr_display" />
            <label for="report_list_afn" class="w-full text-center">افغانی</label>
          </div>
          <div class="w-1/2">
            <input type="radio" v-model="curr_display" @change="mcurr_display = curr_display" value='usd' id="report_list_usd" name="curr_display" />
            <label for="report_list_usd" class="w-full text-center">دالر</label>
          </div>
        </div>
      </div>
    </div>

  </div>
  <vs-divider />
  <div class="vx-row mt-base">
    <div class="vx-col w-full md:w-1/4 mb-base">
      <vx-card>
        <div class="mt-base mb-base flex justify-between">
          <div>
            <strong>نهاد جدید</strong>
            <p>ماه {{ allInOneData[14] }}: {{ allInOneData[0] }}</p>
          </div>
          <feather-icon icon="UsersIcon" svgClasses="w-12 h-12 text-darkgrey"></feather-icon>
        </div>
      </vx-card>
    </div>
    <div class="vx-col w-full md:w-1/4 mb-base">
      <vx-card>
        <div class="mt-base mb-base flex justify-between">
          <div>
            <strong>فروشات</strong>
            <p>ماه {{ allInOneData[14] }}: {{ allInOneData[1] }}</p>
          </div>
          <feather-icon icon="ShoppingBagIcon" svgClasses="w-12 h-12 text-darkgrey"></feather-icon>
        </div>
      </vx-card>
    </div>
    <div class="vx-col w-full md:w-1/4 mb-base">
      <vx-card title-color="#fff" content-color="#fff" card-background="linear-gradient(120deg ,rgba(247,97,161,0.5), rgba(140,27,171,.8))">
        <div class="mt-base mb-base flex justify-between">
          <div>
            <strong>عایدات</strong>
            <p>ماه {{ allInOneData[14] }}: {{ allInOneData[2] }}</p>
          </div>
          <feather-icon icon="DownloadIcon" svgClasses="w-12 h-12 text-white"></feather-icon>
        </div>
      </vx-card>
    </div>
    <div class="vx-col w-full md:w-1/4 mb-base">
      <vx-card title-color="#fff" content-color="#fff" card-background="linear-gradient(to right, #56ab2f, #a8e063)">
        <div class="mt-base mb-base flex justify-between">
          <div>
            <strong>مصارف</strong>
            <p>ماه {{ allInOneData[14] }}: {{ allInOneData[3] }}</p>
          </div>
          <feather-icon icon="UploadIcon" svgClasses="w-12 h-12 text-white"></feather-icon>
        </div>
      </vx-card>
    </div>
  </div>
  <vs-divider />
  <!-- Report generate section -->
  <div class="vx-row mt-base">
    <div class="vx-col w-full md:w-1/3 mb-base hover-move-up cursor-pointer" @click="popupReport = true, reportType = 'benefit'">
      <vx-card>
        <div class="mt-base mb-base flex">
          <feather-icon icon="DownloadIcon" svgClasses="w-12 h-12 text-darkgrey"></feather-icon>
          <strong class="text-3xl ml-2 pt-2">عایدات</strong>
        </div>
        <div class="mb-base">
          <p>ماه {{ allInOneData[14] }}: {{ allInOneData[4] }}</p>
          <p>سال جاری: {{ allInOneData[5] }}</p>
        </div>
      </vx-card>
    </div>
    <div class="vx-col w-full md:w-1/3 mb-base hover-move-up cursor-pointer" @click="popupReport = true, reportType = 'expense'">
      <vx-card>
        <div class="mt-base mb-base flex">
          <feather-icon icon="UploadIcon" svgClasses="w-12 h-12 text-darkgrey"></feather-icon>
          <strong class="text-3xl ml-2 pt-2">مصارف</strong>
        </div>
        <div class="mb-base">
          <p>ماه {{ allInOneData[14] }}: {{ allInOneData[6] }}</p>
          <p>سال جاری: {{ allInOneData[7] }}</p>
        </div>
      </vx-card>
    </div>
    <div class="vx-col w-full md:w-1/3 mb-base hover-move-up cursor-pointer" @click="popupReport = true, reportType = 'sale'">
      <vx-card>
        <div class="mt-base mb-base flex">
          <feather-icon icon="ShoppingCartIcon" svgClasses="w-12 h-12 text-darkgrey"></feather-icon>
          <strong class="text-3xl ml-2 pt-2">فروشات</strong>
        </div>
        <div class="mb-base">
          <p>ماه {{ allInOneData[14] }}: {{ allInOneData[8] }}</p>
          <p>سال جاری: {{ allInOneData[9] }}</p>
        </div>
      </vx-card>
    </div>
    <!-- <div class="vx-col w-full md:w-1/3 mb-base hover-move-up cursor-pointer" @click="popupReport = true, reportType = 'taraz'">
      <vx-card>
        <div class="mt-base mb-base flex">
          <feather-icon icon="CompassIcon" svgClasses="w-12 h-12 text-darkgrey"></feather-icon>
          <strong class="text-3xl ml-2 pt-2">ترازنامه</strong>
        </div>
        <div class="mb-base">
          <p>ماه {{ allInOneData[14] }}: {{ allInOneData[0] }}</p>
          <p>سال جاری: {{ allInOneData[0] }}</p>
        </div>
      </vx-card>
    </div> -->
    <div class="vx-col w-full md:w-2/3 mb-base hover-move-up cursor-pointer" @click="popupReport = true, reportType = 'account_details'">
      <vx-card>
        <div class="mt-base mb-base flex">
          <feather-icon icon="FileTextIcon" svgClasses="w-12 h-12 text-darkgrey"></feather-icon>
          <strong class="text-3xl ml-2 pt-2">صورت حساب</strong>
        </div>
        <div class="mb-base">
          <p>ماه {{ allInOneData[14] }}: {{ allInOneData[10] }}</p>
          <p>سال جاری: {{ allInOneData[11] }}</p>
        </div>
      </vx-card>
    </div>
    <div class="vx-col w-full md:w-1/3 mb-base hover-move-up cursor-pointer" @click="popupReport = true , reportType = 'accounts'">
      <vx-card>
        <div class="mt-base mb-base flex">
          <feather-icon icon="UsersIcon" svgClasses="w-12 h-12 text-darkgrey"></feather-icon>
          <strong class="text-3xl ml-2 pt-2">حسابات</strong>
        </div>
        <div class="mb-base">
          <p>ماه {{ allInOneData[14] }}: {{ allInOneData[12] }}</p>
          <p>سال جاری: {{ allInOneData[13] }}</p>
        </div>
      </vx-card>
    </div>
  </div>

  <!-- Modal -->
  <vs-popup class="holamundo" :title="vsPopupTitle" :active.sync="popupReport">
    <div class="vx-row m-3">
      <div class="sm:w-1 md:w-1/2 lg:w-1/4 float-right xl:w-1/4">
        <div class="radio-group w-full">
          <div class="w-1/2">
            <input type="radio" v-model="reportForm.currency" @change="curr_display = mcurr_display" value='afn' id="mreport_list_afn" name="mcurr_display" />
            <label for="mreport_list_afn" class="w-full text-center">افغانی</label>
          </div>
          <div class="w-1/2">
            <input type="radio" v-model="reportForm.currency" @change="curr_display = mcurr_display" value='usd' id="mreport_list_usd" name="mcurr_display" />
            <label for="mreport_list_usd" class="w-full text-center">دالر</label>
          </div>
        </div>
      </div>
    </div>
    <div class="vx-row m-3">
      <div class="w-1/3">
        <label for="date" class="mt-3"><small>تاریخ شروع </small></label>
      </div>
      <div class="w-2/3">
        <date-picker color="#e85454" name="start_date" v-validate="'required'" input-format="YYYY/MM/DD" format="YYYY/MM/DD" type="date" v-model="reportForm.start_date" />
      </div>
      <span class="absolute text-danger alerttext">{{ errors.first('reportForm.start_date') }}</span>
    </div>
    <div class="vx-row m-3">
      <div class="w-1/3">
        <label for="date" class="mt-3"><small>تاریخ ختم </small></label>
      </div>
      <div class="w-2/3">
        <date-picker color="#e85454" name="end_date" v-validate="'required'" input-format="YYYY/MM/DD" format="YYYY/MM/DD" type="date" v-model="reportForm.end_date" />
      </div>
      <span class="absolute text-danger alerttext">{{ errors.first('reportForm.end_date') }}</span>
    </div>
    <div class="vx-row mx-3" v-if="reportType == 'sale'">
      <vs-select v-model="reportForm.sale_type" label="نوع فروش" class="mt-5 w-full">
        <vs-select-item v-for="item in report_choices" :key="item.value" :value="item.value" :text="item.text" />
      </vs-select>
    </div>
    <div class="vx-row mx-3" v-if="reportType == 'account_details'">
      <vs-select v-model="report_account_choices" label="نوعیت حساب" class="mt-5 w-full" @change="reportForm.account = []">
        <vs-select-item v-for="item in accountTypes" :key="item.id" :value="item.accounts" :text="item.title" />
      </vs-select>
    </div>
    <div class="vx-row mx-3 mb-3" v-if="reportType == 'account_details'">
      <vs-select v-model="reportForm.account" label="حساب مورد نظر" class="mt-5 w-full">
        <span v-if="!report_account_choices.length">
          <vs-select-item value="0" selected="selected" text="حساب یافت نشد." />
        </span>
        <vs-select-item v-for="item in report_account_choices" :key="item.id" :value="item.id" :text="item.name" />

      </vs-select>
    </div>
    <div class="vx-row overflow-hidden">
      <div class="vx-col w-full">
        <vs-button :disabled="!(reportForm.start_date && reportForm.end_date)" class="m-2" @click="goToReport('sale')">بررسی</vs-button>
        <vs-button color="warning" type="border" class="m-2" @click="popupReport = false;">انصراف</vs-button>
      </div>
    </div>
  </vs-popup>

</vx-card>
</template>

<script>
export default {
  name: 'vx-report',
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      curr_display: 'afn',
      mcurr_display: 'afn',
      popupReport: false,
      allInOneData: [],
      vsPopupTitle: 'فیلترهای مورد نظر برای مشاهدۀ راپور را انتخاب کنید.',
      reportType: '',
      report_choices: [{
        text: 'همه',
        value: 'all'
      }, {
        text: 'فروشات عمده قراردادی',
        value: 'sale'
      }, {
        text: 'فروشات پرچون قراردادی',
        value: 'sale3'
      }, {
        text: 'فروشات عمده عادی',
        value: 'sale2'
      }, {
        text: 'فروشات پرچون عادی',
        value: 'sale4'
      }],
      report_account_choices: [],
      accountTypes: [],
      reportForm: new Form({
        currency: 'afn',
        start_date: null,
        end_date: null,
        sale_type: 'all',
        account: null
      }),
    }
  },
  created() {
    this.getAllAccountTypes();
    this.allInOne();
  },
  methods: {
    allInOne(){
      this.axios.get('/api/all-in-one')
        .then((response) => {
          this.allInOneData = response.data;
          this.$Progress.set(100)
        }).catch(() => {
          this.$Progress.set(100)
        });
    },
    // Get Account Types
    getAllAccountTypes() {
      this.$Progress.start()
      this.axios.get('/api/accounts')
        .then((response) => {
          this.accountTypes = response.data;
          this.isdata = true;
          this.$Progress.set(100)
          document.getElementById("loading-bg").style.display = "none";
        }).catch(() => {
          this.$Progress.set(100)
          document.getElementById("loading-bg").style.display = "none";
        });
    },
    goToReport(type) {
      let f = this.reportForm
      window.open(`/reports/${this.reportType}?frdate=${f.start_date}&todate=${f.end_date}&currency=${f.currency}&saletype=${f.sale_type}&printname=${localStorage.getItem("name")} ${localStorage.getItem("lastname")}&account=${f.account}`, '_blank');
    },
    closeModel() {
      this.popupReport = false;
    },
  }
}
</script>
