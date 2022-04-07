<template>
<div>
  <vs-tabs>
    <vs-tab v-if="user_permissions.includes('expense_add')" label="ثبت مصارف">
      <div class="vx-row">
        <vx-card class="height-vh-80">
          <div class="vx-row">
            <div class="vx-col w-1/2 white-space-no-wrap">
              <h3>فورم ثبت مصارف</h3>
            </div>
          </div>
          <form data-vv-scope="expensForm">
            <div class="vx-row">
              <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
                <div class="w-full  ml-3 mr-3">
                  <vs-input size="medium" v-validate="'required'" v-model="form.serial_no" label="سریال نمبر" name="serial_no" class="mt-5 w-full" placeholder="سریال نمبر" disabled />
                  <!--<span class="text-danger text-sm" v-show="errors.has('serialnumber')">{{ errors.first("serialnumber") }}</span> 
                  <has-error :form="form" field="serial_no"></has-error>-->
                </div>
              </vs-col>
              <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12" class="mt-4">
                <div class="w-full pt-2 ml-3 mr-3">
                  <label for class="ml-4 mr-4 mb-2">واحد پولی</label>
                  <div class="radio-group w-full">
                    <div class="w-1/2">
                      <input type="radio" value="1" id="struct" name="status" checked v-model="form.currency_id" v-on:change="currencychange(1)" />
                      <label for="struct" class="w-full text-center">افغانی</label>
                    </div>
                    <div class="w-1/2">
                      <input type="radio" value="2" id="specific" name="status" v-model="form.currency_id" v-on:change="currencychange(2)" />
                      <label for="specific" class="w-full text-center">دالر</label>
                    </div>
                  </div>
                  <span class="absolute text-danger alerttext">{{ errors.first('expensForm.currency_id') }}</span>
                  <!--<has-error :form="form" field="status"></has-error> -->
                </div>
              </vs-col>
              <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
                <div class="w-full ml-3 mr-3">
                  <label for="date" class="mt-3"><small>تاریخ </small></label>
                  <date-picker color="#e85454" name="expense_date" v-validate="'required'" input-format="YYYY/MM/DD HH:mm" format="jYYYY/jMM/jDD HH:mm" type="datetime" v-model="form.datetime" />
                  <span class="absolute text-danger alerttext">{{ errors.first('expensForm.expense_date') }}</span>
                </div>
              </vs-col>
              <vs-col class="mt-3" vs-type="flex" vs-justify="center" vs-align="center" vs-lg="8" vs-sm="6" vs-xs="12">
                <div class="w-full ml-3 mr-3">
                  <vs-input size="medium" name="expense_title" v-validate="'required|min:2'" v-model="form.title" @input="getTitle" label="عنوان مصارف" class="w-full" />
                  <span class="absolute text-danger alerttext">{{ errors.first('expensForm.expense_title') }}</span>
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
                    <vs-input name="expense_ammount" v-validate="'required'" v-model="visualFields.ammount" @input="formatToEnPrice($event, form, 'ammount', visualFields)" />
                    <span class="absolute text-danger alerttext">{{ errors.first('expensForm.expense_ammount') }}</span>
                  </vx-input-group>
                </div>
              </vs-col>
            </div>
            <div class="vx-row sm-row-custom">
              <div class="vx-col lg:w-1/3 md:w-1/3 sm:w-1/2 mt-4">
                <label for=""><small> حساب کریدیت</small></label>
                <v-select size="large" label="name" name="credit_acc_expen" v-validate="'required'" :options="accounts" :dir="$vs.rtl ? 'rtl' : 'ltr'" v-model="form.credit_account" />
                <span class="absolute text-danger alerttext">{{ errors.first('expensForm.credit_acc_expen') }}</span>
              </div>
              <div class="vx-col lg:w-2/3 md:w-2/3 sm:w-1/2">
                <vs-input size="medium" label="تفصیلات " name="credit_acc_exp_desc" class="mt-5 w-full" v-model="form.credit_desc" />
              </div>
              <div class="vx-col lg:w-1/3 md:w-1/3 sm:w-1/2 mt-4">
                <label for=""><small> حساب دبت</small></label>
                <v-select label="name" name="debit_acc_expen" v-validate="'required'" :options="accounts" :dir="$vs.rtl ? 'rtl' : 'ltr'" v-model="form.debit_account" />
                <span class="absolute text-danger alerttext">{{ errors.first('expensForm.debit_acc_expen') }}</span>
              </div>
              <div class="vx-col lg:w-2/3 md:w-2/3 sm:w-1/2">
                <vs-input size="medium" label="تفصیلات " name="debit_acc_exp_desc" class="mt-5 w-full" v-model="form.debit_desc" />
              </div>
              <div class="vx-col w-full mt-4">
                <vs-textarea label="تفصیلات کلی" v-model="form.description" :rows="form.description && form.description.split(`\n`).length > 2 ? form.description.split(`\n`).length + 1 : 3"></vs-textarea>
              </div>
            </div>
            <vs-button :disabled="form.busy" type="filled" @click.prevent="submitData" class="mt-5 block float-left">ثبت</vs-button>
            <p></p>
            <vs-divider></vs-divider>
            <!--  <vs-list v-if="(errors.items.length > 0)">
              <vs-list-header color="danger" title="مشکلات"></vs-list-header>
              <div :key="indextr" v-for="(error, indextr) in errors.items">
                <vs-list-item icon="verified_user" style="color:red;" :subtitle="error.msg"></vs-list-item>
              </div>
              <vs-list-item title="" subtitle=""></vs-list-item>
            </vs-list> -->
          </form>
        </vx-card>
      </div>
    </vs-tab>
    <vs-tab v-if="user_permissions.includes('expense_view')" label="لیست مصارف">
      <Expenselist></Expenselist>
    </vs-tab>
  </vs-tabs>
