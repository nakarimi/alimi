<template>
<div v-if="proposal">
  <vs-button size="small" type="gradient" icon="print" id="printBTN" @click="cprint">چاپ</vs-button>
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
        <small class="mb-5" vs-justify="right" vs-align="right"> {{ proposal.serial_no }}{{ (proposal.pro_data && proposal.pro_data.company_id) ? ' - ' + proposal.pro_data.company_id.sign : '' }}</small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          تاریخ نشر اعلان:
        </strong>
        <small class="mb-5 date" v-text="proposal.publish_date" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          آدرس نشر اعلان:
        </strong>
        <small class="mb-5" v-text="proposal.publish_address" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          تاریخ آفرگشایی:
        </strong>
        <small class="mb-5 date" v-text="proposal.bidding_date" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          آدرس آفرگشایی:
        </strong>
        <small class="mb-5" v-text="proposal.bidding_address" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>

    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          تضمین آفر:
        </strong>
        <small class="mb-5" v-text="proposal.offer_guarantee" vs-justify="right" vs-align="right"></small>
        <small style="color:#42b983;"><b>افغانی </b></small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-1">
        <strong class="mr-4">
          نهاد تطبیق کننده :
        </strong>
        <small class="mb-5" v-if="proposal.pro_data" v-text="proposal.pro_data.client.name" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          عنوان قرارداد:
        </strong>
        <small class="mb-5" v-if="proposal.pro_data" v-text="proposal.pro_data.title" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          شماره شناسایی قرارداد :
        </strong>
        <small class="mb-5" v-if="proposal.pro_data" v-text="proposal.pro_data.reference_no" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          تاریخ ختم پیشنهاد:
        </strong>
        <small class="mb-5 date" v-text="proposal.submission_date" vs-justify="right" vs-align="right"></small>
      </h6>
    </vs-col>
  </vs-row>
  <!-- Ekmalat section -->
  <vs-row vs-w="12" class="project-view-header">
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="12" vs-sm="12" vs-xs="12">
      <h4>&nbsp;بخش اکمالات /اقلام&nbsp;</h4>
    </vs-col>
  </vs-row>
  <vs-table :data="proposal.pro_items">
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
          <p> {{ (tr.item_id && tr.item_id.type) ? tr.item_id.type.type : '' }} {{ tr.item_id.name }} </p>
        </vs-td>
        <vs-td :data="tr.equivalent">
          {{ tr.equivalent | NumThreeDigit }} {{ tr.item_id.uom_equiv_id.title }}
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
</div>
</template>

<script>
export default {

  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      proposal: null,
    }
  },
  created() {
    this.getProposal();
  },
  methods: {
    getProposal() {
      this.axios.get('/api/proposal/' + this.$route.params.id)
        .then((response) => {
          this.proposal = response.data;this.$Progress.set(100)
          document.getElementById("loading-bg").style.display = "none";
        })
    },
    cprint() {
      window.open(`/proposal/print?pid=${this.$route.params.id}&name=${localStorage.getItem("name")} ${localStorage.getItem("lastname")}`, '_blank');
    },
  }
}
</script>

<style>

</style>
