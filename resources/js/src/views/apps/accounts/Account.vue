<template>
<div>
  <Accountadd :isSidebarActive="addNewDataSidebar" @closeSidebar="toggleDataSidebar" :accForm="accForm" :accountTypes="accountTypes" :editMode="editMode" />
  <vs-card>
    <div class="vx-row mb-3">
      <div class="vx-col lg:w-1/2 md:w-1/2 sm-w-full sm-flex sm-items-center sm-justify-center">
        <h3 class="mt-4">
          <b> لیست حسابات </b>
        </h3>
      </div>
      <div class="vx-col lg:w-1/2 md:w-1/2 float-left mt-3 sm-w-full sm-flex sm-items-center sm-justify-center">
        <vs-button v-if="user_permissions.includes('account_add')" color="primary" type="filled" class="account-btn float-right mr-3 ml-3" @click="addNewData">حساب جدید</vs-button>
        <div class="balance_list_currency_toggle sm:w-auto md:w-1/2 lg:w-1/4 float-right xl:w-1/4">
          <div class="radio-group w-full">
            <div class="w-1/2">
              <input type="radio" v-model="curr_display" value='afn' id="acc_list_afn" name="curr_display" />
              <label for="acc_list_afn" class="w-full text-center">افغانی</label>
            </div>
            <div class="w-1/2">
              <input type="radio" v-model="curr_display" value='usd' id="acc_list_usd" name="curr_display" />
              <label for="acc_list_usd" class="w-full text-center">دالر</label>
            </div>
          </div>
        </div>
      </div>
    </div>

  </vs-card>
  <div v-if="!isdata">
    <TableLoading></TableLoading>
  </div>
  <span v-if="isdata" v-for="(type, i) in accountTypes" :key="i">
    <vs-card v-if="type.accounts != null && type.accounts.length > 0">
      <vs-table max-items="3" pagination :data="type.accounts" stripe>
        <template slot="header">
          <h4 class="p-4"><b>{{ type.title }}</b></h4>
          <span class="p-4 align-right">
            <span class="text-rtl-left breadcrumb" v-if="type.type_id">{{ generateBroadCrumps(type) }}</span>
          </span>
        </template>
        <template slot="thead">
          <vs-th><strong class="white-space-no-wrap"> ریفرینس کد</strong> </vs-th>
          <vs-th><strong> عنوان</strong> </vs-th>
          <vs-th><strong> بیلانس</strong> </vs-th>
          <vs-th><strong> حالت</strong> </vs-th>
          <vs-th v-if="user_permissions.includes('account_edit') || user_permissions.includes('account_remove')"><strong> تنظیمات</strong> </vs-th>
        </template>
        <template slot-scope="{ data }">
          <vs-tr :key="i" v-for="(tr, i) in data">
            <vs-td :data="tr.ref_code">
              <p class="cursor-pointer white-space-no-wrap" @click.stop="openFinancialRecords(tr)">{{ tr.ref_code }} </p>
            </vs-td>
            <vs-td class="desc" :data="tr.name">
              <p class="cursor-pointer" @click.stop="openFinancialRecords(tr)">{{ tr.name }} </p>
            </vs-td>
            <vs-td :data="tr" class="float-left">
              <p class="cursor-pointer" @click.stop="openFinancialRecords(tr)">
                <vs-alert class="balance_currency_value flex p-0" v-if="tr[curr_display]" :color="tr[curr_display] > 0 ? 'success' : 'danger'" active="true">
                  <p dir="ltr">&nbsp;{{ tr[curr_display].toFixed(0) | NumThreeDigit }}&nbsp;</p>
                  <span>{{ $t(curr_display) }}</span>
                </vs-alert>
              </p>
            </vs-td>
            <vs-td :data="tr.status">
              <p>{{ (tr.status == 1) ? "فعال" :"غیرفعال"}} </p>
            </vs-td>
            <vs-td class="whitespace-no-wrap notupfromall">
              <feather-icon v-if="user_permissions.includes('account_edit')" icon="EditIcon" svgClasses="w-5 h-5 hover:text-primary stroke-current" class="cursor-pointer" @click.stop="editAccount(tr.id)" />
              <feather-icon v-if="user_permissions.includes('account_remove')" icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click.stop="deleteData(tr.id)" />
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </vs-card>
  </span>
  <vs-popup class="holamundo financial-records-modal" title="اطلاعات معاملات تجاری" :active.sync="popupActive">
    <financial-records :fRData="financialRecordsData"></financial-records>
  </vs-popup>
</div>
</template>

