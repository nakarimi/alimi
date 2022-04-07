<template>
<div v-if="sale">
  <vs-button size="small" type="gradient" icon="print" class="mb-1" id="printBTN" @click="cprint">چاپ</vs-button>
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
        <small class="mb-5">{{sale.type}}-</small>
        <small class="mb-5" v-if="sale.sales.project">{{sale.sales.project.pro_data.reference_no}}-</small>
        <small class="mb-5" v-text="sale.sales.serial_no" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          نوعیت:
        </strong>
        <small class="mb-5" v-text="$t(sale.type)" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          نوع منبع:
        </strong>
        <small class="mb-5" v-text="$t(sale.source_type)" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.source_id">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          منبع:
        </strong>
        <small class="mb-5" v-text="(sale.source_id) ? $t(sale.source_id.name) : ''" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          واحد پولی:
        </strong>
        <small class="mb-5" v-text="sale.currency_id.sign_fa" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>

    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          حساب بانک:
        </strong>
        <small class="mb-5" v-text="(sale.bank_account) ? sale.bank_account.name : ''" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          تاریخ:
        </strong>
        <small class="mb-5 date" v-text="sale.datatime" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.project">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          پروژه:
        </strong>
        <small class="mb-5" v-text="sale.sales.project.pro_data.title" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.driver_name">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          نام دریور:
        </strong>
        <small class="mb-5" v-text="sale.sales.driver_name" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.plate_no">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          نمبر پلیت:
        </strong>
        <small class="mb-5" v-text="sale.sales.plate_no" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.driver_phone">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          شماره تماس دریور :
        </strong>
        <small class="number-rtl-view" v-text="sale.sales.driver_phone"></small>
      </h6>
    </vs-col>
  </vs-row>

  <!-- Ekmalat section -->
  <vs-row vs-w="12" class="project-view-header">
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="12" vs-sm="12" vs-xs="12">
      <h4>&nbsp;بخش اکمالات /اقلام&nbsp;</h4>
    </vs-col>
  </vs-row>
  <vs-table :data="sale.stock_records">
    <template slot="thead">
      <vs-th>جنس / محصول</vs-th>
      <vs-th>مقدار</vs-th>
      <!-- <vs-th>عملیه</vs-th> -->
      <vs-th>قیمت فی واحد</vs-th>
      <vs-th>قیمت مجموعی</vs-th>
    </template>
    <template slot-scope="{data}">
      <vs-tr v-for="(tr, i) in data" :key="i">
        <vs-td :data="tr.item_id">
            <p> {{ (tr.item_id && tr.item_id.type) ? tr.item_id.type.type : '' }} - {{ tr.item_id.name }} </p>
        </vs-td>
        <vs-td :data="tr.increment_equiv">
          {{tr.increment_equiv}} {{ tr.item_id.uom_equiv_id.title }}
        </vs-td>
        <!-- <vs-td :data="tr.operation_id">
          <p> {{ tr.operation_id.formula }} </p>
        </vs-td> -->
        <vs-td :data="tr.unit_price">
          {{tr.unit_price | NumThreeDigit}} <small style="color:#42b983;"><b>افغانی </b></small>
        </vs-td>
        <vs-td :data="tr.total_price">
          {{tr.total_price | NumThreeDigit}} <small style="color:#42b983;"><b>افغانی </b></small>
        </vs-td>
      </vs-tr>
    </template>
  </vs-table>

  <vs-row vs-w="12" class="project-view-header">
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="12" vs-sm="12" vs-xs="12">
      <h4>&nbsp;بخش مالی&nbsp;</h4>
    </vs-col>
  </vs-row>
  <vs-row>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2"> <strong class="mr-4"> مصارف خدمات : </strong> <small class="mb-5" v-text="sale.sales.service_cost" vs-justify="right" vs-align="right"></small> {{sale.currency_id.sign_fa}}</h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.transport_cost">
      <h6 class="mb-5 mt-3 ml-2"> <strong class="mr-4"> مصارف انتقالات : </strong> <small class="mb-5" v-text="sale.sales.transport_cost" vs-justify="right" vs-align="right"></small> {{sale.currency_id.sign_fa}}</h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.tax">
      <h6 class="mb-5 mt-3 ml-2"> <strong class="mr-4"> مالیات : </strong> <small class="mb-5" v-text="sale.sales.tax" vs-justify="right" vs-align="right"></small> %</h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.deposit">
      <h6 class="mb-5 mt-3 ml-2"> <strong class="mr-4"> تامینات : </strong> <small class="mb-5" v-text="sale.sales.deposit" vs-justify="right" vs-align="right"></small> %</h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2"> <strong class="mr-4"> قیمت نهایی : </strong> <small class="mb-5" v-text="sale.sales.total" vs-justify="right" vs-align="right"></small> {{ sale.currency_id.sign_fa }}</h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.steps">
      <h6 class="mb-5 mt-3 ml-2"> <strong class="mr-4"> پیشرفت : </strong> <small class="mb-5" v-text="calcProgress(sale.sales.steps)" vs-justify="right" vs-align="right"></small> %</h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="12" vs-sm="12" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2"> <strong class="mr-4"> تفصیلات : </strong> <small class="mb-5" v-text="sale.sales.description" vs-justify="right" vs-align="right"></small> </h6>
    </vs-col>

  </vs-row>
</div>
</template>

<script>

export default {
  props: {
    sale: {
      required: false,
    },
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
    }
  },
  created() {
    this.getSale();
  },
  methods: {
    getSale() {
      if (this.$route.params.id) {
        this.axios.get('/api/sale/' + this.$route.params.id)
          .then((response) => {
            this.sale = response.data;
            this.$Progress.set(100)
          })
      }
    },
    calcProgress(step) {
      if (this.sale.type == 's1') {
        return ((step * 100) / 8).toFixed(0);
      } else if (this.sale.type == 's2') {
        return ((step * 100) / 6).toFixed(0);
      } else if (this.sale.type == 's3') {
        return ((step * 100) / 3).toFixed(0);
      } else {
        return 0;
      }
    },
    cprint() {
      window.open(`/print?entity=sale&type=${this.sale.type}&id=${this.sale.id}&name=${localStorage.getItem("name")} ${localStorage.getItem("lastname")}`, '_blank');
    },
  }
}
</script>

<style>

</style>