</div>
</template>

<script>
import Expenselist from "./Expenselist.vue";
import vSelect from "vue-select";
import {
  Validator
} from 'vee-validate'

export default {
  components: {
    Expenselist,
    "v-select": vSelect,
    Validator
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      visualFields: {
        ammount: 0
      },
      form: new Form({
        serial_no: '',
        currency_id: 1,
        datetime: this.momentj().format('jYYYY/jMM/jDD HH:mm'),
        title: '',
        user_id: localStorage.getItem('id'),
        ammount: '0',
        credit_account: '',
        debit_account: '',
        credit_desc: '',
        debit_desc: '',
        description: ''
      }),
      currency_title: 'AFN',
      accounts: [],
      dict: {
        custom: {
          expense_date: {
            required: ' تاریخ انجام مصرف الزامی میباشد.'
          },
          expense_title: {
            required: 'عنوان مصرف الزامی میباشد.',
            min: 'عنوان مصرف باید بیشتر از 2 حرف باشد.'
          },
          expense_ammount: {
            required: 'مقدار مصرف الزامی میباشد.'
          },
          credit_acc_expen: {
            required: 'حساب کریدیت الزامی میباشد.'
          },
          debit_acc_expen: {
            required: 'حساب دیبیت الزامی میباشد.'
          }
        }
      }
    };
  },
  methods: {
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
      this.$validator.validateAll('expensForm').then(result => {
        if (result) {
          this.form.post('/api/expenses')
            .then(() => {
              this.$vs.notify({
                title: 'عملیه ثبت موفق بود!',
                text: 'عملیه موفقانه انجام شد',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
              this.form.reset();
              this.$validator.reset();
              // this.getSerialNom();
              this.$router.push(`/expenses`).catch(() => {})
            })
            .catch(() => {
              this.$vs.notify({
                title: 'ثبت عملیه  ناموفق بود!',
                text: 'عملیه  ناکام شد لطفا دوباره تلاش نماید',
                color: 'danger',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
            })
        } else {

          // form have errors
        }
      });

    },

    getAccounts() {
      this.$Progress.start()
      // this.$vs.loading()
      this.axios.get('/api/account')
        .then((response) => {
          this.accounts = response.data;
          this.$Progress.set(100)
          // document.getElementById("loading-bg").style.display = "none";;
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
        })
    },

    getSerialNom() {
      this.axios.get('/api/exserialno')
        .then((resp) => {
          this.form.serial_no = resp.data;
        });
    }
  },

  created() {
    Validator.localize('en', this.dict);
    this.getAccounts();
    this.getSerialNom();
  }
};
</script>

<style>
.customstyle {
  border-right: solid;
  border-right-width: initial;
  border-right-style: solid;
  border-right-color: initial;

}

.vs-radio {
  border-top-left-radius: 1 !important;
  border-bottom-left-radius: 1 !important;
}
</style>