<script>
import Accountadd from "./Accountadd.vue"
import TableLoading from './../shared/TableLoading.vue'
import FinancialRecords from "../transactions/FinancialRecords"
import vSelect from "vue-select"
export default {
  components: {
    "v-select": vSelect,
    Accountadd,
    FinancialRecords,
    TableLoading
  },
  data: () => ({
    // Data Sidebar
    user_permissions: localStorage.getItem('user_permissions'),
    editMode: false,
    isdata: false,
    curr_display: 'afn',
    addNewDataSidebar: false,
    editAccData: {},
    accForm: new Form({
      type_id: '',
      name: '',
      ref_code: '0',
      status: 1,
      description: '',
      credit: '0',
      debit: '0',
      id: null,
      user_id: localStorage.getItem('id'),
    }),
    currentx: 14,
    accounts: [],
    accountTypes: [],
    // financialRecordsData Values
    popupActive: false,
    financialRecordsData: [],
    broadSeperate: `<span class="breadcrumb-separator mx-2"><feather-icon :icon="rtl ? 'ChevronsLeftIcon' : 'ChevronsRightIcon'" svgClasses="w-4 h-4" /></span>`,
  }),
  created() {
    const appLoading = document.getElementById("loading-bg");
    if (appLoading) {
      appLoading.style.display = "none";
    }
    this.getAllAccountTypes()
  },
  methods: {

    editAccount(id) {
      this.$Progress.start()
      this.editMode = true;
      this.accForm.reset();
      this.axios.get('/api/account/' + id)
        .then((response) => {
          for (let [key, value] of Object.entries(response.data)) {
            this.accForm[key] = value;
          }
          this.accForm.type_id = response.data.account_type;
          if (response.data.financial_records[0]) {
            this.accForm.credit = response.data.financial_records[0].credit;
            this.accForm.debit = response.data.financial_records[0].debit;
          }
          this.$Progress.set(100)
          this.toggleDataSidebar(true);
        })
    },
    // Get Account Types
    getAllAccountTypes() {
      this.$Progress.start()
      this.axios.get('/api/accounts')
        .then((response) => {
          this.accountTypes = response.data;
          this.isdata = true;
          this.$Progress.set(100)
          // document.getElementById("loading-bg").style.display = "none";;
        })
    },
    countTheBalance(data) {
      let x = 0;
      for (let [key, data] of Object.entries(data.financial_records)) {
        if (data.credit) {
          x = x + parseInt(data.credit);
        }
        if (data.debit) {
          x = x - parseInt(data.debit);
        }
      }
      return x;
    },
    deleteData(id) {
      swal.fire({
        title: 'آیا مطمئن هستید؟',
        text: "حساب حذف خواهد شد",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'rgb(54 34 119)',
        cancelButtonColor: 'rgb(229 83 85)',
        confirmButtonText: '<span>بله، حذف شود!</span>',
        cancelButtonText: '<span>خیر، لغو عملیه!</span>'
      }).then((result) => {
        if (result.isConfirmed) {
          this.accForm.delete('/api/account/' + id).then((id) => {
              swal.fire({
                title: 'عملیه موفقانه انجام شد.',
                text: "حساب از سیستم پاک شد!",
                icon: 'success',
              })
              this.getAllAccountTypes();
            })
            .catch(() => {
              swal.fire(
                'ناموفق !',
                'سیستم قادر به حذف نیست. وابستگی های آیتم را بررسی نمایید!',
                'error'
              )

              document.getElementById("loading-bg").style.display = "none";
            });
        }
      })
    },
    addNewData() {
      // this.editAccData = {};
      this.accForm.reset();
      this.editMode = false;
      this.toggleDataSidebar(true);
    },
    toggleDataSidebar(val = false) {
      this.getAllAccountTypes();
      this.addNewDataSidebar = val;
    },

    // Financial Records Modal
    openFinancialRecords(data) {
      this.$Progress.start()
      this.axios.post('/api/financial-account', data)
        .then((response) => {
          this.popupActive = true;
          this.financialRecordsData = response.data;
          this.$Progress.set(100)
        })
    },

    // testTost() {
    //     //sweetalirt
    //     swal.fire(
    //         'The Internet?',
    //         'That thing is still around?',
    //         'question'
    //     )

    //     // tost notification
    //     this.$vs.notify({
    //         title: 'Icon mail',
    //         text: 'Lorem ipsum dolor sit amet, consectetur',
    //         color: 'success',
    //         iconPack: 'feather',
    //         icon: 'icon-check',
    //         position: 'top-right'
    //     })
    // }
    generateBroadCrumps(type, seperate = false) {

      if (type.type_id && type.type_id.title) {
        var t = ((seperate) ? " << " : '') + type.type_id.title.replace(/ *\([^)]*\) */g, "") + this.generateBroadCrumps(type.type_id, true);
        return t
      } else {
        return ''
      }
    }
  },
  mounted() {
    this.isMounted = false
  },

};
</script>

<style lang="stylus">
.vs-input--icon {
  top: 12px;
}

.financial-records-modal .vs-popup {
  min-width: 80% !important;
}
</style>
