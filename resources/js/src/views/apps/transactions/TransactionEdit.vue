<template>
<div>
  <AccountaddSidebar :isSidebarActive="addNewDataSidebar" @closeSidebar="toggleDataSidebar" :accForm="accForm" :accountTypes="accountTypes" />
  <div class="vx-row">
    <vx-card>
      <div class="vx-row">
        <div class="vx-col w-1/2">
          <h3>فورم ویرایش معاملات تجارتی</h3>
        </div>
        <div class="vx-col w-1/2 float-left">
          <vs-button color="primary" type="filled" class="float-right ml-3" @click="addNewData">حساب جدید</vs-button>
          <!-- <vs-button @click="testTost">tost</vs-button> -->
          <!-- <vs-input icon-after="true" label-placeholder="icon-after" icon="search" placeholder="Search account" class="mt-1 float-right" style="max-width:320px" /> -->
        </div>
      </div>
      <form data-vv-scope="transactionForm">
        <div class="vx-row">
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
            <div class="w-full  ml-3 mr-3">
              <vs-input size="medium" v-validate="'required'" v-model="form.serial_no" label="سریال نمبر" name="serial_no" class="mt-5 w-full" placeholder="سریال نمبر" disabled />
              <!--<span class="text-danger text-sm" v-show="errors.has('serialnumber')">{{ errors.first("serialnumber") }}</span> -->
              <has-error :form="form" field="serial_no"></has-error>
            </div>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12" class="mt-3">
            <div class="w-full pt-2 ml-3 mr-3">
              <label for class="ml-4 mr-4 mb-2">واحد پولی</label>
              <div class="radio-group w-full">
                <div class="w-1/2">
                  <input type="radio" v-model="form.currency_id" value="1" id="currency_afn" name="currency_id" @change="currencychange(1)" />
                  <label for="currency_afn" class="w-full text-center">افغانی</label>
                </div>
                <div class="w-1/2">
                  <input type="radio" v-model="form.currency_id" value="2" id="currency_usd" name="currency_id" @change="currencychange(2)" />
                  <label for="currency_usd" class="w-full text-center">دالر</label>
                </div>
              </div>
              <has-error :form="form" field="curency"></has-error>
            </div>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
            <div class="w-full  ml-3 mr-3">
              <label for="date" class="mt-3"><small>تاریخ </small></label>
              <date-picker color="#e85454" name="trans_date" v-validate="'required'" input-format="YYYY/MM/DD HH:mm" format="jYYYY/jMM/jDD HH:mm" type="datetime" v-model="form.datetime" />
            </div>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
            <div class="w-full pt-2 ml-3 mr-3">
              <label for class="ml-4 mr-4 mb-2"> حالت معامله</label>
              <div class="radio-group w-full">
                <div class="w-1/2">
                  <input type="radio" v-model="form.transaction_status" value="benefit" id="benefit" name="transaction" />
                  <label for="benefit" class="w-full text-center">عاید</label>
                </div>
                <div class="w-1/2">
                  <input type="radio" v-model="form.transaction_status" value="basic" id="basic" name="transaction" />
                  <label for="basic" class="w-full text-center">امانت/عادی</label>
                </div>
              </div>
              <has-error :form="form" field="transaction_status"></has-error>
            </div>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="8" vs-sm="6" vs-xs="12">
            <div class="w-full  ml-3 mr-3">
              <vs-input size="medium" name="deal_title" v-validate="'required|min:2'" v-model="form.title" @input="getTitle" label="عنوان معامله" class="w-full" />
            </div>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12" class="mt-5">
            <div class="w-full  ml-3 mr-3 mt-2">
              <label for>
                <small>مقدار</small>
              </label>
              <vx-input-group class="mb-base">
                <template slot="prepend">
                  <div class="prepend-text bg-primary">
                    <span>{{currency_title}}</span>
                  </div>
                </template>
                <vs-input name="trans_ammount" v-validate="'required'" v-model="visualFields.ammount" @input="formatToEnPrice($event, form, 'ammount', visualFields)" />
              </vx-input-group>
            </div>
          </vs-col>
          <div class="vx-col w-1/3 mt-4">
            <label for=""><small> حساب کریدیت</small></label>
            <v-select size="large" label="name" name="credit_account" v-validate="'required'" :options="accounts" :dir="$vs.rtl ? 'rtl' : 'ltr'" v-model="form.credit_account" />
          </div>
          <div class="vx-col w-2/3">
            <vs-input size="medium" label="تفصیلات " name="credit_ac_detail" class="mt-5 w-full" v-model="form.credit_desc" />
          </div>
          <div class="vx-col w-1/3 mt-4">
            <label for=""><small> حساب دبت</small></label>
            <v-select label="name" :options="accounts" name="debit_account" v-validate="'required'" :dir="$vs.rtl ? 'rtl' : 'ltr'" v-model="form.debit_account" />
          </div>
          <div class="vx-col w-2/3">
            <vs-input size="medium" label="تفصیلات " name="debit_ac_detail" class="mt-5 w-full" v-model="form.debit_desc" />
          </div>
          <div class="vx-col w-full mt-4">
            <vs-textarea label="تفصیلات کلی" v-model="form.description" :rows="form.description && form.description.split(`\n`).length > 2 ? form.description.split(`\n`).length + 1 : 3"></vs-textarea>
          </div>
        </div>
        <vs-button :disabled="form.busy" class="mr-3 mb-2" @click.prevent="submitData">آپدیت</vs-button>
        <vs-list v-if="(errors.items.length > 0)">
          <vs-list-header color="danger" title="مشکلات"></vs-list-header>
          <div :key="indextr" v-for="(error, indextr) in errors.items">
            <vs-list-item icon="verified_user" style="color:red;" :subtitle="error.msg"></vs-list-item>
          </div>
          <!--<vs-list-item title="" subtitle=""></vs-list-item> -->
        </vs-list>
      </form>
    </vx-card>
  </div>
</div>
</template>

<script>
import AccountaddSidebar from "./../accounts/Accountadd.vue"
import vSelect from "vue-select";
import {
  Validator
} from 'vee-validate'
export default {
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      edit_id: this.$route.params.id,
      accForm: new Form({
        type_id: '',
        name: '',
        ref_code: '0',
        status: 1,
        description: '',
        credit: '0',
        debit: '0',
        id: null
      }),
      addNewDataSidebar: false,
      form: new Form({
        serial_no: '',
        currency_id: 1,
        datetime: this.momentj().format('jYYYY/jMM/jDD HH:mm'),
        title: '',
        user_id: localStorage.getItem('id'),
        transaction_status: 'benefit',
        ammount: '0',
        credit_account: '',
        debit_account: '',
        credit_desc: '',
        debit_desc: '',
        description: ''
      }),
      visualFields: {
        ammount: 0
      },
      currency_title: 'AFN',
      accounts: [],
      accountTypes: [],
      dict: {
        custom: {
          trans_date: {
            required: ' تاریخ انجام معامله الزامی میباشد.'
          },
          deal_title: {
            required: 'عنوان معامله الزامی میباشد.',
            min: 'عنوان معامله باید بیشتر از 2 حرف باشد.'
          },
          trans_ammount: {
            required: 'مقدار معامله الزامی میباشد.'
          },
          credit_account: {
            required: 'حساب کریدیت الزامی میباشد.'
          },
          debit_account: {
            required: 'حساب دیبیت الزامی میباشد.'
          }
        }
      }
    };
  },
  components: {
    "v-select": vSelect,
    Validator,
    AccountaddSidebar
  },
  created() {

    Validator.localize('en', this.dict);
    this.getAllAccountTypes()
    this.getAccounts();
    this.loadTransaction();
  },
  methods: {
    addNewData() {
      this.toggleDataSidebar(true);
    },
    toggleDataSidebar(val = false) {
      if (val == false) {
        this.getAccounts();
      }
      this.addNewDataSidebar = val;
    },
    getAllAccountTypes() {
      this.$Progress.start()
      this.axios.get('/api/accounts')
        .then((response) => {
          this.accountTypes = response.data;
          this.$Progress.set(100)
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
        })
    },
    currencychange(data) {
      if (data == 2) {
        this.currency_title = "USD";
      } else {
        this.currency_title = "AFN";
      }
    },

    getTitle(data) {
      this.form.credit_desc = data;
      this.form.debit_desc = data;
    },

    submitData() {
      this.$validator.validateAll('transactionForm').then(result => {
        if (result) {
          this.form.patch('/api/transaction/' + this.edit_id)
            .then(() => {
              this.$vs.notify({
                title: 'عملیه آپدیت موفق بود!',
                text: 'عملیه موفقانه انجام شد',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
              this.$router.push(`/transactions?tab=1`).catch(() => {})
            })
            .catch(() => {
              this.$vs.notify({
                title: 'آپدیت عملیه ناموفق بود!',
                text: 'عملیه ناکام شد لطفا دوباره تلاش نماید',
                color: 'danger',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
            })
        } else {

          // form have errors
        }
      })

    },
    loadTransaction() {
      this.axios.get('/api/transaction/' + this.edit_id)
        .then((response) => {
          this.form.serial_no = response.data.serial_no;
          this.form.fill(response.data);
          this.visualFields.ammount = this.formatToEnPriceSimple(response.data.ammount);
          this.form.credit_desc = this.form.credit_desc;
          this.form.debit_desc = this.form.debit_desc;
          this.transaction_status = 'benefit';
          // document.getElementById("loading-bg").style.display = "none";;
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
          this.$Progress.set(100)
        })
    },
    getAccounts() {
      this.$Progress.start()
      this.axios.get('/api/account')
        .then((response) => {
          this.accounts = response.data;
        })
    },
  }
}
</script>
